{{--
    Standalone "Add Freight" popup opened from ProspectInfoModal's own
    Requirements tab (Freights table's own "+ Add Freight" button).
    Genuinely separate from ProspectModal, targeting its own `arm*` field
    ids (shared with the Trucking/Charter popups below) so none collide
    with ProspectModal's own `pm*` ids - both modals can exist in the DOM
    at once. Immediate/single-row append - saving POSTs straight to
    POST .../prospects/{uuid}/proposalRequest/containers (see
    savePimFreight() in logic_prospect_add_modals.js), reloads this
    prospect's data (refreshing the read-only Freights table behind it),
    then closes.
--}}
<x-modal id="PimAddFreightModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Freight</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
        <div id="armContainerForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="md:col-span-3">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">Container &amp; Quantity</p>
                </div>

                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armContainerType" class="text-[11px] text-zinc-400 uppercase">Container Type <span class="req-asterisk">*</span></label>
                    <select id="armContainerType" data-field="container_type"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Type</option>
                    </select>
                </div>
                <div class="field-convan-size hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armContainerSize" class="text-[11px] text-zinc-400 uppercase">Size <span class="req-asterisk">*</span></label>
                    <select id="armContainerSize" data-field="container_size_id"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Size</option>
                    </select>
                </div>
                <div class="field-temperature hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armMinTemp" class="text-[11px] text-zinc-400 uppercase">Minimum Temperature (&deg;C) <span class="req-asterisk">*</span></label>
                    <input type="number" step="0.1" id="armMinTemp" data-field="minimum_temperature"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                </div>
                <div class="field-revenue-ton hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armRevenueTonUnit" class="text-[11px] text-zinc-400 uppercase">Revenue Ton (RT) Unit <span class="req-asterisk">*</span></label>
                    <select id="armRevenueTonUnit" data-field="revenue_ton_unit"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Unit</option>
                        <option value="CBM">CBM (Cubic Meter)</option>
                        <option value="MT">MT (Metric Ton)</option>
                    </select>
                </div>
                <div class="field-revenue-ton hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armCargoMeasurement" class="text-[11px] text-zinc-400 uppercase">Cargo Measurement</label>
                    <input type="text" id="armCargoMeasurement" data-field="cargo_measurement"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armContainerQuantity" class="text-[11px] text-zinc-400 uppercase">Quantity <span class="req-asterisk">*</span></label>
                    <input type="text" inputmode="numeric" id="armContainerQuantity" data-field="quantity"
                        class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armContainerFrequency" class="text-[11px] text-zinc-400 uppercase">Frequency</label>
                    <select id="armContainerFrequency" data-field="frequency"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">-</option>
                        <option value="daily">Daily</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                <div class="md:col-span-3">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">Service &amp; Route</p>
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armDeliveryType" class="text-[11px] text-zinc-400 uppercase">Service Type <span class="req-asterisk">*</span></label>
                    <select id="armDeliveryType" data-field="service_type"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Service Type</option>
                    </select>
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armOriginLocation" class="text-[11px] text-zinc-400 uppercase">Origin <span class="req-asterisk">*</span></label>
                    <select id="armOriginLocation" data-field="origin_location_id"
                        class="armOriginLocationSelect w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Location</option>
                    </select>
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armDestinationLocation" class="text-[11px] text-zinc-400 uppercase">Destination <span class="req-asterisk">*</span></label>
                    <select id="armDestinationLocation" data-field="destination_location_id"
                        class="armDestinationLocationSelect w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Location</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                <div class="md:col-span-3">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">Cargo Details</p>
                </div>
                <div>
                    <label for="armCargoType" class="text-[11px] text-zinc-400 uppercase">Cargo Type</label>
                    <select id="armCargoType" data-field="cargo_type"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select Cargo Type</option>
                    </select>
                </div>
                <div>
                    <label for="armDeclaredValue" class="text-[11px] text-zinc-400 uppercase">Declared Value per Unit</label>
                    <input type="text" inputmode="decimal" id="armDeclaredValue" data-field="declared_value_per_unit"
                        class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                </div>
                <div>
                    <label for="armWeight" class="text-[11px] text-zinc-400 uppercase">Weight</label>
                    <div class="flex gap-2">
                        <input type="text" inputmode="decimal" id="armWeight" data-field="weight"
                            class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <select id="armWeightUnit" data-field="weight_unit"
                            class="w-28 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Unit</option>
                            <option value="kg">Kilogram</option>
                            <option value="mt">Metric Ton</option>
                        </select>
                    </div>
                </div>
                <div class="md:col-span-3 border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armGeneralCargoDescription" class="text-[11px] text-zinc-400 uppercase">General Cargo Description <span class="req-asterisk">*</span></label>
                    <textarea id="armGeneralCargoDescription" data-field="general_cargo_description" rows="2"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
                </div>
                <div class="md:col-span-3">
                    <label for="armSpecialRequirements" class="text-[11px] text-zinc-400 uppercase">Special Requirements</label>
                    <textarea id="armSpecialRequirements" data-field="special_requirements" rows="2"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="pimAfmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="pimAfmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                + Add Requirement
            </button>
        </div>
    </div>
</x-modal>
