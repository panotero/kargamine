{{--
    Standalone "Add Break Bulk Cargo (BB)" popup - opened from the Request
    for Proposal wizard's own Products tab (Break Bulk Cargo table's own
    "+ Add Break Bulk Cargo" button). Same field set as Rolling Cargo, no
    "select first" gating. Saving POSTs to
    .../proposalRequests/{id}/products/looseCargo (see saveRpmLooseCargo())
    and appends the row to the table on the tab behind it, then closes.
--}}
<x-modal id="RpmAddLooseCargoModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Break Bulk Cargo (BB)</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
        <div id="rpmLooseCargoForm" class="grid grid-cols-1 gap-3">
            <div>
                <label for="rpmLcCargoType" class="text-[11px] text-zinc-400 uppercase">Cargo Type</label>
                <select id="rpmLcCargoType" data-field="cargo_type"
                    class="rpmCargoTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select Cargo Type</option>
                </select>
            </div>
            <div>
                <label for="rpmLcCargoDetails" class="text-[11px] text-zinc-400 uppercase">Cargo Details</label>
                <input type="text" id="rpmLcCargoDetails" data-field="cargo_details"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label for="rpmLcQuantity" class="text-[11px] text-zinc-400 uppercase">Quantity</label>
                <input type="text" inputmode="numeric" id="rpmLcQuantity" data-field="cargo_quantity"
                    class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label for="rpmLcUnits" class="text-[11px] text-zinc-400 uppercase">Units</label>
                <select id="rpmLcUnits" data-field="cargo_units"
                    class="rpmUnitDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select Unit</option>
                </select>
            </div>
            <div>
                <label for="rpmLcRevenueTonUnit" class="text-[11px] text-zinc-400 uppercase">Revenue Ton (RT) Unit</label>
                <select id="rpmLcRevenueTonUnit" data-field="revenue_ton_unit"
                    class="rpmRtUnitDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm"></select>
            </div>
            <div>
                <label for="rpmLcRevenueTon" class="text-[11px] text-zinc-400 uppercase">Revenue Ton (RT)</label>
                <input type="text" inputmode="decimal" id="rpmLcRevenueTon" data-field="revenue_ton"
                    class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label for="rpmLcMeasurement" class="text-[11px] text-zinc-400 uppercase">Cargo Measurement</label>
                <input type="text" id="rpmLcMeasurement" data-field="cargo_measurement"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label for="rpmLcDeliveryType" class="text-[11px] text-zinc-400 uppercase">Service Type</label>
                <select id="rpmLcDeliveryType" data-field="service_type"
                    class="rpmDeliveryTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select Service Type</option>
                </select>
            </div>
            {{-- ROUTE - two-column, arrow-connected (see VISUALS.md) --}}
            <div class="space-y-1.5">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Route</p>
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-4">
                    <div class="flex flex-col md:flex-row items-stretch md:items-start gap-4">
                        <div class="flex-1 space-y-3">
                            <div>
                                <label for="rpmLcOrigin" class="text-[11px] text-zinc-400 uppercase">Origin</label>
                                <select id="rpmLcOrigin" data-field="origin_prospect_location_id"
                                    class="rpmOdOriginDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                    <option value="">Select Origin</option>
                                </select>
                            </div>
                            <div>
                                <label for="rpmLcOriginPort" class="text-[11px] text-zinc-400 uppercase">Port of Origin</label>
                                <select id="rpmLcOriginPort" data-field="origin_port_id"
                                    class="rpmPortDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                    <option value="">Select Port</option>
                                </select>
                            </div>
                        </div>
                        <div class="shrink-0 flex items-center justify-center py-1 md:py-0 md:pt-7">
                            <span class="inline-block rotate-90 md:rotate-0 text-orange-500 font-bold text-xl leading-none">&rarr;</span>
                        </div>
                        <div class="flex-1 space-y-3">
                            <div>
                                <label for="rpmLcDestination" class="text-[11px] text-zinc-400 uppercase">Destination</label>
                                <select id="rpmLcDestination" data-field="destination_prospect_location_id"
                                    class="rpmOdDestinationDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                    <option value="">Select Destination</option>
                                </select>
                            </div>
                            <div>
                                <label for="rpmLcDestinationPort" class="text-[11px] text-zinc-400 uppercase">Port of Destination</label>
                                <select id="rpmLcDestinationPort" data-field="destination_port_id"
                                    class="rpmPortDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                    <option value="">Select Port</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="rpmAlcmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="rpmAlcmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium disabled:opacity-40 disabled:cursor-not-allowed">
                + Add
            </button>
        </div>
    </div>
</x-modal>
