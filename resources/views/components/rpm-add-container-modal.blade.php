{{--
    Standalone "Add Container" popup - opened from the Request for Proposal
    wizard's own Products tab (Containers table's own "+ Add Container"
    button). Mirrors <x-pm-add-freight-modal />'s "select Container Type
    first" gating exactly (#rpmPcRestOfForm/#rpmPcLockedHint, toggled by
    applyPcContainerTypeVisibility() in logic_prospect_request_proposal.js) -
    the rest of the form stays hidden until a Container Type is picked.
    Saving POSTs to .../proposalRequests/{id}/products/containers (see
    saveRpmContainer()) and appends the row to the table on the tab behind
    it, then closes.
--}}
<x-modal id="RpmAddContainerModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Container</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
        <div id="rpmContainerForm" class="grid grid-cols-1 gap-3">
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="rpmPcContainerType" class="text-[11px] text-zinc-400 uppercase">Container Type <span class="req-asterisk">*</span></label>
                <select id="rpmPcContainerType" data-field="container_type"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select Type</option>
                    <option value="CV">Container Van (CV)</option>
                    <option value="FR">Flatrack (FR)</option>
                    <option value="RF">Reefer Van (RF)</option>
                </select>
            </div>
            <p id="rpmPcLockedHint" class="text-xs text-zinc-400 dark:text-zinc-500">
                Select a Container Type to fill out the rest of this form.
            </p>

            {{-- Hidden until a Container Type is picked - see
                 applyPcContainerTypeVisibility() in
                 logic_prospect_request_proposal.js. --}}
            <div id="rpmPcRestOfForm" class="hidden space-y-3">
                <div>
                    <label for="rpmPcContainerSize" class="text-[11px] text-zinc-400 uppercase">Size</label>
                    <select id="rpmPcContainerSize" data-field="container_size_id"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                        <option value="">Select Size</option>
                    </select>
                </div>
                <div class="rpm-field-min-temp hidden">
                    <label for="rpmPcMinTemp" class="text-[11px] text-zinc-400 uppercase">Minimum Temperature (&deg;C)</label>
                    <input type="number" step="0.1" id="rpmPcMinTemp" data-field="minimum_temperature"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label for="rpmPcDeliveryType" class="text-[11px] text-zinc-400 uppercase">Service Type</label>
                    <select id="rpmPcDeliveryType" data-field="service_type"
                        class="rpmDeliveryTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                        <option value="">Select Service Type</option>
                    </select>
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="rpmPcQty" class="text-[11px] text-zinc-400 uppercase">Quantity (No. of Containers) <span class="req-asterisk">*</span></label>
                    <input type="text" inputmode="numeric" id="rpmPcQty" data-field="quantity"
                        class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                </div>

                {{-- ROUTE - two-column, arrow-connected (see VISUALS.md) --}}
                <div class="space-y-1.5">
                    <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Route</p>
                    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-4">
                        <div class="flex flex-col md:flex-row items-stretch md:items-start gap-4">
                            <div class="flex-1 space-y-3">
                                <div>
                                    <label for="rpmPcOrigin" class="text-[11px] text-zinc-400 uppercase">Origin</label>
                                    <select id="rpmPcOrigin" data-field="origin_prospect_location_id"
                                        class="rpmOdOriginDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                        <option value="">Select Origin</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="rpmPcOriginPort" class="text-[11px] text-zinc-400 uppercase">Port of Origin</label>
                                    <select id="rpmPcOriginPort" data-field="origin_port_id"
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
                                    <label for="rpmPcDestination" class="text-[11px] text-zinc-400 uppercase">Destination</label>
                                    <select id="rpmPcDestination" data-field="destination_prospect_location_id"
                                        class="rpmOdDestinationDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                        <option value="">Select Destination</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="rpmPcDestinationPort" class="text-[11px] text-zinc-400 uppercase">Port of Destination</label>
                                    <select id="rpmPcDestinationPort" data-field="destination_port_id"
                                        class="rpmPortDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                        <option value="">Select Port</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="rpmPcCargoType" class="text-[11px] text-zinc-400 uppercase">Cargo Type</label>
                    <select id="rpmPcCargoType" data-field="cargo_type"
                        class="rpmCargoTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                        <option value="">Select Cargo Type</option>
                    </select>
                </div>
                <div>
                    <label for="rpmPcCargoDescription" class="text-[11px] text-zinc-400 uppercase">Cargo Description</label>
                    <input type="text" id="rpmPcCargoDescription" data-field="cargo_description"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label for="rpmPcDispatchOrigin" class="text-[11px] text-zinc-400 uppercase">Dispatch Mode (Origin)</label>
                    <select id="rpmPcDispatchOrigin" data-field="dispatch_mode_origin"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm disabled:opacity-40">
                        <option value="single">Single</option>
                        <option value="tandem">Tandem</option>
                    </select>
                </div>
                <div>
                    <label for="rpmPcDispatchDestination" class="text-[11px] text-zinc-400 uppercase">Dispatch Mode (Destination)</label>
                    <select id="rpmPcDispatchDestination" data-field="dispatch_mode_destination"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm disabled:opacity-40">
                        <option value="single">Single</option>
                        <option value="tandem">Tandem</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="rpmAcmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="rpmAcmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium disabled:opacity-40 disabled:cursor-not-allowed">
                + Add
            </button>
        </div>
    </div>
</x-modal>
