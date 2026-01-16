<div class="w-full h-full lg:flex lg:m-5 rounded-lg container bg-white">
    <div class="p-6 w-full mx-auto bg-white rounded-lg">
        <h1 class="text-xl font-semibold mb-4">Mailer Configuration</h1>

        {{-- ✅ Success Message --}}
        @if (session('success'))
            <div id="alert-success" class="bg-green-100 text-green-800 p-3 mb-3 rounded transition-opacity duration-500">
                {{ session('success') }}
            </div>
        @endif

        {{-- ✅ Error Message --}}
        @if (session('error'))
            <div id="alert-error" class="bg-red-100 text-red-700 p-3 mb-3 rounded transition-opacity duration-500">
                {{ session('error') }}
            </div>
        @endif

        {{-- ✅ Validation Errors --}}
        @if ($errors->any())
            <div id="alert-validation" class="bg-red-100 text-red-700 p-3 mb-3 rounded transition-opacity duration-500">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 mb-3 rounded">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('mailer_save') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block font-semibold">Mail Mailer</label>
                <input name="mail_mailer" class="w-full border p-2 rounded text-black" required
                    value="{{ old('mail_mailer', $config->mail_mailer ?? 'smtp') }}">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold">Mail Host</label>
                    <input name="mail_host" class="w-full border p-2 rounded text-black" required
                        value="{{ old('mail_host', $config->mail_host ?? '') }}">
                </div>
                <div>
                    <label class="block font-semibold">Mail Port</label>
                    <input name="mail_port" class="w-full border p-2 rounded text-black" required
                        value="{{ old('mail_port', $config->mail_port ?? '') }}">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold">Mail Username</label>
                    <input type="email" name="mail_username" class="w-full border p-2 rounded text-black" required
                        value="{{ old('mail_username', $config->mail_username ?? '') }}">
                </div>
                <div>
                    <label class="block font-semibold">Mail Password</label>
                    <input type="password" name="mail_password" class="w-full border p-2 rounded text-black" required
                        value="{{ old('mail_password', $config->mail_password ?? '') }}">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold">Encryption (tls/ssl)</label>
                    <input name="mail_encryption" class="w-full border p-2 rounded text-black" required
                        value="{{ old('mail_encryption', $config->mail_encryption ?? '') }}">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold">From Email</label>
                    <input type="email" name="mail_from_address" class="w-full border p-2 rounded text-black" required
                        value="{{ old('mail_from_address', $config->mail_from_address ?? '') }}">
                </div>
                <div>
                    <label class="block font-semibold">From Name</label>
                    <input name="mail_from_name" class="w-full border p-2 rounded text-black" required
                        value="{{ old('mail_from_name', $config->mail_from_name ?? '') }}">
                </div>
            </div>

            <div class="flex justify-end">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Save Configuration</button>
            </div>
        </form>
    </div>
    <div class="p-6 w-full mx-auto">
        <h2 class="text-lg font-semibold mb-3">Test Send Mail</h2>

        {{-- Status box for success/error --}}
        <div id="mailStatus" class="hidden p-2 rounded mb-3"></div>

        <form id="testMailForm" class="space-y-3">
            @csrf
            <input type="hidden" name="_token" value="some-long-token-here">
            <div>
                <label class="block font-semibold">Recipient Email</label>
                <input type="email" name="to" class="w-full border p-2 rounded text-black" required>
            </div>

            <div>
                <label class="block font-semibold">Subject</label>
                <input type="text" name="subject" class="no-special-chars w-full border p-2 rounded text-black"
                    required>
            </div>
            <div>
                <label class="block font-semibold">Title</label>
                <input name="title" class="w-full border p-2 rounded text-black" required></input>
            </div>

            <div>
                <label class="block font-semibold">Message</label>
                <textarea name="body" class="no-special-chars w-full border p-2 rounded text-black" rows="3" required></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Send Test Mail
                </button>
            </div>
        </form>

        <div class="p-6 max-w-xl mx-auto">
            <h2 class="text-lg font-semibold mb-3">Test API Trigger</h2>

            <div id="apiStatus" style="display:none; padding:8px; margin-bottom:10px; border-radius:4px;"></div>

            <button id="triggerApiBtn"
                style="padding:8px 16px; background:green; color:white; border:none; border-radius:4px; cursor:pointer;">
                Trigger API
            </button>

        </div>

    </div>
</div>
