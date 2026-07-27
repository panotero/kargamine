{{-- Dashboard --}}
{{-- Real data pulled from /api/dashboard/summary + /api/notifications - team-scoped the
     same way as CRM/Proposals (a member sees their own book of business, a team leader
     sees their subtree, superadmin sees everything). --}}

<div id="dashboard-page" class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Dashboard</h1>
            <p class="text-xs text-zinc-400" id="dashboard-date"></p>
        </div>
    </div>

    {{-- KPI cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm dark:shadow-black/40 px-4 py-4 flex flex-col gap-2">
            <div class="w-8 h-8 rounded-xl bg-orange-50 dark:bg-orange-950/40 flex items-center justify-center">
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.456-2.456L14.25 6l1.035-.259a3.375 3.375 0 002.456-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" />
                </svg>
            </div>
            <p class="text-xs text-zinc-400 font-medium leading-tight">Open Leads</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white" id="kpi-open-leads">—</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm dark:shadow-black/40 px-4 py-4 flex flex-col gap-2">
            <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <p class="text-xs text-zinc-400 font-medium leading-tight">Pending Proposals</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white" id="kpi-pending-proposals">—</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm dark:shadow-black/40 px-4 py-4 flex flex-col gap-2">
            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-xs text-zinc-400 font-medium leading-tight">Active Contracts</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white" id="kpi-active-contracts">—</p>
            <p class="text-xs text-amber-500 font-semibold hidden" id="kpi-expiring-note"></p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm dark:shadow-black/40 px-4 py-4 flex flex-col gap-2">
            <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-4.5 0 2.625 2.625 0 014.5 0z" />
                </svg>
            </div>
            <p class="text-xs text-zinc-400 font-medium leading-tight">Total Clients</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white" id="kpi-total-clients">—</p>
        </div>
    </div>

    {{-- Lead trend chart + Recent notifications --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm dark:shadow-black/40 overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Leads Created</h3>
                <p class="text-xs text-zinc-400">Last 14 days</p>
            </div>
            <div class="px-4 py-4">
                <canvas id="leadTrendChart" height="200"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm dark:shadow-black/40 overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Recent Notifications</h3>
                <p class="text-xs text-zinc-400">Latest activity for you</p>
            </div>
            <div id="dash-notifications" class="divide-y divide-zinc-100 dark:divide-zinc-800 max-h-72 overflow-y-auto flex-1"></div>
        </div>
    </div>

    {{-- Pipeline breakdowns --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm dark:shadow-black/40 overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">CRM Pipeline</h3>
                <p class="text-xs text-zinc-400">Leads by stage</p>
            </div>
            <div id="dash-leads-by-status" class="p-5 space-y-2.5"></div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm dark:shadow-black/40 overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Proposals</h3>
                <p class="text-xs text-zinc-400">By status</p>
            </div>
            <div id="dash-proposals-by-status" class="p-5 space-y-2.5"></div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm dark:shadow-black/40 overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Contracts</h3>
                <p class="text-xs text-zinc-400">By status</p>
            </div>
            <div id="dash-contracts-by-status" class="p-5 space-y-2.5"></div>
        </div>
    </div>

    {{-- Top clients + Container inventory --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm dark:shadow-black/40 overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Top Clients</h3>
                <p class="text-xs text-zinc-400">By number of contracts</p>
            </div>
            <div id="dash-top-clients" class="divide-y divide-zinc-100 dark:divide-zinc-800"></div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm dark:shadow-black/40 overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Container Inventory</h3>
                <p class="text-xs text-zinc-400">Fleet status snapshot</p>
            </div>
            <div id="dash-containers-by-status" class="p-5 space-y-2.5"></div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        'use strict';

        const dateEl = document.getElementById('dashboard-date');
        if (dateEl) {
            dateEl.textContent = new Date().toLocaleDateString('en-PH', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        const STATUS_COLORS = {
            leads: {
                LEAD: '#a1a1aa',
                QUALIFIED: '#3b82f6',
                OPPORTUNITY: '#f97316',
                NEGOTIATION: '#a855f7',
                WIN: '#22c55e',
                LOST: '#ef4444',
            },
            proposals: {
                Pending: '#f59e0b',
                Approved: '#22c55e',
                Disapproved: '#ef4444',
                Accepted: '#3b82f6',
                Rejected: '#a1a1aa',
            },
            contracts: {
                Draft: '#a1a1aa',
                Active: '#22c55e',
                Expired: '#f59e0b',
                Terminated: '#ef4444',
            },
            containers: {
                Available: '#22c55e',
                Booked: '#3b82f6',
                'In Transit': '#f97316',
                'Under Repair': '#f59e0b',
                Damaged: '#ef4444',
                'Out of Service': '#a1a1aa',
            },
        };

        function renderStatusBreakdown(containerId, counts, colorMap) {
            const container = document.getElementById(containerId);
            const total = Object.values(counts).reduce((a, b) => a + b, 0);

            container.innerHTML = Object.entries(counts).map(([label, count]) => {
                const pct = total ? Math.round((count / total) * 100) : 0;
                const color = colorMap[label] || '#a1a1aa';
                return `
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-zinc-500 dark:text-zinc-400">${label}</span>
                            <span class="font-semibold text-zinc-700 dark:text-zinc-200">${count}</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                            <div class="h-1.5 rounded-full transition-all duration-500" style="width:${pct}%;background:${color}"></div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function renderTopClients(clients) {
            const container = document.getElementById('dash-top-clients');

            if (!clients.length) {
                container.innerHTML = `<p class="text-sm text-zinc-400 text-center py-6">No clients yet.</p>`;
                return;
            }

            container.innerHTML = clients.map((c, i) => `
                <div class="dash-client-row flex items-center gap-3 px-5 py-3 cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800 transition" data-uuid="${c.uuid}">
                    <span class="w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-950/40 text-orange-600 dark:text-orange-400 text-xs font-bold flex items-center justify-center shrink-0">${i + 1}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 truncate">${c.company_name ?? '-'}</p>
                        <p class="text-xs text-zinc-400">${c.customer_code ?? '-'}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs font-semibold text-zinc-700 dark:text-zinc-200">${c.contracts_count} contract${c.contracts_count === 1 ? '' : 's'}</p>
                        <p class="text-xs text-zinc-400">${c.proposals_count} proposal${c.proposals_count === 1 ? '' : 's'}</p>
                    </div>
                </div>
            `).join('');

            container.querySelectorAll('.dash-client-row').forEach((row) => {
                row.addEventListener('click', async function() {
                    const uuid = this.dataset.uuid;
                    await window.loadPage({
                        title: 'Clients',
                        link: '/page_clientMasters'
                    });
                    if (typeof window.openClientDetailModal === 'function') {
                        window.openClientDetailModal(uuid);
                    }
                });
            });
        }

        let leadTrendChart;

        function renderLeadTrendChart(trend) {
            const ctx = document.getElementById('leadTrendChart').getContext('2d');
            const labels = trend.map((d) => new Date(d.date).toLocaleDateString('en-PH', {
                month: 'short',
                day: 'numeric'
            }));

            if (leadTrendChart) leadTrendChart.destroy();

            leadTrendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Leads',
                        data: trend.map((d) => d.count),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        fill: true,
                        borderWidth: 2,
                        tension: 0.35,
                        pointRadius: 2,
                        pointHoverRadius: 5,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(161,161,170,0.15)'
                            },
                            ticks: {
                                font: {
                                    size: 10
                                },
                                color: '#a1a1aa',
                                maxTicksLimit: 8
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(161,161,170,0.15)'
                            },
                            ticks: {
                                font: {
                                    size: 10
                                },
                                color: '#a1a1aa',
                                precision: 0
                            },
                            beginAtZero: true
                        },
                    },
                },
            });
        }

        async function loadSummary() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/dashboard/summary'
            });

            if (!response.success) return;

            const d = response.data;

            document.getElementById('kpi-open-leads').textContent = d.kpi.open_leads.toLocaleString();
            document.getElementById('kpi-pending-proposals').textContent = d.kpi.pending_proposals.toLocaleString();
            document.getElementById('kpi-active-contracts').textContent = d.kpi.active_contracts.toLocaleString();
            document.getElementById('kpi-total-clients').textContent = d.kpi.total_clients.toLocaleString();

            if (d.contracts_expiring_soon > 0) {
                const note = document.getElementById('kpi-expiring-note');
                note.textContent = `${d.contracts_expiring_soon} expiring within 30 days`;
                note.classList.remove('hidden');
            }

            renderStatusBreakdown('dash-leads-by-status', d.leads_by_status, STATUS_COLORS.leads);
            renderStatusBreakdown('dash-proposals-by-status', d.proposals_by_status, STATUS_COLORS.proposals);
            renderStatusBreakdown('dash-contracts-by-status', d.contracts_by_status, STATUS_COLORS.contracts);
            renderStatusBreakdown('dash-containers-by-status', d.containers_by_status, STATUS_COLORS.containers);

            renderTopClients(d.top_clients);

            if (typeof Chart !== 'undefined') {
                renderLeadTrendChart(d.lead_trend);
            }
        }

        function formatNotifTime(isoString) {
            const date = new Date(isoString);
            return date.toLocaleString('en-PH', {
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        async function loadNotifications() {
            const container = document.getElementById('dash-notifications');
            const response = await apiCall({
                mode: 'GET',
                url: '/api/notifications'
            });

            if (!response || response.success === false || !response.data?.length) {
                container.innerHTML = `<p class="text-sm text-zinc-400 text-center py-6">No notifications yet.</p>`;
                return;
            }

            container.innerHTML = response.data.slice(0, 6).map((n) => `
                <div class="dash-notif-row flex items-start gap-3 px-5 py-3 cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800 transition" data-id="${n.id}">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">${n.title ?? ''}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate">${n.message ?? ''}</p>
                        <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">${formatNotifTime(n.created_at)}</p>
                    </div>
                    ${n.is_read ? '' : '<span class="mt-1.5 h-2 w-2 rounded-full bg-red-500 shrink-0" title="Unread"></span>'}
                </div>
            `).join('');

            container.querySelectorAll('.dash-notif-row').forEach((row) => {
                row.addEventListener('click', async function() {
                    const n = response.data.find((x) => String(x.id) === this.dataset.id);
                    if (!n) return;

                    await apiCall({
                        mode: 'POST',
                        isJson: true,
                        payload: {
                            ids: [n.id]
                        },
                        url: '/api/notifications/mark-read'
                    });

                    if (n.link_url && typeof window.loadPage === 'function') {
                        await window.loadPage({
                            title: n.link_title || '',
                            link: n.link_url
                        });
                    }

                    const modalFn = n.data?.modal_fn;
                    if (modalFn && typeof window[modalFn] === 'function') {
                        window[modalFn](...(n.data.modal_args || []));
                    }
                });
            });
        }

        function boot() {
            loadSummary();
            loadNotifications();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }
    })();
</script>
