@props([
    'align' => 'right',
    'width' => '48',
    'contentClasses' => 'w-auto py-1 bg-white dark:bg-gray-700',
    'showProfile' => true,
    'showSettings' => true,
    'showLogout' => true,
])

@php
    switch ($align) {
        case 'left':
            $alignmentClasses = 'ltr:origin-top-left rtl:origin-top-right start-0';
            break;
        case 'top':
            $alignmentClasses = 'origin-top';
            break;
        case 'right':
        default:
            $alignmentClasses = 'ltr:origin-top-right rtl:origin-top-left end-0';
            break;
    }

    switch ($width) {
        case '48':
            $width = 'w-48';
            break;
    }
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <!-- Trigger -->
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <!-- Dropdown Content -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-2 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
        style="display: none;" @click="open = false">

        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">

            <!-- User Avatar + Email -->
            <div class="flex items-start justify-center  p-4">
                <div class="">

                    @if (Auth::user()->profile_photo_url)
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="Avatar"
                            class="h-16 w-16 rounded-full object-cover">
                    @else
                        <div
                            class="h-16 w-16 rounded-full bg-gray-400 text-white flex items-center justify-center font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class=" px-3">

                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ Auth::user()->email }}</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</p>
                </div>
            </div>

            {{-- Profile
            @if ($showProfile)
                <x-dropdown-link :href="route('profile')">
                    {{ __('Profile') }}
                </x-dropdown-link>
            @endif

            @if ($showSettings)
                <x-dropdown-link :href="route('settings')">
                    {{ __('Settings') }}
                </x-dropdown-link>
            @endif

            @if ($showProfile || $showSettings)
                <div class="border-t border-gray-200 dark:border-gray-600 my-1"></div>
            @endif --}}

            {{-- Logout --}}
            @if ($showLogout)
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                        {{ __('Log Out') }}
                    </button>
                </form>
            @endif

            {{-- Original custom content --}}
            {{ $content }}
        </div>
    </div>
</div>
