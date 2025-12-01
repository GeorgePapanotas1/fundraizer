<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Campaign · Fundraiser</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Include your compiled CSS/JS as needed; kept static for Vue migration. -->
    <!-- Example: <link rel="stylesheet" href="/build/assets/app.css"> -->
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
    <section class="section-padding">
        <div class="container-page grid lg:grid-cols-[1fr,320px] gap-8">
            <!-- Form -->
            <form class="card card-section stack-lg">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="h2">Edit campaign</h1>
                        <p class="text-[color:var(--fg-muted)]">Update your campaign details. Changes are saved once you
                            confirm.</p>
                    </div>
                    <a href="/campaigns" class="btn-subtle">Back</a>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="title" class="label">Title</label>
                        <input id="title" class="input" value="Community Tree Planting"/>
                    </div>

                    <div class="md:col-span-2">
                        <label for="short_description" class="label">Short description</label>
                        <input id="short_description" class="input"
                               value="Help us plant 10,000 trees across five regions."/>
                    </div>

                    <div>
                        <label for="category" class="label">Category</label>
                        <select id="category" class="select">
                            <option>Environment</option>
                            <option>Education</option>
                            <option>Health</option>
                            <option>Community</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="goal_amount" class="label">Goal amount</label>
                            <input id="goal_amount" type="number" class="input" value="50000"/>
                        </div>
                        <div>
                            <label for="currency" class="label">Currency</label>
                            <select id="currency" class="select">
                                <option>USD</option>
                                <option>EUR</option>
                                <option>GBP</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="start_date" class="label">Start date</label>
                            <input id="start_date" type="date" class="input" value="2025-01-01"/>
                        </div>
                        <div>
                            <label for="end_date" class="label">End date</label>
                            <input id="end_date" type="date" class="input" value="2025-06-30"/>
                        </div>
                    </div>

                    <div>
                        <label for="status" class="label">Status</label>
                        <select id="status" class="select">
                            <option>Draft</option>
                            <option selected>Active</option>
                            <option>Closed</option>
                        </select>
                    </div>

                    <div>
                        <label for="cover" class="label">Cover image URL</label>
                        <input id="cover" class="input" value="https://images.example/trees.jpg"/>
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="label">Long description</label>
                        <textarea id="description" class="textarea">Our employee-led effort to reforest degraded areas through community planting events and partnerships.</textarea>
                    </div>

                    <div class="md:col-span-2 flex items-center justify-between">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" class="sr-only peer" checked/>
                            <span class="switch peer-checked:switch-on">
                  <span class="switch-thumb peer-checked:switch-on-thumb"></span>
                </span>
                            <span class="text-sm text-[color:var(--fg-muted)]">Feature this campaign</span>
                        </label>

                        <label class="flex items-center gap-3">
                            <input type="checkbox" class="sr-only peer"/>
                            <span class="switch peer-checked:switch-on">
                  <span class="switch-thumb peer-checked:switch-on-thumb"></span>
                </span>
                            <span class="text-sm text-[color:var(--fg-muted)]">Allow matching donations</span>
                        </label>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                    <button type="button" class="btn-danger">Archive campaign</button>
                    <div class="flex items-center gap-3">
                        <button type="button" class="btn-primary">Save changes</button>
                    </div>
                </div>
            </form>

            <!-- Meta Sidebar -->
            <aside class="space-y-6">
                <div class="card card-section space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-[color:var(--fg-muted)]">ID</span>
                        <span>#1234</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[color:var(--fg-muted)]">Created</span>
                        <span>2025-01-01 10:15</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[color:var(--fg-muted)]">Updated</span>
                        <span>2025-02-10 14:22</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[color:var(--fg-muted)]">Owner</span>
                        <span>jane.doe</span>
                    </div>
                </div>
                <div class="card card-section space-y-2 text-sm">
                    <div class="text-[color:var(--fg-muted)]">Read/Write boundaries</div>
                    <p class="text-[color:var(--fg-muted)]">This is static markup. In the actual app, updates happen via
                        Core services and writes exclusively through ModelCrudService.</p>
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
