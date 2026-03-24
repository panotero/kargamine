<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="icon" type="image/x-icon" href="">
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        :root {
            --primary: {{ $theme->primary_color ?? '#1d4ed8' }};
            --secondary: {{ $theme->secondary_color ?? '#64748b' }};
            --highlight: {{ $theme->highlight_color ?? '#f59e0b' }};
            --accent: {{ $theme->accent_color ?? '#10b981' }};
        }
    </style>
</head>

<body class="flex bg-gray-100">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-800 text-white h-screen p-4">
        <h1 class="text-xl font-bold mb-4">Admin</h1>
        <ul>
            @php
                $menus = \App\Models\NavMenu::all();
                $role = auth()->user()->role ?? 'guest';
            @endphp
            @foreach ($menus as $menu)
                @if (in_array($role, json_decode($menu->allowed_roles)))
                    <li class="mb-2">
                        <a href="{{ url($menu->link) }}" class="block p-2 rounded hover:bg-gray-700">
                            {{ $menu->title }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col">
        {{-- Topbar --}}
        <header class="flex justify-end items-center bg-white shadow p-4">
            {{-- Notifications --}}
            <div x-data="{ open: false }" class="relative mr-4">
                <button @click="open = !open" class="p-2 bg-gray-100 rounded-full">🔔</button>
                <div x-show="open" class="absolute right-0 mt-2 w-64 bg-white shadow rounded p-4">
                    <p class="text-gray-500">No notifications as of the moment.</p>
                </div>
            </div>

            {{-- Account --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="w-10 h-10 bg-gray-300 rounded-full">👤</button>
                <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white shadow rounded">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Change Password</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="p-6">
            @yield('content')
        </main>
    </div>

</body>

</html>
