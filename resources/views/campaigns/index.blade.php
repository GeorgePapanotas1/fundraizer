<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Campaigns · Fundraiser</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Assets: include your compiled CSS/JS as needed (kept static for Vue migration) -->
    <!-- Example during dev: use your main layout or add <link rel="stylesheet" href="/build/assets/app.css"> when built -->
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
    <!-- Page header -->
    <section class="section-padding">
        <div class="container-page flex flex-col gap-6">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <h1 class="h2">Campaigns</h1>
                    <p class="text-[color:var(--fg-muted)]">Browse active initiatives and find a cause to support.</p>
                </div>
                <div class="hidden sm:flex items-center gap-3">
                    <a href="/campaigns/create" class="btn-primary">Create</a>
                    <a href="#" class="btn-subtle">Guidelines</a>
                </div>
            </div>

            <!-- Filters toolbar -->
            <div class="card card-section">
                <div class="grid gap-4 md:grid-cols-4">
                    <div class="md:col-span-2">
                        <label class="label" for="search">Search</label>
                        <input id="search" type="search" class="input" placeholder="Search by title or description"/>
                    </div>
                    <div>
                        <label class="label" for="category">Category</label>
                        <select id="category" class="select">
                            <option value="">All</option>
                            <option>Education</option>
                            <option>Environment</option>
                            <option>Health</option>
                            <option>Community</option>
                        </select>
                    </div>
                    <div>
                        <label class="label" for="status">Status</label>
                        <select id="status" class="select">
                            <option value="">Any</option>
                            <option>Draft</option>
                            <option>Active</option>
                            <option>Closed</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm">
                        <span class="badge-info">Sort: Newest</span>
                        <span class="badge-success">Active</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="btn-subtle">Clear filters</button>
                        <button class="btn-soft">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Listing grid -->
    <section class="pb-12 sm:pb-16 lg:pb-24">
        <div class="container-page grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <!-- Card template x 8 examples -->
            <article class="card card-hover overflow-hidden flex flex-col">
                <div class="aspect-[16/9] bg-[color:var(--color-primary-50)] pattern-dots"></div>
                <div class="card-section flex-1 flex flex-col gap-3">
                    <h3 class="h3">Community Tree Planting</h3>
                    <p class="text-[color:var(--fg-muted)]">Help us plant 10,000 trees across five regions in the next
                        quarter.</p>
                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-sm text-[color:var(--fg-muted)]">Goal: $50,000</span>
                        <a href="#" class="btn-soft">View</a>
                    </div>
                </div>
            </article>

            <article class="card card-hover overflow-hidden flex flex-col">
                <div class="aspect-[16/9] bg-[color:var(--color-secondary-50)] pattern-grid"></div>
                <div class="card-section flex-1 flex flex-col gap-3">
                    <h3 class="h3">STEM Kits for Schools</h3>
                    <p class="text-[color:var(--fg-muted)]">Sponsor hands-on science kits for under-resourced
                        classrooms.</p>
                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-sm text-[color:var(--fg-muted)]">Goal: $25,000</span>
                        <a href="#" class="btn-soft">View</a>
                    </div>
                </div>
            </article>

            <article class="card card-hover overflow-hidden flex flex-col">
                <div class="aspect-[16/9] bg-[color:var(--color-accent-50)] pattern-dots"></div>
                <div class="card-section flex-1 flex flex-col gap-3">
                    <h3 class="h3">Emergency Relief Fund</h3>
                    <p class="text-[color:var(--fg-muted)]">Rapid response for communities affected by natural
                        disasters.</p>
                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-sm text-[color:var(--fg-muted)]">Goal: $100,000</span>
                        <a href="#" class="btn-soft">View</a>
                    </div>
                </div>
            </article>

            <article class="card card-hover overflow-hidden flex flex-col">
                <div class="aspect-[16/9] bg-[color:var(--color-primary-50)] pattern-grid"></div>
                <div class="card-section flex-1 flex flex-col gap-3">
                    <h3 class="h3">Clean Water Access</h3>
                    <p class="text-[color:var(--fg-muted)]">Building wells and sanitation infrastructure with local
                        partners.</p>
                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-sm text-[color:var(--fg-muted)]">Goal: $80,000</span>
                        <a href="#" class="btn-soft">View</a>
                    </div>
                </div>
            </article>

            <article class="card card-hover overflow-hidden flex flex-col">
                <div class="aspect-[16/9] bg-[color:var(--color-secondary-50)] pattern-dots"></div>
                <div class="card-section flex-1 flex flex-col gap-3">
                    <h3 class="h3">Community Health Clinics</h3>
                    <p class="text-[color:var(--fg-muted)]">Mobile clinics to reach remote rural populations.</p>
                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-sm text-[color:var(--fg-muted)]">Goal: $120,000</span>
                        <a href="#" class="btn-soft">View</a>
                    </div>
                </div>
            </article>

            <article class="card card-hover overflow-hidden flex flex-col">
                <div class="aspect-[16/9] bg-[color:var(--color-accent-50)] pattern-grid"></div>
                <div class="card-section flex-1 flex flex-col gap-3">
                    <h3 class="h3">Girls in Tech Mentorship</h3>
                    <p class="text-[color:var(--fg-muted)]">Mentorship and scholarships for aspiring technologists.</p>
                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-sm text-[color:var(--fg-muted)]">Goal: $40,000</span>
                        <a href="#" class="btn-soft">View</a>
                    </div>
                </div>
            </article>

            <article class="card card-hover overflow-hidden flex flex-col">
                <div class="aspect-[16/9] bg-[color:var(--color-primary-50)] pattern-dots"></div>
                <div class="card-section flex-1 flex flex-col gap-3">
                    <h3 class="h3">Food Security Program</h3>
                    <p class="text-[color:var(--fg-muted)]">Community fridges and partnerships with local farms.</p>
                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-sm text-[color:var(--fg-muted)]">Goal: $60,000</span>
                        <a href="#" class="btn-soft">View</a>
                    </div>
                </div>
            </article>

            <article class="card card-hover overflow-hidden flex flex-col">
                <div class="aspect-[16/9] bg-[color:var(--color-secondary-50)] pattern-grid"></div>
                <div class="card-section flex-1 flex flex-col gap-3">
                    <h3 class="h3">Habitat Restoration</h3>
                    <p class="text-[color:var(--fg-muted)]">Restoring native habitats to protect biodiversity.</p>
                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-sm text-[color:var(--fg-muted)]">Goal: $70,000</span>
                        <a href="#" class="btn-soft">View</a>
                    </div>
                </div>
            </article>
        </div>

        <!-- Pagination (static) -->
        <div class="container-page mt-8 flex items-center justify-between text-sm">
            <div class="text-[color:var(--fg-muted)]">Showing 1–8 of 128</div>
            <div class="flex items-center gap-2">
                <button class="btn-subtle">Previous</button>
                <button class="btn-soft">1</button>
                <button class="btn-subtle">2</button>
                <button class="btn-subtle">3</button>
                <button class="btn-subtle">Next</button>
            </div>
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
