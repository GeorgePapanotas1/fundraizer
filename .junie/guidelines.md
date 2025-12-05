Project: Fundraiser (Laravel 12, PHP 8.2+)

### 🚦 Project-Specific DDD & Architecture Rules

#### 🧱 Core Rules (Domain Layer)

- Core contains ONLY domain logic: Aggregates, Entities, Value Objects, Domain Services, Domain Exceptions, Application
  Services, Repository Interfaces.
- Core must **never perform write operations** directly (no `save()`, `create()`, `update()`, `delete()`).
- Core may perform **read-only queries** and only using:
  Model::query()
  Model::get()
  Model::find()
  Model::paginate()

markdown
Copy code
Read operations must never trigger writes.

- All write operations MUST go exclusively through:
  `/src/core/services/crud/ModelCrudService.php`
- Core must not depend on framework infrastructure (Eloquent models, controllers, queues, mail, cache, payment SDKs,
  etc.)

#### 🔌 Adapters Layer (Infrastructure)

- Adapters implement infrastructure concerns: controllers, Eloquent models, migrations, queues, logging, payment SDKs,
  and repository implementations.
- Adapters **depend on Core**, not vice-versa.
- Adapters must NOT contain business logic.

#### 💳 Payment Anti-Corruption Rule

- Payment providers must be abstracted behind a domain interface.
- No external SDK or provider response can leak into Core.
- Core receives a **Value Object** (e.g. `PaymentResult`) rather than raw gateway data.

### ✍️ Documentation Rules for Future Guidelines

- Always explain behavior using business terms (Campaign, Donation, Employee, PaymentTransaction).
- Never document domain logic using database terminology (tables, rows).
- When documenting persistence, explicitly remind that ONLY `ModelCrudService` writes.
- If showing queries inside Core, remind they must be read-only.
- Reinforce dependency direction: **Adapters → Core**, never Core → Adapters.

### 🧠 Final Reminder

> The Domain owns the rules.  
> Adapters make them run.  
> Core may read, but only `ModelCrudService` may write.

This document captures project-specific knowledge to streamline setup, testing, and development for advanced
contributors.

Build and configuration

- Runtime requirements
    - PHP: ^8.2 (php -v). The repo is known to work with PHP 8.4 as well.
    - Composer: v2.x.
    - Node/npm: required only for asset building and running Vite during local dev.

- Composer scripts of interest (composer.json)
    - composer setup: Installs dependencies, ensures .env exists, generates APP_KEY, runs migrations, installs Node
      deps, and builds assets. Use when bootstrapping the project the first time or on clean environments.
    - composer dev: Starts an integrated dev workflow via npx concurrently, running:
        - php artisan serve
        - php artisan queue:listen --tries=1
        - php artisan pail --timeout=0 (log viewer)
        - npm run dev (Vite)
    - composer test: Clears config cache and runs the Laravel test suite (php artisan test). Prefer this over calling
      phpunit directly to ensure a clean config state.

- Environment (.env)
    - On first install, .env is created from .env.example automatically by composer setup or post-root-package-install.
    - Ensure APP_KEY is generated (composer setup handles this via artisan key:generate).
    - Databases: The default app DB can be configured per typical Laravel practice. Migrations are triggered during
      setup. For tests, see phpunit.xml overrides below (no external DB required for unit/feature tests by default).

Testing

- Runner
    - Primary: composer test (preferred) which runs: artisan config:clear, then artisan test.
    - Direct: php artisan test also works and respects phpunit.xml.

- phpunit.xml specifics relevant to this repo
    - Testsuites: tests/Unit and tests/Feature.
    - Source inclusion: app directory is included for code coverage/whitelisting.
    - Test-time env overrides:
        - APP_ENV=testing
        - CACHE_STORE=array, SESSION_DRIVER=array, QUEUE_CONNECTION=sync, MAIL_MAILER=array
        - DB_DATABASE=testing (no external DB is required for current tests; adjust if database-backed tests are
          introduced)
        - PULSE_ENABLED=false, TELESCOPE_ENABLED=false, NIGHTWATCH_ENABLED=false

- Creating tests
    - Unit tests: Place under tests/Unit and extend PHPUnit\Framework\TestCase for pure PHP units that do not require
      the Laravel container.
    - Feature tests: Place under tests/Feature and extend Tests\TestCase (Laravel’s test case) when interacting with
      HTTP routes, middleware, database, queues, etc.
    - Naming: Use the test_ prefix or #[Test] attribute style. File names typically end with Test.php.

- Executing tests
    - Run all: composer test
    - Run a subset: vendor/bin/phpunit --filter NameOrRegex or php artisan test --filter NameOrRegex

- Example: adding and running a simple test
    - Example unit test file contents (kept intentionally framework-agnostic for reliability):
        - File: tests/Unit/GuidelinesDemoTest.php
          <?php
          namespace Tests\Unit;
          use PHPUnit\Framework\TestCase;
          class GuidelinesDemoTest extends TestCase {
              public function test_demo_assertion(): void {
                  $this->assertSame(1, 1);
              }
          }
    - Run: composer test
    - Expected: All tests pass. After verifying the process locally, remove the demo file to keep the repo clean.

Additional development information

- Namespaces/autoloading
    - App code under app/ is autoloaded under the App\\ namespace.
    - Project-specific code under src/ is autoloaded under the Fundraiser\\ namespace (see composer.json). Example:
      Fundraiser\\Common\\Adapters\\Helpers\\HttpResponseHelper.

- Conventions and helpers
    - HTTP responses: Prefer using HttpResponseHelper for consistent JSON responses:
        - success(mixed $data = null, ?array $links = null, int $status = 200, string $message = 'Success')
        - error(string $message, int $status = 400, array $errors = [])
        - serverError()
        - exception(Exception $exception, int $status = 500)
    - When app.debug is true, exception() includes traces; avoid leaking traces in production.

- Coding style
    - Use Laravel Pint (composer require --dev laravel/pint already present). Run vendor/bin/pint or ./vendor/bin/pint
      to format. Consider integrating via pre-commit hook in your local environment.

- Local dev workflow tips
    - Use composer dev for a multi-process dev session (server + queue + logs + Vite). Requires Node and npx
      concurrently (handled via npx at runtime).
    - For containers, Laravel Sail is available as a dev dependency; if you prefer Docker, composer require laravel/sail
      --dev and follow Laravel documentation to bootstrap.

- Debugging
    - Use php artisan pail (included) for real-time log viewing during development.
    - For API debugging, ensure JSON responses are made through HttpResponseHelper for predictable structure during
      troubleshooting.

- Migrations and database in tests
    - Current tests do not require a database. If you add DB-backed feature tests, use RefreshDatabase or
      DatabaseMigrations traits and configure an in-memory SQLite or appropriate testing DB in phpunit.xml/.env.testing.

Notes for contributors

- Keep repo clean: avoid committing ad-hoc demo files; use the example in this guideline for local verification and
  delete extra test files after use.
- Prefer composer scripts over bespoke commands to ensure consistent environment preparation.

---

## 🧩 Project Contexts & Modules

This project is structured into high-level contexts. Each one maps to a module under `src/Modules`, and must follow our
architecture rules, including read-only Core queries and write-only `ModelCrudService` usage.

### 1. Identity Context

**Goal:** Handle users and permissions (employee vs admin).

**Responsibilities:**

- Authentication (login/logout/session/token).
- User identity (name, email, role).
- Role checks (EMPLOYEE, ADMIN).

**Main Concepts:**

- `User`
- `UserRole` (EMPLOYEE, ADMIN)

**Typical Operations:**

- Login/logout.
- Get current user & role.
- Check admin.

**Module Mapping:**

- Module: `Identity`
    - `src/Modules/Identity/Core`
        - `AuthService`, `UserRole` enum/constants
    - `src/Modules/Identity/Adapters`
        - Laravel auth setup
        - Middleware/guards
        - `User` model + migrations

**Boundary Rule:**  
Other modules use Identity services, not direct DB queries to identify/authorize users.

---

### 2. Campaign Context

**Goal:** Allow employees to create, manage, and browse fundraising campaigns.

**Responsibilities:**

- Create/update campaigns.
- Manage data (title, description, goal, dates, category, status).
- Search/list campaigns.
- Handle campaign lifecycle.

**Main Concepts:**

- `Campaign`
- `Category`
- `CampaignStatus`

**Typical Operations:**

- Create/Edit campaign.
- Activate/close campaign.
- Search/filter campaigns.

**Module Mapping:**

- Module: `Campaign`
    - `src/Modules/Campaign/Core`
        - `CampaignService`
        - DTOs (`CreateCampaignDto`, `UpdateCampaignDto`, `CampaignFiltersDto`)
        - Read queries using `Campaign::query()` (read-only)
        - Write operations via `core/services/crud/ModelCrudService`
    - `src/Modules/Campaign/Adapters`
        - `Campaign` Eloquent model
        - Migrations (campaigns, categories)
        - `CampaignController`, Requests, Resources

**Boundary Rule:**  
Campaign does not know payment logic.

---

### 3. Donations Context

**Goal:** Manage donations and abstract payment logic behind a provider interface.

**Responsibilities:**

- Donation creation.
- Validation (amount, campaign status).
- Payment provider abstraction.
- Donation status updates.

**Main Concepts:**

- `Donation`
- `DonationStatus`
- `PaymentProviderInterface`
- `PaymentRequestForm`, `PaymentResultDto`

**Typical Operations:**

- Initiate donation.
- Trigger payment provider.
- Update donation result.
- Provide donation confirmation.

**Module Mapping:**

- Module: `Donations`
    - `src/Modules/Donations/Core`
        - `DonationService`
        - DTOs for creation and results
        - `PaymentProviderInterface`
        - Reads using `Donation::query()` and `Campaign::query()` (read-only checks)
        - Writes via `core/services/crud/ModelCrudService`
    - `src/Modules/Donations/Adapters`
        - `Donation` Eloquent model + migration
        - `DonationController`
        - Payment provider adapters (Fake + real future)
        - Bindings for `PaymentProviderInterface`

**Boundary Rule:**  
Payment SDK/HTTP calls remain strictly in Adapters — never Core.

---

### 4. Admin Context

**Goal:** Platform governance and moderation.

**Responsibilities:**

- Category and parameter management.
- Campaign moderation.
- Admin-only actions/endpoints.

**Main Concepts:**

- `AdminUser`
- `PlatformSettings` (optional)
- `Moderation`

**Typical Operations:**

- Manage categories/settings.
- Approve/close campaigns.
- Admin-side CRUD.

**Module Mapping:**

- Module: `Admin`
    - `src/Modules/Admin/Core`
        - `AdminService` (moderation & settings)
    - `src/Modules/Admin/Adapters`
        - `AdminController`
        - Admin routes/middleware
        - CRUD endpoints for categories/settings

**Boundary Rule:**  
Admin uses services from other modules; it does not bypass them through direct DB writes.

---

### 5. Common Module

**Goal:** Provide shared tooling, types, and services.

**Responsibilities:**

- Shared DTOs, enums, helpers, utility classes
- Pagination types, response wrappers, custom exceptions
- Common validation or number/date handling if needed

**Main Concepts:**

- `PaginationInput`
- Common exceptions
- Shared enums or helper classes

**Module Mapping:**

- Module: `Common`
    - `src/Modules/Common/Core`
        - DTOs, small services, helpers usable across modules
    - `src/Modules/Common/Adapters`
        - Optional: transformers or utilities tied to Laravel

**Boundary Rule:**  
Common must not contain business logic belonging to a specific context.

---

### Overall Structure Summary

```text
src/
  Modules/
    Identity/
      Core/
      Adapters/
    Common/
      Core/
      Adapters/
    Campaign/
      Core/
      Adapters/
    Donations/
      Core/
      Adapters/
    Admin/
      Core/
      Adapters/
core/
  services/
    crud/
      ModelCrudService.php

---

### Update Operations Contract (Project Rule)

- Update methods must NEVER accept plain arrays or raw IDs.
- Every update operation must accept:
  - the target Model instance (already loaded), and
  - a Form DTO (Spatie Laravel Data) specifically for updates.
- Partial updates are performed via dedicated Update Form DTOs that mark fields as optional and validate only when present.
- This applies across Core services and Crud services. Controllers/adapters are responsible for resolving the model instance before calling the service.

---

### Retrieval & Query DTOs (Read-side Pattern)

- Core may perform read-only queries using Eloquent's read APIs (Model::query(), get(), find(), paginate()). Reads must never trigger writes.
- Use dedicated Query DTOs in Core to represent allowed filters for each aggregate:
  - CampaignQuery: status, campaign_category_id, created_by_user_id, search
  - CampaignCategoryQuery: search
- Services accept either an array or the corresponding Query DTO; arrays are normalized to Query DTOs before applying filters. This keeps adapters simple while preserving Core boundaries.
- Always build the base query using Eloquent's when() for conditional filters rather than if statements for improved readability and composability. Example (read-only):
  - $query = Model::query()
      ->when($dto->status, fn ($q, $v) => $q->where('status', $v))
      ->when($dto->search, function ($q, $term) { $q->where(fn ($n) => $n->where('title', 'like', "%{$term}%")->orWhere('short_description', 'like', "%{$term}%")); });

Reminder: Adapters → Core only. Persistence remains exclusive to ModelCrudService for writes.

#### Controllers should pass validated Query DTOs
- Spatie Data DTOs are first-class request params. In Adapters/controllers, construct Query DTOs directly from query() using validateAndCreate and pass the DTO to Core services (not raw arrays).
- Example: $filters = CampaignQuery::validateAndCreate($request->query()); $service->paginate($perPage, $filters);
