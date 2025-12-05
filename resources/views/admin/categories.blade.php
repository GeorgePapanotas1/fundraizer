<!doctype html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Admin • Categories</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Static markup; wire to backend later. Include compiled CSS via Vite when integrating. -->
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
            <a class="hover:text-[color:var(--fg-strong)]" href="/admin/campaigns">Campaigns</a>
            <a class="text-[color:var(--fg-strong)]" href="/admin/categories">Categories</a>
            <a class="hover:text-[color:var(--fg-strong)]" href="/admin/users">Users</a>
        </nav>
    </div>
    <div class="bg-[color:var(--bg-subtle)] border-t border-[color:var(--border)]">
        <div class="container-page py-8">
            <h1 class="h2">Manage Categories</h1>
            <p class="text-[color:var(--fg-muted)] mt-1">Create, rename, and archive categories for campaigns.</p>
        </div>
    </div>
</header>

<main>
    <section class="section-padding">
        <div class="container-page grid md:grid-cols-3 gap-6">
            <!-- Create / edit form -->
            <div class="card p-6 space-y-4">
                <h3 class="h4">Create category</h3>
                <div>
                    <label for="cat-name" class="label">Name</label>
                    <input id="cat-name" class="input" placeholder="e.g. Environment"/>
                </div>
                <div>
                    <label for="cat-slug" class="label">Slug</label>
                    <input id="cat-slug" class="input" placeholder="environment"/>
                </div>
                <div>
                    <label for="cat-desc" class="label">Description</label>
                    <textarea id="cat-desc" class="textarea" rows="3" placeholder="Short description"></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <button class="btn-primary">Add category</button>
                    <button class="btn-subtle">Reset</button>
                </div>
                <p class="help-text">Writes go through core/services/crud/ModelCrudService.php. This page is static.</p>
            </div>

            <!-- List -->
            <div class="md:col-span-2 card p-0 overflow-hidden">
                <div class="p-4 border-b border-[color:var(--border)] flex items-center justify-between">
                    <div class="h4">Existing categories</div>
                    <div class="flex items-center gap-2 text-sm">
                        <button class="btn-subtle">All</button>
                        <button class="btn-subtle">Active</button>
                        <button class="btn-subtle">Archived</button>
                    </div>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-left text-[color:var(--fg-muted)]">
                            <tr>
                                <th class="py-2">Name</th>
                                <th class="py-2">Slug</th>
                                <th class="py-2">Campaigns</th>
                                <th class="py-2 text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-[color:var(--border)]">
                            <tr>
                                <td class="py-3">Environment</td>
                                <td>environment</td>
                                <td>42</td>
                                <td class="text-right">
                                    <button class="btn-subtle">Rename</button>
                                    <button class="btn-danger">Archive</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3">Education</td>
                                <td>education</td>
                                <td>18</td>
                                <td class="text-right">
                                    <button class="btn-subtle">Rename</button>
                                    <button class="btn-danger">Archive</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3">Community</td>
                                <td>community</td>
                                <td>27</td>
                                <td class="text-right">
                                    <button class="btn-subtle">Rename</button>
                                    <button class="btn-danger">Archive</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-[color:var(--fg-muted)]">
                        <div>Showing 1–3 of 18</div>
                        <div class="flex items-center gap-2 text-sm">
                            <button class="btn-subtle">Previous</button>
                            <button class="btn-subtle">Next</button>
                        </div>
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
