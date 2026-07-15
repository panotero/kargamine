<div class="container mx-auto p-3">

    {{-- PAGE HEADER --}}
    <div class="flex justify-between items-center mb-5 p-2">
        <div>
            <h1 class="text-2xl font-bold">User Management</h1>
            <p class="text-zinc-500">Manage system users and their roles</p>
        </div>
        <button id="btnNewUser"
            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            + New User
        </button>
    </div>

    {{-- STATS CARDS --}}
    <section class="w-full mb-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
                <div class="w-full py-1 rounded-full bg-blue-500 mb-3"></div>
                <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Total Users</p>
                <p class="text-2xl font-bold text-zinc-800 dark:text-zinc-100" id="countAllUsers">0</p>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
                <div class="w-full py-1 rounded-full bg-green-500 mb-3"></div>
                <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Active</p>
                <p class="text-2xl font-bold text-zinc-800 dark:text-zinc-100" id="countActiveUsers">0</p>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
                <div class="w-full py-1 rounded-full bg-zinc-400 mb-3"></div>
                <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Inactive</p>
                <p class="text-2xl font-bold text-zinc-800 dark:text-zinc-100" id="countInactiveUsers">0</p>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
                <div class="w-full py-1 rounded-full bg-purple-500 mb-3"></div>
                <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Roles</p>
                <p class="text-2xl font-bold text-zinc-800 dark:text-zinc-100" id="countRoles">0</p>
            </div>

        </div>
    </section>
    {{-- SETTINGS PANELS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">


    </div>
    {{-- MAIN CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-4">

        {{-- USERS TABLE --}}

        <x-table id="tableUsers" />

        <div class="p-5">

            {{-- ROLES --}}
            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden h-fit">

                <div class="px-4 py-3 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                    <div>
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Settings</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 mt-0.5">Roles</p>
                    </div>
                    <div class="relative">
                        <button id="addRoleBtn"
                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-orange-500 hover:bg-orange-600 text-white transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>

                        {{-- Add Role Dropdown --}}
                        <div id="addRoleDropdown"
                            class="hidden absolute right-0 top-9 w-64 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 flex flex-col gap-3 shadow-xl shadow-black/10 dark:shadow-black/40">
                            <p class="text-xs font-medium text-zinc-400 uppercase tracking-widest">Add Role</p>
                            <div class="flex flex-col gap-1">
                                <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Role
                                    Name</label>
                                <input type="text" id="roleNameInput" placeholder="e.g. manager"
                                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            </div>
                            <div class="flex justify-end gap-2 pt-1 border-t border-zinc-100 dark:border-zinc-800">
                                <button id="cancelRoleBtn"
                                    class="px-3 py-1.5 text-xs font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                                    Cancel
                                </button>
                                <button id="saveRoleBtn"
                                    class="px-3 py-1.5 text-xs font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-zinc-100 dark:divide-zinc-800" id="rolesContainer">
                </div>

            </div>


        </div>

    </div>


    {{-- NEW USER SIDE MODAL --}}
    <x-side-modal id="NewUserSideModal">

        {{-- Header --}}
        <div
            class="p-5 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center sticky top-0 bg-white dark:bg-zinc-900 z-10">
            <div>
                <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">User Management</p>
                <p class="text-lg font-semibold dark:text-white mt-0.5">New User</p>
            </div>
            <button
                class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                ✕
            </button>
        </div>

        {{-- Form --}}
        <div class="p-5">
            <form id="userForm">
                <div class="grid grid-cols-2 gap-3">
                    <input type="hidden" name="userId">
                    {{-- Role --}}
                    <div class="flex flex-col gap-1 col-span-2">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Role</label>
                        <select name="role_id" required
                            class="roleDropDown w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                            <option value="">Select role</option>
                        </select>
                    </div>

                    {{-- First Name --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">First
                            Name</label>
                        <input type="text" name="first_name" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    {{-- Last Name --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Last Name</label>
                        <input type="text" name="last_name" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    {{-- Email --}}
                    <div class="flex flex-col gap-1 col-span-2">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Email</label>
                        <input type="email" name="email" required autocomplete="new-password"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    {{-- Divider --}}
                    <div class="col-span-2 border-t border-zinc-100 dark:border-zinc-800 pt-1">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Security</p>
                    </div>

                    {{-- Password --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Password</label>
                        <input type="password" name="password" required autocomplete="new-password"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    {{-- Confirm Password --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Confirm
                            Password</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                </div>
            </form>
        </div>

        {{-- Footer --}}
        <div
            class="border-t border-zinc-100 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2 sticky bottom-0 bg-white dark:bg-zinc-900">
            <button type="button"
                class="modal-close px-4 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                Cancel
            </button>
            <button type="submit" id="saveUserBtn"
                class="px-4 py-1.5 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
                Save User
            </button>
        </div>

    </x-side-modal>


    {{-- JS --}}
    <script>
        (function() {
            const userForm = document.getElementById('userForm');
            const saveUserBtn = document.getElementById('saveUserBtn');
            async function init() {
                await loadRoles();
                renderTable().load(1);
            }

            function renderTable() {
                const thead = [{
                        title: "Name",
                        key: "name",
                    },
                    {
                        title: "Email",
                        key: "email",
                    },
                    {
                        title: "Role",
                        key: "role.role",
                    },
                    {
                        title: "Created At",
                        key: "created_at",

                    },

                    {
                        title: "Status",
                        key: "status_label",
                    },
                    {
                        title: "Actions",
                        render: (row) => `
        <div class="flex gap-2">
            ${
                Number(row.status) === 0
                    ? `
                                    <button
                                        class="btn-deactivate-user px-2 py-1 bg-red-500 text-white rounded"
                                        data-id="${row.id}">
                                        Deactivate
                                    </button>
                                `
                    : `
                                    <button
                                        class="btn-reactivate-user px-2 py-1 bg-green-500 text-white rounded"
                                        data-id="${row.id}">
                                        Reactivate
                                    </button>
                                `
            }
        </div>
    `,
                    },
                ];

                const table = renderRemoteTable({
                    url: "/api/users",
                    tableId: "tableUsers",
                    afterRenderFunction: handleClick,
                    thead: thead,
                });

                function handleClick(row) {
                    row.addEventListener("click", async function(e) {
                        const data = JSON.parse(row.dataset.row);
                        // console.log(data);


                        const deactivateBtn = e.target.closest('.btn-deactivate-user');
                        if (deactivateBtn) {
                            const id = deactivateBtn.dataset.id;
                            const confirmed = await customConfirm(
                                'Delete this user? This cannot be undone.');
                            if (!confirmed) return;


                            const response = await apiCall({
                                mode: 'PATCH',
                                url: `/api/users/deactivate/${id}`
                            });

                            if (!response.success) {
                                showMessage({
                                    status: 'error',
                                    title: 'Error',
                                    message: response.message ?? 'Failed to delete user.',
                                });
                                return;
                            }

                            showMessage({
                                status: 'success',
                                title: 'User deleted!'
                            });

                            renderTable().load(1);
                            return;
                        }
                        const reactivateBtn = e.target.closest('.btn-reactivate-user');
                        if (reactivateBtn) {
                            const id = reactivateBtn.dataset.id;
                            const confirmed = await customConfirm(
                                'Delete this user? This cannot be undone.');
                            if (!confirmed) return;


                            const response = await apiCall({
                                mode: 'PATCH',
                                url: `/api/users/reactivate/${id}`
                            });

                            if (!response.success) {
                                showMessage({
                                    status: 'error',
                                    title: 'Error',
                                    message: response.message ?? 'Failed to delete user.',
                                });
                                return;
                            }

                            showMessage({
                                status: 'success',
                                title: 'User deleted!'
                            });

                            renderTable().load(1);
                            return;
                        }
                        initSideModal({
                            modalId: 'NewUserSideModal'
                        });

                        LoadUserSideModal(data);
                    });
                }

                return table;
            }

            function LoadUserSideModal(data) {
                console.log(data);
                const fullName = data.name;

                const [firstName, lastName] = fullName.split(" ");
                userForm.userId.value = data.id;
                userForm.role_id.value = data.role_id;
                userForm.email.value = data.email;
                userForm.first_name.value = firstName;
                userForm.last_name.value = lastName;
            }


            document.getElementById("btnNewUser").addEventListener("click", function() {
                initSideModal({
                    modalId: 'NewUserSideModal'
                });

            });


            async function loadRoles() {
                const response = await apiCall({
                    mode: 'GET',
                    url: '/api/roles'
                });
                rolesList = Array.isArray(response) ? response : (response?.data ?? []);
                renderRoles();
                populateRoleDropdowns();
            }

            function populateRoleDropdowns() {
                document.querySelectorAll('.roleDropDown').forEach(select => {
                    const current = select.value;
                    select.innerHTML = '<option value="">Select role</option>' +
                        rolesList.map(r => `<option value="${r.id}">${r.role_name}</option>`).join('');
                    if (current) select.value = current;
                });
            }

            function renderRoles() {
                rolesContainer.innerHTML = '';

                if (!rolesList.length) {
                    rolesContainer.innerHTML = `<p class="text-xs text-zinc-400 p-4">No roles yet.</p>`;
                    return;
                }

                rolesList.forEach(role => {
                    const row = document.createElement('div');
                    row.className = 'flex items-center gap-2 px-4 py-2.5';
                    row.dataset.roleId = role.id;

                    row.innerHTML = `
                    <input type="text" value="${role.role_name}" data-role-name-input readonly
                        class="flex-1 min-w-0 bg-transparent border-none text-sm font-medium text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-0 p-0 truncate" />
                    <span class="text-[10px] font-semibold text-zinc-400 shrink-0">${role.users_count ?? 0} user${(role.users_count ?? 0) !== 1 ? 's' : ''}</span>
                    <button type="button" class="btn-edit-role p-1.5 text-zinc-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition shrink-0" data-id="${role.id}" data-editing="false" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 pointer-events-none">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                        </svg>
                    </button>
                    <button type="button" class="btn-delete-role p-1.5 text-zinc-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition shrink-0" data-id="${role.id}" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 pointer-events-none">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                `;
                    rolesContainer.appendChild(row);
                });
            }


            rolesContainer.addEventListener('click', async (e) => {
                const editBtn = e.target.closest('.btn-edit-role');
                const deleteBtn = e.target.closest('.btn-delete-role');

                // ---- EDIT (toggle -> save) ----
                if (editBtn) {
                    const row = editBtn.closest('[data-role-id]');
                    const input = row.querySelector('[data-role-name-input]');
                    const isEditing = editBtn.dataset.editing === 'true';

                    if (!isEditing) {
                        input.readOnly = false;
                        input.classList.add('border', 'border-blue-300', 'rounded-md', 'px-2', 'py-1');
                        input.focus();
                        editBtn.dataset.editing = 'true';
                        editBtn.innerHTML = '<span class="text-xs font-bold text-blue-600 px-0.5">✓</span>';
                        return;
                    }

                    const roleId = editBtn.dataset.id;
                    const newName = input.value.trim();
                    if (!newName) return;

                    const response = await apiCall({
                        mode: 'PUT',
                        isJson: true,
                        payload: {
                            role_name: newName
                        },
                        url: `/api/roles/${roleId}`,
                    });

                    if (!response.success) {
                        showMessage({
                            status: 'error',
                            title: 'Error',
                            message: response.invalid_fields ?
                                Object.values(response.invalid_fields).flat().join(' ') : (response
                                    .message ?? 'Failed to update role.'),
                        });
                        return;
                    }

                    showMessage({
                        status: 'success',
                        title: 'Role updated!'
                    });
                    await loadRoles();
                    return;
                }

                // ---- DELETE ----
                if (deleteBtn) {
                    const roleId = deleteBtn.dataset.id;
                    const confirmed = await customConfirm(
                        'Delete this role? Roles that still have users assigned cannot be deleted.'
                    );
                    if (!confirmed) return;

                    const response = await apiCall({
                        mode: 'DELETE',
                        url: `/api/roles/${roleId}`
                    });

                    if (!response.success) {
                        showMessage({
                            status: 'error',
                            title: 'Cannot Delete Role',
                            message: response.message ?? 'Failed to delete role.',
                        });
                        return;
                    }

                    showMessage({
                        status: 'success',
                        title: 'Role deleted!'
                    });
                    await loadRoles();
                }
            });

            document.getElementById('addRoleBtn').addEventListener('click', () => {
                document.getElementById('addRoleDropdown').classList.toggle('hidden');
            });

            document.getElementById('cancelRoleBtn').addEventListener('click', () => {
                document.getElementById('roleNameInput').value = '';
                document.getElementById('addRoleDropdown').classList.add('hidden');
            });

            document.getElementById('saveRoleBtn').addEventListener('click', async () => {
                const input = document.getElementById('roleNameInput');
                const roleName = input.value.trim();

                if (!roleName) {
                    input.focus();
                    return;
                }

                const response = await apiCall({
                    mode: 'POST',
                    isJson: true,
                    payload: {
                        role_name: roleName
                    },
                    url: '/api/roles',
                    button: document.getElementById('saveRoleBtn'),
                });

                if (!response.success) {
                    showMessage({
                        status: 'error',
                        title: 'Error',
                        message: response.invalid_fields ?
                            Object.values(response.invalid_fields).flat().join(' ') : (response
                                .message ?? 'Failed to create role.'),
                    });
                    return;
                }

                showMessage({
                    status: 'success',
                    title: 'Role created!'
                });
                input.value = '';
                document.getElementById('addRoleDropdown').classList.add('hidden');
                await loadRoles();
            });


            saveUserBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                const userId = userForm.userId.value;
                const firstName = userForm.first_name.value.trim();
                const lastName = userForm.last_name.value.trim();
                const password = userForm.password.value;
                const passwordConfirmation = userForm.password_confirmation.value;

                if (!firstName || !lastName) {
                    showMessage({
                        status: 'error',
                        title: 'Missing info',
                        message: 'First and last name are required.'
                    });
                    return;
                }

                if (password && password !== passwordConfirmation) {
                    showMessage({
                        status: 'error',
                        title: 'Password mismatch',
                        message: 'Password and confirmation do not match.'
                    });
                    return;
                }

                const payload = {
                    name: `${firstName} ${lastName}`.trim(),
                    email: userForm.email.value.trim(),
                    role_id: userForm.role_id.value || null,
                };

                if (password) payload.password = password;

                const isUpdate = userId != null && userId !== 0;

                const url = isUpdate ?
                    `/api/users/save/${userId}` :
                    '/api/users';

                const mode = isUpdate ? 'PATCH' : 'POST';

                const response = await apiCall({
                    mode,
                    isJson: true,
                    payload,
                    url,
                    button: saveUserBtn
                });

                if (!response.success) {
                    showMessage({
                        status: 'error',
                        title: 'Error',
                        message: response.invalid_fields ?
                            Object.values(response.invalid_fields).flat().join(' ') : (response
                                .message ?? 'Failed to save user.'),
                    });
                    return;
                }

                showMessage({
                    status: 'success',
                    title: 'User created!'
                });

                closeSideModal('NewUserSideModal');
                userForm.reset();
                editingUserId = null;

                renderTable().reload();
            });

            function setModalMode(mode) {
                if (modalTitleEl) modalTitleEl.textContent = mode === 'edit' ? 'Edit User' : 'New User';
                saveUserBtn.textContent = mode === 'edit' ? 'Update User' : 'Save User';

                const pwd = userForm.password;
                const pwdConfirm = userForm.password_confirmation;
                pwd.required = mode !== 'edit';
                pwdConfirm.required = mode !== 'edit';
                pwd.placeholder = mode === 'edit' ? 'Leave blank to keep current password' : '';
                pwdConfirm.placeholder = mode === 'edit' ? 'Leave blank to keep current password' : '';
            }


            init();
        })();
    </script>
