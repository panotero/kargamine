<div class="max-w-4xl mx-auto p-5">
    <h1 class="text-2xl font-semibold mb-1 text-zinc-900 dark:text-zinc-100">Application Information</h1>
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
        Set the application name, logo, and browser tab icon. These apply app-wide - the logo replaces the
        sidebar/login branding and the icon becomes the browser tab favicon.
    </p>

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow-sm p-5 space-y-6">

        <div>
            <label for="appNameInput" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-200 mb-1">Application
                Name</label>
            <input type="text" id="appNameInput" name="app_name" maxlength="255"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-200 uppercase tracking-wide mb-1">Logo</h2>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-3">Shown in the sidebar and on the login page.</p>
                <div id="appLogoPreview" class="mb-3"></div>
                <div class="flex items-center gap-2">
                    <button type="button" id="appLogoChooseBtn"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                        Change Logo
                    </button>
                    <button type="button" id="appLogoRemoveBtn"
                        class="hidden px-3 py-1.5 text-xs font-semibold rounded-lg border border-red-300 dark:border-red-700 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 transition">
                        Remove
                    </button>
                </div>
                <input type="file" id="appLogoInput" accept="image/png,image/jpeg,image/svg+xml" class="hidden">
            </div>

            <div>
                <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-200 uppercase tracking-wide mb-1">Icon
                    (Favicon)</h2>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-3">Shown as the browser tab icon.</p>
                <div id="appIconPreview" class="mb-3"></div>
                <div class="flex items-center gap-2">
                    <button type="button" id="appIconChooseBtn"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                        Change Icon
                    </button>
                    <button type="button" id="appIconRemoveBtn"
                        class="hidden px-3 py-1.5 text-xs font-semibold rounded-lg border border-red-300 dark:border-red-700 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 transition">
                        Remove
                    </button>
                </div>
                <input type="file" id="appIconInput" accept="image/png,image/jpeg,image/svg+xml" class="hidden">
            </div>
        </div>

        <p class="text-xs text-zinc-400 dark:text-zinc-500">PNG, JPG, or SVG - max 2MB each.</p>
    </div>

    <div class="flex justify-end mt-6">
        <button type="button" id="appInformationSaveBtn"
            class="inline-flex items-center px-5 py-2.5 bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white text-sm font-semibold rounded-lg shadow-sm transition">
            Save
        </button>
    </div>
</div>

<script>
    (function() {
        let state = null;
        let pendingLogoFile = null;
        let pendingIconFile = null;

        function previewHtml(url, label) {
            if (!url) {
                return `<div class="h-16 w-16 rounded-lg border border-dashed border-zinc-300 dark:border-zinc-600 flex items-center justify-center text-[10px] text-zinc-400">${label}</div>`;
            }
            return `<img src="${url}" alt="${label}" class="h-16 w-16 rounded-lg object-contain border border-zinc-200 dark:border-zinc-700 bg-white">`;
        }

        function render() {
            document.getElementById('appNameInput').value = state.app_name ?? '';

            document.getElementById('appLogoPreview').innerHTML = previewHtml(state.logo_path, 'No logo');
            document.getElementById('appLogoRemoveBtn').classList.toggle('hidden', !state.logo_path);

            document.getElementById('appIconPreview').innerHTML = previewHtml(state.icon_path, 'No icon');
            document.getElementById('appIconRemoveBtn').classList.toggle('hidden', !state.icon_path);
        }

        async function load() {
            const response = await apiCall({ mode: 'GET', url: '/api/app-information' });
            if (!response.success) return;
            state = response.data;
            render();
        }

        document.getElementById('appLogoChooseBtn').addEventListener('click', () => {
            document.getElementById('appLogoInput').click();
        });
        document.getElementById('appIconChooseBtn').addEventListener('click', () => {
            document.getElementById('appIconInput').click();
        });

        document.getElementById('appLogoInput').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            pendingLogoFile = file;
            document.getElementById('appLogoPreview').innerHTML = previewHtml(URL.createObjectURL(file), 'Logo');
            document.getElementById('appLogoRemoveBtn').classList.remove('hidden');
        });

        document.getElementById('appIconInput').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            pendingIconFile = file;
            document.getElementById('appIconPreview').innerHTML = previewHtml(URL.createObjectURL(file), 'Icon');
            document.getElementById('appIconRemoveBtn').classList.remove('hidden');
        });

        document.getElementById('appLogoRemoveBtn').addEventListener('click', async () => {
            pendingLogoFile = null;
            document.getElementById('appLogoInput').value = '';

            if (!state.logo_path) {
                render();
                return;
            }

            const response = await apiCall({ mode: 'DELETE', url: '/api/app-information/logo' });
            if (response.success) {
                state = response.data;
                render();
                showMessage({ status: 'success', message: 'Logo removed.' });
            }
        });

        document.getElementById('appIconRemoveBtn').addEventListener('click', async () => {
            pendingIconFile = null;
            document.getElementById('appIconInput').value = '';

            if (!state.icon_path) {
                render();
                return;
            }

            const response = await apiCall({ mode: 'DELETE', url: '/api/app-information/icon' });
            if (response.success) {
                state = response.data;
                render();
                showMessage({ status: 'success', message: 'Icon removed.' });
            }
        });

        document.getElementById('appInformationSaveBtn').addEventListener('click', async (e) => {
            const formData = new FormData();
            formData.append('app_name', document.getElementById('appNameInput').value);
            if (pendingLogoFile) formData.append('logo', pendingLogoFile);
            if (pendingIconFile) formData.append('icon', pendingIconFile);

            const response = await apiCall({
                mode: 'POST',
                isJson: false,
                payload: formData,
                url: '/api/app-information',
                button: e.currentTarget,
            });

            if (response.success) {
                pendingLogoFile = null;
                pendingIconFile = null;
                state = response.data;
                render();
                showMessage({ status: 'success', message: 'Application information saved.' });
            }
        });

        load();
    })();
</script>
