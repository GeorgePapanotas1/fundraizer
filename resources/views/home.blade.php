@extends('layouts.app')

@section('title', 'Home · ' . config('app.name', 'Fundraiser'))

@section('content')
  <!-- Hero -->
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
          <div class="mt-4">
            <a href="#campaigns" class="btn-soft">View campaign</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats row -->
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

  <!-- Featured campaigns -->
  <section id="campaigns" class="section-padding">
    <div class="container-page">
      <div class="flex items-end justify-between mb-6">
        <div>
          <h2 class="h2">Featured campaigns</h2>
          <p class="text-[color:var(--fg-muted)]">Hand-picked initiatives making a difference right now.</p>
        </div>
        <a href="#" class="btn-subtle">Browse all</a>
      </div>

      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Card 1 -->
        <article class="card card-hover overflow-hidden flex flex-col">
          <div class="aspect-[16/9] bg-[color:var(--color-primary-50)] pattern-dots"></div>
          <div class="card-section flex-1 flex flex-col gap-3">
            <h3 class="h3">Community Tree Planting</h3>
            <p class="text-[color:var(--fg-muted)]">Help us plant 10,000 trees across five regions in the next quarter.</p>
            <div class="mt-auto flex items-center justify-between">
              <span class="text-sm text-[color:var(--fg-muted)]">Goal: $50,000</span>
              <a href="#" class="btn-soft">View details</a>
            </div>
          </div>
        </article>

        <!-- Card 2 -->
        <article class="card card-hover overflow-hidden flex flex-col">
          <div class="aspect-[16/9] bg-[color:var(--color-secondary-50)] pattern-grid"></div>
          <div class="card-section flex-1 flex flex-col gap-3">
            <h3 class="h3">STEM Kits for Schools</h3>
            <p class="text-[color:var(--fg-muted)]">Sponsor hands-on science kits for under-resourced classrooms.</p>
            <div class="mt-auto flex items-center justify-between">
              <span class="text-sm text-[color:var(--fg-muted)]">Goal: $25,000</span>
              <a href="#" class="btn-soft">View details</a>
            </div>
          </div>
        </article>

        <!-- Card 3 -->
        <article class="card card-hover overflow-hidden flex flex-col">
          <div class="aspect-[16/9] bg-[color:var(--color-accent-50)] pattern-dots"></div>
          <div class="card-section flex-1 flex flex-col gap-3">
            <h3 class="h3">Emergency Relief Fund</h3>
            <p class="text-[color:var(--fg-muted)]">Rapid response for communities affected by natural disasters.</p>
            <div class="mt-auto flex items-center justify-between">
              <span class="text-sm text-[color:var(--fg-muted)]">Goal: $100,000</span>
              <a href="#" class="btn-soft">View details</a>
            </div>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="section-padding">
    <div class="container-page">
      <div class="card card-section flex flex-col lg:flex-row items-center justify-between gap-6">
        <div>
          <h3 class="h3">Start a campaign</h3>
          <p class="text-[color:var(--fg-muted)]">Have an idea to create impact? Launch your employee-led initiative today.</p>
        </div>
        <div class="flex items-center gap-3">
          <a href="#start" class="btn-primary">Create campaign</a>
          <a href="#guidelines" class="btn-subtle">Read guidelines</a>
        </div>
      </div>
    </div>
  </section>
@endsection
