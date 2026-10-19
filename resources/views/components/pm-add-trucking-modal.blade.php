{{--
    Standalone "Add Trucking" popup opened from ProspectModal's own
    Requirements tab (Truckings table's own "+ Add Trucking" button).
    Immediate/single-row append - saving POSTs straight to
    POST .../prospects/{uuid}/proposalRequest/truckings (see
    addTruckingRow() in logic_prospect_modal.js) and splices the returned
    row into the Truckings table on the main tab, then closes.
--}}
<x-modal id="PmAddTruckingModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Trucking</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
        <div id="pmTruckingForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="pmTruckingCargoType" class="text-[11px] text-zinc-400 uppercase">Trucking
                        Cargo Type <span class="req-asterisk">*</span></label>
                    <select id="pmTruckingCargoType" data-field="trucking_cargo_type"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Type</option>
                    </select>
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="pmTruckingQuantity" class="text-[11px] text-zinc-400 uppercase">Quantity
                        <span class="req-asterisk">*</span></label>
                    <input type="text" inputmode="numeric" id="pmTruckingQuantity" data-field="quantity"
                        class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                </div>
                <div>
                    <label for="pmTruckingFrequency"
                        class="text-[11px] text-zinc-400 uppercase">Frequency</label>
                    <select id="pmTruckingFrequency" data-field="frequency"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">-</option>
                        <option value="daily">Daily</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                    </select>
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="pmDispatchMode" class="text-[11px] text-zinc-400 uppercase"
                        title="Single = one truck. Tandem = two trucks working together, for heavier/oversized loads.">Dispatch Mode
                        <span class="req-asterisk">*</span> <span class="text-zinc-300 dark:text-zinc-600">ⓘ</span></label>
                    <select id="pmDispatchMode" data-field="dispatch_mode"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Mode</option>
                        <option value="single">Single</option>
                        <option value="tandem">Tandem</option>
                    </select>
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="pmTruckingOrigin" class="text-[11px] text-zinc-400 uppercase">Origin <span
                            class="req-asterisk">*</span></label>
                    <select id="pmTruckingOrigin" data-field="origin_location_id"
                        class="pmTruckingOriginSelect w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Location</option>
                    </select>
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="pmTruckingDestination"
                        class="text-[11px] text-zinc-400 uppercase">Destination <span
                            class="req-asterisk">*</span></label>
                    <select id="pmTruckingDestination" data-field="destination_location_id"
                        class="pmTruckingDestinationSelect w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Location</option>
                    </select>
                </div>
                <div>
                    <label for="pmTruckingDeclaredValue"
                        class="text-[11px] text-zinc-400 uppercase">Declared Value per Unit</label>
                    <input type="text" inputmode="decimal" id="pmTruckingDeclaredValue"
                        data-field="declared_value_per_unit"
                        class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                </div>
                <div>
                    <label class="text-[11px] text-zinc-400 uppercase">Weight</label>
                    <div class="flex gap-2">
                        <input type="text" inputmode="decimal" id="pmTruckingWeight" data-field="weight"
                            class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <select id="pmTruckingWeightUnit" data-field="weight_unit"
                            class="w-28 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Unit</option>
                            <option value="kg">Kilogram</option>
                            <option value="mt">Metric Ton</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="pmTruckingCargoTypeGeneral"
                        class="text-[11px] text-zinc-400 uppercase">General Cargo Type</label>
                    <select id="pmTruckingCargoTypeGeneral" data-field="cargo_type"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Cargo Type</option>
                    </select>
                </div>
                <div class="md:col-span-3 border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="pmTruckingCargoDescription"
                        class="text-[11px] text-zinc-400 uppercase">Cargo Description <span
                            class="req-asterisk">*</span></label>
                    <textarea id="pmTruckingCargoDescription" data-field="general_cargo_description" rows="2"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
                </div>
                <div class="md:col-span-3">
                    <label for="pmTruckingSpecialRequirements"
                        class="text-[11px] text-zinc-400 uppercase">Special Requirements</label>
                    <textarea id="pmTruckingSpecialRequirements" data-field="special_requirements" rows="2"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="pmAtmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="pmAtmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                + Add Requirement
            </button>
        </div>
    </div>
</x-modal>
