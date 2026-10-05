{{--
    Standalone "Add Requirement" modal - opened from ProspectInfoModal's
    Requirements tab. Genuinely separate from ProspectModal (not a reopened
    form), but the field layout mirrors it exactly - "just like the new
    prospect form" - since the underlying Freight/Trucking/Charter add
    endpoints are already "append one row" (not replace-all), so this modal
    can call them directly without needing the full prospect payload.
    Shows the current tables (with remove actions, unlike the read-only
    summary tables on the Requirements tab itself), the product picker, and
    the three product forms. Closing it refreshes the Requirements tab
    behind it - see logic_prospect_add_modals.js.
--}}
<x-modal id="AddRequirementModal" overlay-padding="p-0 md:p-4"
    class="rounded-none md:rounded-2xl md:min-w-[88vw] md:max-w-[97vw] h-full flex flex-col md:h-auto md:min-h-[80vh] md:max-h-[93vh]">

    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <div>
            <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Requirement</p>
            <p class="text-xs text-zinc-400 dark:text-zinc-500">Independent of any Proposal Request</p>
        </div>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>

    <div class="p-5 space-y-6 flex-1 overflow-y-auto">

        <p id="armRequirementsEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500">
            No requirements added yet.
        </p>

        <div id="armContainersTableWrap" class="hidden space-y-2">
            <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Freights</p>
            <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                <table class="min-w-full text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        <tr>
                            <th class="px-3 py-2 text-left">Type</th>
                            <th class="px-3 py-2 text-left">Size</th>
                            <th class="px-3 py-2 text-left">Min Temp</th>
                            <th class="px-3 py-2 text-left">Revenue Ton (RT)</th>
                            <th class="px-3 py-2 text-left">Cargo Measurement</th>
                            <th class="px-3 py-2 text-left">Qty</th>
                            <th class="px-3 py-2 text-left">Frequency</th>
                            <th class="px-3 py-2 text-left">Service Type</th>
                            <th class="px-3 py-2 text-left">Origin</th>
                            <th class="px-3 py-2 text-left">Destination</th>
                            <th class="px-3 py-2 text-left">Declared Value</th>
                            <th class="px-3 py-2 text-left">Weight</th>
                            <th class="px-3 py-2 text-left">Cargo Type</th>
                            <th class="px-3 py-2 text-left">Cargo Description</th>
                            <th class="px-3 py-2 text-left">Special Requirements</th>
                            <th class="px-3 py-2 text-left">Booking Unit Type</th>
                            <th class="px-3 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="armContainersTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                </table>
            </div>
        </div>

        <div id="armTruckingsTableWrap" class="hidden space-y-2">
            <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Truckings</p>
            <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                <table class="min-w-full text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        <tr>
                            <th class="px-3 py-2 text-left">Trucking Cargo Type</th>
                            <th class="px-3 py-2 text-left">Qty</th>
                            <th class="px-3 py-2 text-left">Frequency</th>
                            <th class="px-3 py-2 text-left">Dispatch Mode</th>
                            <th class="px-3 py-2 text-left">Origin</th>
                            <th class="px-3 py-2 text-left">Destination</th>
                            <th class="px-3 py-2 text-left">Declared Value</th>
                            <th class="px-3 py-2 text-left">Weight</th>
                            <th class="px-3 py-2 text-left">Cargo Type</th>
                            <th class="px-3 py-2 text-left">Cargo Description</th>
                            <th class="px-3 py-2 text-left">Special Requirements</th>
                            <th class="px-3 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="armTruckingsTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                </table>
            </div>
        </div>

        <div id="armChartersTableWrap" class="hidden space-y-2">
            <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Charters</p>
            <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                <table class="min-w-full text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        <tr>
                            <th class="px-3 py-2 text-left">Cargo</th>
                            <th class="px-3 py-2 text-left">Ports</th>
                            <th class="px-3 py-2 text-left">Start Date</th>
                            <th class="px-3 py-2 text-left">End Date</th>
                            <th class="px-3 py-2 text-left">Declared Value</th>
                            <th class="px-3 py-2 text-left">Weight</th>
                            <th class="px-3 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="armChartersTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                </table>
            </div>
        </div>

        <div class="border-t dark:border-zinc-700 pt-4">
            <label for="armRequirementProduct" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                Product
            </label>
            <select id="armRequirementProduct"
                class="w-full md:w-64 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                <option value="">Select Product</option>
                <option value="freight">Freight</option>
                <option value="trucking">Trucking</option>
                <option value="charter">Charter</option>
            </select>
        </div>

        {{-- Freight (Container) pane --}}
        <div id="armProductPaneFreight" class="hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-3">
            <p class="font-semibold text-zinc-700 dark:text-zinc-200 text-sm">Add Freight Requirement</p>

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
                        <label for="armRevenueTon" class="text-[11px] text-zinc-400 uppercase">Revenue Ton (RT) Unit <span class="req-asterisk">*</span></label>
                        <input type="number" step="0.01" id="armRevenueTon" data-field="revenue_ton"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div class="field-revenue-ton hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="armCargoMeasurement" class="text-[11px] text-zinc-400 uppercase">Cargo Measurement</label>
                        <input type="text" id="armCargoMeasurement" data-field="cargo_measurement"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="armContainerQuantity" class="text-[11px] text-zinc-400 uppercase">Quantity <span class="req-asterisk">*</span></label>
                        <input type="number" id="armContainerQuantity" data-field="quantity"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
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
                            <input type="number" step="0.01" id="armWeight" data-field="weight"
                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
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
        </div>

        {{-- Trucking pane --}}
        <div id="armProductPaneTrucking" class="hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-3">
            <p class="font-semibold text-zinc-700 dark:text-zinc-200 text-sm">Add Trucking Requirement</p>

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
                        <input type="number" id="armTruckingQuantity" data-field="quantity"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
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
                            <input type="number" step="0.01" id="armTruckingWeight" data-field="weight"
                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
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
        </div>

        {{-- Charter pane --}}
        <div id="armProductPaneCharter" class="hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-4">
            <p class="font-semibold text-zinc-700 dark:text-zinc-200 text-sm">Add Charter Requirement</p>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Cargo <span class="req-asterisk">*</span></p>
                    <button type="button" id="armAddCharterCargoBtn"
                        class="text-xs px-2 py-1 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                        + Add Cargo
                    </button>
                </div>
                <div id="armCharterCargoContainer" class="space-y-2"></div>
            </div>

            <div class="border-t dark:border-zinc-700 pt-3">
                <div class="flex justify-between items-center mb-2">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Ports <span class="req-asterisk">*</span></p>
                    <button type="button" id="armAddCharterPortBtn"
                        class="text-xs px-2 py-1 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                        + Add Port
                    </button>
                </div>
                <div id="armCharterPortsContainer" class="space-y-2"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 border-t dark:border-zinc-700 pt-3">
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armCharterStartDate" class="text-[11px] text-zinc-400 uppercase">Charter Start Date <span class="req-asterisk">*</span></label>
                    <input type="date" id="armCharterStartDate" data-field="charter_start_date"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                </div>
                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                    <label for="armCharterEndDate" class="text-[11px] text-zinc-400 uppercase">Charter End Date <span class="req-asterisk">*</span></label>
                    <input type="date" id="armCharterEndDate" data-field="charter_end_date"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                </div>
                <div>
                    <label for="armCharterDeclaredValue" class="text-[11px] text-zinc-400 uppercase">Declared Value</label>
                    <input type="text" inputmode="decimal" id="armCharterDeclaredValue" data-field="declared_value"
                        class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                </div>
                <div class="md:col-span-3">
                    <label class="text-[11px] text-zinc-400 uppercase">Weight</label>
                    <div class="flex gap-2 md:w-1/3">
                        <input type="number" step="0.01" id="armCharterWeight" data-field="weight"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <select id="armCharterWeightUnit" data-field="weight_unit"
                            class="w-28 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Unit</option>
                            <option value="kg">Kilogram</option>
                            <option value="mt">Metric Ton</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div id="armAddRequirementBtnWrap" class="hidden flex justify-end pt-2">
            <button type="button" id="armAddRequirementBtn"
                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                + Add Requirement
            </button>
        </div>
    </div>
</x-modal>
