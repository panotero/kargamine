<div class=" max-h-[80vh] overflow-auto">
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
        <button class="modal-close-btn p-2 text-blue-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition"
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
                    <label class="block text-xs font-semibold text-blue-500 mb-1">Drop-off Address
                        (Destination)
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
                        <label class="block text-xs font-semibold text-blue-500 mb-1">Scheduled Pickup Date
                            <span class="text-orange-400">*</span></label>
                        <input type="date" name="pickup_date"
                            class="w-full border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-900 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-300 transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-blue-500 mb-1">Expected Delivery Date
                            <span class="text-orange-400">*</span></label>
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
                <h3 class="text-xs font-bold text-blue-900 uppercase tracking-wider">Assigned Vehicle & Driver
                </h3>
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
                    <label class="block text-xs font-semibold text-blue-500 mb-1">Container / Truck
                        Type</label>
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
                <h3 class="text-xs font-bold text-blue-900 uppercase tracking-wider">Remarks & Special
                    Instructions
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
