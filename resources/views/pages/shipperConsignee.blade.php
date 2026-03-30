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





{{-- Shipper-Consignee Module --}}
{{-- Blade SPA Page | Laravel 10 | Tailwind CSS --}}

<div id="shipper-consignee-page" class="min-h-screen font-sans">

    {{-- Page Header --}}
    <div class=" border-b border-blue-100 px-4 sm:px-6 lg:px-8 py-5">
        <div class="max-w-screen-xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Stats Row --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-blue-100 px-5 py-4 shadow-sm">
                <p class="text-xs text-blue-400 font-medium uppercase tracking-wider">Total Records</p>
                <p class="text-3xl font-bold text-blue-900 mt-1">24</p>
            </div>
            <div class="bg-white rounded-2xl border border-blue-100 px-5 py-4 shadow-sm">
                <p class="text-xs text-orange-400 font-medium uppercase tracking-wider">On-Account</p>
                <p class="text-3xl font-bold text-orange-500 mt-1">11</p>
            </div>
            <div class="bg-white rounded-2xl border border-blue-100 px-5 py-4 shadow-sm">
                <p class="text-xs text-blue-400 font-medium uppercase tracking-wider">Prepaid</p>
                <p class="text-3xl font-bold text-blue-900 mt-1">8</p>
            </div>
            <div class="bg-white rounded-2xl border border-blue-100 px-5 py-4 shadow-sm">
                <p class="text-xs text-blue-400 font-medium uppercase tracking-wider">Cash on Delivery</p>
                <p class="text-3xl font-bold text-blue-900 mt-1">5</p>
            </div>
        </div>

        <button id="btn-open-modal"
            class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-150 w-fit mb-5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Shipper / Consignee
        </button>
        {{-- Table Card --}}
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">

            {{-- Table Toolbar --}}
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-blue-50">
                <div class="relative w-full sm:w-72">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-300" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                    </svg>
                    <input id="table-search" type="text" placeholder="Search company, code, industry..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-blue-100 rounded-xl bg-slate-50 text-blue-900 placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-200 transition" />
                </div>
                <div class="flex items-center gap-2">
                    <select id="filter-customer-type"
                        class="text-sm border border-blue-100 rounded-xl px-3 py-2 bg-slate-50 text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="">All Types</option>
                        <option value="shipper">Shipper</option>
                        <option value="consignee">Consignee</option>
                        <option value="both">Both</option>
                    </select>
                    <select id="filter-payment-mode"
                        class="text-sm border border-blue-100 rounded-xl px-3 py-2 bg-slate-50 text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="">All Payments</option>
                        <option value="on-account">On-Account</option>
                        <option value="prepaid">Prepaid</option>
                        <option value="cod">Cash on Delivery</option>
                    </select>
                </div>
            </div>

            {{-- Scrollable Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-blue-50 text-blue-500 text-xs uppercase tracking-wider">
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">SC Code</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Company Name</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Industry</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Type of Org</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Customer Type</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Payment Mode</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Credit Limit</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">TIN</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Contact Number</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Sales Rep</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Date Created</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="shipper-table-body" class="divide-y divide-blue-50 text-blue-900">
                        {{-- Sample Data Rows --}}
                        <tr class="hover:bg-orange-50 transition-colors duration-100">
                            <td class="px-4 py-3 whitespace-nowrap font-mono text-xs text-blue-400 font-semibold">
                                SC-00001</td>
                            <td class="px-4 py-3 whitespace-nowrap font-semibold text-blue-900">Layag Freight
                                Solutions</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">Logistics</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">Corporation</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">Shipper</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="bg-orange-100 text-orange-600 text-xs font-semibold px-2.5 py-1 rounded-full">On-Account</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-700">₱500,000</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">123-456-789-000</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">+63 917 123 4567</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">Juan dela Cruz</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-400 text-xs">2025-01-15</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <button
                                        class="btn-edit p-1.5 text-blue-400 hover:text-blue-700 hover:bg-blue-100 rounded-lg transition"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a4 4 0 01-1.414.828l-3 1 1-3a4 4 0 01.828-1.414z" />
                                        </svg>
                                    </button>
                                    <button
                                        class="btn-view p-1.5 text-blue-400 hover:text-orange-500 hover:bg-orange-100 rounded-lg transition"
                                        title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button
                                        class="btn-delete p-1.5 text-blue-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition"
                                        title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-orange-50 transition-colors duration-100">
                            <td class="px-4 py-3 whitespace-nowrap font-mono text-xs text-blue-400 font-semibold">
                                SC-00002</td>
                            <td class="px-4 py-3 whitespace-nowrap font-semibold text-blue-900">Daloy Trading
                                Co.</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">Retail</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">Partnership</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Consignee</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="bg-slate-100 text-slate-600 text-xs font-semibold px-2.5 py-1 rounded-full">Prepaid</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-400">—</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">987-654-321-000</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">+63 918 987 6543</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">Maria Santos</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-400 text-xs">2025-02-03</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <button
                                        class="btn-edit p-1.5 text-blue-400 hover:text-blue-700 hover:bg-blue-100 rounded-lg transition"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a4 4 0 01-1.414.828l-3 1 1-3a4 4 0 01.828-1.414z" />
                                        </svg>
                                    </button>
                                    <button
                                        class="btn-view p-1.5 text-blue-400 hover:text-orange-500 hover:bg-orange-100 rounded-lg transition"
                                        title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button
                                        class="btn-delete p-1.5 text-blue-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition"
                                        title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-orange-50 transition-colors duration-100">
                            <td class="px-4 py-3 whitespace-nowrap font-mono text-xs text-blue-400 font-semibold">
                                SC-00003</td>
                            <td class="px-4 py-3 whitespace-nowrap font-semibold text-blue-900">Agos
                                Manufacturing Inc.</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">Manufacturing</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">Corporation</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="bg-purple-100 text-purple-700 text-xs font-semibold px-2.5 py-1 rounded-full">Both</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="bg-orange-100 text-orange-600 text-xs font-semibold px-2.5 py-1 rounded-full">On-Account</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-700">₱1,200,000</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">456-123-789-000</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">+63 920 456 7890</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-600">Pedro Reyes</td>
                            <td class="px-4 py-3 whitespace-nowrap text-blue-400 text-xs">2025-03-10</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <button
                                        class="btn-edit p-1.5 text-blue-400 hover:text-blue-700 hover:bg-blue-100 rounded-lg transition"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a4 4 0 01-1.414.828l-3 1 1-3a4 4 0 01.828-1.414z" />
                                        </svg>
                                    </button>
                                    <button
                                        class="btn-view p-1.5 text-blue-400 hover:text-orange-500 hover:bg-orange-100 rounded-lg transition"
                                        title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button
                                        class="btn-delete p-1.5 text-blue-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition"
                                        title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Table Footer / Pagination --}}
            <div
                class="px-6 py-4 border-t border-blue-50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <p class="text-xs text-blue-400">Showing <span class="font-semibold text-blue-600">3</span> of
                    <span class="font-semibold text-blue-600">24</span> records
                </p>
                <div class="flex items-center gap-1">
                    <button
                        class="px-3 py-1.5 text-xs text-blue-400 hover:text-blue-700 border border-blue-100 rounded-lg hover:bg-blue-50 transition">Prev</button>
                    <button class="px-3 py-1.5 text-xs bg-orange-500 text-white rounded-lg font-semibold">1</button>
                    <button
                        class="px-3 py-1.5 text-xs text-blue-400 hover:text-blue-700 border border-blue-100 rounded-lg hover:bg-blue-50 transition">2</button>
                    <button
                        class="px-3 py-1.5 text-xs text-blue-400 hover:text-blue-700 border border-blue-100 rounded-lg hover:bg-blue-50 transition">3</button>
                    <button
                        class="px-3 py-1.5 text-xs text-blue-400 hover:text-blue-700 border border-blue-100 rounded-lg hover:bg-blue-50 transition">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL ===================== --}}
<div id="sc-modal"
    class="fixed inset-0 z-50 flex items-start justify-center bg-blue-950/40 backdrop-blur-sm overflow-y-auto py-6 px-4 hidden"
    role="dialog" aria-modal="true">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto my-auto relative">

        {{-- Modal Header --}}
        <div
            class="flex items-center justify-between px-6 py-5 border-b border-blue-100 sticky top-0 bg-white rounded-t-2xl z-10">
            <div>
                <h2 class="text-lg font-bold text-blue-900">New Shipper / Consignee</h2>
                <p class="text-xs text-blue-400 mt-0.5">Fill in all required fields to add a record</p>
            </div>
            <button id="btn-close-modal"
                class="p-2 text-blue-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <form id="sc-form" novalidate class="px-6 py-6 space-y-8">

            {{-- ---- SECTION: Company Information ---- --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-5 bg-orange-400 rounded-full inline-block"></span>
                    <h3 class="text-sm font-bold text-blue-900 uppercase tracking-wider">Company Information
                    </h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-blue-500 mb-1">Company Name <span
                                class="text-orange-400">*</span></label>
                        <input type="text" name="company_name" placeholder="e.g. Layag Freight Solutions"
                            class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-blue-500 mb-1">Registered Company
                            Address <span class="text-orange-400">*</span></label>
                        <textarea name="company_address" rows="2" placeholder="Full registered address"
                            class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Contact Number 1
                                <span class="text-blue-300">(Billing)</span></label>
                            <input type="text" name="contact_1" placeholder="+63 9XX XXX XXXX"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Contact Number 2
                                <span class="text-blue-300">(Billing)</span></label>
                            <input type="text" name="contact_2" placeholder="+63 9XX XXX XXXX"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Industry <span
                                    class="text-orange-400">*</span></label>
                            <select name="industry"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                                <option value="">Select Industry</option>
                                <option>Logistics</option>
                                <option>Retail</option>
                                <option>Manufacturing</option>
                                <option>Agriculture</option>
                                <option>Food & Beverage</option>
                                <option>Construction</option>
                                <option>Healthcare</option>
                                <option>Technology</option>
                                <option>Others</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Type of Organization
                                <span class="text-orange-400">*</span></label>
                            <select name="type_of_org"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                                <option value="">Select Type</option>
                                <option>Sole Proprietorship</option>
                                <option>Partnership</option>
                                <option>Corporation</option>
                                <option>Cooperative</option>
                                <option>Government Agency</option>
                                <option>NGO / Non-Profit</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Tax Identification
                                Number (TIN)</label>
                            <input type="text" name="tin" placeholder="XXX-XXX-XXX-000"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Start of
                                Business</label>
                            <input type="date" name="start_of_business"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Number of
                                Employees</label>
                            <input type="text" name="num_employees" placeholder="e.g. 50"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Est. Annual
                                Revenue</label>
                            <input type="number" name="annual_revenue" placeholder="0.00"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Est. Annual Net
                                Income</label>
                            <input type="number" name="annual_net_income" placeholder="0.00"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Customer Type <span
                                    class="text-orange-400">*</span></label>
                            <select name="customer_type"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                                <option value="">Select Type</option>
                                <option>Shipper</option>
                                <option>Consignee</option>
                                <option>Both</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Company URL</label>
                            <input type="url" name="company_url" placeholder="https://company.com"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- ---- SECTION: Company Finance ---- --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-5 bg-orange-400 rounded-full inline-block"></span>
                    <h3 class="text-sm font-bold text-blue-900 uppercase tracking-wider">Company Finance</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-blue-500 mb-1">Payment Mode <span
                                class="text-orange-400">*</span></label>
                        <select name="payment_mode" id="payment-mode-select"
                            class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                            <option value="">Select Payment Mode</option>
                            <option value="on-account">On-Account</option>
                            <option value="prepaid">Prepaid</option>
                            <option value="cod">Cash on Delivery</option>
                        </select>
                    </div>
                    {{-- On-Account Fields (conditional) --}}
                    <div id="on-account-fields"
                        class="hidden space-y-4 bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <p class="text-xs text-blue-400 font-semibold uppercase tracking-wider">On-Account
                            Details</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-blue-500 mb-1">Credit
                                    Limit</label>
                                <input type="number" name="credit_limit" placeholder="0.00"
                                    class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                                <p class="text-xs text-blue-300 mt-1">If exceeded, KFMS changes mode to Prepaid
                                </p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-blue-500 mb-1">Current
                                    Credit</label>
                                <input type="number" name="current_credit" placeholder="0.00"
                                    class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                            </div>
                        </div>
                    </div>

                    {{-- Standard Billing Service --}}
                    <div class="bg-slate-50 rounded-xl p-4 border border-blue-100 space-y-4">
                        <p class="text-xs font-bold text-blue-700 uppercase tracking-wider">Standard Billing
                            Service</p>

                        <div>
                            <p class="text-xs font-semibold text-blue-500 mb-2">Invoice Submission</p>
                            <div class="space-y-3">
                                <div>
                                    <label class="flex items-start gap-2 cursor-pointer">
                                        <input type="checkbox" id="chk-email" name="invoice_email"
                                            class="mt-0.5 accent-orange-500 rounded" />
                                        <span class="text-sm text-blue-700">Electronic — via email</span>
                                    </label>
                                    <div id="field-invoice-email" class="hidden mt-2 ml-6">
                                        <input type="email" name="invoice_email_address"
                                            placeholder="billing@company.com"
                                            class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                                    </div>
                                </div>
                                <div>
                                    <label class="flex items-start gap-2 cursor-pointer">
                                        <input type="checkbox" id="chk-courier" name="invoice_courier"
                                            class="mt-0.5 accent-orange-500 rounded" />
                                        <span class="text-sm text-blue-700">Via Courier</span>
                                    </label>
                                    <div id="field-invoice-courier" class="hidden mt-2 ml-6">
                                        <textarea name="invoice_courier_address" rows="2" placeholder="Courier delivery address"
                                            class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition resize-none"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-blue-500 mb-2">Customer Payment</p>
                            <div class="space-y-3">
                                <div>
                                    <label class="flex items-start gap-2 cursor-pointer">
                                        <input type="checkbox" id="chk-check-pickup" name="payment_check_pickup"
                                            class="mt-0.5 accent-orange-500 rounded" />
                                        <span class="text-sm text-blue-700">Check Pick Up</span>
                                    </label>
                                    <div id="field-check-pickup" class="hidden mt-2 ml-6">
                                        <textarea name="check_pickup_address" rows="2" placeholder="Pick-up address"
                                            class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition resize-none"></textarea>
                                    </div>
                                </div>
                                <div>
                                    <label class="flex items-start gap-2 cursor-pointer">
                                        <input type="checkbox" id="chk-bank" name="payment_bank"
                                            class="mt-0.5 accent-orange-500 rounded" />
                                        <span class="text-sm text-blue-700">Direct Remittance to Bank
                                            Account</span>
                                    </label>
                                    <div id="field-bank" class="hidden mt-2 ml-6">
                                        <textarea name="bank_account_details" rows="2" placeholder="Bank name, account name, account number"
                                            class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition resize-none"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Billing Service --}}
                    <div class="bg-slate-50 rounded-xl p-4 border border-blue-100 space-y-3">
                        <p class="text-xs font-bold text-blue-700 uppercase tracking-wider">Additional Billing
                            Service Request</p>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="add_doc_handling" class="accent-orange-500 rounded" />
                            <span class="text-sm text-blue-700">Document Handling</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="add_billing_report" class="accent-orange-500 rounded" />
                            <span class="text-sm text-blue-700">Billing Report</span>
                        </label>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Others</label>
                            <input type="text" name="add_others" placeholder="Specify other billing requests"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- ---- SECTION: Contact Info ---- --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-5 bg-orange-400 rounded-full inline-block"></span>
                    <h3 class="text-sm font-bold text-blue-900 uppercase tracking-wider">Contact Information
                    </h3>
                </div>
                <div class="space-y-3">
                    <div
                        class="grid grid-cols-5 gap-2 text-xs font-semibold text-blue-400 uppercase tracking-wider px-1">
                        <span class="col-span-1">Name</span>
                        <span class="col-span-1">Contact No.</span>
                        <span class="col-span-1">Email</span>
                        <span class="col-span-1">Role</span>
                        <span class="col-span-1">Position</span>
                    </div>
                    <div id="contact-rows" class="space-y-2">
                        <div class="grid grid-cols-5 gap-2">
                            <input type="text" name="contacts[0][name]" placeholder="Full Name"
                                class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1" />
                            <input type="text" name="contacts[0][number]" placeholder="+63..."
                                class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1" />
                            <input type="email" name="contacts[0][email]" placeholder="email@co.com"
                                class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1" />
                            <input type="text" name="contacts[0][role]" placeholder="Role"
                                class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1" />
                            <input type="text" name="contacts[0][position]" placeholder="Position"
                                class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1" />
                        </div>
                    </div>
                    <button type="button" id="btn-add-contact-row"
                        class="flex items-center gap-1.5 text-xs text-blue-400 hover:text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition mt-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Another Contact
                    </button>
                </div>
            </div>

            {{-- ---- SECTION: Sales Info ---- --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-5 bg-orange-400 rounded-full inline-block"></span>
                    <h3 class="text-sm font-bold text-blue-900 uppercase tracking-wider">Sales Information</h3>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-blue-500 mb-1">Account Owner / Sales Rep
                        Name</label>
                    <input type="text" name="sales_rep" placeholder="Full name of account owner"
                        class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                </div>
            </div>

            {{-- ---- SECTION: Consignees ---- --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-orange-400 rounded-full inline-block"></span>
                        <h3 class="text-sm font-bold text-blue-900 uppercase tracking-wider">Consignees</h3>
                    </div>
                    <button type="button" id="btn-add-consignee"
                        class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm transition-all duration-150">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Consignee
                    </button>
                </div>

                <div id="consignee-container" class="space-y-4">
                    {{-- Consignee cards will be appended here by JS --}}
                    <div class="text-center py-8 text-blue-300 text-sm border-2 border-dashed border-blue-100 rounded-2xl"
                        id="consignee-empty-state">
                        <svg class="w-8 h-8 mx-auto mb-2 text-blue-200" fill="none" stroke="currentColor"
                            stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                        No consignees added yet. Click "Add Consignee" to begin.
                    </div>
                </div>
            </div>

        </form>

        {{-- Modal Footer --}}
        <div
            class="px-6 py-5 border-t border-blue-100 flex flex-col sm:flex-row sm:items-center justify-end gap-3 sticky bottom-0 bg-white rounded-b-2xl">
            <button id="btn-cancel-modal" type="button"
                class="px-5 py-2.5 text-sm font-semibold text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition w-full sm:w-auto">
                Cancel
            </button>
            <button id="btn-submit-form" type="button"
                class="px-6 py-2.5 text-sm font-semibold text-white bg-orange-500 hover:bg-orange-600 active:bg-orange-700 rounded-xl shadow-sm transition w-full sm:w-auto">
                Save Shipper / Consignee
            </button>
        </div>
    </div>
</div>


{{-- ===================== JAVASCRIPT ===================== --}}
<script>
    (function() {
        'use strict';

        // ── Helpers ────────────────────────────────────────────
        const $ = (id) => document.getElementById(id);

        // ── Modal open/close ────────────────────────────────────
        const modal = $('sc-modal');
        const openBtn = $('btn-open-modal');
        const closeBtn = $('btn-close-modal');
        const cancelBtn = $('btn-cancel-modal');

        function openModal() {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            console.log('[SC Module] Modal opened — New Shipper/Consignee form');
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            console.log('[SC Module] Modal closed');
        }

        openBtn.addEventListener('click', openModal);
        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Close when clicking backdrop
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        // ── Payment Mode — On-Account conditional fields ────────
        const paymentSelect = $('payment-mode-select');
        const onAccountFields = $('on-account-fields');

        paymentSelect.addEventListener('change', function() {
            if (this.value === 'on-account') {
                onAccountFields.classList.remove('hidden');
                console.log('[SC Module] Payment mode: On-Account — credit fields shown');
            } else {
                onAccountFields.classList.add('hidden');
                console.log('[SC Module] Payment mode changed to:', this.value);
            }
        });

        // ── Checkbox conditional fields ─────────────────────────
        const checkboxFieldMap = {
            'chk-email': 'field-invoice-email',
            'chk-courier': 'field-invoice-courier',
            'chk-check-pickup': 'field-check-pickup',
            'chk-bank': 'field-bank',
        };

        Object.entries(checkboxFieldMap).forEach(([chkId, fieldId]) => {
            const chk = $(chkId);
            const field = $(fieldId);
            chk.addEventListener('change', function() {
                field.classList.toggle('hidden', !this.checked);
                console.log(`[SC Module] Checkbox "${chkId}" toggled:`, this.checked);
            });
        });

        // ── Add Contact Row ─────────────────────────────────────
        let contactIndex = 1;
        const contactRows = $('contact-rows');

        $('btn-add-contact-row').addEventListener('click', function() {
            const i = contactIndex++;
            const row = document.createElement('div');
            row.className = 'grid grid-cols-5 gap-2 contact-row';
            row.innerHTML = `
            <input type="text"  name="contacts[${i}][name]"     placeholder="Full Name"    class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1"/>
            <input type="text"  name="contacts[${i}][number]"   placeholder="+63..."       class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1"/>
            <input type="email" name="contacts[${i}][email]"    placeholder="email@co.com" class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1"/>
            <input type="text"  name="contacts[${i}][role]"     placeholder="Role"         class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1"/>
            <div class="flex gap-1 col-span-1">
                <input type="text" name="contacts[${i}][position]" placeholder="Position" class="flex-1 border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                <button type="button" class="btn-remove-contact p-2 text-red-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Remove">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        `;
            row.querySelector('.btn-remove-contact').addEventListener('click', function() {
                row.remove();
                console.log('[SC Module] Contact row removed, index:', i);
            });
            contactRows.appendChild(row);
            console.log('[SC Module] Contact row added, index:', i);
        });

        // ── Add Consignee Card ──────────────────────────────────
        let consigneeIndex = 0;
        const container = $('consignee-container');
        const emptyState = $('consignee-empty-state');

        function buildConsigneeCard(idx) {
            const card = document.createElement('div');
            card.className = 'consignee-card bg-blue-50 border border-blue-100 rounded-2xl overflow-hidden';
            card.dataset.idx = idx;

            card.innerHTML = `
            <!-- Card Header -->
            <div class="flex items-center justify-between px-5 py-3 bg-blue-100/60 border-b border-blue-100">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-400 text-white text-xs font-bold flex items-center justify-center consignee-num">${idx + 1}</span>
                    <span class="text-sm font-semibold text-blue-800">Consignee ${idx + 1}</span>
                </div>
                <button type="button" class="btn-remove-consignee p-1.5 text-blue-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Remove Consignee">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>

            <!-- Card Body -->
            <div class="px-5 py-4 space-y-4">

                <!-- Company Info -->
                <div>
                    <p class="text-xs font-bold text-orange-400 uppercase tracking-wider mb-3">Company Information</p>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Company Name <span class="text-orange-400">*</span></label>
                            <input type="text" name="consignees[${idx}][company_name]" placeholder="Consignee company name"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Registered Address</label>
                            <textarea name="consignees[${idx}][company_address]" rows="2" placeholder="Full registered address"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition resize-none"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-blue-500 mb-1">Contact No. 1</label>
                                <input type="text" name="consignees[${idx}][contact_1]" placeholder="+63..."
                                    class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-blue-500 mb-1">Contact No. 2</label>
                                <input type="text" name="consignees[${idx}][contact_2]" placeholder="+63..."
                                    class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-blue-500 mb-1">Industry</label>
                                <select name="consignees[${idx}][industry]" class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                                    <option value="">Select Industry</option>
                                    <option>Logistics</option><option>Retail</option><option>Manufacturing</option>
                                    <option>Agriculture</option><option>Food & Beverage</option><option>Construction</option>
                                    <option>Healthcare</option><option>Technology</option><option>Others</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-blue-500 mb-1">Type of Organization</label>
                                <select name="consignees[${idx}][type_of_org]" class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                                    <option value="">Select Type</option>
                                    <option>Sole Proprietorship</option><option>Partnership</option><option>Corporation</option>
                                    <option>Cooperative</option><option>Government Agency</option><option>NGO / Non-Profit</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-blue-500 mb-1">TIN</label>
                                <input type="text" name="consignees[${idx}][tin]" placeholder="XXX-XXX-XXX-000"
                                    class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-blue-500 mb-1">Start of Business</label>
                                <input type="date" name="consignees[${idx}][start_of_business]"
                                    class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-blue-500 mb-1">Employees</label>
                                <input type="text" name="consignees[${idx}][num_employees]" placeholder="e.g. 50"
                                    class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-blue-500 mb-1">Est. Revenue</label>
                                <input type="number" name="consignees[${idx}][annual_revenue]" placeholder="0.00"
                                    class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-blue-500 mb-1">Est. Net Income</label>
                                <input type="number" name="consignees[${idx}][annual_net_income]" placeholder="0.00"
                                    class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-blue-500 mb-1">Customer Type</label>
                                <select name="consignees[${idx}][customer_type]" class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                                    <option value="">Select</option>
                                    <option>Shipper</option><option>Consignee</option><option>Both</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-blue-500 mb-1">Company URL</label>
                                <input type="url" name="consignees[${idx}][company_url]" placeholder="https://..."
                                    class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Finance -->
                <div>
                    <p class="text-xs font-bold text-orange-400 uppercase tracking-wider mb-3">Finance</p>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Payment Mode</label>
                            <select name="consignees[${idx}][payment_mode]" class="c-payment-mode w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                                <option value="">Select</option>
                                <option value="on-account">On-Account</option>
                                <option value="prepaid">Prepaid</option>
                                <option value="cod">Cash on Delivery</option>
                            </select>
                        </div>
                        <div class="c-on-account-fields hidden space-y-3 bg-white rounded-xl p-3 border border-blue-100">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-blue-500 mb-1">Credit Limit</label>
                                    <input type="number" name="consignees[${idx}][credit_limit]" placeholder="0.00"
                                        class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-blue-500 mb-1">Current Credit</label>
                                    <input type="number" name="consignees[${idx}][current_credit]" placeholder="0.00"
                                        class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div>
                    <p class="text-xs font-bold text-orange-400 uppercase tracking-wider mb-3">Contact Info</p>
                    <div class="grid grid-cols-5 gap-2 text-xs font-semibold text-blue-400 uppercase tracking-wider px-1 mb-2">
                        <span>Name</span><span>Contact</span><span>Email</span><span>Role</span><span>Position</span>
                    </div>
                    <div class="c-contact-rows space-y-2">
                        <div class="grid grid-cols-5 gap-2">
                            <input type="text"  name="consignees[${idx}][contacts][0][name]"     placeholder="Full Name"    class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1"/>
                            <input type="text"  name="consignees[${idx}][contacts][0][number]"   placeholder="+63..."       class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1"/>
                            <input type="email" name="consignees[${idx}][contacts][0][email]"    placeholder="email@co.com" class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1"/>
                            <input type="text"  name="consignees[${idx}][contacts][0][role]"     placeholder="Role"         class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1"/>
                            <input type="text"  name="consignees[${idx}][contacts][0][position]" placeholder="Position"     class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1"/>
                        </div>
                    </div>
                    <button type="button" class="btn-add-c-contact flex items-center gap-1.5 text-xs text-blue-400 hover:text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition mt-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Add Contact
                    </button>
                </div>

                <!-- Sales Info -->
                <div>
                    <label class="block text-xs font-semibold text-blue-500 mb-1">Account Owner / Sales Rep</label>
                    <input type="text" name="consignees[${idx}][sales_rep]" placeholder="Sales rep name"
                        class="w-full border border-blue-100 rounded-xl px-4 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                </div>
            </div>
        `;

            // Consignee payment mode toggle
            const cPaySelect = card.querySelector('.c-payment-mode');
            const cOnAccountFields = card.querySelector('.c-on-account-fields');
            cPaySelect.addEventListener('change', function() {
                cOnAccountFields.classList.toggle('hidden', this.value !== 'on-account');
                console.log(`[SC Module] Consignee ${idx + 1} payment mode:`, this.value);
            });

            // Consignee add contact row
            let cContactIndex = 1;
            const cContactRows = card.querySelector('.c-contact-rows');
            card.querySelector('.btn-add-c-contact').addEventListener('click', function() {
                const ci = cContactIndex++;
                const row = document.createElement('div');
                row.className = 'grid grid-cols-5 gap-2';
                row.innerHTML = `
                <input type="text"  name="consignees[${idx}][contacts][${ci}][name]"     placeholder="Full Name"    class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1"/>
                <input type="text"  name="consignees[${idx}][contacts][${ci}][number]"   placeholder="+63..."       class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1"/>
                <input type="email" name="consignees[${idx}][contacts][${ci}][email]"    placeholder="email@co.com" class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1"/>
                <input type="text"  name="consignees[${idx}][contacts][${ci}][role]"     placeholder="Role"         class="border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition col-span-1"/>
                <div class="flex gap-1 col-span-1">
                    <input type="text" name="consignees[${idx}][contacts][${ci}][position]" placeholder="Position" class="flex-1 border border-blue-100 rounded-xl px-3 py-2 text-sm text-blue-900 placeholder-blue-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-300 transition"/>
                    <button type="button" class="p-2 text-red-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            `;
                row.querySelector('button').addEventListener('click', () => {
                    row.remove();
                    console.log(`[SC Module] Consignee ${idx + 1} contact row removed`);
                });
                cContactRows.appendChild(row);
                console.log(`[SC Module] Consignee ${idx + 1} contact row added`);
            });

            // Remove consignee card
            card.querySelector('.btn-remove-consignee').addEventListener('click', function() {
                card.remove();
                renumberConsignees();
                console.log(`[SC Module] Consignee card ${idx + 1} removed`);
                toggleEmptyState();
            });

            return card;
        }

        function renumberConsignees() {
            const cards = container.querySelectorAll('.consignee-card');
            cards.forEach((card, i) => {
                card.querySelector('.consignee-num').textContent = i + 1;
                card.querySelector('span.font-semibold').textContent = `Consignee ${i + 1}`;
            });
        }

        function toggleEmptyState() {
            const cards = container.querySelectorAll('.consignee-card');
            emptyState.classList.toggle('hidden', cards.length > 0);
        }

        $('btn-add-consignee').addEventListener('click', function() {
            const card = buildConsigneeCard(consigneeIndex++);
            container.appendChild(card);
            toggleEmptyState();
            card.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            console.log('[SC Module] Consignee card added. Total:', container.querySelectorAll(
                '.consignee-card').length);
        });

        // ── Table Actions ───────────────────────────────────────
        document.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.btn-edit');
            const viewBtn = e.target.closest('.btn-view');
            const deleteBtn = e.target.closest('.btn-delete');

            if (editBtn) {
                const row = editBtn.closest('tr');
                const scCode = row?.querySelector('td:first-child')?.textContent?.trim();
                console.log('[SC Module] Edit clicked — SC Code:', scCode);
            }
            if (viewBtn) {
                const row = viewBtn.closest('tr');
                const scCode = row?.querySelector('td:first-child')?.textContent?.trim();
                console.log('[SC Module] View clicked — SC Code:', scCode);
            }
            if (deleteBtn) {
                const row = deleteBtn.closest('tr');
                const scCode = row?.querySelector('td:first-child')?.textContent?.trim();
                console.log('[SC Module] Delete clicked — SC Code:', scCode);
            }
        });

        // ── Table Search ────────────────────────────────────────
        $('table-search').addEventListener('input', function() {
            console.log('[SC Module] Table search query:', this.value);
        });

        // ── Table Filters ───────────────────────────────────────
        $('filter-customer-type').addEventListener('change', function() {
            console.log('[SC Module] Filter by Customer Type:', this.value);
        });
        $('filter-payment-mode').addEventListener('change', function() {
            console.log('[SC Module] Filter by Payment Mode:', this.value);
        });

        // ── Form Submit ─────────────────────────────────────────
        $('btn-submit-form').addEventListener('click', function() {
            const form = $('sc-form');
            const data = new FormData(form);
            const payload = {};
            data.forEach((value, key) => {
                payload[key] = value;
            });
            console.log('[SC Module] Form submitted — payload:', payload);
            // TODO: Replace with axios.post('/api/shipper-consignee', payload) or fetch()
        });

    })();
</script>
