<div id="usersManagementPage" class="container mx-auto p-3" data-current-user-id="{{ auth()->id() }}">

    {{-- PAGE HEADER --}}
    <div class="flex justify-between items-center mb-5 p-2 gap-4 flex-wrap">
        <div>
            <h1 class="text-2xl font-bold dark:text-white">User Management</h1>
            <p class="text-zinc-500">Manage system users and their roles</p>
        </div>
        <div class="flex items-center gap-4">
            {{-- Active users stat --}}
            <div class="text-right">
                <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Active Users</p>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="statActiveUsers">—</p>
            </div>
            <button id="btnRolesPermissions" type="button"
                class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3.5 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 4.556-3.04 8.371-7.2 9.591a1.99 1.99 0 0 1-1.6 0C7.04 20.371 4 16.556 4 12V6.75a1.5 1.5 0 0 1 1.5-1.5h.75a11.209 11.209 0 0 0 5.618-1.503.75.75 0 0 1 .624 0A11.209 11.209 0 0 0 18.75 5.25h.75A1.5 1.5 0 0 1 21 6.75V12Z" />
                </svg>
                Roles &amp; Permissions
            </button>
            <button id="btnNewUser"
                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                + New User
            </button>
        </div>
    </div>

    {{-- TOOLBAR: search + filter chips --}}
    <div class="flex items-center gap-2 mb-4 flex-wrap">
        <input type="text" id="usersSearchInput" placeholder="Search name, email, or role"
            class="w-full max-w-xs rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
        <button type="button" id="usersSearchBtn"
            class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3.5 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.3 5.3a7.5 7.5 0 0 0 10.5 10.5Z" />
            </svg>
            Search
        </button>

        <div class="flex bg-zinc-100 dark:bg-zinc-800 rounded-lg p-0.5 gap-0.5 ml-auto" id="usersFilterChips">
            <button type="button" class="user-filter-chip px-3 py-1.5 text-xs font-medium rounded-md transition"
                data-filter="all">All</button>
            <button type="button" class="user-filter-chip px-3 py-1.5 text-xs font-medium rounded-md transition"
                data-filter="active">Active</button>
            <button type="button" class="user-filter-chip px-3 py-1.5 text-xs font-medium rounded-md transition"
                data-filter="inactive">Inactive</button>
        </div>
    </div>

    {{-- USERS TABLE --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">Name</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">Email</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">Role</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">Team</th>
                        <th class="px-4 py-2.5 text-center text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">Status</th>
                        <th class="px-4 py-2.5 text-center text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">Action</th>
                    </tr>
                </thead>
                <tbody id="usersTbody" class="divide-y divide-zinc-100 dark:divide-zinc-800"></tbody>
            </table>
        </div>
        <div id="usersPagination"></div>
    </div>

    {{-- CREATE / EDIT USER SIDE MODAL --}}
    <x-side-modal id="userSideModal">

        {{-- Header --}}
        <div class="p-5 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center sticky top-0 bg-white dark:bg-zinc-900 z-10">
            <div>
                <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">User Management</p>
                <p class="text-lg font-semibold dark:text-white mt-0.5" id="userModalTitle">New User</p>
            </div>
            <button
                class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                ✕
            </button>
        </div>

        {{-- Form --}}
        <div class="p-5">
            <form id="userForm" class="space-y-4">
                <input type="hidden" name="userId" id="userId">

                {{-- Role --}}
                <div class="flex flex-col gap-1">
                    <label for="userRoleSelect" class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Role</label>
                    <select name="role_id" id="userRoleSelect" required
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                        <option value="">Select role</option>
                    </select>
                </div>

                {{-- Name --}}
                <div class="flex flex-col gap-1">
                    <label for="userName" class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Name</label>
                    <input type="text" name="name" id="userName" required
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                </div>

                {{-- Email --}}
                <div class="flex flex-col gap-1">
                    <label for="userEmail" class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Email</label>
                    <input type="email" name="email" id="userEmail" required autocomplete="new-password"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                </div>

                {{-- Password --}}
                <div class="flex flex-col gap-1">
                    <label for="userPassword" id="userPasswordLabel"
                        class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Password</label>
                    <div class="relative">
                        <button type="button"
                            class="js-toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300"
                            tabindex="-1" data-target="userPassword">
                            <svg class="js-eye-icon w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                        <input type="password" name="password" id="userPassword" autocomplete="new-password"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 pr-12 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>
                </div>

                {{-- Team & Leadership (edit only) --}}
                <div id="userTeamBlock" class="hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-4 space-y-2">
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Team &amp; Leadership</p>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-medium text-zinc-800 dark:text-zinc-100" id="userTeamName">No team assigned</span>
                        <span id="userLeaderBadge"
                            class="hidden text-[10px] font-semibold uppercase tracking-wide bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300 rounded px-1.5 py-0.5">Team Leader</span>
                    </div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                        Team leaders can see every record their team and its sub-teams own. Team and leader status are
                        set in Team Management, not here.
                    </p>
                    <button type="button" id="btnGoTeamManagement"
                        class="inline-flex items-center gap-1 text-xs font-medium text-orange-600 hover:text-orange-700 dark:text-orange-400">
                        View this user's team assignment in Team Management →
                    </button>
                </div>
            </form>
        </div>

        {{-- Footer --}}
        <div class="border-t border-zinc-100 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2 sticky bottom-0 bg-white dark:bg-zinc-900">
            <button type="button"
                class="modal-close px-4 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                Cancel
            </button>
            <button type="button" id="saveUserBtn"
                class="px-4 py-1.5 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
                Save User
            </button>
        </div>

    </x-side-modal>

    {{-- JS --}}
    <script>
        (function () {
            const page = document.getElementById('usersManagementPage');
            if (!page) return;

            const CURRENT_USER_ID = Number(page.dataset.currentUserId);

            const STATUS = { ACTIVE: 0, INACTIVE: 1 };

            const tbody = document.getElementById('usersTbody');
            const pagination = document.getElementById('usersPagination');
            const searchInput = document.getElementById('usersSearchInput');
            const searchBtn = document.getElementById('usersSearchBtn');
            const statActiveEl = document.getElementById('statActiveUsers');

            const userForm = document.getElementById('userForm');
            const userIdEl = document.getElementById('userId');
            const roleSelect = document.getElementById('userRoleSelect');
            const nameEl = document.getElementById('userName');
            const emailEl = document.getElementById('userEmail');
            const passwordEl = document.getElementById('userPassword');
            const passwordLabel = document.getElementById('userPasswordLabel');
            const modalTitle = document.getElementById('userModalTitle');
            const saveUserBtn = document.getElementById('saveUserBtn');

            const teamBlock = document.getElementById('userTeamBlock');
            const teamNameEl = document.getElementById('userTeamName');
            const leaderBadge = document.getElementById('userLeaderBadge');
            const btnGoTeam = document.getElementById('btnGoTeamManagement');

            let activeStatusFilter = 'all';
            // userId -> team display name (from /api/teams/users)
            let teamNameByUserId = {};
            let usersTable = null;

            // ── Chips ────────────────────────────────────────────────
            function paintChips() {
                document.querySelectorAll('.user-filter-chip').forEach((chip) => {
                    const isActive = chip.dataset.filter === activeStatusFilter;
                    chip.classList.toggle('bg-orange-500', isActive);
                    chip.classList.toggle('text-white', isActive);
                    chip.classList.toggle('shadow-sm', isActive);
                    chip.classList.toggle('text-zinc-600', !isActive);
                    chip.classList.toggle('dark:text-zinc-300', !isActive);
                });
            }

            function applyStatusFilter() {
                const rows = Array.from(tbody.querySelectorAll('tr[data-id]'));
                let visible = 0;
                rows.forEach((tr) => {
                    const s = Number(tr.dataset.status);
                    const match =
                        activeStatusFilter === 'all' ||
                        (activeStatusFilter === 'active' && s === STATUS.ACTIVE) ||
                        (activeStatusFilter === 'inactive' && s === STATUS.INACTIVE);
                    tr.classList.toggle('hidden', !match);
                    if (match) visible++;
                });

                const existing = tbody.querySelector('#usersFilterEmptyRow');
                if (existing) existing.remove();

                if (rows.length && visible === 0) {
                    tbody.insertAdjacentHTML(
                        'beforeend',
                        `<tr id="usersFilterEmptyRow"><td colspan="6" class="px-4 py-10 text-center text-sm text-zinc-400 dark:text-zinc-500">No ${activeStatusFilter} users on this page.</td></tr>`,
                    );
                }
            }

            document.querySelectorAll('.user-filter-chip').forEach((chip) => {
                chip.addEventListener('click', () => {
                    activeStatusFilter = chip.dataset.filter;
                    paintChips();
                    applyStatusFilter();
                });
            });

            // ── Stat + supporting data ───────────────────────────────
            async function refreshActiveStat() {
                const res = await apiCall({ mode: 'GET', url: '/api/users/counts' });
                if (res && res.success && res.data) {
                    statActiveEl.textContent = Number(res.data.active ?? 0).toLocaleString();
                }
            }

            async function loadRoles() {
                const settings = await apiCall({ mode: 'GET', url: '/api/users/settings' });
                const roles = Array.isArray(settings?.roles) ? settings.roles : [];
                roleSelect.innerHTML =
                    '<option value="">Select role</option>' +
                    roles.map((r) => `<option value="${r.id}">${r.role_name}</option>`).join('');
                if (typeof window.makeSearchableSelect === 'function') {
                    window.makeSearchableSelect(roleSelect);
                }
            }

            async function loadTeamMap() {
                const users = await apiCall({ mode: 'GET', url: '/api/teams/users' });
                teamNameByUserId = {};
                (Array.isArray(users) ? users : []).forEach((u) => {
                    teamNameByUserId[u.id] = u.team?.name || null;
                });
            }

            function refreshRoleSelect() {
                roleSelect._searchableSelect?.refresh?.();
            }

            // ── Table ────────────────────────────────────────────────
            function statusBadge(status) {
                const isActive = Number(status) === STATUS.ACTIVE;
                return `<span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full ${
                    isActive
                        ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300'
                        : 'bg-zinc-200 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300'
                }">
                    <span class="w-1.5 h-1.5 rounded-full ${isActive ? 'bg-green-500' : 'bg-zinc-400'}"></span>
                    ${isActive ? 'Active' : 'Inactive'}
                </span>`;
            }

            function actionButton(row) {
                const isActive = Number(row.status) === STATUS.ACTIVE;
                const isSelf = Number(row.id) === CURRENT_USER_ID;

                if (isActive) {
                    return `<button type="button"
                        class="btn-deactivate px-3 py-1 text-xs font-medium rounded-lg border border-red-200 text-red-500 hover:bg-red-50 dark:border-red-900/50 dark:hover:bg-red-950/40 transition disabled:opacity-40 disabled:cursor-not-allowed"
                        data-id="${row.id}" data-name="${(row.name || '').replace(/"/g, '&quot;')}"
                        ${isSelf ? 'disabled title="You can&#39;t deactivate your own account."' : ''}>
                        Deactivate
                    </button>`;
                }
                return `<button type="button"
                    class="btn-reactivate px-3 py-1 text-xs font-medium rounded-lg border border-green-200 text-green-600 hover:bg-green-50 dark:border-green-900/50 dark:hover:bg-green-950/40 transition"
                    data-id="${row.id}" data-name="${(row.name || '').replace(/"/g, '&quot;')}">
                    Reactivate
                </button>`;
            }

            function rowTemplate(row) {
                const teamName = teamNameByUserId[row.id] || '—';
                const roleName = row.role?.role_name || '—';
                return `
                <tr class="table-row cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800" data-id="${row.id}" data-status="${row.status}">
                    <td class="px-4 py-2.5 text-zinc-900 dark:text-zinc-100 font-medium whitespace-nowrap">${row.name ?? '—'}</td>
                    <td class="px-4 py-2.5 text-zinc-600 dark:text-zinc-300 whitespace-nowrap">${row.email ?? '—'}</td>
                    <td class="px-4 py-2.5 text-zinc-600 dark:text-zinc-300 whitespace-nowrap">${roleName}</td>
                    <td class="px-4 py-2.5 text-zinc-600 dark:text-zinc-300 whitespace-nowrap">${teamName}</td>
                    <td class="px-4 py-2.5 text-center whitespace-nowrap">${statusBadge(row.status)}</td>
                    <td class="px-4 py-2.5 text-center whitespace-nowrap">${actionButton(row)}</td>
                </tr>`;
            }

            function emptyState() {
                return `<div class="py-10 text-center">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">No users yet.</p>
                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">Create your first user with the “New User” button.</p>
                </div>`;
            }

            function buildTable() {
                usersTable = createRemoteTable({
                    url: '/api/users',
                    tableBodySelector: tbody,
                    paginationSelector: pagination,
                    searchInputSelector: searchInput,
                    searchButtonSelector: searchBtn,
                    emptyMessage: emptyState,
                    colspan: 6,
                    rowTemplate,
                    afterRender: () => applyStatusFilter(),
                });
                usersTable.load(1);
            }

            // ── Row / action clicks (delegated on persistent tbody) ──
            tbody.addEventListener('click', async (e) => {
                const deactivateBtn = e.target.closest('.btn-deactivate');
                if (deactivateBtn) {
                    e.stopPropagation();
                    await handleDeactivate(deactivateBtn);
                    return;
                }

                const reactivateBtn = e.target.closest('.btn-reactivate');
                if (reactivateBtn) {
                    e.stopPropagation();
                    await handleReactivate(reactivateBtn);
                    return;
                }

                const tr = e.target.closest('tr[data-id]');
                if (tr) openEditModal(tr.dataset.id);
            });

            async function handleDeactivate(btn) {
                const id = btn.dataset.id;
                const name = btn.dataset.name || 'This user';
                const confirmed = await customConfirm(
                    `${name} will be immediately signed out and blocked from logging in. Other data (assigned leads, proposals, team roles) stays in place — you can reactivate them later.`,
                );
                if (!confirmed) return;

                const res = await apiCall({
                    mode: 'PATCH',
                    url: `/api/users/deactivate/${id}`,
                    button: btn,
                });

                if (!res || !res.success) {
                    showMessage({
                        status: 'error',
                        title: 'Cannot deactivate user',
                        message: res?.message ?? 'Failed to deactivate user.',
                    });
                    return;
                }

                showMessage({ status: 'success', title: 'User deactivated' });
                usersTable.reload();
                refreshActiveStat();
            }

            async function handleReactivate(btn) {
                const id = btn.dataset.id;
                const name = btn.dataset.name || 'this user';
                const confirmed = await customConfirm(`Reactivate ${name}? They will be able to log in again.`);
                if (!confirmed) return;

                const res = await apiCall({
                    mode: 'PATCH',
                    url: `/api/users/reactivate/${id}`,
                    button: btn,
                });

                if (!res || !res.success) {
                    showMessage({
                        status: 'error',
                        title: 'Cannot reactivate user',
                        message: res?.message ?? 'Failed to reactivate user.',
                    });
                    return;
                }

                showMessage({ status: 'success', title: 'User reactivated' });
                usersTable.reload();
                refreshActiveStat();
            }

            // ── Create / Edit modal ──────────────────────────────────
            function setModalMode(mode) {
                const isEdit = mode === 'edit';
                modalTitle.textContent = isEdit ? 'Edit User' : 'New User';
                saveUserBtn.textContent = isEdit ? 'Update User' : 'Save User';
                passwordLabel.textContent = isEdit ? 'Set New Password' : 'Password';
                passwordEl.required = !isEdit;
                passwordEl.placeholder = isEdit ? 'Leave blank to keep current password' : '';
                teamBlock.classList.toggle('hidden', !isEdit);
            }

            function resetForm() {
                userForm.reset();
                userIdEl.value = '';
                roleSelect.value = '';
                refreshRoleSelect();
                leaderBadge.classList.add('hidden');
                teamNameEl.textContent = 'No team assigned';
            }

            document.getElementById('btnNewUser').addEventListener('click', () => {
                resetForm();
                setModalMode('create');
                initSideModal({ modalId: 'userSideModal' });
            });

            document.getElementById('btnRolesPermissions').addEventListener('click', () => {
                loadPage({ title: 'Roles & Permissions', link: '/page_roles_permissions' });
            });

            async function openEditModal(id) {
                resetForm();
                setModalMode('edit');
                initSideModal({ modalId: 'userSideModal' });

                const user = await apiCall({ mode: 'GET', url: `/api/users/${id}` });
                if (!user || user.error) {
                    showMessage({ status: 'error', title: 'Error', message: 'Unable to load user.' });
                    return;
                }

                userIdEl.value = user.id;
                nameEl.value = user.name ?? '';
                emailEl.value = user.email ?? '';
                roleSelect.value = user.role_id ?? '';
                refreshRoleSelect();

                const teamName = teamNameByUserId[user.id] || null;
                teamNameEl.textContent = teamName || 'No team assigned';
                leaderBadge.classList.toggle('hidden', !user.is_team_leader);
            }

            btnGoTeam.addEventListener('click', () => {
                closeSideModal('userSideModal');
                loadPage({ title: 'Team Management', link: '/page_team_management' });
            });

            saveUserBtn.addEventListener('click', async () => {
                const userId = userIdEl.value;
                const isUpdate = Boolean(userId);

                const name = nameEl.value.trim();
                const email = emailEl.value.trim();
                const roleId = roleSelect.value;
                const password = passwordEl.value;

                if (!name || !email || !roleId) {
                    showMessage({
                        status: 'error',
                        title: 'Missing info',
                        message: 'Name, email, and role are required.',
                    });
                    return;
                }

                if (!isUpdate && !password) {
                    showMessage({
                        status: 'error',
                        title: 'Missing info',
                        message: 'A password is required when creating a user.',
                    });
                    return;
                }

                const payload = { name, email, role_id: Number(roleId) };
                if (password) payload.password = password;

                const res = await apiCall({
                    mode: isUpdate ? 'PATCH' : 'POST',
                    isJson: true,
                    payload,
                    url: isUpdate ? `/api/users/save/${userId}` : '/api/users',
                    button: saveUserBtn,
                });

                if (!res || !res.success) {
                    showMessage({
                        status: 'error',
                        title: 'Error',
                        message: res?.invalid_fields
                            ? Object.values(res.invalid_fields).flat().join(' ')
                            : res?.message ?? 'Failed to save user.',
                    });
                    return;
                }

                showMessage({ status: 'success', title: isUpdate ? 'User updated!' : 'User created!' });
                closeSideModal('userSideModal');
                resetForm();

                await loadTeamMap();
                usersTable.reload();
                refreshActiveStat();
            });

            // ── Boot ─────────────────────────────────────────────────
            async function init() {
                paintChips();
                await Promise.all([loadRoles(), loadTeamMap(), refreshActiveStat()]);
                buildTable();
            }

            init();
        })();
    </script>
</div>
