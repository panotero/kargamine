{{-- Dashboard --}}
{{-- Blade SPA Page | Laravel 10 | Tailwind CSS | Chart.js --}}

<div id="dashboard-page" class="min-h-screen font-sans">

    {{-- ── Top Bar ─────────────────────────────────────────────── --}}
    <div class="bg-white border-b border-blue-100 px-4 sm:px-6 lg:px-8 py-5 hidden">
        <div class="max-w-screen-xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-blue-900 tracking-tight">Dashboard</h1>
                <p class="text-xs text-blue-400 mt-0.5" id="dashboard-date"></p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse inline-block"></span>
                <span class="text-xs text-blue-400 font-medium">Live — updates every 60s</span>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- ══════════════════════════════════════════════════════
             ROW 1 — KPI COUNT CARDS (3 + 3 responsive grid)
        ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            {{-- Active Accounts --}}
            <div
                class="bg-white rounded-2xl border border-blue-100 shadow-sm px-4 py-4 flex flex-col gap-2 hover:shadow-md transition">
                <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-4.5 0 2.625 2.625 0 014.5 0z" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium leading-tight">Active Accounts</p>
                <p class="text-2xl font-bold text-blue-900" id="kpi-accounts">—</p>
                <p class="text-xs text-green-500 font-semibold">+4 this month</p>
            </div>
            {{-- Active Bookings --}}
            <div
                class="bg-white rounded-2xl border border-blue-100 shadow-sm px-4 py-4 flex flex-col gap-2 hover:shadow-md transition">
                <div class="w-8 h-8 rounded-xl bg-orange-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium leading-tight">Active Bookings</p>
                <p class="text-2xl font-bold text-blue-900" id="kpi-active-bookings">—</p>
                <p class="text-xs text-orange-400 font-semibold">+12 this week</p>
            </div>
            {{-- Ongoing Transport --}}
            <div
                class="bg-white rounded-2xl border border-blue-100 shadow-sm px-4 py-4 flex flex-col gap-2 hover:shadow-md transition">
                <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium leading-tight">Ongoing Transport</p>
                <p class="text-2xl font-bold text-blue-900" id="kpi-ongoing">—</p>
                <p class="text-xs text-blue-400 font-semibold">In transit now</p>
            </div>
            {{-- Completed Transport --}}
            <div
                class="bg-white rounded-2xl border border-blue-100 shadow-sm px-4 py-4 flex flex-col gap-2 hover:shadow-md transition">
                <div class="w-8 h-8 rounded-xl bg-green-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium leading-tight">Completed Transport</p>
                <p class="text-2xl font-bold text-blue-900" id="kpi-completed-transport">—</p>
                <p class="text-xs text-green-500 font-semibold">All time</p>
            </div>
            {{-- Completed Bookings --}}
            <div
                class="bg-white rounded-2xl border border-blue-100 shadow-sm px-4 py-4 flex flex-col gap-2 hover:shadow-md transition">
                <div class="w-8 h-8 rounded-xl bg-green-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium leading-tight">Completed Bookings</p>
                <p class="text-2xl font-bold text-blue-900" id="kpi-completed-bookings">—</p>
                <p class="text-xs text-green-500 font-semibold">All time</p>
            </div>
            {{-- Pending Bookings --}}
            <div
                class="bg-white rounded-2xl border border-orange-100 shadow-sm px-4 py-4 flex flex-col gap-2 hover:shadow-md transition">
                <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium leading-tight">Pending Bookings</p>
                <p class="text-2xl font-bold text-amber-500" id="kpi-pending">—</p>
                <p class="text-xs text-amber-400 font-semibold">Awaiting action</p>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             ROW 2 — REVENUE SUMMARY BANNER
        ═══════════════════════════════════════════════════════════ --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-sm px-6 py-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-xs text-blue-200 font-semibold uppercase tracking-wider mb-1">Revenue Summary —
                        {{ now()->format('F Y') }}</p>
                    <div class="flex flex-wrap items-end gap-6">
                        <div>
                            <p class="text-xs text-blue-300">Total Billed</p>
                            <p class="text-3xl font-bold text-white" id="rev-billed">₱0</p>
                        </div>
                        <div class="w-px h-10 bg-blue-500 hidden sm:block"></div>
                        <div>
                            <p class="text-xs text-blue-300">Total Collected</p>
                            <p class="text-3xl font-bold text-green-300" id="rev-collected">₱0</p>
                        </div>
                        <div class="w-px h-10 bg-blue-500 hidden sm:block"></div>
                        <div>
                            <p class="text-xs text-blue-300">Outstanding</p>
                            <p class="text-3xl font-bold text-amber-300" id="rev-outstanding">₱0</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-1">
                    <p class="text-xs text-blue-300">Collection Rate</p>
                    <p class="text-4xl font-black text-white" id="rev-rate">—%</p>
                    <div class="w-32 h-2 bg-blue-700 rounded-full overflow-hidden">
                        <div id="rev-progress" class="h-2 bg-green-400 rounded-full transition-all duration-700"
                            style="width:0%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             ROW 3 — LINE GRAPHS (2-column grid, with toggle)
        ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Bookings Graph --}}
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-blue-50">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-orange-400 rounded-full"></span>
                        <div>
                            <h3 class="text-sm font-bold text-blue-900">Booking Trends</h3>
                            <p class="text-xs text-blue-400">Active, Completed, Pending</p>
                        </div>
                    </div>
                    <div class="flex bg-slate-100 rounded-xl p-0.5 gap-0.5" id="booking-range-toggle">
                        <button
                            class="chart-range-btn active-range px-3 py-1 text-xs font-semibold rounded-lg transition"
                            data-chart="bookingChart" data-range="7">7D</button>
                        <button
                            class="chart-range-btn px-3 py-1 text-xs font-semibold rounded-lg text-blue-400 hover:text-blue-600 transition"
                            data-chart="bookingChart" data-range="30">30D</button>
                        <button
                            class="chart-range-btn px-3 py-1 text-xs font-semibold rounded-lg text-blue-400 hover:text-blue-600 transition"
                            data-chart="bookingChart" data-range="365">1Y</button>
                    </div>
                </div>
                <div class="px-4 py-4">
                    <canvas id="bookingChart" height="220"></canvas>
                </div>
            </div>

            {{-- Transport Graph --}}
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-blue-50">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-blue-500 rounded-full"></span>
                        <div>
                            <h3 class="text-sm font-bold text-blue-900">Transport Trends</h3>
                            <p class="text-xs text-blue-400">Ongoing, Completed, Delayed</p>
                        </div>
                    </div>
                    <div class="flex bg-slate-100 rounded-xl p-0.5 gap-0.5" id="transport-range-toggle">
                        <button
                            class="chart-range-btn active-range px-3 py-1 text-xs font-semibold rounded-lg transition"
                            data-chart="transportChart" data-range="7">7D</button>
                        <button
                            class="chart-range-btn px-3 py-1 text-xs font-semibold rounded-lg text-blue-400 hover:text-blue-600 transition"
                            data-chart="transportChart" data-range="30">30D</button>
                        <button
                            class="chart-range-btn px-3 py-1 text-xs font-semibold rounded-lg text-blue-400 hover:text-blue-600 transition"
                            data-chart="transportChart" data-range="365">1Y</button>
                    </div>
                </div>
                <div class="px-4 py-4">
                    <canvas id="transportChart" height="220"></canvas>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             ROW 4 — LATEST BOOKINGS (full width)
        ═══════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
            <div
                class="flex items-center justify-between px-6 py-4 border-b border-blue-50 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-900">Latest Bookings</h3>
                        <p class="text-xs text-blue-400">Most recent 10 bookings</p>
                    </div>
                </div>
                <a href="#" class="text-xs text-orange-500 hover:text-orange-600 font-semibold transition">View
                    All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-blue-50 text-blue-500 text-xs uppercase tracking-wider">
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Booking #</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Shipper</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Consignee</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Route</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Mode</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Amount</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Payment</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Status</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Date</th>
                        </tr>
                    </thead>
                    <tbody id="bookings-tbody" class="divide-y divide-blue-50 text-blue-900">
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             ROW 5 — 3-COLUMN: Upcoming + Leaderboard + Activity
        ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Upcoming Pickups & Deliveries --}}
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
                <div
                    class="px-5 py-4 border-b border-blue-50 bg-gradient-to-r from-blue-50 to-white flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-900">Upcoming</h3>
                        <p class="text-xs text-blue-400">Pickups & Deliveries today</p>
                    </div>
                </div>
                <div id="upcoming-list" class="divide-y divide-blue-50 max-h-80 overflow-y-auto"></div>
            </div>

            {{-- Top Shippers Leaderboard --}}
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
                <div
                    class="px-5 py-4 border-b border-blue-50 bg-gradient-to-r from-orange-50 to-white flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.26 9.71 2 12 2c2.291 0 4.545.26 6.75.721v1.515M19.75 4.236c.982.143 1.954.317 2.916.52a6.003 6.003 0 01-5.395 5.492M19.75 4.236V4.5a9.005 9.005 0 01-2.48 5.228m2.48-5.228V2.721" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-900">Top Shippers</h3>
                        <p class="text-xs text-blue-400">By booking volume this month</p>
                    </div>
                </div>
                <div id="leaderboard-list" class="divide-y divide-blue-50 max-h-80 overflow-y-auto"></div>
            </div>

            {{-- Recent System Activity --}}
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
                <div
                    class="px-5 py-4 border-b border-blue-50 bg-gradient-to-r from-slate-50 to-white flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-900">Activity Log</h3>
                        <p class="text-xs text-blue-400">Recent system actions</p>
                    </div>
                </div>
                <div id="activity-list" class="divide-y divide-slate-50 max-h-80 overflow-y-auto"></div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             ROW 6 — INQUIRIES (full width)
        ═══════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
            <div
                class="flex items-center justify-between px-6 py-4 border-b border-blue-50 bg-gradient-to-r from-orange-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-900">Contact Inquiries</h3>
                        <p class="text-xs text-blue-400">Submitted via the Contact Us form</p>
                    </div>
                    <span id="inquiry-badge"
                        class="bg-orange-100 text-orange-600 text-xs font-bold px-2.5 py-0.5 rounded-full">0</span>
                </div>
                <a href="#" class="text-xs text-orange-500 hover:text-orange-600 font-semibold transition">View
                    All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-orange-50 text-orange-400 text-xs uppercase tracking-wider">
                            <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">#</th>
                            <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Name</th>
                            <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Email Address</th>
                            <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Contact Number</th>
                            <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Message</th>
                            <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Date Sent</th>
                            <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody id="inquiries-tbody" class="divide-y divide-orange-50 text-blue-900">
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- end max-w --}}
</div>{{-- end page --}}


{{-- ═══════════════════════════════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        'use strict';

        // ── Set dashboard date ───────────────────────────────────────────
        const dateEl = document.getElementById('dashboard-date');
        if (dateEl) {
            dateEl.textContent = new Date().toLocaleDateString('en-PH', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        // ════════════════════════════════════════════════════════
        // SAMPLE DATA
        // ════════════════════════════════════════════════════════

        const KPI = {
            activeAccounts: 142,
            activeBookings: 58,
            ongoingTransport: 34,
            completedTransport: 1204,
            completedBookings: 1389,
            pendingBookings: 23,
        };

        const REVENUE = {
            billed: 4_872_500,
            collected: 3_915_000,
        };

        // Chart data generators
        function randomBetween(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        function generateLabels(range) {
            const labels = [];
            const now = new Date();
            if (range === 7 || range === 30) {
                for (let i = range - 1; i >= 0; i--) {
                    const d = new Date(now);
                    d.setDate(now.getDate() - i);
                    labels.push(d.toLocaleDateString('en-PH', {
                        month: 'short',
                        day: 'numeric'
                    }));
                }
            } else {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                for (let i = 11; i >= 0; i--) {
                    const d = new Date(now);
                    d.setMonth(now.getMonth() - i);
                    labels.push(months[d.getMonth()]);
                }
            }
            return labels;
        }

        function generateDataset(range, min, max) {
            return Array.from({
                length: range === 365 ? 12 : range
            }, () => randomBetween(min, max));
        }

        const CHART_DATA = {
            booking: {
                7: {
                    active: generateDataset(7, 5, 25),
                    completed: generateDataset(7, 10, 40),
                    pending: generateDataset(7, 2, 15)
                },
                30: {
                    active: generateDataset(30, 5, 30),
                    completed: generateDataset(30, 10, 50),
                    pending: generateDataset(30, 1, 20)
                },
                365: {
                    active: generateDataset(365, 20, 80),
                    completed: generateDataset(365, 40, 120),
                    pending: generateDataset(365, 5, 30)
                },
            },
            transport: {
                7: {
                    ongoing: generateDataset(7, 3, 20),
                    completed: generateDataset(7, 5, 35),
                    delayed: generateDataset(7, 0, 8)
                },
                30: {
                    ongoing: generateDataset(30, 3, 25),
                    completed: generateDataset(30, 8, 45),
                    delayed: generateDataset(30, 0, 12)
                },
                365: {
                    ongoing: generateDataset(365, 10, 60),
                    completed: generateDataset(365, 30, 100),
                    delayed: generateDataset(365, 2, 20)
                },
            },
        };

        const BOOKINGS = [{
                id: 'BK-2025-0001',
                shipper: 'Layag Freight Solutions',
                consignee: 'Daloy Trading Co.',
                route: 'Manila → Cebu',
                mode: 'Sea Freight',
                amount: 45000,
                payment: 'On-Account',
                status: 'completed',
                date: '2025-03-18'
            },
            {
                id: 'BK-2025-0002',
                shipper: 'Agos Manufacturing Inc.',
                consignee: 'Bukid Agritech Corp.',
                route: 'Davao → Manila',
                mode: 'Air Freight',
                amount: 92000,
                payment: 'Prepaid',
                status: 'ongoing',
                date: '2025-03-19'
            },
            {
                id: 'BK-2025-0003',
                shipper: 'Sigla Logistics PH',
                consignee: 'Kulay Retail Group',
                route: 'Manila → Davao',
                mode: 'Land',
                amount: 28500,
                payment: 'On-Account',
                status: 'pending',
                date: '2025-03-20'
            },
            {
                id: 'BK-2025-0004',
                shipper: 'Daloy Trading Co.',
                consignee: 'Layag Freight Solutions',
                route: 'Iloilo → Manila',
                mode: 'Sea Freight',
                amount: 61000,
                payment: 'COD',
                status: 'active',
                date: '2025-03-20'
            },
            {
                id: 'BK-2025-0005',
                shipper: 'Kulay Retail Group',
                consignee: 'Agos Manufacturing',
                route: 'Manila → Iloilo',
                mode: 'Land',
                amount: 19500,
                payment: 'Prepaid',
                status: 'completed',
                date: '2025-03-21'
            },
            {
                id: 'BK-2025-0006',
                shipper: 'Bukid Agritech Corp.',
                consignee: 'Sigla Logistics PH',
                route: 'Cagayan → Cebu',
                mode: 'Sea Freight',
                amount: 33000,
                payment: 'On-Account',
                status: 'pending',
                date: '2025-03-21'
            },
            {
                id: 'BK-2025-0007',
                shipper: 'Layag Freight Solutions',
                consignee: 'Kulay Retail Group',
                route: 'Cebu → Davao',
                mode: 'Air Freight',
                amount: 88000,
                payment: 'Prepaid',
                status: 'active',
                date: '2025-03-22'
            },
            {
                id: 'BK-2025-0008',
                shipper: 'Sigla Logistics PH',
                consignee: 'Daloy Trading Co.',
                route: 'Manila → Zamboanga',
                mode: 'Sea Freight',
                amount: 52000,
                payment: 'On-Account',
                status: 'ongoing',
                date: '2025-03-22'
            },
            {
                id: 'BK-2025-0009',
                shipper: 'Agos Manufacturing Inc.',
                consignee: 'Bukid Agritech Corp.',
                route: 'Davao → Cebu',
                mode: 'Land',
                amount: 24000,
                payment: 'COD',
                status: 'pending',
                date: '2025-03-23'
            },
            {
                id: 'BK-2025-0010',
                shipper: 'Kulay Retail Group',
                consignee: 'Layag Freight Solutions',
                route: 'Cebu → Manila',
                mode: 'Sea Freight',
                amount: 71500,
                payment: 'Prepaid',
                status: 'active',
                date: '2025-03-24'
            },
        ];

        const UPCOMING = [{
                type: 'pickup',
                company: 'Layag Freight Solutions',
                location: 'Pasay City, Manila',
                time: '08:00 AM'
            },
            {
                type: 'delivery',
                company: 'Daloy Trading Co.',
                location: 'Lapu-Lapu, Cebu',
                time: '10:30 AM'
            },
            {
                type: 'pickup',
                company: 'Sigla Logistics PH',
                location: 'Davao City',
                time: '01:00 PM'
            },
            {
                type: 'delivery',
                company: 'Agos Manufacturing Inc.',
                location: 'Iloilo City',
                time: '02:45 PM'
            },
            {
                type: 'delivery',
                company: 'Bukid Agritech Corp.',
                location: 'Zamboanga City',
                time: '04:15 PM'
            },
        ];

        const LEADERBOARD = [{
                rank: 1,
                name: 'Layag Freight Solutions',
                bookings: 84,
                growth: '+12%'
            },
            {
                rank: 2,
                name: 'Agos Manufacturing Inc.',
                bookings: 67,
                growth: '+8%'
            },
            {
                rank: 3,
                name: 'Sigla Logistics PH',
                bookings: 53,
                growth: '+5%'
            },
            {
                rank: 4,
                name: 'Kulay Retail Group',
                bookings: 41,
                growth: '-2%'
            },
            {
                rank: 5,
                name: 'Bukid Agritech Corp.',
                bookings: 38,
                growth: '+3%'
            },
        ];

        const ACTIVITY = [{
                action: 'New booking created',
                user: 'jdelacruz',
                time: '5m ago',
                color: 'blue'
            },
            {
                action: 'Shipper account updated',
                user: 'msantos',
                time: '18m ago',
                color: 'orange'
            },
            {
                action: 'Invoice #INV-0482 sent',
                user: 'preyes',
                time: '34m ago',
                color: 'green'
            },
            {
                action: 'Transport marked complete',
                user: 'jdelacruz',
                time: '1h ago',
                color: 'green'
            },
            {
                action: 'Inquiry #INQ-091 received',
                user: 'system',
                time: '2h ago',
                color: 'orange'
            },
            {
                action: 'LOV "Industry" updated',
                user: 'admin',
                time: '3h ago',
                color: 'slate'
            },
            {
                action: 'New account registered',
                user: 'msantos',
                time: '4h ago',
                color: 'blue'
            },
        ];

        const INQUIRIES = [{
                id: 'INQ-091',
                name: 'Anna Reyes',
                email: 'anna.reyes@email.com',
                contact: '+63 917 111 2222',
                message: 'I would like to inquire about your sea freight rates from Manila to Cebu.',
                date: '2025-03-24',
                status: 'unread'
            },
            {
                id: 'INQ-090',
                name: 'Carlo Bautista',
                email: 'cbautista@biz.ph',
                contact: '+63 918 333 4444',
                message: 'Can you provide a quotation for 5-ton land transport from Davao to Manila?',
                date: '2025-03-23',
                status: 'read'
            },
            {
                id: 'INQ-089',
                name: 'Maria Lim',
                email: 'mlim@corp.com',
                contact: '+63 920 555 6666',
                message: 'Interested in opening a corporate account. Please send requirements.',
                date: '2025-03-22',
                status: 'replied'
            },
            {
                id: 'INQ-088',
                name: 'Ramon Torres',
                email: 'rtorres@trading.ph',
                contact: '+63 915 777 8888',
                message: 'What is the transit time for air freight from Manila to Zamboanga?',
                date: '2025-03-21',
                status: 'read'
            },
            {
                id: 'INQ-087',
                name: 'Luz Garcia',
                email: 'lgarcia@agri.ph',
                contact: '+63 912 999 0000',
                message: 'We need weekly scheduled pickups in Cagayan. Who should I talk to?',
                date: '2025-03-20',
                status: 'replied'
            },
        ];

        // ════════════════════════════════════════════════════════
        // RENDER KPI CARDS
        // ════════════════════════════════════════════════════════
        document.getElementById('kpi-accounts').textContent = KPI.activeAccounts.toLocaleString();
        document.getElementById('kpi-active-bookings').textContent = KPI.activeBookings.toLocaleString();
        document.getElementById('kpi-ongoing').textContent = KPI.ongoingTransport.toLocaleString();
        document.getElementById('kpi-completed-transport').textContent = KPI.completedTransport.toLocaleString();
        document.getElementById('kpi-completed-bookings').textContent = KPI.completedBookings.toLocaleString();
        document.getElementById('kpi-pending').textContent = KPI.pendingBookings.toLocaleString();

        // ════════════════════════════════════════════════════════
        // RENDER REVENUE
        // ════════════════════════════════════════════════════════
        const fmt = (n) => '₱' + n.toLocaleString('en-PH');
        const outstanding = REVENUE.billed - REVENUE.collected;
        const rate = Math.round((REVENUE.collected / REVENUE.billed) * 100);
        document.getElementById('rev-billed').textContent = fmt(REVENUE.billed);
        document.getElementById('rev-collected').textContent = fmt(REVENUE.collected);
        document.getElementById('rev-outstanding').textContent = fmt(outstanding);
        document.getElementById('rev-rate').textContent = rate + '%';
        setTimeout(() => {
            document.getElementById('rev-progress').style.width = rate + '%';
        }, 300);

        // ════════════════════════════════════════════════════════
        // RENDER CHARTS
        // ════════════════════════════════════════════════════════
        const chartDefaults = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        font: {
                            size: 11
                        },
                        padding: 16
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: '#f1f5f9'
                    },
                    ticks: {
                        font: {
                            size: 10
                        },
                        color: '#94a3b8',
                        maxTicksLimit: 8
                    }
                },
                y: {
                    grid: {
                        color: '#f1f5f9'
                    },
                    ticks: {
                        font: {
                            size: 10
                        },
                        color: '#94a3b8'
                    },
                    beginAtZero: true
                },
            },
            elements: {
                line: {
                    tension: 0.4
                },
                point: {
                    radius: 3,
                    hoverRadius: 5
                }
            },
        };

        function buildBookingDatasets(range) {
            const d = CHART_DATA.booking[range];
            return [{
                    label: 'Active',
                    data: d.active,
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249,115,22,0.08)',
                    fill: true,
                    borderWidth: 2
                },
                {
                    label: 'Completed',
                    data: d.completed,
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34,197,94,0.07)',
                    fill: true,
                    borderWidth: 2
                },
                {
                    label: 'Pending',
                    data: d.pending,
                    borderColor: '#fbbf24',
                    backgroundColor: 'rgba(251,191,36,0.07)',
                    fill: true,
                    borderWidth: 2
                },
            ];
        }

        function buildTransportDatasets(range) {
            const d = CHART_DATA.transport[range];
            return [{
                    label: 'Ongoing',
                    data: d.ongoing,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.08)',
                    fill: true,
                    borderWidth: 2
                },
                {
                    label: 'Completed',
                    data: d.completed,
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34,197,94,0.07)',
                    fill: true,
                    borderWidth: 2
                },
                {
                    label: 'Delayed',
                    data: d.delayed,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239,68,68,0.06)',
                    fill: true,
                    borderWidth: 2
                },
            ];
        }

        let bookingChart, transportChart;
        let currentBookingRange = 7,
            currentTransportRange = 7;

        function initCharts() {
            const bCtx = document.getElementById('bookingChart').getContext('2d');
            bookingChart = new Chart(bCtx, {
                type: 'line',
                data: {
                    labels: generateLabels(7),
                    datasets: buildBookingDatasets(7)
                },
                options: JSON.parse(JSON.stringify(chartDefaults)),
            });

            const tCtx = document.getElementById('transportChart').getContext('2d');
            transportChart = new Chart(tCtx, {
                type: 'line',
                data: {
                    labels: generateLabels(7),
                    datasets: buildTransportDatasets(7)
                },
                options: JSON.parse(JSON.stringify(chartDefaults)),
            });
        }

        function updateChart(chartObj, range, buildFn) {
            const r = parseInt(range);
            chartObj.data.labels = generateLabels(r);
            chartObj.data.datasets = buildFn(r);
            chartObj.update();
        }

        // ── Range toggle buttons ─────────────────────────────────────────
        document.querySelectorAll('.chart-range-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const chartId = this.dataset.chart;
                const range = this.dataset.range;
                const siblings = this.parentElement.querySelectorAll('.chart-range-btn');
                siblings.forEach(b => {
                    b.classList.remove('active-range', 'bg-white', 'text-blue-700',
                        'shadow-sm');
                    b.classList.add('text-blue-400');
                });
                this.classList.add('active-range', 'bg-white', 'text-blue-700', 'shadow-sm');
                this.classList.remove('text-blue-400');

                if (chartId === 'bookingChart') {
                    updateChart(bookingChart, range, buildBookingDatasets);
                    console.log('[Dashboard] Booking chart range:', range);
                } else {
                    updateChart(transportChart, range, buildTransportDatasets);
                    console.log('[Dashboard] Transport chart range:', range);
                }
            });
        });

        // style default active button
        document.querySelectorAll('.active-range').forEach(btn => {
            btn.classList.add('bg-white', 'text-blue-700', 'shadow-sm');
        });

        // ════════════════════════════════════════════════════════
        // RENDER LATEST BOOKINGS TABLE
        // ════════════════════════════════════════════════════════
        const BOOKING_STATUS_CLASSES = {
            active: 'bg-blue-100 text-blue-700',
            ongoing: 'bg-purple-100 text-purple-700',
            completed: 'bg-green-100 text-green-700',
            pending: 'bg-amber-100 text-amber-600',
        };

        function renderBookings() {
            const tbody = document.getElementById('bookings-tbody');
            tbody.innerHTML = '';
            BOOKINGS.forEach(b => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-orange-50 transition-colors duration-100 cursor-pointer';
                tr.innerHTML = `
                <td class="px-4 py-3 whitespace-nowrap font-mono text-xs font-semibold text-blue-400">${b.id}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-blue-900">${b.shipper}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-blue-600">${b.consignee}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-blue-500">${b.route}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-blue-600">${b.mode}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-blue-900">₱${b.amount.toLocaleString()}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-blue-500">${b.payment}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full capitalize ${BOOKING_STATUS_CLASSES[b.status] || 'bg-slate-100 text-slate-500'}">${b.status}</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-xs text-blue-400">${b.date}</td>
            `;
                tr.addEventListener('click', () => console.log('[Dashboard] Booking row clicked, ID:', b
                    .id));
                tbody.appendChild(tr);
            });
        }

        // ════════════════════════════════════════════════════════
        // RENDER UPCOMING PICKUPS & DELIVERIES
        // ════════════════════════════════════════════════════════
        function renderUpcoming() {
            const container = document.getElementById('upcoming-list');
            container.innerHTML = '';
            UPCOMING.forEach(u => {
                const isPickup = u.type === 'pickup';
                const div = document.createElement('div');
                div.className =
                    'flex items-start gap-3 px-5 py-3 hover:bg-blue-50 transition cursor-pointer';
                div.innerHTML = `
                <div class="w-7 h-7 rounded-lg ${isPickup ? 'bg-orange-100' : 'bg-blue-100'} flex items-center justify-center shrink-0 mt-0.5">
                    ${isPickup
                        ? `<svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>`
                        : `<svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>`
                    }
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-blue-900 truncate">${u.company}</p>
                    <p class="text-xs text-blue-400 truncate">${u.location}</p>
                </div>
                <div class="shrink-0 text-right">
                    <p class="text-xs font-bold ${isPickup ? 'text-orange-500' : 'text-blue-500'}">${u.time}</p>
                    <p class="text-xs text-blue-300 capitalize">${u.type}</p>
                </div>
            `;
                div.addEventListener('click', () => console.log('[Dashboard] Upcoming item clicked:', u));
                container.appendChild(div);
            });
        }

        // ════════════════════════════════════════════════════════
        // RENDER LEADERBOARD
        // ════════════════════════════════════════════════════════
        const RANK_COLORS = ['bg-amber-400', 'bg-slate-400', 'bg-orange-700', 'bg-blue-200', 'bg-blue-200'];

        function renderLeaderboard() {
            const container = document.getElementById('leaderboard-list');
            container.innerHTML = '';
            LEADERBOARD.forEach((l, i) => {
                const div = document.createElement('div');
                div.className =
                    'flex items-center gap-3 px-5 py-3 hover:bg-orange-50 transition cursor-pointer';
                div.innerHTML = `
                <span class="w-6 h-6 rounded-full ${RANK_COLORS[i]} text-white text-xs font-black flex items-center justify-center shrink-0">${l.rank}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-blue-900 truncate">${l.name}</p>
                    <p class="text-xs text-blue-400">${l.bookings} bookings</p>
                </div>
                <span class="text-xs font-bold ${l.growth.startsWith('+') ? 'text-green-500' : 'text-red-400'}">${l.growth}</span>
            `;
                div.addEventListener('click', () => console.log('[Dashboard] Leaderboard row clicked, ID:',
                    l.rank, l.name));
                container.appendChild(div);
            });
        }

        // ════════════════════════════════════════════════════════
        // RENDER ACTIVITY LOG
        // ════════════════════════════════════════════════════════
        const ACTIVITY_DOT = {
            blue: 'bg-blue-400',
            orange: 'bg-orange-400',
            green: 'bg-green-400',
            slate: 'bg-slate-300'
        };

        function renderActivity() {
            const container = document.getElementById('activity-list');
            container.innerHTML = '';
            ACTIVITY.forEach(a => {
                const div = document.createElement('div');
                div.className = 'flex items-start gap-3 px-5 py-3 hover:bg-slate-50 transition';
                div.innerHTML = `
                <span class="w-2 h-2 rounded-full ${ACTIVITY_DOT[a.color] || 'bg-slate-300'} mt-1.5 shrink-0"></span>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-blue-900 truncate">${a.action}</p>
                    <p class="text-xs text-blue-400">by <span class="font-mono">${a.user}</span></p>
                </div>
                <p class="text-xs text-blue-300 whitespace-nowrap shrink-0">${a.time}</p>
            `;
                container.appendChild(div);
            });
        }

        // ════════════════════════════════════════════════════════
        // RENDER INQUIRIES TABLE
        // ════════════════════════════════════════════════════════
        const INQ_STATUS = {
            unread: 'bg-orange-100 text-orange-600',
            read: 'bg-blue-100 text-blue-600',
            replied: 'bg-green-100 text-green-700',
        };

        function renderInquiries() {
            const tbody = document.getElementById('inquiries-tbody');
            const badge = document.getElementById('inquiry-badge');
            tbody.innerHTML = '';
            badge.textContent = INQUIRIES.length;
            INQUIRIES.forEach(inq => {
                const tr = document.createElement('tr');
                const isUnread = inq.status === 'unread';
                tr.className =
                    `hover:bg-orange-50 transition-colors duration-100 cursor-pointer ${isUnread ? 'font-semibold' : ''}`;
                tr.innerHTML = `
                <td class="px-5 py-3 whitespace-nowrap font-mono text-xs text-blue-400 font-semibold">${inq.id}</td>
                <td class="px-5 py-3 whitespace-nowrap text-sm text-blue-900">${inq.name}</td>
                <td class="px-5 py-3 whitespace-nowrap text-sm text-blue-500">${inq.email}</td>
                <td class="px-5 py-3 whitespace-nowrap text-sm text-blue-500">${inq.contact}</td>
                <td class="px-5 py-3 text-sm text-blue-500 max-w-xs truncate">${inq.message}</td>
                <td class="px-5 py-3 whitespace-nowrap text-xs text-blue-400">${inq.date}</td>
                <td class="px-5 py-3 whitespace-nowrap">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full capitalize ${INQ_STATUS[inq.status] || 'bg-slate-100 text-slate-500'}">${inq.status}</span>
                </td>
            `;
                tr.addEventListener('click', () => console.log('[Dashboard] Inquiry row clicked, ID:', inq
                    .id));
                tbody.appendChild(tr);
            });
        }

        // ════════════════════════════════════════════════════════
        // BOOT
        // ════════════════════════════════════════════════════════
        function boot() {
            renderBookings();
            renderUpcoming();
            renderLeaderboard();
            renderActivity();
            renderInquiries();
            if (typeof Chart !== 'undefined') {
                initCharts();
            } else {
                console.warn('[Dashboard] Chart.js not loaded — graphs skipped.');
            }
            console.log('[Dashboard] Initialized successfully.');
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }

    })();
</script>
