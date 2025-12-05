# Fundraiser

Fundraiser is a Laravel 12 (PHP 8.2+) application implementing a layered, Domain‑Driven Design (DDD) architecture for
managing fundraising Campaigns and Donations. It includes a Vue 3 + Vite SPA frontend and a clean separation between
domain logic (Core) and infrastructure (Adapters).

This README documents the key architecture decisions, assumptions, how to run the project, how to test it, and how to
contribute within the established boundaries.

## Architecture Decisions (High‑level)

- Layering and dependency direction
    - Core contains only domain logic (Aggregates/Entities, Value Objects, Domain Services, Application Services,
      Repository Interfaces, Domain Exceptions).
    - Adapters implement infrastructure and delivery (HTTP controllers, Eloquent models and migrations, queues/jobs,
      notifications/mail, payment gateways, logging, etc.).
    - Dependency direction is strictly Adapters → Core. Core never depends on Adapters or framework specifics.

- Read/write segregation
    - Core may perform read‑only queries using Eloquent’s read APIs only: `Model::query()`, `get()`, `find()`,
      `paginate()`.
    - All writes must go exclusively through `core/services/crud/ModelCrudService.php` (or the per‑aggregate Crud
      service in Core calling it). No `save/create/update/delete` from within domain services.
    - Read operations must never trigger writes.

- Update operations contract
    - Update methods never accept raw arrays or IDs. They accept the target Model instance and an Update Form DTO (
      Spatie Laravel Data). Partial updates are handled by optional fields present in Update DTOs.

- Payment anti‑corruption layer
    - The domain talks to payments via `PaymentGatewayInterface` (Core).
    - Concrete gateways live in Adapters. A `PaymentGatewayFactory` (Adapters) resolves the configured gateway. No
      external SDK response leaks into Core.
    - Core receives a value object `PaymentResult` only.

- Eager loading policy (read side)
    - To avoid client‑controlled eager loads, Core read services accept an internal `$with` array propagated into the
      base query. Adapters (controllers/services) decide which relations to eager‑load per use case.

- Aggregations for presentation
    - Adapters may add read‑only presentation attributes on Eloquent models (e.g., `Campaign::raised_amount`,
      `donors_count`) derived from read queries. These do not perform writes and exist only to shape API responses for
      the SPA.

## Modules (Contexts)

The code under `src/Modules` follows the same pattern per context, plus a shared `core/services/crud` write layer:

- Identity
    - Core: authentication/authorization services and DTOs.
    - Adapters: `User` model, policies, middleware, controllers.

- Campaign
    - Core: `CampaignService`, DTOs for create/update/query, enums for status, read‑only queries.
    - Adapters: Eloquent models (`Campaign`, `CampaignCategory`), migrations, HTTP controllers, resources.

- Donations
    - Core: `DonationService`, Create/Payment DTOs, `PaymentGatewayInterface`, `PaymentResult` VO. Reads campaign status
      and orchestrates payment call; writes donation via Crud service.
    - Adapters: `Donation` model, HTTP controller, payment provider adapters, payment gateway factory, email/queue
      infrastructure.

- Admin
    - Core: admin orchestration (moderation/settings as needed).
    - Adapters: admin controllers, routes and middleware.

- Common
    - Core: shared DTOs, small helpers, constants/enums.
    - Adapters: optional framework‑bound helpers (e.g., JSON response helper).

## Notable Implementations

- Payment gateway factory
    - `src/Donations/Adapters/Payments/PaymentGatewayFactory.php` resolves an implementation of
      `PaymentGatewayInterface` based on `PAYMENT_GATEWAY` env (defaults to `fake`). `AppServiceProvider` binds the
      interface to the factory result.

- Donations email confirmations
    - After a successful donation, a queued job sends a confirmation email:
        - Job: `app/Jobs/Donations/SendDonationReceipt.php` (ShouldQueue)
        - Notification: `app/Notifications/Donations/DonationReceiptNotification.php` (ShouldQueue)
        - Mailable: `app/Mail/Donations/DonationReceiptMail.php` using `Envelope` and Blade view at
          `resources/views/mail/donations/receipt.blade.php`.

- Read‑side totals for listings
    - `Campaign` model (Adapters) exposes `raised_amount` and `donors_count` based on paid donations. These are
      read‑only computed attributes for UI.

- Active campaigns listing and caching
    - Public `/v1/campaigns/active` endpoint returns only active campaigns. The controller applies simple cache
      versioning to accelerate public listings.

- Eager loading via `$with`
    - Core services `CampaignService` and `UserService` accept `$with` and pass it into their `baseQuery()` methods to
      support controller‑decided eager loading while keeping DTOs free of client‑controlled load hints.

## Assumptions

- Currency
    - Default currency is USD for donations. Monetary writes store integer cents; UI converts to major units.

- Donor count semantics
    - `donors_count` currently reflects count of paid donations (not unique donors). This can be switched to distinct
      users if required.

- Authorization
    - Gate/Policies protect reads and writes. For example, donations require that a campaign is viewable and active.

## Project Layout

```
src/
  Campaign/
    Core/            # Domain services, DTOs, enums (read‑only queries)
    Adapters/        # Controllers, Eloquent models, migrations
  Donations/
    Core/            # DonationService, payment interfaces/DTOs
    Adapters/        # Models, controllers, payment adapters, jobs/mail
  Identity/
    Core/
    Adapters/
  Common/
    Core/
    Adapters/
core/
  services/
    crud/
      ModelCrudService.php  # Single write gateway used by per‑aggregate Crud services
resources/
  js/              # Vue 3 SPA (Vite)
```

## Running the project

Prerequisites:

- PHP ^8.2 (8.4 supported), Composer v2.x
- Node.js + npm (for the SPA assets)

First‑time setup (recommended):

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

```bash
vendor/bin/sail up -d
```

```bash
composer setup
```

During the composer setup, you will be promted to create a passport client. Make sure to copy the client and add it to
resources/js/services/auth.ts

finally

```bash
npm run dev

```

