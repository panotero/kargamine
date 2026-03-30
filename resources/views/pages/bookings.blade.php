{{-- Bookings Module — List Page --}}
{{-- Blade SPA Page | Laravel 10 | Tailwind CSS | DataTables --}}

<div id="bookings-page" class="min-h-screen  font-sans">


    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- ══════════════════════════════════════════════════════
             ROW 1 — STATUS COUNT CARDS
        ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm px-4 py-4 flex flex-col gap-2 hover:shadow-md transition cursor-pointer kpi-filter-card"
                data-filter="all">
                <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium">All Bookings</p>
                <p class="text-2xl font-bold text-blue-900" id="kpi-all">—</p>
            </div>
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm px-4 py-4 flex flex-col gap-2 hover:shadow-md transition cursor-pointer kpi-filter-card"
                data-filter="pending">
                <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium">Pending</p>
                <p class="text-2xl font-bold text-amber-500" id="kpi-pending">—</p>
            </div>
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm px-4 py-4 flex flex-col gap-2 hover:shadow-md transition cursor-pointer kpi-filter-card"
                data-filter="confirmed">
                <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium">Confirmed</p>
                <p class="text-2xl font-bold text-blue-700" id="kpi-confirmed">—</p>
            </div>
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm px-4 py-4 flex flex-col gap-2 hover:shadow-md transition cursor-pointer kpi-filter-card"
                data-filter="in_transit">
                <div class="w-8 h-8 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium">In Transit</p>
                <p class="text-2xl font-bold text-purple-600" id="kpi-in-transit">—</p>
            </div>
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm px-4 py-4 flex flex-col gap-2 hover:shadow-md transition cursor-pointer kpi-filter-card"
                data-filter="completed">
                <div class="w-8 h-8 rounded-xl bg-green-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium">Completed</p>
                <p class="text-2xl font-bold text-green-600" id="kpi-completed">—</p>
            </div>
            <div class="bg-white rounded-2xl border border-red-50 shadow-sm px-4 py-4 flex flex-col gap-2 hover:shadow-md transition cursor-pointer kpi-filter-card"
                data-filter="cancelled">
                <div class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <p class="text-xs text-blue-400 font-medium">Cancelled</p>
                <p class="text-2xl font-bold text-red-400" id="kpi-cancelled">—</p>
            </div>
        </div>


        {{-- ══════════════════════════════════════════════════════
             ROW 2 — BOOKINGS TABLE
        ═══════════════════════════════════════════════════════════ --}}


        <button id="btn-open-new-booking"
            class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Booking
        </button>
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-blue-50 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-900">All Bookings</h3>
                        <p class="text-xs text-blue-400" id="table-subtitle">Click a row to view full details</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <select id="filter-mode"
                        class="text-xs border border-blue-100 rounded-xl px-3 py-2 bg-slate-50 text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="">All Modes</option>
                        <option>Sea Freight</option>
                        <option>Air Freight</option>
                        <option>Land</option>
                    </select>
                    <select id="filter-payment"
                        class="text-xs border border-blue-100 rounded-xl px-3 py-2 bg-slate-50 text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="">All Payments</option>
                        <option>On-Account</option>
                        <option>Prepaid</option>
                        <option>Cash on Delivery</option>
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="bookings-table" class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-blue-50 text-blue-500 text-xs uppercase tracking-wider">
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Booking #</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Shipper</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Consignee</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Route</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Mode</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Amount</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Payment</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Credit/Prepaid</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Status</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Date</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="bookings-tbody" class="divide-y divide-blue-50 text-blue-900"></tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     MODAL — New Booking (single scrollable column)
════════════════════════════════════════════════════════════════ --}}
<div id="new-booking-modal"
    class="fixed inset-0 z-50 flex items-start justify-center bg-blue-950/40 backdrop-blur-sm overflow-y-auto py-6 px-4 hidden"
    role="dialog" aria-modal="true">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto my-auto">

        {{-- Header --}}
        <div
            class="flex items-center justify-between px-6 py-5 border-b border-blue-100 sticky top-0 bg-white rounded-t-2xl z-10">
            <div class="flex items-center gap-3">
                <span class="w-1.5 h-6 bg-orange-400 rounded-full"></span>
                <div>
                    <h2 class="text-base font-bold text-blue-900">New Booking</h2>
                    <p class="text-xs text-blue-400 mt-0.5">Fill in all sections to create a shipment booking</p>
                </div>
            </div>
            <button
                class="modal-close-btn p-2 text-blue-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition"
                data-modal="new-booking-modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="new-booking-form" novalidate class="px-6 py-6 space-y-8">

            {{-- ── SECTION 1: Shipper & Consignee ── --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-5 bg-orange-400 rounded-full"></span>
                    <h3 class="text-xs font-bold text-blue-900 uppercase tracking-wider">Shipper & Consignee</h3>
                </div>
                <div class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Shipper <span
                                    class="text-orange-400">*</span></label>
                            <select name="shipper_id" id="form-shipper"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                                <option value="">Select Shipper</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Consignee <span
                                    class="text-orange-400">*</span></label>
                            <select name="consignee_id" id="form-consignee"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                                <option value="">Select Consignee</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 2: Origin & Destination ── --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-5 bg-orange-400 rounded-full"></span>
                    <h3 class="text-xs font-bold text-blue-900 uppercase tracking-wider">Origin & Destination</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-blue-500 mb-1">Pickup Address (Origin) <span
                                class="text-orange-400">*</span></label>
                        <textarea name="origin_address" rows="2" placeholder="Full pickup address"
                            class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-blue-500 mb-1">Drop-off Address (Destination)
                            <span class="text-orange-400">*</span></label>
                        <textarea name="destination_address" rows="2" placeholder="Full delivery address"
                            class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Mode of Transport <span
                                    class="text-orange-400">*</span></label>
                            <select name="transport_mode"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                                <option value="">Select Mode</option>
                                <option>Sea Freight</option>
                                <option>Air Freight</option>
                                <option>Land</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Route / Lane</label>
                            <input type="text" name="route" placeholder="e.g. Manila → Cebu"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Scheduled Pickup Date <span
                                    class="text-orange-400">*</span></label>
                            <input type="date" name="pickup_date"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Expected Delivery Date <span
                                    class="text-orange-400">*</span></label>
                            <input type="date" name="delivery_date"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 3: Cargo Details ── --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-5 bg-orange-400 rounded-full"></span>
                    <h3 class="text-xs font-bold text-blue-900 uppercase tracking-wider">Cargo Details</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-blue-500 mb-1">Cargo Description <span
                                class="text-orange-400">*</span></label>
                        <textarea name="cargo_description" rows="2" placeholder="Describe the cargo contents"
                            class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Quantity</label>
                            <input type="number" name="cargo_quantity" placeholder="0"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Weight (kg)</label>
                            <input type="number" name="cargo_weight" placeholder="0.00"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Volume (m³)</label>
                            <input type="number" name="cargo_volume" placeholder="0.00"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Pkg Type</label>
                            <select name="cargo_packaging"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                                <option value="">Select</option>
                                <option>Boxes</option>
                                <option>Pallets</option>
                                <option>Drums</option>
                                <option>Bags</option>
                                <option>Crates</option>
                                <option>Loose</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label
                            class="flex items-center gap-2 cursor-pointer bg-slate-50 border border-blue-100 rounded-xl px-4 py-2.5 hover:bg-blue-50 transition">
                            <input type="checkbox" name="is_hazardous" class="accent-orange-500 rounded" />
                            <span class="text-xs font-semibold text-blue-700">Hazardous / Dangerous Goods</span>
                        </label>
                        <label
                            class="flex items-center gap-2 cursor-pointer bg-slate-50 border border-blue-100 rounded-xl px-4 py-2.5 hover:bg-blue-50 transition">
                            <input type="checkbox" name="is_fragile" class="accent-orange-500 rounded" />
                            <span class="text-xs font-semibold text-blue-700">Fragile — Handle with Care</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 4: Vehicle / Driver / Container ── --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-5 bg-orange-400 rounded-full"></span>
                    <h3 class="text-xs font-bold text-blue-900 uppercase tracking-wider">Assigned Vehicle & Driver</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-blue-500 mb-1">Driver Name</label>
                        <input type="text" name="driver_name" placeholder="Full name"
                            class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-blue-500 mb-1">Plate / Vehicle No.</label>
                        <input type="text" name="vehicle_plate" placeholder="e.g. ABC 1234"
                            class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-blue-500 mb-1">Container / Truck Type</label>
                        <select name="vehicle_type"
                            class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                            <option value="">Select Type</option>
                            <option>6-Wheeler Truck</option>
                            <option>10-Wheeler Truck</option>
                            <option>Closed Van</option>
                            <option>Reefer Van</option>
                            <option>20ft Container</option>
                            <option>40ft Container</option>
                            <option>Motorbike</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 5: Billing Details ── --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-5 bg-orange-400 rounded-full"></span>
                    <h3 class="text-xs font-bold text-blue-900 uppercase tracking-wider">Billing Details</h3>
                </div>
                <div class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Freight Charge (₱) <span
                                    class="text-orange-400">*</span></label>
                            <input type="number" name="freight_charge" placeholder="0.00"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Other Charges (₱)</label>
                            <input type="number" name="other_charges" placeholder="0.00"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Total Amount (₱)</label>
                            <input type="number" name="total_amount" id="form-total" placeholder="0.00" readonly
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-700 font-bold bg-blue-50 focus:outline-none cursor-default" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Payment Mode <span
                                    class="text-orange-400">*</span></label>
                            <select name="payment_mode" id="form-payment-mode"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition">
                                <option value="">Select Mode</option>
                                <option value="on-account">On-Account</option>
                                <option value="prepaid">Prepaid</option>
                                <option value="cod">Cash on Delivery</option>
                            </select>
                        </div>
                        <div id="invoice-field">
                            <label class="block text-xs font-semibold text-blue-500 mb-1">Invoice # <span
                                    class="text-blue-300">(if applicable)</span></label>
                            <input type="text" name="invoice_number" placeholder="INV-XXXX"
                                class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 6: Documents ── --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-5 bg-orange-400 rounded-full"></span>
                    <h3 class="text-xs font-bold text-blue-900 uppercase tracking-wider">Documents</h3>
                </div>
                <div class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="doc-checklist">
                        <label
                            class="flex items-center gap-2 cursor-pointer bg-slate-50 border border-blue-100 rounded-xl px-4 py-2.5 hover:bg-blue-50 transition">
                            <input type="checkbox" name="doc_bill_of_lading" class="accent-orange-500 rounded" />
                            <span class="text-xs font-semibold text-blue-700">Bill of Lading</span>
                        </label>
                        <label
                            class="flex items-center gap-2 cursor-pointer bg-slate-50 border border-blue-100 rounded-xl px-4 py-2.5 hover:bg-blue-50 transition">
                            <input type="checkbox" name="doc_invoice" class="accent-orange-500 rounded" />
                            <span class="text-xs font-semibold text-blue-700">Commercial Invoice</span>
                        </label>
                        <label
                            class="flex items-center gap-2 cursor-pointer bg-slate-50 border border-blue-100 rounded-xl px-4 py-2.5 hover:bg-blue-50 transition">
                            <input type="checkbox" name="doc_packing_list" class="accent-orange-500 rounded" />
                            <span class="text-xs font-semibold text-blue-700">Packing List</span>
                        </label>
                        <label
                            class="flex items-center gap-2 cursor-pointer bg-slate-50 border border-blue-100 rounded-xl px-4 py-2.5 hover:bg-blue-50 transition">
                            <input type="checkbox" name="doc_delivery_receipt" class="accent-orange-500 rounded" />
                            <span class="text-xs font-semibold text-blue-700">Delivery Receipt</span>
                        </label>
                        <label
                            class="flex items-center gap-2 cursor-pointer bg-slate-50 border border-blue-100 rounded-xl px-4 py-2.5 hover:bg-blue-50 transition">
                            <input type="checkbox" name="doc_customs" class="accent-orange-500 rounded" />
                            <span class="text-xs font-semibold text-blue-700">Customs Declaration</span>
                        </label>
                        <label
                            class="flex items-center gap-2 cursor-pointer bg-slate-50 border border-blue-100 rounded-xl px-4 py-2.5 hover:bg-blue-50 transition">
                            <input type="checkbox" name="doc_insurance" class="accent-orange-500 rounded" />
                            <span class="text-xs font-semibold text-blue-700">Insurance Certificate</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-blue-500 mb-1">Attach Files <span
                                class="text-blue-300">(optional)</span></label>
                        <div
                            class="border-2 border-dashed border-blue-100 rounded-xl px-4 py-6 text-center hover:border-orange-300 transition cursor-pointer bg-slate-50">
                            <svg class="w-8 h-8 text-blue-200 mx-auto mb-2" fill="none" stroke="currentColor"
                                stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            <p class="text-xs text-blue-400">Drag & drop files here or <span
                                    class="text-orange-400 font-semibold">browse</span></p>
                            <p class="text-xs text-blue-300 mt-1">PDF, PNG, JPG up to 10MB each</p>
                            <input type="file" name="attachments[]" multiple class="hidden" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 7: Remarks ── --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-5 bg-orange-400 rounded-full"></span>
                    <h3 class="text-xs font-bold text-blue-900 uppercase tracking-wider">Remarks & Special Instructions
                    </h3>
                </div>
                <textarea name="remarks" rows="3"
                    placeholder="Any special instructions, handling notes, or additional remarks..."
                    class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 placeholder-blue-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition resize-none"></textarea>
            </div>

        </form>

        {{-- Modal Footer --}}
        <div
            class="px-6 py-5 border-t border-blue-100 flex flex-col sm:flex-row sm:items-center justify-end gap-3 sticky bottom-0 bg-white rounded-b-2xl">
            <button type="button"
                class="modal-close-btn px-5 py-2.5 text-sm font-semibold text-blue-500 bg-blue-50 hover:bg-blue-100 rounded-xl transition w-full sm:w-auto"
                data-modal="new-booking-modal">Cancel</button>
            <button type="button" id="btn-submit-booking"
                class="px-6 py-2.5 text-sm font-semibold text-white bg-orange-500 hover:bg-orange-600 active:bg-orange-700 rounded-xl shadow-sm transition w-full sm:w-auto">Create
                Booking</button>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════════════════════════ --}}
<script>
    (function() {
        'use strict';

        // ══════════════════════════════════════
        // SAMPLE DATA
        // ══════════════════════════════════════
        const SHIPPERS_CONSIGNEES = [{
                id: 1,
                name: 'Layag Freight Solutions',
                type: 'both'
            },
            {
                id: 2,
                name: 'Daloy Trading Co.',
                type: 'consignee'
            },
            {
                id: 3,
                name: 'Agos Manufacturing Inc.',
                type: 'both'
            },
            {
                id: 4,
                name: 'Sigla Logistics PH',
                type: 'shipper'
            },
            {
                id: 5,
                name: 'Kulay Retail Group',
                type: 'consignee'
            },
            {
                id: 6,
                name: 'Bukid Agritech Corp.',
                type: 'both'
            },
        ];

        const BOOKINGS = [{
                id: 1,
                code: 'BK-2025-0001',
                shipper: 'Layag Freight Solutions',
                consignee: 'Daloy Trading Co.',
                route: 'Manila → Cebu',
                mode: 'Sea Freight',
                amount: 45000,
                payment: 'On-Account',
                creditPrepaid: 'Credit',
                status: 'completed',
                date: '2025-03-18',
                remarks: 'Handle with care'
            },
            {
                id: 2,
                code: 'BK-2025-0002',
                shipper: 'Agos Manufacturing Inc.',
                consignee: 'Bukid Agritech Corp.',
                route: 'Davao → Manila',
                mode: 'Air Freight',
                amount: 92000,
                payment: 'Prepaid',
                creditPrepaid: 'Prepaid',
                status: 'in_transit',
                date: '2025-03-19',
                remarks: 'Urgent delivery'
            },
            {
                id: 3,
                code: 'BK-2025-0003',
                shipper: 'Sigla Logistics PH',
                consignee: 'Kulay Retail Group',
                route: 'Manila → Davao',
                mode: 'Land',
                amount: 28500,
                payment: 'On-Account',
                creditPrepaid: 'Credit',
                status: 'pending',
                date: '2025-03-20',
                remarks: ''
            },
            {
                id: 4,
                code: 'BK-2025-0004',
                shipper: 'Daloy Trading Co.',
                consignee: 'Layag Freight Solutions',
                route: 'Iloilo → Manila',
                mode: 'Sea Freight',
                amount: 61000,
                payment: 'COD',
                creditPrepaid: 'COD',
                status: 'confirmed',
                date: '2025-03-20',
                remarks: 'Fragile items inside'
            },
            {
                id: 5,
                code: 'BK-2025-0005',
                shipper: 'Kulay Retail Group',
                consignee: 'Agos Manufacturing',
                route: 'Manila → Iloilo',
                mode: 'Land',
                amount: 19500,
                payment: 'Prepaid',
                creditPrepaid: 'Prepaid',
                status: 'completed',
                date: '2025-03-21',
                remarks: ''
            },
            {
                id: 6,
                code: 'BK-2025-0006',
                shipper: 'Bukid Agritech Corp.',
                consignee: 'Sigla Logistics PH',
                route: 'Cagayan → Cebu',
                mode: 'Sea Freight',
                amount: 33000,
                payment: 'On-Account',
                creditPrepaid: 'Credit',
                status: 'pending',
                date: '2025-03-21',
                remarks: 'Perishable — refrigerate'
            },
            {
                id: 7,
                code: 'BK-2025-0007',
                shipper: 'Layag Freight Solutions',
                consignee: 'Kulay Retail Group',
                route: 'Cebu → Davao',
                mode: 'Air Freight',
                amount: 88000,
                payment: 'Prepaid',
                creditPrepaid: 'Prepaid',
                status: 'confirmed',
                date: '2025-03-22',
                remarks: ''
            },
            {
                id: 8,
                code: 'BK-2025-0008',
                shipper: 'Sigla Logistics PH',
                consignee: 'Daloy Trading Co.',
                route: 'Manila → Zamboanga',
                mode: 'Sea Freight',
                amount: 52000,
                payment: 'On-Account',
                creditPrepaid: 'Credit',
                status: 'in_transit',
                date: '2025-03-22',
                remarks: 'Customs clearance pending'
            },
            {
                id: 9,
                code: 'BK-2025-0009',
                shipper: 'Agos Manufacturing Inc.',
                consignee: 'Bukid Agritech Corp.',
                route: 'Davao → Cebu',
                mode: 'Land',
                amount: 24000,
                payment: 'COD',
                creditPrepaid: 'COD',
                status: 'cancelled',
                date: '2025-03-23',
                remarks: 'Cancelled by client'
            },
            {
                id: 10,
                code: 'BK-2025-0010',
                shipper: 'Kulay Retail Group',
                consignee: 'Layag Freight Solutions',
                route: 'Cebu → Manila',
                mode: 'Sea Freight',
                amount: 71500,
                payment: 'Prepaid',
                creditPrepaid: 'Prepaid',
                status: 'in_transit',
                date: '2025-03-24',
                remarks: ''
            },
            {
                id: 11,
                code: 'BK-2025-0011',
                shipper: 'Bukid Agritech Corp.',
                consignee: 'Daloy Trading Co.',
                route: 'Iloilo → Davao',
                mode: 'Land',
                amount: 17000,
                payment: 'On-Account',
                creditPrepaid: 'Credit',
                status: 'confirmed',
                date: '2025-03-25',
                remarks: 'Standard delivery'
            },
            {
                id: 12,
                code: 'BK-2025-0012',
                shipper: 'Layag Freight Solutions',
                consignee: 'Agos Manufacturing',
                route: 'Manila → Cebu',
                mode: 'Sea Freight',
                amount: 55000,
                payment: 'Prepaid',
                creditPrepaid: 'Prepaid',
                status: 'pending',
                date: '2025-03-26',
                remarks: ''
            },
        ];

        // ══════════════════════════════════════
        // STATUS CONFIG
        // ══════════════════════════════════════
        const STATUS_CFG = {
            pending: {
                label: 'Pending',
                cls: 'bg-amber-100 text-amber-600',
                dot: 'bg-amber-400'
            },
            confirmed: {
                label: 'Confirmed',
                cls: 'bg-blue-100 text-blue-700',
                dot: 'bg-blue-500'
            },
            in_transit: {
                label: 'In Transit',
                cls: 'bg-purple-100 text-purple-700',
                dot: 'bg-purple-500'
            },
            completed: {
                label: 'Completed',
                cls: 'bg-green-100 text-green-700',
                dot: 'bg-green-500'
            },
            cancelled: {
                label: 'Cancelled',
                cls: 'bg-red-100 text-red-500',
                dot: 'bg-red-400'
            },
        };

        function statusBadge(status) {
            const cfg = STATUS_CFG[status] || {
                label: status,
                cls: 'bg-slate-100 text-slate-500',
                dot: 'bg-slate-400'
            };
            return `<span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full ${cfg.cls}">
                    <span class="w-1.5 h-1.5 rounded-full ${cfg.dot}"></span>${cfg.label}
                </span>`;
        }

        // ══════════════════════════════════════
        // KPI CARDS
        // ══════════════════════════════════════
        function renderKPIs(data) {
            document.getElementById('kpi-all').textContent = data.length;
            document.getElementById('kpi-pending').textContent = data.filter(b => b.status === 'pending').length;
            document.getElementById('kpi-confirmed').textContent = data.filter(b => b.status === 'confirmed')
                .length;
            document.getElementById('kpi-in-transit').textContent = data.filter(b => b.status === 'in_transit')
                .length;
            document.getElementById('kpi-completed').textContent = data.filter(b => b.status === 'completed')
                .length;
            document.getElementById('kpi-cancelled').textContent = data.filter(b => b.status === 'cancelled')
                .length;
        }

        // KPI card filter click
        let activeStatusFilter = 'all';
        document.querySelectorAll('.kpi-filter-card').forEach(card => {
            card.addEventListener('click', function() {
                activeStatusFilter = this.dataset.filter;
                document.querySelectorAll('.kpi-filter-card').forEach(c => c.classList.remove(
                    'ring-2', 'ring-orange-400'));
                this.classList.add('ring-2', 'ring-orange-400');
                renderBookingsTable(activeStatusFilter);
                console.log('[Bookings] KPI filter clicked:', activeStatusFilter);
            });
        });

        // ══════════════════════════════════════
        // BOOKINGS TABLE
        // ══════════════════════════════════════
        function renderBookingsTable(statusFilter = 'all') {
            const tbody = document.getElementById('bookings-tbody');
            tbody.innerHTML = '';
            const modeFilter = document.getElementById('filter-mode').value;
            const paymentFilter = document.getElementById('filter-payment').value;

            let data = BOOKINGS;
            if (statusFilter !== 'all') data = data.filter(b => b.status === statusFilter);
            if (modeFilter) data = data.filter(b => b.mode === modeFilter);
            if (paymentFilter) data = data.filter(b => b.payment === paymentFilter);

            data.forEach(b => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-orange-50 transition-colors duration-100 cursor-pointer';
                tr.dataset.id = b.id;
                tr.innerHTML = `
                <td class="px-4 py-3 whitespace-nowrap font-mono text-xs font-bold text-blue-400">${b.code}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-blue-900">${b.shipper}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-blue-600">${b.consignee}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-blue-500">${b.route}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                        ${b.mode === 'Sea Freight' ? 'bg-blue-100 text-blue-700' : b.mode === 'Air Freight' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-600'}">
                        ${b.mode}
                    </span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-blue-900">₱${b.amount.toLocaleString()}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-blue-500">${b.payment}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                        ${b.creditPrepaid === 'Credit' ? 'bg-orange-100 text-orange-600' : b.creditPrepaid === 'Prepaid' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500'}">
                        ${b.creditPrepaid}
                    </span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">${statusBadge(b.status)}</td>
                <td class="px-4 py-3 whitespace-nowrap text-xs text-blue-400">${b.date}</td>
                <td class="px-4 py-3 text-xs text-blue-400 max-w-[160px] truncate">${b.remarks || '—'}</td>
            `;
                tr.addEventListener('click', () => {
                    console.log('[Bookings] Row clicked — ID:', b.id, 'Code:', b.code);
                    openDetailPage(b.id);
                });
                tbody.appendChild(tr);
            });

            if (typeof window.initDataTables === 'function') window.initDataTables();
        }

        document.getElementById('filter-mode').addEventListener('change', () => renderBookingsTable(
            activeStatusFilter));
        document.getElementById('filter-payment').addEventListener('change', () => renderBookingsTable(
            activeStatusFilter));

        // ══════════════════════════════════════
        // DETAIL PAGE (inline SPA navigation)
        // ══════════════════════════════════════
        function openDetailPage(id) {
            const booking = BOOKINGS.find(b => b.id === id);
            if (!booking) return;
            // In a real SPA: window.router.push(`/bookings/${id}`)
            // For now — emit a custom event that the SPA shell can listen to
            document.dispatchEvent(new CustomEvent('spa:navigate', {
                detail: {
                    page: 'booking-detail',
                    id
                }
            }));
            console.log('[Bookings] Navigating to detail page — ID:', id);
        }

        // ══════════════════════════════════════
        // POPULATE FORM DROPDOWNS
        // ══════════════════════════════════════
        function populateDropdowns() {
            const shipperSel = document.getElementById('form-shipper');
            const consigneeSel = document.getElementById('form-consignee');
            SHIPPERS_CONSIGNEES.filter(s => ['both', 'shipper'].includes(s.type)).forEach(s => {
                shipperSel.innerHTML += `<option value="${s.id}">${s.name}</option>`;
            });
            SHIPPERS_CONSIGNEES.filter(s => ['both', 'consignee'].includes(s.type)).forEach(s => {
                consigneeSel.innerHTML += `<option value="${s.id}">${s.name}</option>`;
            });
        }

        // Auto-compute total
        ['freight_charge', 'other_charges'].forEach(name => {
            document.querySelector(`[name="${name}"]`)?.addEventListener('input', function() {
                const freight = parseFloat(document.querySelector('[name="freight_charge"]')
                    ?.value) || 0;
                const other = parseFloat(document.querySelector('[name="other_charges"]')?.value) ||
                    0;
                document.getElementById('form-total').value = (freight + other).toFixed(2);
            });
        });

        // ══════════════════════════════════════
        // MODAL OPEN / CLOSE
        // ══════════════════════════════════════
        function openModal(id) {
            const m = document.getElementById(id);
            if (!m) return;
            m.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            console.log('[Bookings] Modal opened:', id);
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            if (!m) return;
            m.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            console.log('[Bookings] Modal closed:', id);
        }

        document.getElementById('btn-open-new-booking').addEventListener('click', () => openModal(
            'new-booking-modal'));
        document.querySelectorAll('.modal-close-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                closeModal(this.dataset.modal);
            });
        });
        document.getElementById('new-booking-modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal('new-booking-modal');
        });

        // ══════════════════════════════════════
        // FORM SUBMISSION
        // ══════════════════════════════════════
        document.getElementById('btn-submit-booking').addEventListener('click', function() {
            const form = document.getElementById('new-booking-form');
            const data = new FormData(form);
            const payload = {};
            data.forEach((value, key) => {
                payload[key] = value;
            });
            console.log('[Bookings] New booking submitted — payload:', payload);
            // TODO: axios.post('/api/bookings', payload)
            closeModal('new-booking-modal');
            form.reset();
            document.getElementById('form-total').value = '';
        });

        // ══════════════════════════════════════
        // DATATABLE INIT
        // ══════════════════════════════════════
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
                        dom: "<'dt-top'f><'dt-wrapper't><'dt-bottom'i p>",
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

        // ══════════════════════════════════════
        // BOOT
        // ══════════════════════════════════════
        function boot() {
            renderKPIs(BOOKINGS);
            populateDropdowns();
            renderBookingsTable('all');
            if (typeof $ !== 'undefined' && $.fn?.DataTable) window.initDataTables();
            console.log('[Bookings] Page initialized.');
        }
        document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', boot) : boot();

    })();
</script>
