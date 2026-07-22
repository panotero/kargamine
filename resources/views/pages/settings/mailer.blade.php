<div class="container mx-auto p-3">

    {{-- PAGE HEADER --}}
    <div class="mb-5 p-2">
        <h1 class="text-2xl font-bold dark:text-white">Mailer Configuration</h1>
        <p class="text-zinc-500">Configure the outgoing SMTP connection and test mail delivery</p>
    </div>

    {{--  Success Message --}}
    @if (session('success'))
        <div id="alert-success"
            class="bg-green-50 dark:bg-green-950/40 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-900 rounded-lg p-3 mb-4 text-sm transition-opacity duration-500">
            {{ session('success') }}
        </div>
    @endif

    {{--  Error Message --}}
    @if (session('error'))
        <div id="alert-error"
            class="bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-900 rounded-lg p-3 mb-4 text-sm transition-opacity duration-500">
            {{ session('error') }}
        </div>
    @endif

    {{--  Validation Errors --}}
    @if ($errors->any())
        <div id="alert-validation"
            class="bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-900 rounded-lg p-3 mb-4 text-sm transition-opacity duration-500">
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- MAILER CONFIGURATION --}}
        <div
            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden h-fit">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
                <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Settings</p>
                <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 mt-0.5">SMTP Configuration</p>
            </div>

            <form method="POST" action="{{ route('mailer_save') }}" class="p-5 space-y-4">
                @csrf
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
                            Username</label>
                        <input type="email" name="mail_username" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            value="{{ old('mail_username', $config->mail_username ?? '') }}">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Mail
                            Password</label>
                        <input type="password" name="mail_password" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            value="{{ old('mail_password', $config->mail_password ?? '') }}">
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Encryption
                        (tls/ssl)</label>
                    <input name="mail_encryption" required
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
                    {{-- Status box for success/error --}}
                    <div id="mailStatus" class="hidden p-2 rounded-lg mb-3 text-sm"></div>

                    <form id="testMailForm" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_token" value="some-long-token-here">
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
                    <div id="apiStatus" class="hidden p-2 rounded-lg mb-3 text-sm"></div>

                    <button id="triggerApiBtn"
                        class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-200 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                        Trigger API
                    </button>
                </div>
            </div>

        </div>

    </div>
</div>
