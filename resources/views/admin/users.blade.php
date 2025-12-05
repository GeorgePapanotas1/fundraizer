<!doctype html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Admin • Users & Roles</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Static markup only. Wire up to Identity module later. Include compiled CSS/JS via Vite. -->
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
            <a class="hover:text-[color:var(--fg-strong)]" href="/admin/categories">Categories</a>
            <a class="text-[color:var(--fg-strong)]" href="/admin/users">Users</a>
        </nav>
    </div>
    <div class="bg-[color:var(--bg-subtle)] border-t border-[color:var(--border)]">
        <div class="container-page py-8">
            <h1 class="h2">Users & Roles</h1>
            <p class="text-[color:var(--fg-muted)] mt-1">Add users, remove users, and grant admin roles.</p>
        </div>
    </div>
</header>

<main>
    <section class="section-padding">
        <div class="container-page grid md:grid-cols-3 gap-6">
            <!-- Invite/Add user -->
            <div class="card p-6 space-y-4">
                <h3 class="h4">Add user</h3>
                <div class="grid sm:grid-cols-2 gap-3">
                    <div>
                        <label class="label" for="name">Full name</label>
                        <input id="name" class="input" placeholder="Jane Doe"/>
                    </div>
                    <div>
                        <label class="label" for="email">Email</label>
                        <input id="email" type="email" class="input" placeholder="jane.doe@company.com"/>
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-3">
                    <div>
                        <label class="label" for="role">Role</label>
                        <select id="role" class="select">
                            <option value="employee">EMPLOYEE</option>
                            <option value="csr_admin">CSR_ADMIN</option>
                            <option value="system_admin">SYSTEM_ADMIN</option>
                        </select>
                    </div>
                    <div>
                        <label class="label" for="dept">Department (optional)</label>
                        <input id="dept" class="input" placeholder="Sustainability"/>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="btn-primary">Add user</button>
                    <button class="btn-subtle">Reset</button>
                </div>
                <p class="help-text">Actual create/remove/role changes must go through ModelCrudService and Identity
                    services. Static UI only.</p>
            </div>

            <!-- Users table -->
            <div class="md:col-span-2 card p-0 overflow-hidden">
                <div class="p-4 border-b border-[color:var(--border)] flex items-center justify-between">
                    <div class="h4">All users</div>
                    <div class="flex items-center gap-2 text-sm">
                        <input class="input h-9 w-56" placeholder="Search users..."/>
                        <select class="select h-9">
                            <option>All roles</option>
                            <option>EMPLOYEE</option>
                            <option>CSR_ADMIN</option>
                            <option>SYSTEM_ADMIN</option>
                        </select>
                    </div>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-left text-[color:var(--fg-muted)]">
                            <tr>
                                <th class="py-2">Name</th>
                                <th class="py-2">Email</th>
                                <th class="py-2">Role</th>
                                <th class="py-2">Joined</th>
                                <th class="py-2 text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-[color:var(--border)]">
                            <tr>
                                <td class="py-3">System Admin</td>
                                <td>admin@fundraizer.gr</td>
                                <td><span class="badge">SYSTEM_ADMIN</span></td>
                                <td>2025-01-02</td>
                                <td class="text-right">
                                    <button class="btn-subtle">Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3">CSR Admin</td>
                                <td>csr_admin@fundraizer.gr</td>
                                <td><span class="badge">CSR_ADMIN</span></td>
                                <td>2025-02-12</td>
                                <td class="text-right">
                                    <button class="btn-subtle">Make system admin</button>
                                    <button class="btn-danger">Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3">Jane Employee</td>
                                <td>jane@company.com</td>
                                <td><span class="badge">EMPLOYEE</span></td>
                                <td>2025-03-08</td>
                                <td class="text-right">
                                    <button class="btn-primary">Make admin</button>
                                    <button class="btn-danger">Remove</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-[color:var(--fg-muted)]">
                        <div>Showing 1–10 of 124</div>
                        <div class="flex items-center gap-2 text-sm">
                            <button class="btn-subtle">Previous</button>
                            <button class="btn-subtle">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container-page grid md:grid-cols-3 gap-6">
            <div class="card p-6 md:col-span-2">
                <h3 class="h4">Role model</h3>
                <ul class="mt-3 text-sm list-disc pl-5 space-y-2 text-[color:var(--fg-muted)]">
                    <li>EMPLOYEE: default role for company users.</li>
                    <li>CSR_ADMIN: can moderate campaigns and manage categories.</li>
                    <li>SYSTEM_ADMIN: full platform administration.</li>
                </ul>
            </div>
            <div class="card p-6">
                <h3 class="h4">Architecture reminder</h3>
                <p class="help-text mt-2">Adapters → Core, not the other way. Core reads are read-only
                    (Model::query/get/find/paginate). All writes (create user, delete user, assign roles) must go
                    through core/services/crud/ModelCrudService.php and domain services in the Identity module.</p>
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
