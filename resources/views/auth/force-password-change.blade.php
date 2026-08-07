<x-guest-layout>
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Set a new password</h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
            Your account was just created. Choose a new password to continue.
        </p>
    </div>

    <form method="POST" action="{{ route('password.force.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="password" :value="__('New Password')" />
            <div class="relative mt-1">
                <button type="button" id="toggleNewPassword" tabindex="-1"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                    <svg id="eyeIconNewPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51
                       7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431
                       0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
                <x-text-input id="password" class="block w-full pr-12" type="password" name="password" required
                    autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
            <div class="relative mt-1">
                <button type="button" id="toggleConfirmPassword" tabindex="-1"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                    <svg id="eyeIconConfirmPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51
                       7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431
                       0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
                <x-text-input id="password_confirmation" class="block w-full pr-12" type="password"
                    name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit"
            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-orange-500 hover:bg-orange-600 border border-transparent rounded-lg font-semibold text-sm text-white focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition">
            {{ __('Set Password & Continue') }}
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="text-center mt-4">
        @csrf
        <button type="submit" class="text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 underline">
            Log out instead
        </button>
    </form>
</x-guest-layout>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const eyeOpen = `
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51
                   7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431
                   0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>`;

        const eyeClosed = `
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.98 8.223A10.477 10.477 0 001.934 12c1.832
                4.068 5.728 7 10.066 7 1.676 0 3.285-.37
                4.712-1.034M6.228 6.228A10.45 10.45 0 0112
                5c4.38 0 8.293 2.953 10.07 7.063a10.522
                10.522 0 01-4.517 4.92M6.228 6.228L3 3m3.228
                3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0
                0a3 3 0 10-4.243-4.243m4.242 4.242L9.878
                9.878" />`;

        function wireToggle(buttonId, iconId, fieldId) {
            const toggleBtn = document.getElementById(buttonId);
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(iconId);
            if (!toggleBtn || !field || !eyeIcon) return;

            toggleBtn.addEventListener("click", () => {
                const type = field.type === "password" ? "text" : "password";
                field.type = type;
                eyeIcon.innerHTML = type === "password" ? eyeOpen : eyeClosed;
            });
        }

        wireToggle("toggleNewPassword", "eyeIconNewPassword", "password");
        wireToggle("toggleConfirmPassword", "eyeIconConfirmPassword", "password_confirmation");
    });
</script>
