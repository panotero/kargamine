<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link rel="icon" href="/favicon.ico?v=999">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-zinc-100 dark:bg-zinc-950 min-h-screen text-zinc-800 dark:text-zinc-200">

    <div class="min-h-screen flex justify-center items-center p-6">

        <div
            class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-xl dark:shadow-black/40 overflow-hidden">

            {{-- LEFT SIDE (Branding panel - deliberately always-dark, doesn't follow the site theme) --}}
            <div class="hidden md:flex flex-col justify-between p-10 bg-zinc-900 text-white relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-orange-500/10"></div>
                <div class="absolute -right-4 top-24 w-24 h-24 rounded-full bg-orange-500/10"></div>

                <div class="relative">
                    <div class="w-10 h-10 rounded-xl bg-orange-500 flex items-center justify-center mb-6">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 .97-.616 1.813-1.5 2.097M3.75 8.511c-.884.284-1.5 1.128-1.5 2.097v4.286c0 .97.616 1.813 1.5 2.097m16.5-8.48L12 3 3.75 8.511m16.5 0L12 13.5m-8.25-4.989L12 13.5m0 0v7.5" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold leading-tight">{{ config('app.name', 'Management System') }}</h1>
                    <p class="text-sm text-zinc-400 mt-3 max-w-xs">
                        Document tracking, CRM, proposals, contracts, and finance - all in one place.
                    </p>
                </div>

                <div class="relative text-xs text-zinc-500">
                    &copy; {{ now()->year }} {{ config('app.name', 'Management System') }}
                </div>
            </div>

            {{-- RIGHT SIDE (Form panel) --}}
            <div class="flex justify-center items-center p-8 md:p-12">
                <div class="w-full sm:max-w-md">

                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-zinc-900 dark:text-white">Welcome back</h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Please sign in to continue</p>
                    </div>

                    {{ $slot }}

                </div>
            </div>

        </div>
    </div>

</body>

</html>
