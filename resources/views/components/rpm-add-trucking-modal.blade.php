{{--
    Standalone "Add Trucking" popup - opened from the Request for Proposal
    wizard's own Products tab (Truckings table's own "+ Add Trucking"
    button). No "select first" gating. Saving POSTs to
    .../proposalRequests/{id}/products/truckings (see saveRpmTrucking())
    and appends the row to the table on the tab behind it, then closes.
--}}
<x-modal id="RpmAddTruckingModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Trucking</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
        <div id="rpmTruckingForm" class="grid grid-cols-1 gap-3">
            <div>
                <label for="rpmTrTruckingCargoType" class="text-[11px] text-zinc-400 uppercase">Trucking Cargo Type</label>
                <select id="rpmTrTruckingCargoType" data-field="trucking_cargo_type"
                    class="rpmTruckingCargoTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select Cargo Type</option>
                </select>
            </div>
            <div>
                <label for="rpmTrDispatchMode" class="text-[11px] text-zinc-400 uppercase">Dispatch Mode</label>
                <select id="rpmTrDispatchMode" data-field="dispatch_mode"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    <option value="single">Single</option>
                    <option value="tandem">Tandem</option>
                </select>
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="rpmTrQty" class="text-[11px] text-zinc-400 uppercase">Quantity (No. of Units) <span class="req-asterisk">*</span></label>
                <input type="text" inputmode="numeric" id="rpmTrQty" data-field="quantity"
                    class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>

            {{-- ROUTE - two-column, arrow-connected (no ports for Trucking) --}}
            <div class="space-y-1.5">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Route</p>
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-4">
                    <div class="flex flex-col md:flex-row items-stretch md:items-start gap-4">
                        <div class="flex-1">
                            <label for="rpmTrOrigin" class="text-[11px] text-zinc-400 uppercase">Origin</label>
                            <select id="rpmTrOrigin" data-field="origin_prospect_location_id"
                                class="rpmOdOriginDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                <option value="">Select Origin</option>
                            </select>
                        </div>
                        <div class="shrink-0 flex items-center justify-center py-1 md:py-0 md:pt-6">
                            <span class="inline-block rotate-90 md:rotate-0 text-orange-500 font-bold text-xl leading-none">&rarr;</span>
                        </div>
                        <div class="flex-1">
                            <label for="rpmTrDestination" class="text-[11px] text-zinc-400 uppercase">Destination</label>
                            <select id="rpmTrDestination" data-field="destination_prospect_location_id"
                                class="rpmOdDestinationDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                <option value="">Select Destination</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label for="rpmTrCargoType" class="text-[11px] text-zinc-400 uppercase">Cargo Type</label>
                <select id="rpmTrCargoType" data-field="cargo_type"
                    class="rpmCargoTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select Cargo Type</option>
                </select>
            </div>
            <div>
                <label for="rpmTrCargoDescription" class="text-[11px] text-zinc-400 uppercase">Cargo Description</label>
                <input type="text" id="rpmTrCargoDescription" data-field="cargo_description"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="rpmAtmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="rpmAtmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium disabled:opacity-40 disabled:cursor-not-allowed">
                + Add
            </button>
        </div>
    </div>
</x-modal>
