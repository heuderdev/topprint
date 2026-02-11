<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Inter oficial Flux -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @fluxAppearance
</head>

<body
    class="font-inter antialiased bg-gradient-to-br from-zinc-50 via-white to-blue-50 dark:from-zinc-900 dark:via-zinc-800 dark:to-slate-900">
    <div class="min-h-screen flex items-center justify-center p-8 relative overflow-hidden">
        <!-- Background decorativo -->
        <div
            class="absolute inset-0 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-zinc-800/50 dark:to-slate-900/50">
        </div>
        {{ $slot }}
    </div>
    @livewireScripts
    @fluxScripts
</body>

</html>