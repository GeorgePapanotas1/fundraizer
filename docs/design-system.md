### Fundraiser UI — Tailwind v4 Design System

This is the baseline theme and style guide for all frontend work (Blade and future Vue). It uses Tailwind CSS v4 tokens and utilities defined in resources/css/app.css.

Principles
- Modern enterprise quality with a warm, community tone suitable for CSR initiatives.
- High accessibility (WCAG-oriented color pairs and focus states).
- Consistency via design tokens and reusable utilities (btn, card, input, etc.).
- No JavaScript framework required. All snippets are Blade-ready.

How to use
- Ensure Bunny font is linked in your layout head:
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
- In your Blade layouts: @vite(['resources/css/app.css','resources/js/app.js'])
- For dark mode, toggle the .dark class on <html>.

Tokens (defined in @theme)
- Typography
  - --font-sans: Instrument Sans stack
  - Sizes: --text-xs … --text-6xl
  - Line heights: --leading-tight, --leading-snug, --leading-normal, --leading-relaxed
- Color palette
  - Primary (teal): --color-primary-50 … --color-primary-900 (base: --color-primary-500/600)
  - Secondary (indigo): --color-secondary-50 … --color-secondary-900
  - Accent (coral for donate CTAs): --color-accent-50 … --color-accent-900
  - Neutral (surfaces): --color-neutral-50 … --color-neutral-900
  - Semantic: success/info/warning/danger 50/500/600
  - Surface pairs:
    - Light: --bg-app, --bg-surface, --fg-app, --fg-muted, --border
    - Dark (via :root.dark): overrides for the above
- Radii
  - --radius-xs (4px), --radius-sm (8px), --radius-md (12px), --radius-lg (16px), --radius-xl (24px), --radius-full
- Shadows / elevation
  - --shadow-xs, --shadow-sm, --shadow-md, --shadow-lg, --shadow-inset
- Gradients
  - --gradient-hero, --gradient-accent, --gradient-soft
- Spacing
  - --space-unit = 4px base. Use Tailwind spacing scale (0, 0.5, 1, 1.5, 2, 3, 4, 6, 8, 10, 12, 16, 20, 24, etc.).

Utilities (copy‑paste class names)
- Layout
  - container-page → responsive page container with side paddings
  - section-padding → vertical section spacing
  - stack-md / stack-lg → vertical flex stacks with consistent gaps
- Patterns & Backgrounds
  - hero-surface → soft brand gradient backdrop
  - pattern-grid / pattern-dots → subtle premium textures
- Headings helpers
  - h1, h2, h3, h4 → curated sizes/weights
- Cards
  - card → bordered, rounded, subtle elevation
  - card-hover → hover elevation transition
  - card-section → adaptive paddings
- Buttons
  - btn (base) + variants:
    - btn-primary (teal), btn-secondary (indigo), btn-accent (coral)
    - btn-soft (soft brand background), btn-subtle (quiet bordered)
    - btn-danger
  - All include accessible :focus-visible via focus-ring
- Badges
  - badge + badge-success | badge-info | badge-warn | badge-danger
- Form controls
  - input, select, textarea, label, help-text, invalid
- Switch (checkbox toggle)
  - switch, switch-thumb, switch-on, switch-on-thumb
- Focus ring
  - focus-ring utility is embedded in buttons/inputs via focus-visible state

Component guidelines
- Buttons
  - Primary: use for primary actions (Donate, Create Campaign)
  - Secondary: alternative strong action (Manage, View details)
  - Accent: promotional or conversion moments related to donations
  - Soft/Subtle: low-emphasis actions (Filters, Cancel)
  - Disabled state is included; avoid reduced contrast beyond the provided opacity
- Cards
  - Use card for campaign listing tiles and content sections
  - Combine with card-hover for interactive tiles
- Forms
  - Use label + input/select/textarea + help-text
  - Apply invalid when server-side error occurs
- State styles
  - :hover provides subtle color shifts; :focus-visible uses focus-ring
  - Selected states: use badge or bg-[color:var(--color-primary-50)] on containers

Copyable snippets

1) Page shell
<body class="min-h-screen bg-[color:var(--bg-app)] text-[color:var(--fg-app)]">
  <header class="border-b border-[color:var(--border)] bg-[color:var(--bg-surface)]">
    <div class="container-page h-16 flex items-center justify-between">
      <a class="text-lg font-semibold">Fundraiser</a>
      <nav class="hidden md:flex items-center gap-6 text-sm text-[color:var(--fg-muted)]">
        <a class="hover:text-[color:var(--fg-app)]">Campaigns</a>
        <a class="hover:text-[color:var(--fg-app)]">About</a>
        <a class="hover:text-[color:var(--fg-app)]">My Donations</a>
      </nav>
      <div class="flex items-center gap-3">
        <button class="btn-subtle">Sign in</button>
        <button class="btn-primary">Start a Campaign</button>
      </div>
    </div>
  </header>
  <main>
    <!-- sections here -->
  </main>
</body>

2) Hero section
<section class="hero-surface section-padding">
  <div class="container-page grid lg:grid-cols-2 items-center gap-10">
    <div class="stack-lg">
      <h1 class="h1">Empower Employee Giving</h1>
      <p class="text-lg text-[color:var(--fg-muted)] max-w-prose">Discover, participate, and contribute to initiatives that matter. A trusted space for our community’s impact.</p>
      <div class="flex items-center gap-3">
        <a href="#campaigns" class="btn-accent">Donate now</a>
        <a href="#learn" class="btn-subtle">Learn more</a>
      </div>
      <div class="flex items-center gap-2">
        <span class="badge-success">Trusted</span>
        <span class="badge-info">CSR</span>
        <span class="badge-warn">Employee-led</span>
      </div>
    </div>
    <div class="rounded-[var(--radius-xl)] p-1 bg-[image:var(--gradient-soft)]">
      <div class="card card-hover card-section pattern-grid"> 
        <h3 class="h3 mb-2">This month’s spotlight</h3>
        <p class="text-[color:var(--fg-muted)]">Clean water initiative in rural regions. Every contribution helps communities thrive.</p>
      </div>
    </div>
  </div>
  <div class="container-page mt-12 grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="card card-section text-center">
      <div class="text-3xl font-semibold">12k</div>
      <div class="text-sm text-[color:var(--fg-muted)]">Employees engaged</div>
    </div>
    <div class="card card-section text-center">
      <div class="text-3xl font-semibold">$3.2M</div>
      <div class="text-sm text-[color:var(--fg-muted)]">Raised to date</div>
    </div>
    <div class="card card-section text-center">
      <div class="text-3xl font-semibold">180</div>
      <div class="text-sm text-[color:var(--fg-muted)]">Active campaigns</div>
    </div>
    <div class="card card-section text-center">
      <div class="text-3xl font-semibold">96%</div>
      <div class="text-sm text-[color:var(--fg-muted)]">Satisfaction</div>
    </div>
  </div>
</section>

3) Campaign card (listing)
<article class="card card-hover overflow-hidden flex flex-col">
  <img alt="Cover" src="/images/campaign.jpg" class="aspect-[16/9] object-cover" />
  <div class="card-section flex-1 flex flex-col gap-3">
    <h3 class="h3">Community Tree Planting</h3>
    <p class="text-[color:var(--fg-muted)]">Help us plant 10,000 trees across five regions in the next quarter.</p>
    <div class="mt-auto flex items-center justify-between">
      <span class="text-sm text-[color:var(--fg-muted)]">Goal: $50,000</span>
      <a href="#" class="btn-soft">View details</a>
    </div>
  </div>
</article>

4) Button set (states)
<div class="flex flex-wrap gap-3">
  <button class="btn-primary">Primary</button>
  <button class="btn-secondary">Secondary</button>
  <button class="btn-accent">Accent</button>
  <button class="btn-soft">Soft</button>
  <button class="btn-subtle">Subtle</button>
  <button class="btn-danger">Destructive</button>
  <button class="btn-primary" disabled>Disabled</button>
</div>

5) Form example
<form class="card card-section max-w-xl stack-lg">
  <div>
    <label for="amount" class="label">Donation amount</label>
    <input id="amount" type="number" class="input" placeholder="$25" />
    <p class="help-text">Choose any amount you’re comfortable with.</p>
  </div>

  <div>
    <label for="campaign" class="label">Select campaign</label>
    <select id="campaign" class="select">
      <option>Clean Water</option>
      <option>Tree Planting</option>
    </select>
  </div>

  <div>
    <label for="message" class="label">Message (optional)</label>
    <textarea id="message" class="textarea" placeholder="A note of support…"></textarea>
  </div>

  <div class="flex items-center justify-between">
    <label class="flex items-center gap-3">
      <input type="checkbox" class="sr-only peer" />
      <span class="switch peer-checked:switch-on">
        <span class="switch-thumb peer-checked:switch-on-thumb"></span>
      </span>
      <span class="text-sm text-[color:var(--fg-muted)]">Make my donation anonymous</span>
    </label>
    <button type="submit" class="btn-accent">Donate</button>
  </div>
</form>

6) Grid layout examples
<div class="container-page section-padding">
  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <!-- place campaign cards here -->
  </div>
</div>

7) Admin overview tiles
<div class="container-page section-padding grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
  <div class="card card-section">
    <div class="text-sm text-[color:var(--fg-muted)]">Open campaigns</div>
    <div class="text-3xl font-semibold">48</div>
  </div>
  <div class="card card-section">
    <div class="text-sm text-[color:var(--fg-muted)]">Pending approvals</div>
    <div class="text-3xl font-semibold">7</div>
  </div>
  <div class="card card-section">
    <div class="text-sm text-[color:var(--fg-muted)]">Donations today</div>
    <div class="text-3xl font-semibold">$12,450</div>
  </div>
  <div class="card card-section">
    <div class="text-sm text-[color:var(--fg-muted)]">Refund rate</div>
    <div class="text-3xl font-semibold">0.3%</div>
  </div>
</div>

Accessibility & Contrast
- Primary/Secondary button text is white for adequate contrast.
- Focus-visible rings use the brand primary color with offset; avoid removing outlines.
- Avoid using accent (coral) on red backgrounds to prevent confusion with error.

Persistence & Architecture reminders
- This document is purely presentational. No domain writes here.
- For Laravel Blade, keep business rules in Core. Adapters render views, while Core only performs read-only queries. All writes go through ModelCrudService.
