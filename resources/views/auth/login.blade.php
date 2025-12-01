<!doctype html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Sign in • Fundraiser</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Static, Vue-friendly markup. Wire to Identity/Auth later. -->
</head>
<body class="min-h-screen bg-[color:var(--bg-app)] text-[color:var(--fg-app)]">
<main class="container-page grid min-h-screen">
    <!-- Brand / Visual panel -->
    <section class="relative overflow-hidden hidden md:block">
        <div class="absolute inset-0 hero-surface"></div>
        <div class="absolute inset-0 pattern-grid opacity-50"></div>
        <div class="absolute inset-0 pattern-dots opacity-50"></div>
        <div class="relative h-full w-full p-10 flex flex-col">
            <a href="/" class="flex items-center gap-3">
                <span
                    class="h-9 w-9 rounded-xl bg-gradient-to-br from-[color:var(--color-primary-400)] to-[color:var(--color-primary-700)] shadow-elev-3"></span>
                <span class="font-semibold tracking-tight">Fundraiser</span>
            </a>

            <div class="mt-auto mb-16 max-w-xl">
                <span
                    class="inline-flex items-center gap-2 text-xs uppercase tracking-wider text-[color:var(--fg-muted)]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[color:var(--color-accent-400)]"></span>
                    Corporate Social Responsibility
                </span>
                <h1 class="mt-3 text-4xl/tight font-semibold text-[color:var(--fg-strong)]">
                    A more generous company, together
                </h1>
                <p class="mt-3 text-[color:var(--fg-muted)]">
                    Discover, support, and amplify initiatives across our community. Your donations and time
                    make a measurable impact on people and the planet.
                </p>
                <div class="mt-6 flex items-center gap-4 text-sm">
                    <div class="flex -space-x-2">
                        <span
                            class="h-8 w-8 rounded-full bg-[color:var(--color-primary-200)] border border-[color:var(--border)]"></span>
                        <span
                            class="h-8 w-8 rounded-full bg-[color:var(--color-secondary-200)] border border-[color:var(--border)]"></span>
                        <span
                            class="h-8 w-8 rounded-full bg-[color:var(--color-accent-200)] border border-[color:var(--border)]"></span>
                    </div>
                    <span class="text-[color:var(--fg-muted)]">Trusted by employees company‑wide</span>
                </div>
            </div>

            <div class="text-xs text-[color:var(--fg-muted)]">© 2025 Fundraiser</div>
        </div>
    </section>

    <!-- Sign-in panel -->
    <section class="flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-md">
            <div class="mb-8 md:hidden">
                <a href="/" class="flex items-center gap-3">
                    <span
                        class="h-9 w-9 rounded-xl bg-gradient-to-br from-[color:var(--color-primary-400)] to-[color:var(--color-primary-700)] shadow-elev-3"></span>
                    <span class="font-semibold tracking-tight">Fundraiser</span>
                </a>
            </div>

            <div class="card card-section space-y-6">
                <div>
                    <h2 class="h3">Welcome back</h2>
                    <p class="text-sm text-[color:var(--fg-muted)] mt-1">Sign in with your company account to
                        continue.</p>
                </div>

                <!-- SSO buttons (placeholders) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <button class="btn-subtle w-full"><span class="mr-2">🔒</span> Sign in with SSO</button>
                    <button class="btn-subtle w-full"><span class="mr-2">🌐</span> Microsoft 365</button>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-[color:var(--border)]"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span
                            class="bg-[color:var(--bg-card)] px-3 text-xs text-[color:var(--fg-muted)]">or use email</span>
                    </div>
                </div>

                <!-- Email/password form (static) -->
                <form class="space-y-4" action="#" method="post">
                    <div>
                        <label for="email" class="label">Email</label>
                        <input id="email" name="email" type="email" class="input" placeholder="you@company.com"
                               autocomplete="email"/>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="label">Password</label>
                            <a href="#forgot" class="text-sm text-[color:var(--color-primary-700)] hover:underline">Forgot
                                password?</a>
                        </div>
                        <input id="password" name="password" type="password" class="input" placeholder="••••••••"
                               autocomplete="current-password"/>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 text-sm cursor-pointer select-none">
                            <input type="checkbox"
                                   class="h-4 w-4 rounded border-[color:var(--border)] text-[color:var(--color-primary-600)] focus:ring-[color:var(--color-primary-400)]"/>
                            <span>Remember me</span>
                        </label>
                        <span class="text-sm text-[color:var(--fg-muted)]">Security first</span>
                    </div>
                    <button type="submit" class="w-full btn-primary">Sign in</button>
                </form>

                <p class="help-text">This is static UI for now. When wiring authentication, use the Identity module
                    and keep Core reads read-only; any writes go exclusively through
                    core/services/crud/ModelCrudService.php.</p>
            </div>

            <!-- Footer links -->
            <div class="mt-6 text-center text-sm text-[color:var(--fg-muted)]">
                Need access? <a class="text-[color:var(--color-primary-700)] hover:underline" href="#request">Request an
                    account</a>
                • <a class="text-[color:var(--color-primary-700)] hover:underline" href="#help">Help</a>
            </div>
        </div>
    </section>
</main>
</body>
</html>
