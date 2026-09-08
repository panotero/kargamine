<div class="container mx-auto p-3">

    {{-- PAGE HEADER --}}
    <div class="mb-5 p-2">
        <h1 class="text-2xl font-bold dark:text-white">Mailer Configuration</h1>
        <p class="text-zinc-500">Configure the outgoing SMTP connection and test mail delivery</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- MAILER CONFIGURATION --}}
        <div
            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden h-fit">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
                <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Settings</p>
                <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 mt-0.5">SMTP Configuration</p>
            </div>

            <form id="mailerConfigForm" class="p-5 space-y-4">
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Mail Mailer</label>
                    <input name="mail_mailer" required
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                        value="{{ old('mail_mailer', $config->mail_mailer ?? 'smtp') }}">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Mail
                            Host</label>
                        <input name="mail_host" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            value="{{ old('mail_host', $config->mail_host ?? '') }}">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Mail
                            Port</label>
                        <input name="mail_port" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            value="{{ old('mail_port', $config->mail_port ?? '') }}">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Mail
                            Username (optional)</label>
                        <input type="email" name="mail_username"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            value="{{ old('mail_username', $config->mail_username ?? '') }}">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Mail
                            Password (optional)</label>
                        <div class="relative">
                            <button type="button" class="js-toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300"
                                tabindex="-1" data-target="mail_password">
                                <svg class="js-eye-icon w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51
                               7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431
                               0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <input type="password" name="mail_password" id="mail_password" autocomplete="new-password"
                                placeholder="Leave blank to keep the current password"
                                class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 pr-12 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                        </div>
                        @if (!empty($config->mail_password))
                            <p class="text-xs text-zinc-400 dark:text-zinc-500">A password is already saved.</p>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Encryption
                        (tls/ssl, optional)</label>
                    <input name="mail_encryption"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                        value="{{ old('mail_encryption', $config->mail_encryption ?? '') }}">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">From
                            Email</label>
                        <input type="email" name="mail_from_address" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            value="{{ old('mail_from_address', $config->mail_from_address ?? '') }}">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">From
                            Name</label>
                        <input name="mail_from_name" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            value="{{ old('mail_from_name', $config->mail_from_name ?? '') }}">
                    </div>
                </div>

                <div class="flex justify-end pt-1">
                    <button
                        class="px-4 py-2 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>

        {{-- DIAGNOSTICS --}}
        <div class="flex flex-col gap-4">

            {{-- Test Send Mail --}}
            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Diagnostics</p>
                    <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 mt-0.5">Test Send Mail</p>
                </div>

                <div class="p-5">
                    <form id="testMailForm" class="space-y-4">
                        @csrf
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Recipient
                                Email</label>
                            <input type="email" name="to" required
                                class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                        </div>

                        <div class="flex flex-col gap-1">
                            <label
                                class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Subject</label>
                            <input type="text" name="subject" required
                                class="no-special-chars w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                        </div>

                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Title</label>
                            <input name="title" required
                                class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                        </div>

                        <div class="flex flex-col gap-1">
                            <label
                                class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Message</label>
                            <textarea name="body" rows="3" required
                                class="no-special-chars w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"></textarea>
                        </div>

                        <div class="flex justify-end pt-1">
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
                                Send Test Mail
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Test API Trigger --}}
            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Diagnostics</p>
                    <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 mt-0.5">Test API Trigger</p>
                </div>

                <div class="p-5">
                    <button id="triggerApiBtn"
                        class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-200 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                        Trigger API
                    </button>
                </div>
            </div>

        </div>

    </div>
</div>
