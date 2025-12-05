<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Fundraiser'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[color:var(--bg-app)] text-[color:var(--fg-app)]">
<!-- Site header -->
<header class="border-b border-[color:var(--border)] bg-[color:var(--bg-surface)]">
    <div class="container-page h-16 flex items-center justify-between">
        <a href="/" class="text-lg font-semibold">{{ config('app.name', 'Fundraiser') }}</a>
        <nav class="hidden md:flex items-center gap-6 text-sm text-[color:var(--fg-muted)]">
            <a href="#campaigns" class="hover:text-[color:var(--fg-app)]">Campaigns</a>
            <a href="#about" class="hover:text-[color:var(--fg-app)]">About</a>
            <a href="#donations" class="hover:text-[color:var(--fg-app)]">My Donations</a>
        </nav>
        <div class="flex items-center gap-3">
            @guest
                <a class="btn-subtle">Sign in</a>
            @else
                <a href="{{ url('/dashboard') }}" class="btn-subtle">Dashboard</a>
            @endguest
            <a href="#start" class="btn-primary">Start a Campaign</a>
        </div>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="mt-20 border-t border-[color:var(--border)]">
    <div class="container-page py-10 text-sm text-[color:var(--fg-muted)] flex items-center justify-between">
        <div>© {{ date('Y') }} {{ config('app.name', 'Fundraiser') }}</div>
        <div class="flex items-center gap-4">
            <a href="#privacy" class="hover:text-[color:var(--fg-app)]">Privacy</a>
            <a href="#terms" class="hover:text-[color:var(--fg-app)]">Terms</a>
        </div>
    </div>
</footer>
</body>
</html>
