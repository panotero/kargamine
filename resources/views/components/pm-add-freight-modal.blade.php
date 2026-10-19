{{--
    Standalone "Add Freight" popup opened from ProspectModal's own
    Requirements tab (Freights table's own "+ Add Freight" button). Unlike
    Locations/Contacts, this append is already immediate/single-row - saving
    here POSTs straight to POST .../prospects/{uuid}/proposalRequest/containers
    (see addContainerRow() in logic_prospect_modal.js) and splices the
    returned row into the Freights table on the main tab, then closes.
--}}
<x-modal id="PmAddFreightModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Freight</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
        <div id="pmContainerForm" class="space-y-4">
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <p
                        class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">
                        Container &amp; Quantity</p>
                </div>

                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="pmContainerType" class="text-[11px] text-zinc-400 uppercase">Container
                        Type <span class="req-asterisk">*</span></label>
                    <select id="pmContainerType" data-field="container_type"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Type</option>
                    </select>
                </div>
                <p id="pmFreightLockedHint" class="text-xs text-zinc-400 dark:text-zinc-500">
                    Select a Container Type to fill out the rest of this form.
                </p>
            </div>

            {{-- Hidden until a Container Type is picked - see
                 applyContainerTypeVisibility() in logic_prospect_modal.js.
                 The .field-convan-size/.field-temperature/.field-revenue-ton
                 toggles inside here still further narrow by the SPECIFIC
                 type picked. --}}
            <div id="pmFreightRestOfForm" class="hidden space-y-4">
                <div class="grid grid-cols-1 gap-3">
                    <div
                        class="field-convan-size hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="pmContainerSize" class="text-[11px] text-zinc-400 uppercase">Size <span
                                class="req-asterisk">*</span></label>
                        <select id="pmContainerSize" data-field="container_size_id"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select Size</option>
                        </select>
                    </div>
                    <div
                        class="field-temperature hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="pmMinTemp" class="text-[11px] text-zinc-400 uppercase">Minimum Temperature
                            (&deg;C) <span class="req-asterisk">*</span></label>
                        <input type="number" step="0.1" id="pmMinTemp" data-field="minimum_temperature"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div
                        class="field-revenue-ton hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="pmRevenueTonUnit" class="text-[11px] text-zinc-400 uppercase"
                            title="Billing unit for cargo with no fixed container size - whichever is greater, weight or space taken up.">Revenue Ton (RT)
                            Unit <span class="req-asterisk">*</span> <span class="text-zinc-300 dark:text-zinc-600">ⓘ</span></label>
                        <select id="pmRevenueTonUnit" data-field="revenue_ton_unit"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select Unit</option>
                            <option value="CBM">CBM (Cubic Meter)</option>
                            <option value="MT">MT (Metric Ton)</option>
                        </select>
                    </div>
                    <div
                        class="field-revenue-ton hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="pmCargoMeasurement" class="text-[11px] text-zinc-400 uppercase">Cargo
                            Measurement</label>
                        <input type="text" id="pmCargoMeasurement" data-field="cargo_measurement"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="pmContainerQuantity" class="text-[11px] text-zinc-400 uppercase">Quantity
                            <span class="req-asterisk">*</span></label>
                        <input type="text" inputmode="numeric" id="pmContainerQuantity" data-field="quantity"
                            class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="pmContainerFrequency"
                            class="text-[11px] text-zinc-400 uppercase">Frequency</label>
                        <select id="pmContainerFrequency" data-field="frequency"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">-</option>
                            <option value="daily">Daily</option>
                            <option value="Weekly">Weekly</option>
                            <option value="Monthly">Monthly</option>
                        </select>
                    </div>
                </div>

                <div
                    class="grid grid-cols-1 gap-3 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                    <div>
                        <p
                            class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">
                            Service &amp; Route</p>
                    </div>
                    <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="pmDeliveryType" class="text-[11px] text-zinc-400 uppercase"
                            title="Door = picked up/delivered at the client's own address. Pier = handled only at the port.">Service Type
                            <span class="req-asterisk">*</span> <span class="text-zinc-300 dark:text-zinc-600">ⓘ</span></label>
                        <select id="pmDeliveryType" data-field="service_type"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select Service Type</option>
                        </select>
                    </div>
                    <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="pmOriginLocation" class="text-[11px] text-zinc-400 uppercase">Origin <span
                                class="req-asterisk">*</span></label>
                        <select id="pmOriginLocation" data-field="origin_location_id"
                            class="pmOriginLocationSelect w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select Location</option>
                        </select>
                    </div>
                    <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="pmDestinationLocation"
                            class="text-[11px] text-zinc-400 uppercase">Destination <span
                                class="req-asterisk">*</span></label>
                        <select id="pmDestinationLocation" data-field="destination_location_id"
                            class="pmDestinationLocationSelect w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select Location</option>
                        </select>
                    </div>
                </div>

                <div
                    class="grid grid-cols-1 gap-3 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                    <div>
                        <p
                            class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">
                            Cargo Details</p>
                    </div>
                    <div>
                        <label for="pmCargoType" class="text-[11px] text-zinc-400 uppercase">Cargo
                            Type</label>
                        <select id="pmCargoType" data-field="cargo_type"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select Cargo Type</option>
                        </select>
                    </div>
                    <div>
                        <label for="pmDeclaredValue" class="text-[11px] text-zinc-400 uppercase">Declared
                            Value per Unit</label>
                        <input type="text" inputmode="decimal" id="pmDeclaredValue"
                            data-field="declared_value_per_unit"
                            class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div>
                        <label for="pmWeight" class="text-[11px] text-zinc-400 uppercase">Weight</label>
                        <div class="flex gap-2">
                            <input type="text" inputmode="decimal" id="pmWeight" data-field="weight"
                                class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <select id="pmWeightUnit" data-field="weight_unit"
                                class="w-28 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                <option value="">Unit</option>
                                <option value="kg">Kilogram</option>
                                <option value="mt">Metric Ton</option>
                            </select>
                        </div>
                    </div>
                    <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="pmGeneralCargoDescription"
                            class="text-[11px] text-zinc-400 uppercase">General Cargo Description <span
                                class="req-asterisk">*</span></label>
                        <textarea id="pmGeneralCargoDescription" data-field="general_cargo_description" rows="2"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
                    </div>
                    <div>
                        <label for="pmSpecialRequirements" class="text-[11px] text-zinc-400 uppercase">Special
                            Requirements</label>
                        <textarea id="pmSpecialRequirements" data-field="special_requirements" rows="2"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="pmAfmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="pmAfmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                + Add Requirement
            </button>
        </div>
    </div>
</x-modal>
