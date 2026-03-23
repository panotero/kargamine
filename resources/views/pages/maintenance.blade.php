{{-- Application Maintenance — List of Values (LOV) Configuration --}}
{{-- Blade SPA Page | Laravel 10 | Tailwind CSS | DataTables --}}

<div id="app-maintenance-page" class="min-h-screen font-sans px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-screen-xl mx-auto space-y-6">

        {{-- ═══════════════════════════════════════════════════════════
             ROW 1 — FULL WIDTH: Industry
        ════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
            {{-- Section Header --}}
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-blue-50 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-blue-900">Industry</h2>
                        <p class="text-xs text-blue-400">Manage industry classification values</p>
                    </div>
                    <span id="industry-count-badge"
                        class="ml-1 bg-blue-100 text-blue-600 text-xs font-bold px-2.5 py-0.5 rounded-full">6</span>
                </div>
                <button data-modal="industry-modal"
                    class="btn-open-lov-modal inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm transition w-fit">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Industry
                </button>
            </div>
            {{-- Table --}}
            <div class="overflow-x-auto">
                <table id="industry-table" class="min-w-full text-sm lov-table">
                    <thead>
                        <tr class="bg-blue-50 text-blue-500 text-xs uppercase tracking-wider">
                            <th class="px-5 py-3 text-left font-semibold w-28">Code</th>
                            <th class="px-5 py-3 text-left font-semibold">Name / Label</th>
                            <th class="px-5 py-3 text-left font-semibold">Description</th>
                            <th class="px-5 py-3 text-left font-semibold w-28">Status</th>
                            <th class="px-5 py-3 text-center font-semibold w-20">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50 text-blue-900" id="industry-tbody">
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════
             ROW 2 — 2-COLUMN GRID: Type of Organization + Payment Mode
        ════════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Type of Organization --}}
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-blue-50 bg-gradient-to-r from-orange-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-blue-900">Type of Organization</h2>
                            <p class="text-xs text-blue-400">Organization structure values</p>
                        </div>
                        <span id="typeorg-count-badge"
                            class="ml-1 bg-orange-100 text-orange-600 text-xs font-bold px-2.5 py-0.5 rounded-full">6</span>
                    </div>
                    <button data-modal="typeorg-modal"
                        class="btn-open-lov-modal inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm transition w-fit">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Type
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table id="typeorg-table" class="min-w-full text-sm lov-table">
                        <thead>
                            <tr class="bg-orange-50 text-orange-400 text-xs uppercase tracking-wider">
                                <th class="px-4 py-3 text-left font-semibold w-24">Code</th>
                                <th class="px-4 py-3 text-left font-semibold">Name / Label</th>
                                <th class="px-4 py-3 text-left font-semibold">Description</th>
                                <th class="px-4 py-3 text-left font-semibold w-24">Status</th>
                                <th class="px-4 py-3 text-center font-semibold w-16">Del</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-orange-50 text-blue-900" id="typeorg-tbody">
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Payment Mode --}}
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-blue-50 bg-gradient-to-r from-blue-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-blue-900">Payment Mode</h2>
                            <p class="text-xs text-blue-400">Available payment method types</p>
                        </div>
                        <span id="payment-count-badge"
                            class="ml-1 bg-blue-100 text-blue-600 text-xs font-bold px-2.5 py-0.5 rounded-full">3</span>
                    </div>
                    <button data-modal="payment-modal"
                        class="btn-open-lov-modal inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm transition w-fit">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Mode
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table id="payment-table" class="min-w-full text-sm lov-table">
                        <thead>
                            <tr class="bg-blue-50 text-blue-500 text-xs uppercase tracking-wider">
                                <th class="px-4 py-3 text-left font-semibold w-24">Code</th>
                                <th class="px-4 py-3 text-left font-semibold">Name / Label</th>
                                <th class="px-4 py-3 text-left font-semibold">Description</th>
                                <th class="px-4 py-3 text-left font-semibold w-24">Status</th>
                                <th class="px-4 py-3 text-center font-semibold w-16">Del</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50 text-blue-900" id="payment-tbody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════
             ROW 3 — FULL WIDTH: Customer Type
        ════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-blue-50 bg-gradient-to-r from-orange-50 via-white to-blue-50">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange-100 to-blue-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-blue-900">Customer Type</h2>
                        <p class="text-xs text-blue-400">Classify customers by their role in the shipping process</p>
                    </div>
                    <span id="custtype-count-badge"
                        class="ml-1 bg-blue-100 text-blue-600 text-xs font-bold px-2.5 py-0.5 rounded-full">3</span>
                </div>
                <button data-modal="custtype-modal"
                    class="btn-open-lov-modal inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm transition w-fit">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Customer Type
                </button>
            </div>
            <div class="overflow-x-auto">
                <table id="custtype-table" class="min-w-full text-sm lov-table">
                    <thead>
                        <tr
                            class="bg-gradient-to-r from-orange-50 to-blue-50 text-blue-500 text-xs uppercase tracking-wider">
                            <th class="px-5 py-3 text-left font-semibold w-28">Code</th>
                            <th class="px-5 py-3 text-left font-semibold">Name / Label</th>
                            <th class="px-5 py-3 text-left font-semibold">Description</th>
                            <th class="px-5 py-3 text-left font-semibold w-28">Status</th>
                            <th class="px-5 py-3 text-center font-semibold w-20">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50 text-blue-900" id="custtype-tbody">
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- end max-w --}}
</div>{{-- end page --}}


{{-- ═══════════════════════════════════════════════════════════════════
     MODALS — one per LOV section
════════════════════════════════════════════════════════════════════ --}}

{{-- Generic modal template (rendered 4×, differentiated by section) --}}
@php
    $lovModals = [
        [
            'id' => 'industry-modal',
            'title' => 'Add Industry',
            'subtitle' => 'Create a new industry classification entry',
            'accent' => 'blue',
            'code_prefix' => 'IND-',
        ],
        [
            'id' => 'typeorg-modal',
            'title' => 'Add Type of Organization',
            'subtitle' => 'Define a new organization structure type',
            'accent' => 'orange',
            'code_prefix' => 'ORG-',
        ],
        [
            'id' => 'payment-modal',
            'title' => 'Add Payment Mode',
            'subtitle' => 'Register a new payment method option',
            'accent' => 'blue',
            'code_prefix' => 'PAY-',
        ],
        [
            'id' => 'custtype-modal',
            'title' => 'Add Customer Type',
            'subtitle' => 'Define a new customer classification type',
            'accent' => 'orange',
            'code_prefix' => 'CST-',
        ],
    ];
@endphp

@foreach ($lovModals as $m)
    <div id="{{ $m['id'] }}"
        class="lov-modal fixed inset-0 z-50 flex items-center justify-center bg-blue-950/40 backdrop-blur-sm px-4 hidden"
        role="dialog" aria-modal="true">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-blue-100">
                <div class="flex items-center gap-3">
                    <span
                        class="w-1.5 h-6 bg-{{ $m['accent'] === 'blue' ? 'blue-500' : 'orange-400' }} rounded-full inline-block"></span>
                    <div>
                        <h3 class="text-base font-bold text-blue-900">{{ $m['title'] }}</h3>
                        <p class="text-xs text-blue-400 mt-0.5">{{ $m['subtitle'] }}</p>
                    </div>
                </div>
                <button
                    class="btn-close-lov-modal p-2 text-blue-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form class="lov-form px-6 py-5 space-y-4" data-section="{{ $m['id'] }}" novalidate>
                <div>
                    <label class="block text-xs font-semibold text-blue-500 mb-1">Code <span
                            class="text-orange-400">*</span></label>
                    <div class="flex items-center gap-0">
                        <span
                            class="inline-flex items-center px-3 py-2.5 bg-blue-50 border border-r-0 border-blue-100 rounded-l-xl text-xs text-blue-400 font-mono font-semibold select-none">{{ $m['code_prefix'] }}</span>
                        <input type="text" name="code" placeholder="001"
                            class="flex-1 border border-blue-100 rounded-r-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition font-mono" />
                    </div>
                    <p class="text-xs text-blue-300 mt-1">Unique identifier — e.g. {{ $m['code_prefix'] }}001</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-blue-500 mb-1">Name / Label <span
                            class="text-orange-400">*</span></label>
                    <input type="text" name="name" placeholder="Enter display name"
                        class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-blue-500 mb-1">Description</label>
                    <textarea name="description" rows="3" placeholder="Short description of this value..."
                        class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-blue-500 mb-2">Status <span
                            class="text-orange-400">*</span></label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="active" checked class="accent-orange-500" />
                            <span class="text-sm text-blue-700 font-medium">Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="inactive" class="accent-orange-500" />
                            <span class="text-sm text-blue-700 font-medium">Inactive</span>
                        </label>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button"
                        class="btn-close-lov-modal px-5 py-2.5 text-sm font-semibold text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-orange-500 hover:bg-orange-600 active:bg-orange-700 rounded-xl shadow-sm transition">
                        Save Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach


{{-- ═══════════════════════════════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════════════════════════════ --}}
<script>
    (function() {
        'use strict';

        // ── Sample Data ─────────────────────────────────────────────────
        const LOV_DATA = {
            industry: [{
                    id: 1,
                    code: 'IND-001',
                    name: 'Logistics',
                    description: 'Companies involved in goods transport and supply chain management',
                    status: 'active'
                },
                {
                    id: 2,
                    code: 'IND-002',
                    name: 'Retail',
                    description: 'Businesses selling goods directly to end consumers',
                    status: 'active'
                },
                {
                    id: 3,
                    code: 'IND-003',
                    name: 'Manufacturing',
                    description: 'Production and assembly of physical goods and products',
                    status: 'active'
                },
                {
                    id: 4,
                    code: 'IND-004',
                    name: 'Agriculture',
                    description: 'Farming, fishing, and natural resource-based businesses',
                    status: 'active'
                },
                {
                    id: 5,
                    code: 'IND-005',
                    name: 'Food & Beverage',
                    description: 'Production and distribution of food and drink products',
                    status: 'inactive'
                },
                {
                    id: 6,
                    code: 'IND-006',
                    name: 'Healthcare',
                    description: 'Medical services, pharmaceuticals, and wellness companies',
                    status: 'active'
                },
            ],
            typeorg: [{
                    id: 1,
                    code: 'ORG-001',
                    name: 'Sole Proprietorship',
                    description: 'Business owned and operated by a single individual',
                    status: 'active'
                },
                {
                    id: 2,
                    code: 'ORG-002',
                    name: 'Partnership',
                    description: 'Business owned by two or more individuals sharing liability',
                    status: 'active'
                },
                {
                    id: 3,
                    code: 'ORG-003',
                    name: 'Corporation',
                    description: 'Legal entity separate from its owners with limited liability',
                    status: 'active'
                },
                {
                    id: 4,
                    code: 'ORG-004',
                    name: 'Cooperative',
                    description: 'Member-owned and democratically controlled organization',
                    status: 'active'
                },
                {
                    id: 5,
                    code: 'ORG-005',
                    name: 'Government Agency',
                    description: 'Publicly funded body operating under government authority',
                    status: 'active'
                },
                {
                    id: 6,
                    code: 'ORG-006',
                    name: 'NGO / Non-Profit',
                    description: 'Organization operating for social benefit, not profit',
                    status: 'inactive'
                },
            ],
            payment: [{
                    id: 1,
                    code: 'PAY-001',
                    name: 'On-Account',
                    description: 'Billed on credit terms with a set credit limit threshold',
                    status: 'active'
                },
                {
                    id: 2,
                    code: 'PAY-002',
                    name: 'Prepaid',
                    description: 'Payment collected fully before service or shipment is released',
                    status: 'active'
                },
                {
                    id: 3,
                    code: 'PAY-003',
                    name: 'Cash on Delivery',
                    description: 'Payment collected upon successful delivery to the consignee',
                    status: 'active'
                },
            ],
            custtype: [{
                    id: 1,
                    code: 'CST-001',
                    name: 'Shipper',
                    description: 'Entity responsible for originating and sending the shipment',
                    status: 'active'
                },
                {
                    id: 2,
                    code: 'CST-002',
                    name: 'Consignee',
                    description: 'Entity designated to receive the shipment at the destination',
                    status: 'active'
                },
                {
                    id: 3,
                    code: 'CST-003',
                    name: 'Both',
                    description: 'Company acting as both shipper and consignee in different transactions',
                    status: 'active'
                },
            ],
        };

        // ── Render Helpers ───────────────────────────────────────────────
        function statusBadge(status) {
            return status === 'active' ?
                `<span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                   <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>Active
               </span>` :
                `<span class="inline-flex items-center gap-1 bg-slate-100 text-slate-500 text-xs font-semibold px-2.5 py-1 rounded-full">
                   <span class="w-1.5 h-1.5 rounded-full bg-slate-400 inline-block"></span>Inactive
               </span>`;
        }

        function deleteBtn(id, section) {
            return `<button
                    class="btn-delete-row p-1.5 bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 rounded-lg transition"
                    data-id="${id}" data-section="${section}" title="Delete">
                    <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>`;
        }

        function buildRows(section, data) {
            const tbody = document.getElementById(`${section}-tbody`);
            if (!tbody) return;
            tbody.innerHTML = '';
            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'cursor-pointer hover:bg-orange-50 transition-colors duration-100 group';
                tr.dataset.id = item.id;
                tr.dataset.section = section;
                tr.innerHTML = `
                <td class="px-5 py-3 whitespace-nowrap font-mono text-xs text-blue-400 font-semibold">${item.code}</td>
                <td class="px-5 py-3 whitespace-nowrap font-semibold text-blue-900">${item.name}</td>
                <td class="px-5 py-3 text-blue-500 text-xs max-w-xs truncate">${item.description}</td>
                <td class="px-5 py-3 whitespace-nowrap">${statusBadge(item.status)}</td>
                <td class="px-5 py-3 text-center">${deleteBtn(item.id, section)}</td>
            `;
                tbody.appendChild(tr);
            });
        }

        // ── Populate All Tables ──────────────────────────────────────────
        buildRows('industry', LOV_DATA.industry);
        buildRows('typeorg', LOV_DATA.typeorg);
        buildRows('payment', LOV_DATA.payment);
        buildRows('custtype', LOV_DATA.custtype);

        // Update badge counts
        document.getElementById('industry-count-badge').textContent = LOV_DATA.industry.length;
        document.getElementById('typeorg-count-badge').textContent = LOV_DATA.typeorg.length;
        document.getElementById('payment-count-badge').textContent = LOV_DATA.payment.length;
        document.getElementById('custtype-count-badge').textContent = LOV_DATA.custtype.length;

        // ── Initialize DataTables ────────────────────────────────────────
        window.initDataTables = function initDataTables() {
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
                        // Prevent DataTables from hijacking the delete button click
                        columnDefs: [{
                            orderable: false,
                            targets: -1
                        }],
                    });

                    styleDataTable(this);
                    dt.on("draw", () => {
                        styleDataTable(this);
                    });

                    $(window).on("resize", () => {
                        dt.settings()[0].oInit.scrollX = $(window).width() < 1024;
                        dt.columns.adjust();
                    });
                }
            });
        };

        function styleDataTable(table) {
            table.querySelectorAll("tbody").forEach((tbody) => {
                tbody.classList.remove("divide-y", "divide-gray-200", "dark:divide-gray-700");
                tbody.querySelectorAll("tr").forEach((row) => {
                    row.classList.remove("even:bg-gray-50", "dark:even:bg-gray-900/50");
                    row.classList.add("transition-colors", "duration-300", "hover:border-white",
                        "hover:border-3");
                });
            });
            document.querySelectorAll(".pagination").forEach((el) => {
                el.classList.add("flex", "justify-center", "p-5", "lg:justify-end", "dark:text-white");
            });
            document.querySelectorAll(".dt-search").forEach((el) => {
                el.classList.add("flex", "justify-center", "p-5", "lg:justify-end", "dark:text-white");
            });
            document.querySelectorAll(".dt-wrapper").forEach((el) => {
                el.classList.add("bg-white", "text-black");
            });
        }

        // Init on DOM ready (call after jQuery + DataTables are loaded)
        if (typeof $ !== 'undefined' && $.fn && $.fn.DataTable) {
            window.initDataTables();
        } else {
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof window.initDataTables === 'function') window.initDataTables();
            });
        }

        // ── Modal Open / Close ───────────────────────────────────────────
        document.querySelectorAll('.btn-open-lov-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                const modalId = this.dataset.modal;
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                    console.log(`[LOV] Modal opened: ${modalId}`);
                }
            });
        });

        function closeLovModal(modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            console.log(`[LOV] Modal closed: ${modal.id}`);
        }

        document.querySelectorAll('.btn-close-lov-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                closeLovModal(this.closest('.lov-modal'));
            });
        });

        // Close on backdrop click
        document.querySelectorAll('.lov-modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeLovModal(modal);
            });
        });

        // ── Form Submission ──────────────────────────────────────────────
        document.querySelectorAll('.lov-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const section = this.dataset.section;
                const data = new FormData(this);
                const payload = {
                    section
                };
                data.forEach((value, key) => {
                    payload[key] = value;
                });
                console.log(`[LOV] Form submitted for section "${section}":`, payload);
                // TODO: Replace with axios.post('/api/lov', payload)
                closeLovModal(this.closest('.lov-modal'));
                this.reset();
            });
        });

        // ── Row Click (log ID) ───────────────────────────────────────────
        document.addEventListener('click', function(e) {
            // Delete button
            const delBtn = e.target.closest('.btn-delete-row');
            if (delBtn) {
                e.stopPropagation();
                const id = delBtn.dataset.id;
                const section = delBtn.dataset.section;
                console.log(`[LOV] Delete clicked — Section: "${section}", ID: ${id}`);
                // TODO: confirm then axios.delete(`/api/lov/${id}`)
                return;
            }

            // Row click
            const row = e.target.closest('tr[data-id]');
            if (row) {
                const id = row.dataset.id;
                const section = row.dataset.section;
                console.log(`[LOV] Row clicked — Section: "${section}", ID: ${id}`);
                // TODO: open edit modal or navigate to detail
            }
        });

    })();
</script>
