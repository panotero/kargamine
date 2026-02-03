<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('/assets/images/TESDA_Logo.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-sky-400 via-cyan-300 to-blue-500 min-h-screen text-gray-800">

    <!-- Background Overlay Effects -->
    <div class="fixed inset-0 bg-black/20"></div>
    <div class="fixed inset-0 backdrop-blur-[2px]"></div>

    <div class="relative z-10 min-h-screen flex justify-center items-center p-6">

        <div
            class="w-full max-w-6xl grid grid-cols-1 md:grid-cols-2 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl overflow-hidden">

            <!-- LEFT SIDE (Branding Panel) -->
            <div class="flex flex-col justify-center items-center p-10 text-white text-center space-y-6">

                <div>

                    <h1 class="text-3xl md:text-4xl font-bold mt-2 leading-tight">
                        LiveAboardTrips
                    </h1>
                </div>


                <p class="text-sm opacity-80 pt-4 max-w-xs">
                    Your partner in you adventures!
                </p>
            </div>

            <!-- RIGHT SIDE (Login Form Panel) -->
            <div class="flex justify-center items-center p-8 md:p-12 bg-white/80 backdrop-blur-md">

                <div class="w-full sm:max-w-md">

                    <div class="mb-6 text-center">
                        <h2 class="text-2xl font-semibold text-gray-800">Welcome Back</h2>
                        <p class="text-sm text-gray-500">Please sign in to continue</p>
                    </div>

                    <!-- LOGIN FORM SLOT -->
                    <div class="bg-white rounded-xl shadow-lg px-6 py-6 border border-gray-100">
                        {{ $slot }}
                    </div>

                </div>
            </div>

        </div>
    </div>

</body>


</html>
