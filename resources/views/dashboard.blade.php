<x-app-layout>
    <div class="flex h-screen dark:bg-gray-900">
        <aside id="sidebar-wrapper"
            class="bg-white dark:bg-zinc-900 shadow-lg w-64
           fixed lg:static inset-y-0 left-0
           h-full flex flex-col
           transform -translate-x-full lg:translate-x-0
           transition-transform duration-300
           z-40">
            <div class="w-full p-5 dark:text-white">
                <h1 class="font-semibold text-sm">Management System</h1>
                <h1 class="text-md md:text-md font-bold"></h1>
            </div>

            <div class="w-full flex flex-col justify-between flex-grow">
                <nav id="sidebar-menu" class="p-4 space-y-2 text-black dark:text-white font-semibold">
                </nav>

            </div>
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30 lg:hidden"></div>

        <div class="flex-1 flex flex-col min-w-0">
            <button id="sidebar-toggle"
                class="lg:hidden absolute top-2 left-2  px-2 py-4 bg-gray-800 text-white rounded z-10">
                ☰
            </button>
            <header class="bg-white dark:bg-zinc-700 shadow px-6 py-4 flex justify-between items-center">
                <h2 id="page-title" class="text-xl font-semibold text-gray-800 dark:text-gray-200 max-lg:pl-5">
                    Dashboard
                </h2>

                <div class="flex items-center space-x-4">
                    @include('layouts.partials.notification-bell')
                    <x-dropdown align="right" width="w-auto">
                        <x-slot name="trigger">
                            <button class="text-gray-800 dark:text-gray-200">
                                <div class="flex flex-col items-center justify-center">
                                    @if (Auth::user()->profile_photo_url)
                                        <img src="{{ Auth::user()->profile_photo_url }}" alt="Avatar"
                                            class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div
                                            class="h-10 w-10 rounded-full bg-gray-400 text-white flex items-center justify-center font-semibold">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                        </x-slot>
                    </x-dropdown>
                </div>
            </header>
            <main id="content"
                class="flex-1 w-full overflow-y-auto dark:bg-zinc-800  text-gray-800 dark:text-gray-200">
            </main>

        </div>
    </div>



</x-app-layout>
