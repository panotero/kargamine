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

<body class="">
    <div class="min-h-screen flex justify-center items-center bg-gray-100 dark:bg-gray-900 p-4">
        <div
            class="w-full max-w-5xl grid grid-cols-1 md:grid-cols-2 bg-white rounded-lg drop-shadow-md overflow-hidden">
            <!-- Left Column -->
            <div class="flex flex-col justify-center items-center p-6 md:p-10 space-y-6">
                <div class="text-center">
                    <img class="h-28 w-28 mx-auto mb-3" src="{{ asset('/assets/images/TESDA_Logo.png') }}"
                        {{ $attributes }} alt="Logo">
                    <h1 class="font-semibold text-xl">ODDG-PP</h1>
                    <h1 class="text-2xl md:text-3xl font-semibold">Document Monitoring Tool</h1>
                </div>

                <div class="flex  justify-center items-center space-y-4 md:space-y-0 md:space-x-4">
                    <img class="max-md:h-15 h-28 w-auto mx-auto"
                        src="{{ asset('/assets/images/bagong_pilipinas.png') }}" {{ $attributes }} alt="Logo">
                    <img class="max-md:h-5 h-10 w-auto mx-auto"
                        src="{{ asset('/assets/images/tesda_kayang_kaya.png') }}" {{ $attributes }} alt="Logo">
                </div>
            </div>

            <!-- Right Column -->
            <div class="flex justify-center items-center p-6">
                <div class="w-full sm:max-w-md px-6 py-4 dark:bg-gray-800rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>



</body>

</html>
