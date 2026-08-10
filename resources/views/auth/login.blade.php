<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (session('message'))
        <div class="mb-4 p-3 rounded-lg bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-400 text-sm">
            {{ session('message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autofocus autocomplete="username" placeholder="you@company.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative mt-1">
                <button type="button" class="js-toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300"
                    tabindex="-1" data-target="password">
                    <svg class="js-eye-icon w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51
                       7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431
                       0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>

                <x-text-input id="password" class="block w-full pr-12" type="password" name="password" required
                    autocomplete="current-password" placeholder="••••••••" />
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="rounded border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-orange-500 focus:ring-orange-400 dark:focus:ring-orange-500"
                    name="remember">
                <span class="ms-2 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <button type="submit"
            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-orange-500 hover:bg-orange-600 border border-transparent rounded-lg font-semibold text-sm text-white focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition">
            {{ __('Log in') }}
        </button>
    </form>
</x-guest-layout>
