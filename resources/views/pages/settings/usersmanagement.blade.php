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
        <x-table-container>
            <table id="usersTable" class="w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                </tbody>
            </table>
        </x-table-container>

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

            {{-- DEPARTMENTS --}}
            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden h-fit">

                <div class="px-4 py-3 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                    <div>
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Settings</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 mt-0.5">Departments</p>
                    </div>
                    <div class="relative">
                        <button id="addDeptBtn"
                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-orange-500 hover:bg-orange-600 text-white transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>

                        {{-- Add Dept Dropdown --}}
                        <div id="addDeptDropdown"
                            class="hidden absolute right-0 top-9 w-64 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 flex flex-col gap-3 shadow-xl shadow-black/10 dark:shadow-black/40">
                            <p class="text-xs font-medium text-zinc-400 uppercase tracking-widest">Add Department</p>
                            <div class="flex flex-col gap-1">
                                <label
                                    class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Department
                                    Name</label>
                                <input type="text" id="deptNameInput" placeholder="e.g. Finance Department"
                                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            </div>
                            <div class="flex justify-end gap-2 pt-1 border-t border-zinc-100 dark:border-zinc-800">
                                <button id="cancelDeptBtn"
                                    class="px-3 py-1.5 text-xs font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                                    Cancel
                                </button>
                                <button id="saveDeptBtn"
                                    class="px-3 py-1.5 text-xs font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-zinc-100 dark:divide-zinc-800" id="departmentsContainer">
                </div>

            </div>

            {{-- STATUSES --}}
            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden h-fit">

                <div class="px-4 py-3 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                    <div>
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Settings</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 mt-0.5">Statuses</p>
                    </div>
                    <div class="relative">
                        <button id="addStatusBtn"
                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-orange-500 hover:bg-orange-600 text-white transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>

                        {{-- Add Status Dropdown --}}
                        <div id="addStatusDropdown"
                            class="hidden absolute right-0 top-9 w-64 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 flex flex-col gap-3 shadow-xl shadow-black/10 dark:shadow-black/40">
                            <p class="text-xs font-medium text-zinc-400 uppercase tracking-widest">Add Status</p>
                            <div class="flex flex-col gap-1">
                                <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Status
                                    Name</label>
                                <input type="text" id="statusNameInput" placeholder="e.g. On Leave"
                                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            </div>
                            <div class="flex justify-end gap-2 pt-1 border-t border-zinc-100 dark:border-zinc-800">
                                <button id="cancelStatusBtn"
                                    class="px-3 py-1.5 text-xs font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                                    Cancel
                                </button>
                                <button id="saveStatusBtn"
                                    class="px-3 py-1.5 text-xs font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-zinc-100 dark:divide-zinc-800" id="statusesContainer">
                </div>

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
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">First Name</label>
                    <input type="text" name="first_name" required
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                </div>

                {{-- Last Name --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Last Name</label>
                    <input type="text" name="last_name" required
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                </div>

                {{-- Department --}}
                <div class="flex flex-col gap-1 col-span-2">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Department</label>
                    <input type="text" name="department"
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
        $("#btnNewUser").click(function() {
            initSideModal({
                modalId: "NewUserSideModal"
            });
        });
        getallusers();
        //get all user function
        async function getallusers() {
            const response = await apiCall({
                mode: "GET",
                url: '/api/users',
            });


        }
        getUserSettings();
        async function getUserSettings() {
            const response = await apiCall({
                mode: "GET",
                url: '/api/users/settings',
            });
            console.log(response);

        }

        if (!response.success) {
            showMessage({
                status: "error",
                title: "Error",
                message: "An unexpected error occurred. Please contact the system administrator.",
            });
            return;
        }



        if (!response.success) {
            showMessage({
                status: "error",
                title: "Error",
                message: "An unexpected error occurred. Please contact the system administrator.",
            });
            return;
        }





        //render user table
        function renderUserTable() {

        }

    })();
</script>
