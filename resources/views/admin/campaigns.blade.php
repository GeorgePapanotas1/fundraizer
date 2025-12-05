<!doctype html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Admin • Campaign Moderation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Static markup only. Include compiled CSS/JS when integrating (Vite). -->
</head>
<body class="bg-[color:var(--bg-app)] text-[color:var(--fg-app)]">
<header class="border-b border-[color:var(--border)]">
    <div class="container-page h-16 flex items-center justify-between">
        <a href="/admin" class="flex items-center gap-2 font-semibold">
            <span
                class="h-7 w-7 rounded-lg bg-gradient-to-br from-[color:var(--color-primary-400)] to-[color:var(--color-primary-600)]"></span>
            <span>Fundraiser Admin</span>
        </a>
        <nav class="flex items-center gap-6 text-sm">
            <a class="hover:text-[color:var(--fg-strong)]" href="/admin">Overview</a>
            <a class="text-[color:var(--fg-strong)]" href="/admin/campaigns">Campaigns</a>
            <a class="hover:text-[color:var(--fg-strong)]" href="/admin/categories">Categories</a>
            <a class="hover:text-[color:var(--fg-strong)]" href="/admin/users">Users</a>
        </nav>
    </div>
    <div class="bg-[color:var(--bg-subtle)] border-t border-[color:var(--border)]">
        <div class="container-page py-8">
            <h1 class="h2">Campaign Moderation</h1>
            <p class="text-[color:var(--fg-muted)] mt-1">Approve or reject campaigns pending review.</p>
        </div>
    </div>
</header>

<main>
    <section class="section-padding">
        <div class="container-page grid md:flex gap-6">
            <aside class="md:w-1/4 card p-4 space-y-4">
                <div>
                    <label class="label" for="q">Search</label>
                    <input id="q" class="input" placeholder="Find campaigns..."/>
                </div>
                <div>
                    <label class="label" for="status">Status</label>
                    <select id="status" class="select">
                        <option>Pending approval</option>
                        <option>Rejected</option>
                        <option>Approved</option>
                    </select>
                </div>
                <div>
                    <label class="label" for="category">Category</label>
                    <select id="category" class="select">
                        <option>All</option>
                        <option>Environment</option>
                        <option>Education</option>
                        <option>Community</option>
                    </select>
                </div>
                <p class="help-text">Reads must be read-only in Core. Writes (approve/reject) go through
                    ModelCrudService.</p>
            </aside>

            <div class="md:w-3/4 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="h4">Pending (12)</h2>
                    <div class="flex items-center gap-2 text-sm">
                        <button class="btn-subtle">Newest</button>
                        <button class="btn-subtle">Most funded</button>
                        <button class="btn-subtle">Ending soon</button>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <!-- Card example -->
                    <div class="card card-hover overflow-hidden">
                        <div
                            class="h-28 bg-gradient-to-br from-[color:var(--color-primary-100)] to-[color:var(--color-accent-100)]"></div>
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-medium leading-tight">Eco Tree Planting</h3>
                                    <div class="text-sm text-[color:var(--fg-muted)]">Environment • Submitted by Jane
                                        Doe
                                    </div>
                                </div>
                                <span class="badge">Goal $10,000</span>
                            </div>
                            <p class="text-sm">Company-wide volunteering and donation drive to plant 5,000 trees.</p>
                            <div class="flex items-center gap-2">
                                <button class="btn-primary">Approve</button>
                                <button class="btn-danger">Reject</button>
                                <button class="btn-subtle">View</button>
                            </div>
                        </div>
                    </div>

                    <div class="card card-hover overflow-hidden">
                        <div
                            class="h-28 bg-gradient-to-br from-[color:var(--color-secondary-100)] to-[color:var(--color-secondary-200)]"></div>
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-medium leading-tight">STEM Kits for Schools</h3>
                                    <div class="text-sm text-[color:var(--fg-muted)]">Education • Submitted by Alex
                                        Smith
                                    </div>
                                </div>
                                <span class="badge">Goal $5,000</span>
                            </div>
                            <p class="text-sm">Providing hands-on STEM kits to underserved classrooms.</p>
                            <div class="flex items-center gap-2">
                                <button class="btn-primary">Approve</button>
                                <button class="btn-danger">Reject</button>
                                <button class="btn-subtle">View</button>
                            </div>
                        </div>
                    </div>

                    <!-- Duplicate a few for layout realism -->
                    <div class="card card-hover overflow-hidden">
                        <div
                            class="h-28 bg-gradient-to-br from-[color:var(--color-accent-100)] to-[color:var(--color-accent-200)]"></div>
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-medium leading-tight">Community Food Bank</h3>
                                    <div class="text-sm text-[color:var(--fg-muted)]">Community • Submitted by Maria
                                    </div>
                                </div>
                                <span class="badge">Goal $20,000</span>
                            </div>
                            <p class="text-sm">Support weekly food distributions for families in need.</p>
                            <div class="flex items-center gap-2">
                                <button class="btn-primary">Approve</button>
                                <button class="btn-danger">Reject</button>
                                <button class="btn-subtle">View</button>
                            </div>
                        </div>
                    </div>

                    <div class="card card-hover overflow-hidden">
                        <div
                            class="h-28 bg-gradient-to-br from-[color:var(--color-primary-100)] to-[color:var(--color-secondary-100)]"></div>
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-medium leading-tight">Clean Water Initiative</h3>
                                    <div class="text-sm text-[color:var(--fg-muted)]">Health • Submitted by Lee</div>
                                </div>
                                <span class="badge">Goal $15,000</span>
                            </div>
                            <p class="text-sm">Install filtration systems in rural communities.</p>
                            <div class="flex items-center gap-2">
                                <button class="btn-primary">Approve</button>
                                <button class="btn-danger">Reject</button>
                                <button class="btn-subtle">View</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between text-sm">
                    <div class="text-[color:var(--fg-muted)]">Showing 1–8 of 12</div>
                    <div class="flex items-center gap-2">
                        <button class="btn-subtle">Previous</button>
                        <button class="btn-subtle">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="mt-12 border-t border-[color:var(--border)]">
    <div class="container-page py-8 text-sm text-[color:var(--fg-muted)] flex items-center justify-between">
        <div>© 2025 Fundraiser • Admin</div>
        <div class="flex items-center gap-4">
            <a href="/admin" class="hover:text-[color:var(--fg-app)]">Overview</a>
        </div>
    </div>
</footer>
</body>
</html>
