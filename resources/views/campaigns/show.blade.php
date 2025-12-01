<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Campaign · Fundraiser</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Static markup for Vue migration; include built CSS/JS as needed when integrating. -->
</head>
<body class="min-h-screen bg-[color:var(--bg-app)] text-[color:var(--fg-app)]">
<!-- Header -->
<header class="border-b border-[color:var(--border)] bg-[color:var(--bg-surface)]">
    <div class="container-page h-16 flex items-center justify-between">
        <a href="/" class="text-lg font-semibold">Fundraiser</a>
        <nav class="hidden md:flex items-center gap-6 text-sm text-[color:var(--fg-muted)]">
            <a href="/campaigns" class="hover:text-[color:var(--fg-app)]">Campaigns</a>
            <a href="#about" class="hover:text-[color:var(--fg-app)]">About</a>
            <a href="#donations" class="hover:text-[color:var(--fg-app)]">My Donations</a>
        </nav>
        <div class="flex items-center gap-3">
            <a class="btn-subtle">Sign in</a>
            <a href="/campaigns/create" class="btn-primary">Start a Campaign</a>
        </div>
    </div>
</header>

<main>
    <!-- Cover / Hero -->
    <section class="relative">
        <div class="h-64 sm:h-80 lg:h-96 w-full overflow-hidden bg-[color:var(--color-primary-50)] pattern-grid">
            <img alt="Campaign cover"
                 src="https://images.unsplash.com/photo-1523978591478-c753949ff840?q=80&w=1920&auto=format&fit=crop"
                 class="w-full h-full object-cover opacity-90"/>
        </div>
        <div class="container-page mt-12 sm:-mt-16">
            <div class="card card-section mt-12">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="space-y-2">
                        <a href="/campaigns"
                           class="text-sm text-[color:var(--fg-muted)] hover:text-[color:var(--fg-app)]">← Back to
                            campaigns</a>
                        <h1 class="h2">Community Tree Planting</h1>
                        <div class="flex flex-wrap items-center gap-2 text-sm">
                            <span class="badge-info">Environment</span>
                            <span class="badge-success">Active</span>
                            <span class="badge">Created by jane.doe</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="/campaigns/123/edit" class="btn-subtle">Manage</a>
                        <a href="#donate" class="btn-accent">Donate</a>
                    </div>
                </div>

                <!-- Progress -->
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="sm:col-span-2 lg:col-span-2">
                        <div class="flex items-center justify-between text-sm mb-2">
                            <div><span class="font-medium">$28,450</span> raised</div>
                            <div class="text-[color:var(--fg-muted)]">Goal $50,000</div>
                        </div>
                        <div
                            class="h-2 rounded-[var(--radius-full)] bg-[color:var(--color-neutral-200)] overflow-hidden">
                            <div class="h-full bg-[color:var(--color-primary-600)]" style="width:57%"></div>
                        </div>
                        <div class="mt-2 text-xs text-[color:var(--fg-muted)]">534 donors • 32 days left</div>
                    </div>
                    <div class="flex sm:justify-start items-center">
                        <a href="#donate" class="btn-primary w-full sm:w-auto">Contribute now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content + Sidebar -->
    <section class="section-padding">
        <div class="container-page grid gap-8 lg:grid-cols-[1fr,360px]">
            <!-- Main content -->
            <div class="space-y-6">
                <article class="card card-section space-y-4">
                    <h2 class="h3">About this campaign</h2>
                    <p class="text-[color:var(--fg-muted)]">Our employee-led effort to reforest degraded areas through
                        community planting events and partnerships. Funds will support saplings, tools, local
                        coordinators, and follow-up maintenance to ensure long-term survivability.</p>
                    <p class="text-[color:var(--fg-muted)]">We are partnering with regional environmental groups and
                        municipalities to prioritize high-impact zones. Volunteers from our company will participate in
                        weekend planting drives across five regions.</p>
                </article>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="card card-section">
                        <h3 class="h4 mb-2">Impact highlights</h3>
                        <ul class="list-disc pl-5 text-sm text-[color:var(--fg-muted)] space-y-1">
                            <li>10,000 trees planted across five regions</li>
                            <li>Community engagement through volunteer days</li>
                            <li>Improved biodiversity and soil stability</li>
                        </ul>
                    </div>
                    <div class="card card-section">
                        <h3 class="h4 mb-2">Timeline</h3>
                        <ul class="text-sm text-[color:var(--fg-muted)] space-y-1">
                            <li><span class="font-medium text-[color:var(--fg-app)]">Jan 02</span> — Campaign launched
                            </li>
                            <li><span class="font-medium text-[color:var(--fg-app)]">Feb 10</span> — First 2,000
                                saplings secured
                            </li>
                            <li><span class="font-medium text-[color:var(--fg-app)]">Mar 12</span> — Region A planting
                                weekend
                            </li>
                        </ul>
                    </div>
                </div>

                <article class="card card-section space-y-4">
                    <h3 class="h4">Updates</h3>
                    <div class="space-y-3">
                        <div class="border-b border-[color:var(--border)] pb-3">
                            <div class="text-sm text-[color:var(--fg-muted)]">Feb 10 • by jane.doe</div>
                            <p>We surpassed 50% of our goal! Thanks to everyone who contributed and volunteered at the
                                kickoff event.</p>
                        </div>
                        <div>
                            <div class="text-sm text-[color:var(--fg-muted)]">Jan 15 • by jane.doe</div>
                            <p>Partnerships confirmed with local nurseries to ensure sapling quality and survival.</p>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <aside class="space-y-6">
                <!-- Quick donate -->
                <div id="donate" class="card card-section space-y-4">
                    <h3 class="h4">Donate</h3>
                    <div class="grid grid-cols-3 gap-2">
                        <button class="btn-soft">$10</button>
                        <button class="btn-soft">$25</button>
                        <button class="btn-soft">$50</button>
                        <button class="btn-soft">$100</button>
                        <button class="btn-soft">$250</button>
                        <button class="btn-soft">$500</button>
                    </div>
                    <div>
                        <label for="amount" class="label">Custom amount</label>
                        <input id="amount" type="number" class="input" placeholder="25"/>
                    </div>
                    <button class="w-full btn-accent">Donate to cause</button>
                    <p class="help-text">Payments are processed securely. Your employer may match donations when
                        enabled.</p>
                </div>

                <!-- Summary -->
                <div class="card card-section space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-[color:var(--fg-muted)]">Goal</span><span>$50,000</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[color:var(--fg-muted)]">Raised</span><span>$28,450</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[color:var(--fg-muted)]">Donors</span><span>534</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[color:var(--fg-muted)]">Days left</span><span>32</span>
                    </div>
                </div>

                <!-- Organizers -->
                <div class="card card-section space-y-3">
                    <h3 class="h4">Organizers</h3>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-[color:var(--color-primary-100)]"></div>
                        <div>
                            <div class="font-medium">Jane Doe</div>
                            <div class="text-sm text-[color:var(--fg-muted)]">Employee • Sustainability Guild</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="btn-subtle w-full">Share</button>
                        <button class="btn-subtle w-full">Contact</button>
                    </div>
                    <p class="help-text">This page is static markup. Actual writes go through ModelCrudService per
                        project rules.</p>
                </div>
            </aside>
        </div>
    </section>
</main>

<footer class="mt-20 border-t border-[color:var(--border)]">
    <div class="container-page py-10 text-sm text-[color:var(--fg-muted)] flex items-center justify-between">
        <div>© 2025 Fundraiser</div>
        <div class="flex items-center gap-4">
            <a href="#privacy" class="hover:text-[color:var(--fg-app)]">Privacy</a>
            <a href="#terms" class="hover:text-[color:var(--fg-app)]">Terms</a>
        </div>
    </div>
</footer>
</body>
</html>
