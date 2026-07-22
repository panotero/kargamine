<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link rel="icon" href="/favicon.ico?v=999">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.tailwindcss.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $__theme = \App\Models\AppThemeSetting::current();
    @endphp
    <style>
        :root {
            {{ \App\Support\TailwindPalette::cssVariables('main', $__theme->main_color) }}
            {{ \App\Support\TailwindPalette::cssVariables('accent', $__theme->accent_color) }}
            {{ \App\Support\TailwindPalette::cssVariables('secondary', $__theme->button_secondary_color) }}
            {{ \App\Support\TailwindPalette::cssVariables('danger', $__theme->button_danger_color) }}
        }
    </style>
    <script>
        // Applies the app-wide dark-mode preference before first paint, to
        // avoid a light/dark flash. Tailwind is set to `darkMode: 'class'`
        // (see tailwind.config.js) precisely so this can force a mode
        // instead of only ever following the OS (the old `media` default).
        (function() {
            const mode = @json($__theme->dark_mode);
            const root = document.documentElement;

            function applySystem() {
                root.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches);
            }

            if (mode === 'dark') {
                root.classList.add('dark');
            } else if (mode === 'light') {
                root.classList.remove('dark');
            } else {
                applySystem();
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', applySystem);
            }
        })();
    </script>
</head>

<body class="font-sans antialiased [&_*]:duration-300">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900  max-md:max-w-screen">
        @if (isset($header))
            <header class="bg-white dark:bg-zinc-700 shadow">
                <div class="max-w-screen mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>

            </header>
        @endif
        <main>
            {{ $slot }}
        </main>

    </div>
    <x-custom-message />
    <div id="globalMessageContainer"
        class="fixed top-0 left-1/2 -translate-x-1/2 mt-10 z-50 flex flex-col items-center pointer-events-none">
    </div>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.5/flowbite.min.js"></script> --}}

    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/glide.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.tailwindcss.js"></script>


</body>

</html>
