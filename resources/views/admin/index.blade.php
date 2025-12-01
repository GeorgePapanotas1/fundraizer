<!doctype html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Admin • Overview</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Include your compiled CSS/JS via Vite or static links when integrating. This page is static markup. -->
</head>
<body class="bg-[color:var(--bg-app)] text-[color:var(--fg-app)]">
<header class="border-b border-[color:var(--border)] bg-[color:var(--bg-elevated))]">
    <div class="container-page h-16 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2 font-semibold">
            <span
                class="h-7 w-7 rounded-lg bg-gradient-to-br from-[color:var(--color-primary-400)] to-[color:var(--color-primary-600)]"></span>
            <span>Fundraiser Admin</span>
        </a>
        <nav class="flex items-center gap-6 text-sm">
            <a class="hover:text-[color:var(--fg-strong)]" href="/admin">Overview</a>
            <a class="hover:text-[color:var(--fg-strong)]" href="/admin/campaigns">Campaigns</a>
            <a class="hover:text-[color:var(--fg-strong)]" href="/admin/categories">Categories</a>
            <a class="hover:text-[color:var(--fg-strong)]" href="/admin/users">Users</a>
        </nav>
    </div>
    <div class="pattern-grid h-px"></div>
    <div class="pattern-dots h-px"></div>
    <div class="sr-only">Admin overview. All writes must go through ModelCrudService per project rules.</div>
    <!-- Reminder: Adapters → Core. Core reads are read-only. -->
    <div class="sr-only">Static markup only, no Blade composition.</div>
    <div class="border-t border-[color:var(--border)]"></div>
    <div class="bg-[color:var(--bg-subtle)]">
        <div class="container-page py-8">
            <h1 class="h2">Administration</h1>
            <p class="text-[color:var(--fg-muted)] mt-1">Moderate campaigns, manage categories, and administer
                users.</p>
        </div>
    </div>
    <div class="border-b border-[color:var(--border)]"></div>
</header>

<main>
    <section class="section-padding">
        <div class="container-page grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="/admin/campaigns" class="card card-hover p-6 block">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="h4">Campaign Moderation</h3>
                        <p class="text-sm text-[color:var(--fg-muted)] mt-1">Approve or reject pending campaigns.</p>
                    </div>
                    <span
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-[color:var(--color-primary-100)] text-[color:var(--color-primary-700)]">✓</span>
                </div>
                <div class="mt-4 flex items-center gap-2 text-sm text-[color:var(--fg-muted)]">
                    <span class="badge">12 pending</span>
                    <span>•</span>
                    <span>24 reviewed this week</span>
                </div>
            </a>
            <a href="/admin/categories" class="card card-hover p-6 block">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="h4">Categories</h3>
                        <p class="text-sm text-[color:var(--fg-muted)] mt-1">Curate and organize campaign
                            categories.</p>
                    </div>
                    <span
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-[color:var(--color-secondary-100)] text-[color:var(--color-secondary-700)]">#</span>
                </div>
                <div class="mt-4 flex items-center gap-2 text-sm text-[color:var(--fg-muted)]">
                    <span>18 total</span>
                </div>
            </a>
            <a href="/admin/users" class="card card-hover p-6 block">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="h4">Users & Roles</h3>
                        <p class="text-sm text-[color:var(--fg-muted)] mt-1">Add/remove users and grant admin
                            rights.</p>
                    </div>
                    <span
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-[color:var(--color-accent-100)] text-[color:var(--color-accent-700)]">👤</span>
                </div>
                <div class="mt-4 flex items-center gap-2 text-sm text-[color:var(--fg-muted)]">
                    <span>2 admins</span>
                    <span>•</span>
                    <span>124 employees</span>
                </div>
            </a>
        </div>
    </section>

    <section class="section-padding">
        <div class="container-page grid md:grid-cols-3 gap-6">
            <div class="card p-6 md:col-span-2">
                <h3 class="h4">Recent moderation activity</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="h-2.5 w-2.5 rounded-full bg-[color:var(--color-success-500)]"></span>
                            <span><strong>Eco Tree Planting</strong> approved by <span
                                    class="text-[color:var(--fg-muted)]">csr_admin</span></span>
                        </div>
                        <span class="text-[color:var(--fg-muted)]">2h ago</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="h-2.5 w-2.5 rounded-full bg-[color:var(--color-danger-500)]"></span>
                            <span><strong>Winter Drive</strong> rejected (insufficient details)</span>
                        </div>
                        <span class="text-[color:var(--fg-muted)]">1d ago</span>
                    </li>
                </ul>
            </div>
            <div class="card p-6">
                <h3 class="h4">Guidance</h3>
                <p class="help-text mt-2">This is static UI. In production, all writes (approve/reject, create/delete)
                    go through core/services/crud/ModelCrudService.php. Core services must only perform read-only
                    queries. Adapters depend on Core, never the other way around.</p>
            </div>
        </div>
    </section>
</main>

<footer class="mt-12 border-t border-[color:var(--border)]">
    <div class="container-page py-8 text-sm text-[color:var(--fg-muted)] flex items-center justify-between">
        <div>© 2025 Fundraiser • Admin</div>
        <div class="flex items-center gap-4">
            <a href="/campaigns" class="hover:text-[color:var(--fg-app)]">Back to campaigns</a>
        </div>
    </div>
</footer>
</body>
</html>
