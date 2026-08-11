<div class="max-w-2xl mx-auto p-5">
    <h1 class="text-2xl font-semibold mb-1 text-zinc-900 dark:text-zinc-100">My Profile</h1>
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
        Manage your display name and profile picture. Your email address cannot be changed here.
    </p>

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow-sm p-5 space-y-6">

        <div class="flex items-center gap-5">
            <div id="profilePhotoPreview" class="shrink-0"></div>
            <div class="space-y-2">
                <div class="flex gap-2">
                    <button type="button" id="profilePhotoChooseBtn"
                        class="inline-flex items-center px-3 py-1.5 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 text-sm font-medium rounded-lg transition">
                        Change Photo
                    </button>
                    <button type="button" id="profilePhotoRemoveBtn"
                        class="hidden items-center px-3 py-1.5 bg-red-50 dark:bg-red-950/40 hover:bg-red-100 dark:hover:bg-red-950/70 text-red-600 dark:text-red-400 text-sm font-medium rounded-lg transition">
                        Remove Photo
                    </button>
                </div>
                <input type="file" id="profilePhotoInput" accept="image/png,image/jpeg" class="hidden">
                <p class="text-xs text-zinc-400">JPG or PNG, up to 5MB.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Name <span class="text-red-500">*</span></label>
                <input type="text" id="profileNameInput"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Email</label>
                <input type="text" id="profileEmailInput" disabled
                    class="w-full border border-zinc-200 dark:border-zinc-800 bg-zinc-100 dark:bg-zinc-950 text-zinc-500 dark:text-zinc-400 rounded-lg px-3 py-2 text-sm cursor-not-allowed">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="button" id="profileSaveBtn"
                class="inline-flex items-center px-5 py-2.5 bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                Save Changes
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow-sm p-5 space-y-6 mt-6">
        <div>
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Change Password</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Ensure your account is using a long, random password to stay secure.</p>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Current Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="currentPasswordInput" name="current_password" autocomplete="current-password"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 pr-12 text-sm">
                    <button type="button" class="js-toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300"
                        tabindex="-1" data-target="currentPasswordInput">
                        <svg class="js-eye-icon w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51
                       7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431
                       0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">New Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="newPasswordInput" name="password" autocomplete="new-password"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 pr-12 text-sm">
                    <button type="button" class="js-toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300"
                        tabindex="-1" data-target="newPasswordInput">
                        <svg class="js-eye-icon w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51
                       7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431
                       0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Confirm New Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="newPasswordConfirmationInput" name="password_confirmation" autocomplete="new-password"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 pr-12 text-sm">
                    <button type="button" class="js-toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300"
                        tabindex="-1" data-target="newPasswordConfirmationInput">
                        <svg class="js-eye-icon w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51
                       7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431
                       0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="button" id="passwordSaveBtn"
                class="inline-flex items-center px-5 py-2.5 bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                Update Password
            </button>
        </div>
    </div>
</div>

<script>
    (function() {
        let currentUser = null;

        function avatarHtml(user, sizeClasses) {
            if (user.profile_photo_url) {
                return `<img src="${user.profile_photo_url}" alt="Avatar" class="${sizeClasses} rounded-full object-cover">`;
            }
            const initial = (user.name ?? '?').trim().charAt(0).toUpperCase() || '?';
            return `<div class="${sizeClasses} rounded-full bg-orange-500 text-white flex items-center justify-center font-semibold">${initial}</div>`;
        }

        function renderPreview() {
            document.getElementById('profilePhotoPreview').innerHTML = avatarHtml(currentUser, 'h-16 w-16 text-xl');
            document.getElementById('profilePhotoRemoveBtn').classList.toggle('hidden', !currentUser.profile_photo_url);
            document.getElementById('profilePhotoRemoveBtn').classList.toggle('flex', !!currentUser.profile_photo_url);
        }

        // Keeps the header/account-dropdown avatar and name in sync without
        // requiring a full reload, since dashboard.blade.php only renders
        // that markup once per session.
        function refreshHeaderIdentity(user) {
            const headerWrap = document.getElementById('headerAvatarWrap');
            if (headerWrap) headerWrap.innerHTML = avatarHtml(user, 'h-10 w-10');

            const dropdownWrap = document.getElementById('dropdownAvatarWrap');
            if (dropdownWrap) dropdownWrap.innerHTML = avatarHtml(user, 'h-16 w-16 text-xl');

            const dropdownName = document.getElementById('dropdownUserName');
            if (dropdownName) dropdownName.textContent = user.name;
        }

        async function load() {
            const response = await apiCall({ mode: 'GET', url: '/api/profile' });
            if (!response.success) return;

            currentUser = response.data;
            document.getElementById('profileNameInput').value = currentUser.name ?? '';
            document.getElementById('profileEmailInput').value = currentUser.email ?? '';
            renderPreview();
        }

        document.getElementById('profileSaveBtn').addEventListener('click', async (e) => {
            const name = document.getElementById('profileNameInput').value.trim();
            if (!name) {
                showMessage({ status: 'error', message: 'Name is required.' });
                return;
            }

            const response = await apiCall({
                mode: 'PUT',
                isJson: true,
                payload: { name },
                url: '/api/profile',
                button: e.currentTarget,
            });

            if (response.success) {
                currentUser = response.data;
                refreshHeaderIdentity(currentUser);
                showMessage({ status: 'success', message: 'Profile updated.' });
            }
        });

        document.getElementById('profilePhotoChooseBtn').addEventListener('click', () => {
            document.getElementById('profilePhotoInput').click();
        });

        document.getElementById('profilePhotoInput').addEventListener('change', async function() {
            const file = this.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('photo', file);

            const response = await apiCall({
                mode: 'POST',
                isJson: false,
                payload: formData,
                url: '/api/profile/photo',
            });

            this.value = '';

            if (response.success) {
                currentUser = response.data;
                renderPreview();
                refreshHeaderIdentity(currentUser);
                showMessage({ status: 'success', message: 'Profile photo updated.' });
            } else {
                showMessage({ status: 'error', message: response.message ?? 'Unable to upload the photo.' });
            }
        });

        document.getElementById('passwordSaveBtn').addEventListener('click', async (e) => {
            const currentPasswordInput = document.getElementById('currentPasswordInput');
            const newPasswordInput = document.getElementById('newPasswordInput');
            const newPasswordConfirmationInput = document.getElementById('newPasswordConfirmationInput');

            const response = await apiCall({
                mode: 'PUT',
                isJson: true,
                payload: {
                    current_password: currentPasswordInput.value,
                    password: newPasswordInput.value,
                    password_confirmation: newPasswordConfirmationInput.value,
                },
                url: '/api/profile/password',
                button: e.currentTarget,
            });

            if (response.success) {
                currentPasswordInput.value = '';
                newPasswordInput.value = '';
                newPasswordConfirmationInput.value = '';
                showMessage({ status: 'success', message: 'Password updated.' });
            } else {
                showMessage({ status: 'error', message: response.message ?? 'Unable to update the password.' });
            }
        });

        document.getElementById('profilePhotoRemoveBtn').addEventListener('click', async () => {
            const response = await apiCall({ mode: 'DELETE', url: '/api/profile/photo' });

            if (response.success) {
                currentUser = response.data;
                renderPreview();
                refreshHeaderIdentity(currentUser);
                showMessage({ status: 'success', message: 'Profile photo removed.' });
            }
        });

        load();
    })();
</script>
