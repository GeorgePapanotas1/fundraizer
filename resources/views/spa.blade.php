<!doctype html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Fundraiser • App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[color:var(--bg-app)] text-[color:var(--fg-app)]">
<div id="app"></div>
</body>
</html>
