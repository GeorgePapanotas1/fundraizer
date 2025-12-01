<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Campaign · Fundraiser</title>

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
                        <h1 class="h2">Create a new campaign</h1>
                        <p class="text-[color:var(--fg-muted)]">Provide details about your initiative. You can edit
                            later.</p>
                    </div>
                    <a href="/campaigns" class="btn-subtle">Cancel</a>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="title" class="label">Title</label>
                        <input id="title" class="input" placeholder="e.g., Community Tree Planting"/>
                    </div>

                    <div class="md:col-span-2">
                        <label for="short_description" class="label">Short description</label>
                        <input id="short_description" class="input"
                               placeholder="A concise one-liner about the campaign"/>
                    </div>

                    <div>
                        <label for="category" class="label">Category</label>
                        <select id="category" class="select">
                            <option value="">Select category</option>
                            <option>Environment</option>
                            <option>Education</option>
                            <option>Health</option>
                            <option>Community</option>
                        </select>
                        <p class="help-text">Categories are curated by Admin.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="goal_amount" class="label">Goal amount</label>
                            <input id="goal_amount" type="number" class="input" placeholder="50000"/>
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
                            <input id="start_date" type="date" class="input"/>
                        </div>
                        <div>
                            <label for="end_date" class="label">End date</label>
                            <input id="end_date" type="date" class="input"/>
                        </div>
                    </div>

                    <div>
                        <label for="status" class="label">Status</label>
                        <select id="status" class="select">
                            <option>Draft</option>
                            <option>Active</option>
                            <option>Closed</option>
                        </select>
                    </div>

                    <div>
                        <label for="cover" class="label">Cover image URL</label>
                        <input id="cover" class="input" placeholder="https://…"/>
                        <p class="help-text">Use a 16:9 image for best results.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="label">Long description</label>
                        <textarea id="description" class="textarea"
                                  placeholder="Tell the story, goals, and impact plan…"></textarea>
                    </div>

                    <div class="md:col-span-2 flex items-center justify-between">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" class="sr-only peer"/>
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

                    <div>
                        <label for="location" class="label">Location (optional)</label>
                        <input id="location" class="input" placeholder="City, Country"/>
                    </div>

                    <div>
                        <label for="tags" class="label">Tags (optional)</label>
                        <input id="tags" class="input" placeholder="e.g., trees, climate, volunteer"/>
                        <p class="help-text">Separate by commas. Example: trees, climate, volunteer</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="/campaigns" class="btn-subtle">Cancel</a>
                    <button type="button" class="btn-primary">Create campaign</button>
                </div>
            </form>

            <!-- Meta / Help sidebar -->
            <aside class="space-y-6">
                <div class="card card-section space-y-3">
                    <h3 class="h4">Guidelines</h3>
                    <ul class="list-disc pl-5 text-sm text-[color:var(--fg-muted)] space-y-1">
                        <li>Use clear, respectful language.</li>
                        <li>Set a realistic, measurable goal.</li>
                        <li>Include timelines and partners if relevant.</li>
                        <li>Admins review featured requests.</li>
                    </ul>
                </div>
                <div class="card card-section space-y-2 text-sm">
                    <div class="text-[color:var(--fg-muted)]">Persistence reminder</div>
                    <p class="text-[color:var(--fg-muted)]">This page is static HTML for design. Actual writes must go
                        through ModelCrudService according to our DDD rules.</p>
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
