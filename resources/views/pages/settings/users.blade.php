<section class="min-h-screen flex items-center justify-center bg-gray-50 px-6 hidden">
    <div class="max-w-xl w-full text-center">

        <!-- Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 flex items-center justify-center rounded-full bg-red-100">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            This Page is Under Maintenance
        </h1>

        <!-- Description -->
        <p class="text-gray-600 mb-6">
            Our system is currently undergoing scheduled maintenance to improve performance and reliability.
            Please check back shortly.
        </p>

        <!-- Status Badge -->
        <div class="inline-block px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm font-medium mb-6">
            Ongoing Maintenance
        </div>

        <!-- Optional Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">

            <!-- Refresh Button -->
            <button onclick="location.reload()"
                class="px-6 py-3 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700 transition">
                Refresh Page
            </button>


        </div>

    </div>
</section>


<div id="user-management-page" class="min-h-screen bg-slate-50 font-sans">

    <div class="bg-white border-b border-blue-100 px-4 sm:px-6 lg:px-8 py-5">
        <div class="max-w-screen-xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-2">
                <button id="btn-open-role-manager"
                    class="inline-flex items-center gap-2 border border-blue-200 hover:border-blue-400 text-blue-600 hover:text-blue-800 bg-white hover:bg-blue-50 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Manage Roles
                </button>
                <button id="btn-open-new-user"
                    class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    New User
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div
                class="bg-white rounded-2xl border border-blue-100 shadow-sm px-5 py-4 flex flex-col gap-2 hover:shadow-md transition col-span-1">
                <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-4.5 0 2.625 2.625 0 014.5 0z" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium">Total Users</p>
                <p class="text-3xl font-bold text-blue-900" id="kpi-total">—</p>
            </div>
            <div
                class="bg-white rounded-2xl border border-blue-100 shadow-sm px-5 py-4 flex flex-col gap-2 hover:shadow-md transition col-span-1">
                <div class="w-8 h-8 rounded-xl bg-green-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium">Active Users</p>
                <p class="text-3xl font-bold text-green-600" id="kpi-active">—</p>
            </div>
            <div
                class="bg-white rounded-2xl border border-blue-100 shadow-sm px-5 py-4 flex flex-col gap-2 hover:shadow-md transition col-span-1">
                <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium">Inactive Users</p>
                <p class="text-3xl font-bold text-slate-500" id="kpi-inactive">—</p>
            </div>
            <div
                class="bg-white rounded-2xl border border-orange-100 shadow-sm px-5 py-4 flex flex-col gap-2 hover:shadow-md transition col-span-1">
                <div class="w-8 h-8 rounded-xl bg-orange-100 flex items-center justify-center">
                    <span class="w-2.5 h-2.5 rounded-full bg-orange-400 animate-pulse inline-block"></span>
                </div>
                <p class="text-xs text-blue-400 font-medium">Online Now</p>
                <p class="text-3xl font-bold text-orange-500" id="kpi-online">—</p>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             ROW 2 — ROLE PERMISSION SUMMARY CARDS
        ═══════════════════════════════════════════════════════════ --}}
        <div class="hidden">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-orange-400 rounded-full"></span>
                    <h2 class="text-sm font-bold text-blue-900 uppercase tracking-wider">Roles & Permissions</h2>
                </div>
                <button id="btn-open-role-manager-2"
                    class="text-xs text-orange-500 hover:text-orange-600 font-semibold transition">
                    Manage Roles →
                </button>
            </div>
            <div id="role-cards-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Rendered by JS --}}
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             ROW 3 — ONLINE USERS STRIP
        ═══════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm px-6 py-4 hidden">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    <h3 class="text-sm font-bold text-blue-900">Currently Online</h3>
                </div>
                <span class="text-xs text-blue-400" id="online-count-label">0 users active</span>
            </div>
            <div id="online-strip" class="flex flex-wrap gap-3">
                {{-- Rendered by JS --}}
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             ROW 4 — USERS TABLE
        ═══════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
            <div
                class="flex items-center justify-between px-6 py-4 border-b border-blue-50 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-4.5 0 2.625 2.625 0 014.5 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-900">All Users</h3>
                        <p class="text-xs text-blue-400">Click a row to view details</p>
                    </div>
                </div>
                {{-- Filter tabs --}}
                <div class="flex bg-slate-100 rounded-xl p-0.5 gap-0.5" id="user-filter-tabs">
                    <button class="user-tab-btn active-tab px-3 py-1.5 text-xs font-semibold rounded-lg transition"
                        data-filter="all">All</button>
                    <button
                        class="user-tab-btn px-3 py-1.5 text-xs font-semibold rounded-lg text-blue-400 hover:text-blue-600 transition"
                        data-filter="active">Active</button>
                    <button
                        class="user-tab-btn px-3 py-1.5 text-xs font-semibold rounded-lg text-blue-400 hover:text-blue-600 transition"
                        data-filter="inactive">Inactive</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="users-table" class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-blue-50 text-blue-500 text-xs uppercase tracking-wider">
                            <th class="px-5 py-3 text-left font-semibold">User</th>
                            <th class="px-5 py-3 text-left font-semibold">Email</th>
                            <th class="px-5 py-3 text-left font-semibold">Role</th>
                            <th class="px-5 py-3 text-left font-semibold">Status</th>
                            <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Last Seen</th>
                            <th class="px-5 py-3 text-center font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody" class="divide-y divide-blue-50 text-blue-900">
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             ROW 5 — LOGIN ACTIVITY LOG
        ═══════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
            <div
                class="flex items-center justify-between px-6 py-4 border-b border-blue-50 bg-gradient-to-r from-orange-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-900">Login Activity</h3>
                        <p class="text-xs text-blue-400">Recent sign-in events across all users</p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="activity-table" class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-orange-50 text-orange-400 text-xs uppercase tracking-wider">
                            <th class="px-5 py-3 text-left font-semibold">User</th>
                            <th class="px-5 py-3 text-left font-semibold">Role</th>
                            <th class="px-5 py-3 text-left font-semibold">IP Address</th>
                            <th class="px-5 py-3 text-left font-semibold">Device / Browser</th>
                            <th class="px-5 py-3 text-left font-semibold">Date & Time</th>
                            <th class="px-5 py-3 text-left font-semibold">Result</th>
                        </tr>
                    </thead>
                    <tbody id="activity-tbody" class="divide-y divide-orange-50 text-blue-900">
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- end max-w --}}
</div>{{-- end page --}}


{{-- ═══════════════════════════════════════════════════════════
     MODAL — Add New User
════════════════════════════════════════════════════════════════ --}}
<div id="new-user-modal"
    class="fixed inset-0 z-50 flex items-start justify-center bg-blue-950/40 backdrop-blur-sm overflow-y-auto py-6 px-4 hidden"
    role="dialog" aria-modal="true">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-auto my-auto">
        <div
            class="flex items-center justify-between px-6 py-5 border-b border-blue-100 sticky top-0 bg-white rounded-t-2xl z-10">
            <div class="flex items-center gap-3">
                <span class="w-1.5 h-6 bg-orange-400 rounded-full"></span>
                <div>
                    <h2 class="text-base font-bold text-blue-900">New User</h2>
                    <p class="text-xs text-blue-400 mt-0.5">Create a new system user account</p>
                </div>
            </div>
            <button
                class="modal-close-btn p-2 text-blue-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition"
                data-modal="new-user-modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="new-user-form" novalidate class="px-6 py-5 space-y-4">
            {{-- Name row --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-blue-500 mb-1">First Name <span
                            class="text-orange-400">*</span></label>
                    <input type="text" name="first_name" placeholder="Juan"
                        class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-blue-500 mb-1">Last Name <span
                            class="text-orange-400">*</span></label>
                    <input type="text" name="last_name" placeholder="dela Cruz"
                        class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-blue-500 mb-1">Email Address <span
                        class="text-orange-400">*</span></label>
                <input type="email" name="email" placeholder="juan@company.com"
                    class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
            </div>
            <div>
                <label class="block text-xs font-semibold text-blue-500 mb-1">Phone Number</label>
                <input type="text" name="phone" placeholder="+63 9XX XXX XXXX"
                    class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-blue-500 mb-1">Password <span
                            class="text-orange-400">*</span></label>
                    <div class="relative">
                        <input type="password" id="new-user-password" name="password"
                            placeholder="Min. 8 characters"
                            class="w-full border border-blue-100 rounded-xl px-4 py-2.5 pr-10 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        <button type="button"
                            class="btn-toggle-pw absolute right-3 top-1/2 -translate-y-1/2 text-blue-300 hover:text-blue-500 transition"
                            data-target="new-user-password">
                            <svg class="w-4 h-4 icon-eye" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-blue-500 mb-1">Department</label>
                    <select name="department"
                        class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                        <option value="">Select Dept.</option>
                        <option>Operations</option>
                        <option>Finance</option>
                        <option>Sales</option>
                        <option>IT</option>
                        <option>Administration</option>
                        <option>Customer Service</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-blue-500 mb-1">Role <span
                            class="text-orange-400">*</span></label>
                    <select name="role" id="new-user-role"
                        class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                        <option value="">Select Role</option>
                        {{-- Populated dynamically by JS --}}
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-blue-500 mb-1">Status <span
                            class="text-orange-400">*</span></label>
                    <select name="status"
                        class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-blue-500 mb-1">Assigned Accounts</label>
                <div id="assigned-accounts-list"
                    class="border border-blue-100 rounded-xl bg-slate-50 px-4 py-3 grid grid-cols-2 gap-2">
                    {{-- Populated by JS --}}
                </div>
                <p class="text-xs text-blue-300 mt-1">Select the shipper/consignee accounts this user can access</p>
            </div>
            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button"
                    class="modal-close-btn px-5 py-2.5 text-sm font-semibold text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition"
                    data-modal="new-user-modal">Cancel</button>
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-orange-500 hover:bg-orange-600 active:bg-orange-700 rounded-xl shadow-sm transition">Create
                    User</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     MODAL — Role Manager
════════════════════════════════════════════════════════════════ --}}
<div id="role-manager-modal"
    class="fixed inset-0 z-50 flex items-start justify-center bg-blue-950/40 backdrop-blur-sm overflow-y-auto py-6 px-4 hidden"
    role="dialog" aria-modal="true">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto my-auto">
        <div
            class="flex items-center justify-between px-6 py-5 border-b border-blue-100 sticky top-0 bg-white rounded-t-2xl z-10">
            <div class="flex items-center gap-3">
                <span class="w-1.5 h-6 bg-blue-500 rounded-full"></span>
                <div>
                    <h2 class="text-base font-bold text-blue-900">Role Manager</h2>
                    <p class="text-xs text-blue-400 mt-0.5">Define roles and toggle permissions</p>
                </div>
            </div>
            <button
                class="modal-close-btn p-2 text-blue-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition"
                data-modal="role-manager-modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="px-6 py-5 space-y-5">

            {{-- Add New Role --}}
            <div class="flex gap-3 items-end">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-blue-500 mb-1">New Role Name</label>
                    <input type="text" id="new-role-input" placeholder="e.g. Logistics Officer"
                        class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                </div>
                <button id="btn-add-role"
                    class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Role
                </button>
            </div>

            {{-- Permission Matrix --}}
            <div id="role-permission-matrix" class="space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                {{-- Rendered by JS --}}
            </div>
        </div>
        <div class="px-6 py-5 border-t border-blue-100 flex justify-end gap-3">
            <button
                class="modal-close-btn px-5 py-2.5 text-sm font-semibold text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition"
                data-modal="role-manager-modal">Cancel</button>
            <button id="btn-save-roles"
                class="px-6 py-2.5 text-sm font-semibold text-white bg-orange-500 hover:bg-orange-600 rounded-xl shadow-sm transition">Save
                Roles</button>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════════════════════════ --}}
<script>
    (function() {
        'use strict';

        // ══════════════════════════════════════════════
        // SAMPLE DATA
        // ══════════════════════════════════════════════

        const PERMISSIONS = [{
                key: 'create_booking',
                label: 'Create Booking'
            },
            {
                key: 'manage_accounts',
                label: 'Manage Accounts'
            },
            {
                key: 'app_maintenance',
                label: 'App Maintenance (LOV)'
            },
            {
                key: 'user_management',
                label: 'User Management'
            },
            {
                key: 'finance_billing',
                label: 'Finance / Billing'
            },
        ];

        let ROLES = [{
                id: 1,
                name: 'Administrator',
                color: 'blue',
                permissions: {
                    create_booking: true,
                    manage_accounts: true,
                    app_maintenance: true,
                    user_management: true,
                    finance_billing: true
                }
            },
            {
                id: 2,
                name: 'Operations',
                color: 'orange',
                permissions: {
                    create_booking: true,
                    manage_accounts: true,
                    app_maintenance: false,
                    user_management: false,
                    finance_billing: false
                }
            },
            {
                id: 3,
                name: 'Finance',
                color: 'green',
                permissions: {
                    create_booking: false,
                    manage_accounts: false,
                    app_maintenance: false,
                    user_management: false,
                    finance_billing: true
                }
            },
            {
                id: 4,
                name: 'Sales',
                color: 'purple',
                permissions: {
                    create_booking: true,
                    manage_accounts: true,
                    app_maintenance: false,
                    user_management: false,
                    finance_billing: false
                }
            },
        ];

        const ACCOUNTS = [
            'Layag Freight Solutions', 'Daloy Trading Co.', 'Agos Manufacturing Inc.',
            'Sigla Logistics PH', 'Kulay Retail Group', 'Bukid Agritech Corp.',
        ];

        const ROLE_COLORS = {
            blue: {
                badge: 'bg-blue-100 text-blue-700',
                card: 'border-blue-200 bg-blue-50',
                dot: 'bg-blue-500'
            },
            orange: {
                badge: 'bg-orange-100 text-orange-600',
                card: 'border-orange-200 bg-orange-50',
                dot: 'bg-orange-400'
            },
            green: {
                badge: 'bg-green-100 text-green-700',
                card: 'border-green-200 bg-green-50',
                dot: 'bg-green-500'
            },
            purple: {
                badge: 'bg-purple-100 text-purple-700',
                card: 'border-purple-200 bg-purple-50',
                dot: 'bg-purple-500'
            },
            slate: {
                badge: 'bg-slate-100 text-slate-600',
                card: 'border-slate-200 bg-slate-50',
                dot: 'bg-slate-400'
            },
        };

        const USERS = [{
                id: 1,
                name: 'Juan dela Cruz',
                email: 'jdelacruz@company.com',
                role: 'Administrator',
                status: 'active',
                lastSeen: 'Online now',
                online: true
            },
            {
                id: 2,
                name: 'Maria Santos',
                email: 'msantos@company.com',
                role: 'Operations',
                status: 'active',
                lastSeen: 'Online now',
                online: true
            },
            {
                id: 3,
                name: 'Pedro Reyes',
                email: 'preyes@company.com',
                role: 'Finance',
                status: 'active',
                lastSeen: '12m ago',
                online: false
            },
            {
                id: 4,
                name: 'Ana Garcia',
                email: 'agarcia@company.com',
                role: 'Sales',
                status: 'active',
                lastSeen: '1h ago',
                online: false
            },
            {
                id: 5,
                name: 'Carlo Bautista',
                email: 'cbautista@company.com',
                role: 'Operations',
                status: 'active',
                lastSeen: 'Online now',
                online: true
            },
            {
                id: 6,
                name: 'Luz Torres',
                email: 'ltorres@company.com',
                role: 'Finance',
                status: 'inactive',
                lastSeen: '3 days ago',
                online: false
            },
            {
                id: 7,
                name: 'Ramon Cruz',
                email: 'rcruz@company.com',
                role: 'Sales',
                status: 'active',
                lastSeen: '2h ago',
                online: false
            },
            {
                id: 8,
                name: 'Elena Mendoza',
                email: 'emendoza@company.com',
                role: 'Operations',
                status: 'inactive',
                lastSeen: '1 week ago',
                online: false
            },
            {
                id: 9,
                name: 'Dino Aquino',
                email: 'daquino@company.com',
                role: 'Sales',
                status: 'active',
                lastSeen: 'Online now',
                online: true
            },
            {
                id: 10,
                name: 'Rosa Villanueva',
                email: 'rvillanueva@company.com',
                role: 'Administrator',
                status: 'active',
                lastSeen: '30m ago',
                online: false
            },
        ];

        const LOGIN_ACTIVITY = [{
                user: 'Juan dela Cruz',
                role: 'Administrator',
                ip: '192.168.1.10',
                device: 'Chrome / Windows',
                datetime: '2025-03-24 08:02',
                result: 'success'
            },
            {
                user: 'Maria Santos',
                role: 'Operations',
                ip: '192.168.1.22',
                device: 'Safari / macOS',
                datetime: '2025-03-24 08:15',
                result: 'success'
            },
            {
                user: 'Carlo Bautista',
                role: 'Operations',
                ip: '192.168.1.34',
                device: 'Chrome / Android',
                datetime: '2025-03-24 08:44',
                result: 'success'
            },
            {
                user: 'Luz Torres',
                role: 'Finance',
                ip: '203.77.12.45',
                device: 'Firefox / Windows',
                datetime: '2025-03-24 09:01',
                result: 'failed'
            },
            {
                user: 'Dino Aquino',
                role: 'Sales',
                ip: '192.168.1.55',
                device: 'Chrome / Windows',
                datetime: '2025-03-24 09:18',
                result: 'success'
            },
            {
                user: 'Ana Garcia',
                role: 'Sales',
                ip: '192.168.1.42',
                device: 'Edge / Windows',
                datetime: '2025-03-24 09:45',
                result: 'success'
            },
            {
                user: 'Rosa Villanueva',
                role: 'Administrator',
                ip: '192.168.1.11',
                device: 'Chrome / macOS',
                datetime: '2025-03-24 10:02',
                result: 'success'
            },
            {
                user: 'Luz Torres',
                role: 'Finance',
                ip: '203.77.12.45',
                device: 'Firefox / Windows',
                datetime: '2025-03-24 10:15',
                result: 'failed'
            },
            {
                user: 'Pedro Reyes',
                role: 'Finance',
                ip: '192.168.1.28',
                device: 'Chrome / Linux',
                datetime: '2025-03-24 10:31',
                result: 'success'
            },
            {
                user: 'Ramon Cruz',
                role: 'Sales',
                ip: '192.168.1.67',
                device: 'Safari / iOS',
                datetime: '2025-03-24 10:58',
                result: 'success'
            },
        ];

        // ══════════════════════════════════════════════
        // KPI CARDS
        // ══════════════════════════════════════════════
        function renderKPIs() {
            const active = USERS.filter(u => u.status === 'active').length;
            const inactive = USERS.filter(u => u.status === 'inactive').length;
            const online = USERS.filter(u => u.online).length;
            document.getElementById('kpi-total').textContent = USERS.length;
            document.getElementById('kpi-active').textContent = active;
            document.getElementById('kpi-inactive').textContent = inactive;
            document.getElementById('kpi-online').textContent = online;
        }

        // ══════════════════════════════════════════════
        // ROLE PERMISSION CARDS
        // ══════════════════════════════════════════════
        function getRoleColor(roleName) {
            const r = ROLES.find(r => r.name === roleName);
            return r ? r.color : 'slate';
        }

        function renderRoleCards() {
            const container = document.getElementById('role-cards-container');
            container.innerHTML = '';
            ROLES.forEach(role => {
                const c = ROLE_COLORS[role.color] || ROLE_COLORS.slate;
                const count = USERS.filter(u => u.role === role.name).length;
                const grantedPerms = PERMISSIONS.filter(p => role.permissions[p.key]);
                const card = document.createElement('div');
                card.className = `rounded-2xl border ${c.card} px-5 py-4 space-y-3`;
                card.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full ${c.dot}"></span>
                        <span class="text-sm font-bold text-blue-900">${role.name}</span>
                    </div>
                    <span class="${c.badge} text-xs font-bold px-2 py-0.5 rounded-full">${count} user${count !== 1 ? 's' : ''}</span>
                </div>
                <div class="space-y-1.5">
                    ${PERMISSIONS.map(p => `
                        <div class="flex items-center gap-2">
                            ${role.permissions[p.key]
                                ? `<svg class="w-3.5 h-3.5 text-green-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>`
                                : `<svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>`
                            }
                            <span class="text-xs ${role.permissions[p.key] ? 'text-blue-700' : 'text-slate-400'}">${p.label}</span>
                        </div>
                    `).join('')}
                </div>
            `;
                container.appendChild(card);
            });
        }

        // ══════════════════════════════════════════════
        // ONLINE STRIP
        // ══════════════════════════════════════════════
        function renderOnlineStrip() {
            const strip = document.getElementById('online-strip');
            const label = document.getElementById('online-count-label');
            const online = USERS.filter(u => u.online);
            label.textContent = `${online.length} user${online.length !== 1 ? 's' : ''} active`;
            strip.innerHTML = '';
            if (online.length === 0) {
                strip.innerHTML = '<p class="text-xs text-blue-300">No users currently online.</p>';
                return;
            }
            online.forEach(u => {
                const initials = u.name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();
                const c = ROLE_COLORS[getRoleColor(u.role)] || ROLE_COLORS.slate;
                const div = document.createElement('div');
                div.className =
                    'flex items-center gap-2 bg-slate-50 border border-blue-100 rounded-xl px-3 py-2 hover:bg-blue-50 transition cursor-pointer';
                div.innerHTML = `
                <div class="relative">
                    <div class="w-8 h-8 rounded-full ${c.badge} flex items-center justify-center text-xs font-bold">${initials}</div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-green-400 border-2 border-white"></span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-blue-900 whitespace-nowrap">${u.name.split(' ')[0]}</p>
                    <p class="text-xs text-blue-400">${u.role}</p>
                </div>
            `;
                div.addEventListener('click', () => console.log('[UserMgmt] Online user clicked, ID:', u.id,
                    u.name));
                strip.appendChild(div);
            });
        }

        // ══════════════════════════════════════════════
        // USERS TABLE
        // ══════════════════════════════════════════════
        let activeFilter = 'all';

        function renderUsersTable(filter = 'all') {
            activeFilter = filter;
            const tbody = document.getElementById('users-tbody');
            tbody.innerHTML = '';
            const filtered = filter === 'all' ? USERS : USERS.filter(u => u.status === filter);

            filtered.forEach(u => {
                const initials = u.name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();
                const c = ROLE_COLORS[getRoleColor(u.role)] || ROLE_COLORS.slate;
                const isActive = u.status === 'active';
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-blue-50 transition-colors duration-100 cursor-pointer group';
                tr.dataset.id = u.id;
                tr.innerHTML = `
                <td class="px-5 py-3 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        <div class="relative shrink-0">
                            <div class="w-9 h-9 rounded-full ${c.badge} flex items-center justify-center text-xs font-bold">${initials}</div>
                            ${u.online ? `<span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-green-400 border-2 border-white"></span>` : ''}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-blue-900">${u.name}</p>
                            <p class="text-xs text-blue-400">${u.email.split('@')[1] ? '@' + u.email.split('@')[1] : ''}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3 whitespace-nowrap text-sm text-blue-500">${u.email}</td>
                <td class="px-5 py-3 whitespace-nowrap">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full ${c.badge}">${u.role}</span>
                </td>
                <td class="px-5 py-3 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full ${isActive ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500'}">
                        <span class="w-1.5 h-1.5 rounded-full ${isActive ? 'bg-green-500' : 'bg-slate-400'}"></span>
                        ${u.status.charAt(0).toUpperCase() + u.status.slice(1)}
                    </span>
                </td>
                <td class="px-5 py-3 whitespace-nowrap text-xs ${u.online ? 'text-green-500 font-semibold' : 'text-blue-400'}">${u.lastSeen}</td>
                <td class="px-5 py-3 whitespace-nowrap text-center">
                    <button
                        class="btn-deactivate inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg border transition
                            ${isActive
                                ? 'border-red-200 text-red-400 hover:bg-red-50 hover:text-red-600 hover:border-red-300'
                                : 'border-green-200 text-green-500 hover:bg-green-50 hover:text-green-700 hover:border-green-300'}"
                        data-id="${u.id}" data-status="${u.status}">
                        ${isActive
                            ? `<svg class="w-3.5 h-3.5 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>Deactivate`
                            : `<svg class="w-3.5 h-3.5 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Activate`
                        }
                    </button>
                </td>
            `;
                tbody.appendChild(tr);
            });

            // Re-init DataTables if available
            if (typeof window.initDataTables === 'function') {
                window.initDataTables();
            }
        }

        // Row click (not on action button)
        document.addEventListener('click', function(e) {
            const deactivateBtn = e.target.closest('.btn-deactivate');
            if (deactivateBtn) {
                e.stopPropagation();
                const id = parseInt(deactivateBtn.dataset.id);
                const status = deactivateBtn.dataset.status;
                const newStatus = status === 'active' ? 'inactive' : 'active';
                const user = USERS.find(u => u.id === id);
                if (user) {
                    user.status = newStatus;
                    if (newStatus === 'inactive') user.online = false;
                    console.log(
                        `[UserMgmt] User ${newStatus === 'inactive' ? 'deactivated' : 'activated'} — ID: ${id}, Name: ${user.name}`
                    );
                    renderUsersTable(activeFilter);
                    renderKPIs();
                    renderOnlineStrip();
                    renderRoleCards();
                }
                return;
            }
            const row = e.target.closest('tr[data-id]');
            if (row) {
                const id = row.dataset.id;
                const user = USERS.find(u => u.id === parseInt(id));
                console.log('[UserMgmt] User row clicked — ID:', id, 'Name:', user?.name);
            }
        });

        // Filter tabs
        document.querySelectorAll('.user-tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.user-tab-btn').forEach(b => {
                    b.classList.remove('active-tab', 'bg-white', 'text-blue-700',
                        'shadow-sm');
                    b.classList.add('text-blue-400');
                });
                this.classList.add('active-tab', 'bg-white', 'text-blue-700', 'shadow-sm');
                this.classList.remove('text-blue-400');
                renderUsersTable(this.dataset.filter);
                console.log('[UserMgmt] Filter changed to:', this.dataset.filter);
            });
        });

        // ══════════════════════════════════════════════
        // LOGIN ACTIVITY TABLE
        // ══════════════════════════════════════════════
        function renderActivityTable() {
            const tbody = document.getElementById('activity-tbody');
            tbody.innerHTML = '';
            LOGIN_ACTIVITY.forEach(a => {
                const isSuccess = a.result === 'success';
                const initials = a.user.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();
                const c = ROLE_COLORS[getRoleColor(a.role)] || ROLE_COLORS.slate;
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-orange-50 transition-colors duration-100';
                tr.innerHTML = `
                <td class="px-5 py-3 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full ${c.badge} flex items-center justify-center text-xs font-bold shrink-0">${initials}</div>
                        <span class="text-sm font-semibold text-blue-900">${a.user}</span>
                    </div>
                </td>
                <td class="px-5 py-3 whitespace-nowrap">
                    <span class="text-xs font-semibold px-2 py-1 rounded-full ${c.badge}">${a.role}</span>
                </td>
                <td class="px-5 py-3 whitespace-nowrap font-mono text-xs text-blue-500">${a.ip}</td>
                <td class="px-5 py-3 whitespace-nowrap text-xs text-blue-500">${a.device}</td>
                <td class="px-5 py-3 whitespace-nowrap text-xs text-blue-400">${a.datetime}</td>
                <td class="px-5 py-3 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full ${isSuccess ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-500'}">
                        <span class="w-1.5 h-1.5 rounded-full ${isSuccess ? 'bg-green-500' : 'bg-red-400'}"></span>
                        ${isSuccess ? 'Success' : 'Failed'}
                    </span>
                </td>
            `;
                tbody.appendChild(tr);
            });
        }

        // ══════════════════════════════════════════════
        // ROLE MANAGER MODAL — PERMISSION MATRIX
        // ══════════════════════════════════════════════
        function renderPermissionMatrix() {
            const container = document.getElementById('role-permission-matrix');
            container.innerHTML = '';
            ROLES.forEach(role => {
                const c = ROLE_COLORS[role.color] || ROLE_COLORS.slate;
                const card = document.createElement('div');
                card.className = `rounded-xl border ${c.card} overflow-hidden`;
                card.innerHTML = `
                <div class="flex items-center justify-between px-4 py-3 border-b border-blue-50">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full ${c.dot}"></span>
                        <span class="text-sm font-bold text-blue-900">${role.name}</span>
                    </div>
                    <button class="btn-delete-role p-1 text-slate-300 hover:text-red-400 hover:bg-red-50 rounded-lg transition text-xs" data-role-id="${role.id}" title="Remove role">
                        <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                <div class="px-4 py-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                    ${PERMISSIONS.map(p => `
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <div class="relative">
                                <input type="checkbox" class="perm-toggle sr-only" data-role-id="${role.id}" data-perm="${p.key}" ${role.permissions[p.key] ? 'checked' : ''}/>
                                <div class="perm-toggle-track w-8 h-4 rounded-full transition-colors duration-200 ${role.permissions[p.key] ? 'bg-orange-400' : 'bg-slate-200'}">
                                    <div class="perm-toggle-thumb absolute top-0.5 left-0.5 w-3 h-3 rounded-full bg-white shadow-sm transition-transform duration-200 ${role.permissions[p.key] ? 'translate-x-4' : 'translate-x-0'}"></div>
                                </div>
                            </div>
                            <span class="text-xs ${role.permissions[p.key] ? 'text-blue-800 font-semibold' : 'text-slate-400'}">${p.label}</span>
                        </label>
                    `).join('')}
                </div>
            `;
                container.appendChild(card);
            });

            // Toggle permission checkboxes
            container.querySelectorAll('.perm-toggle').forEach(chk => {
                chk.addEventListener('change', function() {
                    const roleId = parseInt(this.dataset.roleId);
                    const perm = this.dataset.perm;
                    const role = ROLES.find(r => r.id === roleId);
                    if (role) role.permissions[perm] = this.checked;
                    const track = this.nextElementSibling;
                    const thumb = track.querySelector('.perm-toggle-thumb');
                    const label = this.closest('label').querySelector('span');
                    track.classList.toggle('bg-orange-400', this.checked);
                    track.classList.toggle('bg-slate-200', !this.checked);
                    thumb.classList.toggle('translate-x-4', this.checked);
                    thumb.classList.toggle('translate-x-0', !this.checked);
                    label.classList.toggle('text-blue-800', this.checked);
                    label.classList.toggle('font-semibold', this.checked);
                    label.classList.toggle('text-slate-400', !this.checked);
                    console.log(
                        `[RoleMgr] Permission toggled — Role: ${role?.name}, Perm: ${perm}, Granted: ${this.checked}`
                    );
                });
            });

            // Delete role
            container.querySelectorAll('.btn-delete-role').forEach(btn => {
                btn.addEventListener('click', function() {
                    const roleId = parseInt(this.dataset.roleId);
                    const role = ROLES.find(r => r.id === roleId);
                    ROLES = ROLES.filter(r => r.id !== roleId);
                    renderPermissionMatrix();
                    console.log('[RoleMgr] Role deleted — ID:', roleId, 'Name:', role?.name);
                });
            });
        }

        // Add new role
        document.getElementById('btn-add-role').addEventListener('click', function() {
            const input = document.getElementById('new-role-input');
            const name = input.value.trim();
            if (!name) {
                input.focus();
                return;
            }
            const colors = ['slate', 'blue', 'orange', 'green', 'purple'];
            const newRole = {
                id: Date.now(),
                name,
                color: colors[ROLES.length % colors.length],
                permissions: Object.fromEntries(PERMISSIONS.map(p => [p.key, false])),
            };
            ROLES.push(newRole);
            input.value = '';
            renderPermissionMatrix();
            populateRoleSelect();
            console.log('[RoleMgr] Role added:', name);
        });

        document.getElementById('btn-save-roles').addEventListener('click', function() {
            console.log('[RoleMgr] Roles saved:', JSON.parse(JSON.stringify(ROLES)));
            renderRoleCards();
            closeModal('role-manager-modal');
            // TODO: axios.post('/api/roles', { roles: ROLES })
        });

        // ══════════════════════════════════════════════
        // POPULATE ROLE SELECT IN NEW USER FORM
        // ══════════════════════════════════════════════
        function populateRoleSelect() {
            const sel = document.getElementById('new-user-role');
            const val = sel.value;
            sel.innerHTML = '<option value="">Select Role</option>';
            ROLES.forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.name;
                opt.textContent = r.name;
                if (r.name === val) opt.selected = true;
                sel.appendChild(opt);
            });
        }

        // ══════════════════════════════════════════════
        // POPULATE ASSIGNED ACCOUNTS CHECKBOXES
        // ══════════════════════════════════════════════
        function populateAccountsList() {
            const container = document.getElementById('assigned-accounts-list');
            container.innerHTML = '';
            ACCOUNTS.forEach(acc => {
                const label = document.createElement('label');
                label.className =
                    'flex items-center gap-2 cursor-pointer text-xs text-blue-700 hover:text-blue-900 transition';
                label.innerHTML = `
                <input type="checkbox" name="assigned_accounts[]" value="${acc}" class="accent-orange-500 rounded"/>
                <span>${acc}</span>
            `;
                container.appendChild(label);
            });
        }

        // ══════════════════════════════════════════════
        // NEW USER FORM SUBMISSION
        // ══════════════════════════════════════════════
        document.getElementById('new-user-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const data = new FormData(this);
            const payload = {};
            data.forEach((value, key) => {
                if (key === 'assigned_accounts[]') {
                    if (!payload.assigned_accounts) payload.assigned_accounts = [];
                    payload.assigned_accounts.push(value);
                } else {
                    payload[key] = value;
                }
            });
            console.log('[UserMgmt] New user form submitted — payload:', payload);
            // TODO: axios.post('/api/users', payload)
            closeModal('new-user-modal');
            this.reset();
        });

        // ══════════════════════════════════════════════
        // PASSWORD TOGGLE
        // ══════════════════════════════════════════════
        document.querySelectorAll('.btn-toggle-pw').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = document.getElementById(this.dataset.target);
                input.type = input.type === 'password' ? 'text' : 'password';
                console.log('[UserMgmt] Password visibility toggled');
            });
        });

        // ══════════════════════════════════════════════
        // MODAL OPEN / CLOSE
        // ══════════════════════════════════════════════
        function openModal(id) {
            const m = document.getElementById(id);
            if (!m) return;
            m.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            if (id === 'role-manager-modal') renderPermissionMatrix();
            console.log('[UserMgmt] Modal opened:', id);
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            if (!m) return;
            m.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            console.log('[UserMgmt] Modal closed:', id);
        }

        document.getElementById('btn-open-new-user').addEventListener('click', () => openModal('new-user-modal'));
        document.getElementById('btn-open-role-manager').addEventListener('click', () => openModal(
            'role-manager-modal'));
        document.getElementById('btn-open-role-manager-2').addEventListener('click', () => openModal(
            'role-manager-modal'));

        document.querySelectorAll('.modal-close-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                closeModal(this.dataset.modal);
            });
        });

        document.querySelectorAll('#new-user-modal, #role-manager-modal').forEach(m => {
            m.addEventListener('click', function(e) {
                if (e.target === m) closeModal(m.id);
            });
        });

        // ══════════════════════════════════════════════
        // DEFAULT ACTIVE TAB STYLING
        // ══════════════════════════════════════════════
        document.querySelectorAll('.active-tab').forEach(btn => {
            btn.classList.add('bg-white', 'text-blue-700', 'shadow-sm');
        });

        // ══════════════════════════════════════════════
        // DATATABLE INIT
        // ══════════════════════════════════════════════
        window.initDataTables = function() {
            $("table").each(function() {
                if (!$.fn.DataTable.isDataTable(this)) {
                    const dt = $(this).DataTable({
                        paging: true,
                        searching: true,
                        info: false,
                        lengthChange: false,
                        scrollY: "550px",
                        scrollCollapse: true,
                        pageLength: 10,
                        scrollX: $(window).width() < 1024,
                        responsive: true,
                        autoWidth: true,
                        dom: "<'dt-top'f>" + "<'dt-wrapper't>" + "<'dt-bottom'i p>",
                        order: [],
                        columnDefs: [{
                            orderable: false,
                            targets: -1
                        }],
                    });
                    styleDataTable(this);
                    dt.on("draw", () => styleDataTable(this));
                    $(window).on("resize", () => {
                        dt.settings()[0].oInit.scrollX = $(window).width() < 1024;
                        dt.columns.adjust();
                    });
                }
            });
        };

        function styleDataTable(table) {
            table.querySelectorAll("tbody").forEach(tbody => {
                tbody.classList.remove("divide-y", "divide-gray-200", "dark:divide-gray-700");
                tbody.querySelectorAll("tr").forEach(row => {
                    row.classList.remove("even:bg-gray-50", "dark:even:bg-gray-900/50");
                    row.classList.add("transition-colors", "duration-300", "hover:border-white",
                        "hover:border-3");
                });
            });
            document.querySelectorAll(".pagination").forEach(el => el.classList.add("flex", "justify-center", "p-5",
                "lg:justify-end", "dark:text-white"));
            document.querySelectorAll(".dt-search").forEach(el => el.classList.add("flex", "justify-center", "p-5",
                "lg:justify-end", "dark:text-white"));
            document.querySelectorAll(".dt-wrapper").forEach(el => el.classList.add("bg-white", "text-black"));
        }

        // ══════════════════════════════════════════════
        // BOOT
        // ══════════════════════════════════════════════
        function boot() {
            renderKPIs();
            renderRoleCards();
            renderOnlineStrip();
            renderUsersTable('all');
            renderActivityTable();
            populateRoleSelect();
            populateAccountsList();
            if (typeof $ !== 'undefined' && $.fn && $.fn.DataTable) {
                window.initDataTables();
            }
            console.log('[UserMgmt] Page initialized.');
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }

    })();
</script>
