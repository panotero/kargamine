{{--
    Standalone "Add Trucking" popup opened from ProspectInfoModal's own
    Requirements tab (Truckings table's own "+ Add Trucking" button).
    Targets its own `arm*` field ids - see pim-add-freight-modal.blade.php's
    header comment for why. Immediate/single-row append - saving POSTs
    straight to POST .../prospects/{uuid}/proposalRequest/truckings (see
    savePimTrucking() in logic_prospect_add_modals.js), reloads this
    prospect's data, then closes.
--}}
<x-modal id="PimAddTruckingModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Trucking</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
        <div id="armTruckingForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armTruckingCargoType" class="text-[11px] text-zinc-400 uppercase">Trucking Cargo Type <span class="req-asterisk">*</span></label>
                    <select id="armTruckingCargoType" data-field="trucking_cargo_type"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Type</option>
                    </select>
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armTruckingQuantity" class="text-[11px] text-zinc-400 uppercase">Quantity <span class="req-asterisk">*</span></label>
                    <input type="text" inputmode="numeric" id="armTruckingQuantity" data-field="quantity"
                        class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                </div>
                <div>
                    <label for="armTruckingFrequency" class="text-[11px] text-zinc-400 uppercase">Frequency</label>
                    <select id="armTruckingFrequency" data-field="frequency"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">-</option>
                        <option value="daily">Daily</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                    </select>
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armDispatchMode" class="text-[11px] text-zinc-400 uppercase">Dispatch Mode <span class="req-asterisk">*</span></label>
                    <select id="armDispatchMode" data-field="dispatch_mode"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Mode</option>
                        <option value="single">Single</option>
                        <option value="tandem">Tandem</option>
                    </select>
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armTruckingOrigin" class="text-[11px] text-zinc-400 uppercase">Origin <span class="req-asterisk">*</span></label>
                    <select id="armTruckingOrigin" data-field="origin_location_id"
                        class="armTruckingOriginSelect w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Location</option>
                    </select>
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armTruckingDestination" class="text-[11px] text-zinc-400 uppercase">Destination <span class="req-asterisk">*</span></label>
                    <select id="armTruckingDestination" data-field="destination_location_id"
                        class="armTruckingDestinationSelect w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Location</option>
                    </select>
                </div>
                <div>
                    <label for="armTruckingDeclaredValue" class="text-[11px] text-zinc-400 uppercase">Declared Value per Unit</label>
                    <input type="text" inputmode="decimal" id="armTruckingDeclaredValue" data-field="declared_value_per_unit"
                        class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                </div>
                <div>
                    <label for="armTruckingWeight" class="text-[11px] text-zinc-400 uppercase">Weight</label>
                    <div class="flex gap-2">
                        <input type="text" inputmode="decimal" id="armTruckingWeight" data-field="weight"
                            class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <select id="armTruckingWeightUnit" data-field="weight_unit"
                            class="w-28 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Unit</option>
                            <option value="kg">Kilogram</option>
                            <option value="mt">Metric Ton</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="armTruckingCargoTypeGeneral" class="text-[11px] text-zinc-400 uppercase">Cargo Type</label>
                    <select id="armTruckingCargoTypeGeneral" data-field="cargo_type"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Cargo Type</option>
                    </select>
                </div>
                <div class="md:col-span-3 border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armTruckingCargoDescription" class="text-[11px] text-zinc-400 uppercase">Cargo Description <span class="req-asterisk">*</span></label>
                    <textarea id="armTruckingCargoDescription" data-field="general_cargo_description" rows="2"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
                </div>
                <div class="md:col-span-3">
                    <label for="armTruckingSpecialRequirements" class="text-[11px] text-zinc-400 uppercase">Special Requirements</label>
                    <textarea id="armTruckingSpecialRequirements" data-field="special_requirements" rows="2"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="pimAtmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="pimAtmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                + Add Requirement
            </button>
        </div>
    </div>
</x-modal>
