{{-- Rate Maintenance - loaded into the SPA content area, not a full page --}}
<div class="container mx-auto p-3">

    <div class="mb-6">
        <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">Rate Maintenance</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Manage the master data and rate tables used by the
            freight booking engine.
        </p>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-zinc-200 dark:border-zinc-800">
        <nav class="flex flex-wrap gap-1 -mb-px" id="maintenanceTabs">
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200"
                data-tab="locations">Locations</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200"
                data-tab="containers">Containers</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200"
                data-tab="chargeTypes">Charge Types</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200"
                data-tab="deliveryTypes">Delivery Types</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200"
                data-tab="lanes">Lanes</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200"
                data-tab="generalCharges">General Charges</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200"
                data-tab="portCharges">Port Charges</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200"
                data-tab="handlingFees">Handling Fees</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200"
                data-tab="truckingTariffs">Trucking Tariffs</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200"
                data-tab="vatRates">VAT Rates</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200"
                data-tab="specialCharges">Special Charges</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200"
                data-tab="cargoYards">Cargo Yard (CY)</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200"
                data-tab="generalLookups">General Lookups</button>
        </nav>
    </div>

    {{-- Tab panels --}}
    <div class="mt-5" id="maintenanceTabPanels">
        @foreach ([
        'locations' => 'Locations',
        'containers' => 'Containers',
        'chargeTypes' => 'Charge Types',
        'deliveryTypes' => 'Delivery Types',
        'lanes' => 'Lanes',
        'generalCharges' => 'General Charges',
        'portCharges' => 'Port Charges',
        'handlingFees' => 'Handling Fees',
        'truckingTariffs' => 'Trucking Tariffs',
        'vatRates' => 'VAT Rates',
        'specialCharges' => 'Special Charges',
        'cargoYards' => 'Cargo Yard (CY)',
    ] as $key => $label)
            <div class="tab-panel hidden" data-tab-panel="{{ $key }}">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-medium text-zinc-900 dark:text-white">{{ $label }}</h2>
                    <button type="button"
                        class="add-new-btn inline-flex items-center gap-1.5 rounded-lg bg-orange-600 px-3.5 py-2 text-sm font-medium text-white hover:bg-orange-700 transition"
                        data-entity="{{ $key }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add New
                    </button>
                </div>

                <x-search-bar :id="$key" placeholder="Search {{ strtolower($label) }}..." />

                <div
                    class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr data-table-head="{{ $key }}"></tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800"
                            data-table-body="{{ $key }}"></tbody>
                    </table>
                    <x-table-pagination :id="$key" />
                </div>
            </div>
        @endforeach

        {{-- General Lookups - generic Option/List-of-Value categories (Type of Business,
             Address Type, Lead Source, etc.), formerly the standalone /page_lookupValues page --}}
        <div class="tab-panel hidden" data-tab-panel="generalLookups">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-medium text-zinc-900 dark:text-white">General Lookups</h2>
                <button type="button" id="newOptionButton"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-orange-600 px-3.5 py-2 text-sm font-medium text-white hover:bg-orange-700 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add New Option
                </button>
            </div>

            <div class="space-y-5" id="optionsContainer"></div>
        </div>
    </div>
</div>

{{-- Generic slide-over form - fields are injected by JS based on which entity's "Add New"/"Edit" was clicked --}}
<x-side-modal id="maintenanceFormModal">
    <div
        class="flex items-center justify-between px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <h3 id="maintenanceFormTitle" class="text-base font-semibold text-zinc-900 dark:text-white">Add Item</h3>
        <button type="button" id="maintenanceFormCloseBtn"
            class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <form id="maintenanceForm" class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
        <div id="maintenanceFormFields" class="space-y-4"></div>
    </form>

    <div
        class="px-5 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 flex justify-end gap-2">
        <button type="button" id="maintenanceFormCancelBtn"
            class="rounded-lg border border-zinc-300 dark:border-zinc-700 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
            Cancel
        </button>
        <button type="submit" form="maintenanceForm" id="maintenanceFormSubmitBtn"
            class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700">
            Save
        </button>
    </div>
</x-side-modal>

<x-side-modal id="containerFormModal">
    <div
        class="flex items-center justify-between px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <h3 id="containerFormTitle" class="text-base font-semibold text-zinc-900 dark:text-white">Add Container</h3>
        <button type="button" id="containerFormCloseBtn"
            class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <form id="containerForm" class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
        <input type="hidden" id="containerIdInput">

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Code <span
                        class="req-asterisk">*</span></label>
                <input type="text" id="containerCodeInput" required placeholder="e.g. CV"
                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Name <span
                        class="req-asterisk">*</span></label>
                <input type="text" id="containerNameInput" required placeholder="e.g. Con Van"
                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
            </div>
            <div class="col-span-2">
                <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                    <input type="checkbox" id="containerActiveInput" checked
                        class="rounded border-zinc-300 dark:border-zinc-700 text-orange-600 focus:ring-orange-500">
                    Active
                </label>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Sizes</label>
                    <button type="button" id="addSizeRowBtn"
                        class="inline-flex items-center gap-1 text-xs font-medium text-orange-600 hover:text-orange-700">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Size
                    </button>
                </div>
                <p class="text-xs text-zinc-400 mb-2">e.g. 20-GP, 40-GP</p>
                <div id="containerSizeRows" class="space-y-2"></div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Classes</label>
                    <button type="button" id="addClassRowBtn"
                        class="inline-flex items-center gap-1 text-xs font-medium text-orange-600 hover:text-orange-700">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Class
                    </button>
                </div>
                <p class="text-xs text-zinc-400 mb-2">Optional - e.g. A, B, C. Leave empty for a size-only
                    container.</p>
                <div id="containerClassRows" class="space-y-2"></div>
            </div>
        </div>
        <p class="text-xs text-zinc-400">Combinations are generated automatically: a base price per size, plus a
            price per class + size pair when classes are set. Containers with no fixed size (e.g. Loose Cargo,
            Rolling Cargo) can leave Sizes empty and add Classes only - each class then gets its own price.
            Prices themselves are set later, per lane, in the Lane's Tariff Rates section.</p>
    </form>

    <div
        class="px-5 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 flex justify-end gap-2">
        <button type="button" id="containerFormCancelBtn"
            class="rounded-lg border border-zinc-300 dark:border-zinc-700 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
            Cancel
        </button>
        <button type="submit" form="containerForm" id="containerFormSubmitBtn"
            class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700">
            Save Container
        </button>
    </div>
</x-side-modal>

<x-side-modal id="locationFormModal">
    <div
        class="flex items-center justify-between px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <h3 id="locationFormTitle" class="text-base font-semibold text-zinc-900 dark:text-white">Add Location</h3>
        <button type="button" id="locationFormCloseBtn"
            class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <form id="locationForm" class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
        <input type="hidden" id="locationIdInput">

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Location Name <span
                        class="req-asterisk">*</span></label>
                <input type="text" id="locationNameInput" required placeholder="e.g. Aklan"
                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
            </div>
            <div class="flex items-end">
                <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300 pb-2">
                    <input type="checkbox" id="locationActiveInput" checked
                        class="rounded border-zinc-300 dark:border-zinc-700 text-orange-600 focus:ring-orange-500">
                    Active
                </label>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Ports</label>
                <button type="button" id="addPortRowBtn"
                    class="inline-flex items-center gap-1 text-xs font-medium text-orange-600 hover:text-orange-700">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Port
                </button>
            </div>
            <p class="text-xs text-zinc-400 mb-2">e.g. Caticlan Port, Dumaguit Port</p>
            <div id="locationPortRows" class="space-y-2"></div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Serviceable Areas</label>
                <button type="button" id="addAreaRowBtn"
                    class="inline-flex items-center gap-1 text-xs font-medium text-orange-600 hover:text-orange-700">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Area
                </button>
            </div>
            <p class="text-xs text-zinc-400 mb-2">Trucking zones within this location, e.g. Cabuyao, Calamba</p>
            <div id="locationAreaRows" class="space-y-2"></div>
        </div>
    </form>

    <div
        class="px-5 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 flex justify-end gap-2">
        <button type="button" id="locationFormCancelBtn"
            class="rounded-lg border border-zinc-300 dark:border-zinc-700 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
            Cancel
        </button>
        <button type="submit" form="locationForm" id="locationFormSubmitBtn"
            class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700">
            Save Location
        </button>
    </div>
</x-side-modal>

{{-- Lane modal - Tariff Rates are managed here too (same idea as Ports/Serviceable
     Areas living inside the Location modal), rather than their own tab. Wider than
     the default side-modal since the pricing grid is laid out in 2 columns, but
     still responsive: full-width on mobile, capped width from md up. --}}
<x-side-modal id="laneFormModal" panelClass="w-full md:w-[600px] lg:w-[900px] xl:w-[1100px]">
    <div
        class="flex items-center justify-between px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <h3 id="laneFormTitle" class="text-base font-semibold text-zinc-900 dark:text-white">Add Lane</h3>
        <button type="button" id="laneFormCloseBtn"
            class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <form id="laneForm" class="flex-1 overflow-y-auto px-5 py-4 space-y-5">
        <input type="hidden" id="laneIdInput">
        <input type="hidden" id="laneTariffRateIdInput">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Origin Port <span
                        class="req-asterisk">*</span></label>
                <select id="laneOriginPortInput" required
                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <option value="">Select origin port…</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Destination Port
                    <span class="req-asterisk">*</span></label>
                <select id="laneDestinationPortInput" required
                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <option value="">Select destination port…</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                    <input type="checkbox" id="laneActiveInput" checked
                        class="rounded border-zinc-300 dark:border-zinc-700 text-orange-600 focus:ring-orange-500">
                    Active
                </label>
            </div>
        </div>

        <div class="border-t border-zinc-200 dark:border-zinc-800 pt-4">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Tariff Rates (FRT per
                combination)</label>
            <p class="text-xs text-zinc-400 mb-3" id="laneTariffRateHint">Set the freight rate for every available
                container combination on this lane. Combinations come from the Containers tab and are grouped by
                container type below.</p>
            <div id="laneTariffGroups" class="space-y-5"></div>
        </div>
    </form>

    <div
        class="px-5 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 flex justify-end gap-2">
        <button type="button" id="laneFormCancelBtn"
            class="rounded-lg border border-zinc-300 dark:border-zinc-700 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
            Cancel
        </button>
        <button type="submit" form="laneForm" id="laneFormSubmitBtn"
            class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700">
            Save Lane
        </button>
    </div>
</x-side-modal>

{{-- General Lookups modals (ported from the old /page_lookupValues page) --}}
<x-modal id="NewOptionModal">
    <div
        class="w-full p-5 flex justify-between items-center border-b border-gray-200 dark:border-zinc-700 text-black dark:text-white">
        <p class="text-xl font-semibold">Add New Option</p>
    </div>

    <div class="max-h-[70vh] overflow-y-auto p-5 space-y-6">

        {{-- Option Information --}}
        <div class="border border-gray-200 dark:border-zinc-700 rounded-xl p-5 space-y-4">
            <p class="font-semibold text-lg dark:text-white">Option Information</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col">
                    <label class="text-sm dark:text-white">Option Name</label>
                    <input type="text" name="OptionName" id="OptionName"
                        class="border p-2 rounded-lg dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm dark:text-white">Option Description</label>
                    <input type="text" name="OptionDescription" id="OptionDescription"
                        class="border p-2 rounded-lg dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">
                </div>
            </div>
        </div>

        {{-- LOV Section --}}
        <div class="border border-gray-200 dark:border-zinc-700 rounded-xl p-5 space-y-4">
            <div class="flex justify-between items-center">
                <p class="font-semibold text-lg dark:text-white">List of Values</p>
                <button id="addLovButton"
                    class="px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium">
                    + Add LOV
                </button>
            </div>

            <div id="lovContainer" class="space-y-3"></div>
        </div>
    </div>

    <div class="border-t border-gray-200 dark:border-zinc-700 px-6 py-4 flex justify-end gap-3">
        <button id="SaveOption"
            class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium">
            Submit
        </button>
        <button
            class="modal-close border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-600 px-5 py-2 rounded-lg text-sm font-medium">
            Cancel
        </button>
    </div>
</x-modal>

<x-modal id="addLOVModal">
    <div
        class="w-full p-5 flex justify-between items-center border-b border-gray-200 dark:border-zinc-700 text-black dark:text-white">
        <p class="text-xl font-semibold">Add List of Value</p>
    </div>

    <div class="max-h-[70vh] overflow-y-auto p-5 space-y-6">
        <input type="hidden" name="OptionID" id="OptionID">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex flex-col">
                <label class="text-sm dark:text-white">LOV Code</label>
                <input type="text"
                    class="add-lov-code border p-2 rounded-lg dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">
            </div>
            <div class="flex flex-col">
                <label class="text-sm dark:text-white">LOV Name</label>
                <input type="text"
                    class="add-lov-name border p-2 rounded-lg dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">
            </div>
        </div>

        <div class="flex flex-col">
            <label class="text-sm dark:text-white">LOV Description</label>
            <input type="text"
                class="add-lov-description border p-2 rounded-lg dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">
        </div>
    </div>

    <div class="border-t border-gray-200 dark:border-zinc-700 px-6 py-4 flex justify-end gap-3">
        <button id="SaveLOV"
            class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium">
            Submit
        </button>
        <button
            class="modal-close border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-600 px-5 py-2 rounded-lg text-sm font-medium">
            Cancel
        </button>
    </div>
</x-modal>


<script>
    (function() {
        /**
         * Rate Maintenance page (SPA content-area script).
         *
         * Wrapped in an IIFE so re-injecting this content on SPA navigation
         * never leaks globals or double-declares anything. Runs immediately
         * at the bottom of the file (no DOMContentLoaded) since this script
         * only ever executes after its markup is already in the DOM.
         *
         * One config object per entity (ENTITY_CONFIG) drives everything:
         * table columns, the slide-over form fields, and which endpoint to hit.
         * Add a new entity by adding a new config block - no other code
         * changes needed for a plain CRUD list.
         *
         * Requires public/js/remote-table.js to be loaded first - it supplies
         * window.createRemoteTable(), which handles fetching, pagination, and
         * search for every entity's table here. This file only supplies the
         * row template + rebinds Edit/Delete buttons after each render.
         *
         * ASSUMPTION about apiCall()'s return shape (adjust the spot marked
         * "ADJUST ME" below if your actual response shape differs):
         *   - GET      -> { success: true, data: <raw Laravel response body> }
         *   - POST/PUT -> { success: true, data: <created/updated record> }
         */

        // -----------------------------------------------------------------
        // Tailwind class sets for tab active/inactive state (no custom CSS)
        // -----------------------------------------------------------------
        const TAB_ACTIVE_CLASSES = ['border-orange-600', 'text-orange-600'];
        // dark:text-zinc-400 must be toggled here too (not just left in the
        // static class list) - otherwise it never gets removed from the
        // active tab and can win over text-orange-600 in dark mode.
        const TAB_INACTIVE_CLASSES = ['border-transparent', 'text-zinc-500', 'dark:text-zinc-400'];

        // -----------------------------------------------------------------
        // STATUS / badge mapping
        // -----------------------------------------------------------------
        const ACTIVE_BADGE_MAPPING = {
            true: '<span class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-900/40 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-300">Active</span>',
            false: '<span class="inline-flex items-center rounded-full bg-zinc-100 dark:bg-zinc-700 px-2 py-0.5 text-xs font-medium text-zinc-600 dark:text-zinc-300">Inactive</span>',
        };

        function activeBadge(value) {
            return ACTIVE_BADGE_MAPPING[Boolean(value)];
        }

        const APPLICABLE_TO_BADGE_MAPPING = {
            PORT: '<span class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/40 px-2 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-300">Port</span>',
            GENERAL: '<span class="inline-flex items-center rounded-full bg-purple-50 dark:bg-purple-900/40 px-2 py-0.5 text-xs font-medium text-purple-700 dark:text-purple-300">General</span>',
        };

        function applicableToBadge(value) {
            return APPLICABLE_TO_BADGE_MAPPING[value] ?? value ?? '-';
        }

        function getValueByPath(obj, path) {
            return path.split('.').reduce((acc, key) => (acc == null ? acc : acc[key]), obj);
        }

        function money(value) {
            const num = Number(value ?? 0);
            return num.toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // -----------------------------------------------------------------
        // Entity configuration
        // -----------------------------------------------------------------
        const ENTITY_CONFIG = {
            locations: {
                label: 'Location',
                pk: 'location_id',
                listUrl: '/api/locations',
                createUrl: '/api/locations',
                updateUrl: (id) => `/api/locations/${id}`,
                deleteUrl: (id) => `/api/locations/${id}`,
                columns: [{
                        key: 'name',
                        label: 'Location Name'
                    },
                    {
                        key: 'ports_count',
                        label: 'Ports',
                        render: (row) => row.ports_count ?? (row.ports?.length ?? 0)
                    },
                    {
                        key: 'serviceable_areas_count',
                        label: 'Serviceable Areas',
                        render: (row) => row.serviceable_areas_count ?? (row.serviceable_areas?.length ?? 0)
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                fields: [],
            },
            containers: {
                label: 'Container',
                pk: 'id',
                listUrl: '/api/containers',
                deleteUrl: (id) => `/api/containers/${id}`,
                columns: [{
                        key: 'code',
                        label: 'Code'
                    },
                    {
                        key: 'name',
                        label: 'Name'
                    },
                    {
                        key: 'variants',
                        label: 'Combinations',
                        render: (row) => (row.variants?.length ?? 0)
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                fields: [],
            },
            chargeTypes: {
                label: 'Charge Type',
                pk: 'charge_type_id',
                listUrl: '/api/chargeTypes',
                createUrl: '/api/chargeTypes',
                updateUrl: (id) => `/api/chargeTypes/${id}`,
                deleteUrl: (id) => `/api/chargeTypes/${id}`,
                columns: [{
                        key: 'code',
                        label: 'Code'
                    },
                    {
                        key: 'name',
                        label: 'Name'
                    },
                    {
                        key: 'applicable_to',
                        label: 'Applicable To',
                        render: (row) => applicableToBadge(row.applicable_to)
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                fields: [{
                        name: 'code',
                        label: 'Charge Code',
                        type: 'text',
                        required: true,
                        placeholder: 'e.g. DOC_STAMP'
                    },
                    {
                        name: 'name',
                        label: 'Charge Name',
                        type: 'text',
                        required: true,
                        placeholder: 'e.g. Documentary Stamp'
                    },
                    {
                        name: 'applicable_to',
                        label: 'Applicable To',
                        type: 'select',
                        required: true,
                        options: [{
                                value: 'PORT',
                                label: 'Port (used in Port Charges)'
                            },
                            {
                                value: 'GENERAL',
                                label: 'General (applies to every booking)'
                            },
                        ],
                    },
                    {
                        name: 'is_active',
                        label: 'Active',
                        type: 'checkbox',
                        default: true
                    },
                ],
            },

            deliveryTypes: {
                label: 'Delivery Type',
                pk: 'delivery_type_id',
                listUrl: '/api/deliveryTypes',
                createUrl: '/api/deliveryTypes',
                updateUrl: (id) => `/api/deliveryTypes/${id}`,
                deleteUrl: (id) => `/api/deliveryTypes/${id}`,
                columns: [{
                        key: 'code',
                        label: 'Code'
                    },
                    {
                        key: 'name',
                        label: 'Name'
                    },
                    {
                        key: 'includes_origin_trucking',
                        label: 'Origin Trucking',
                        render: (row) => (row.includes_origin_trucking ? 'Yes' : 'No')
                    },
                    {
                        key: 'includes_destination_trucking',
                        label: 'Destination Trucking',
                        render: (row) => (row.includes_destination_trucking ? 'Yes' : 'No')
                    },
                ],
                fields: [{
                        name: 'code',
                        label: 'Code',
                        type: 'text',
                        required: true,
                        placeholder: 'e.g. DD'
                    },
                    {
                        name: 'name',
                        label: 'Name',
                        type: 'text',
                        required: true,
                        placeholder: 'e.g. Door-to-Door'
                    },
                    {
                        name: 'includes_origin_trucking',
                        label: 'Includes Origin Trucking',
                        type: 'checkbox'
                    },
                    {
                        name: 'includes_destination_trucking',
                        label: 'Includes Destination Trucking',
                        type: 'checkbox'
                    },
                ],
            },
            // Add/Edit uses a dedicated modal (openLaneForm) - Tariff Rates are
            // managed inline there, same idea as Ports/Serviceable Areas living
            // inside the Location modal. This config still drives the Lanes
            // table itself (columns/listUrl/deleteUrl).
            lanes: {
                label: 'Lane',
                pk: 'lane_id',
                listUrl: '/api/lanes',
                deleteUrl: (id) => `/api/lanes/${id}`,
                columns: [{
                        key: 'origin',
                        label: 'Origin',
                        render: (row) => row.origin_port ? `${row.origin_port.location?.name ?? '-'} - ${row.origin_port.name}` : '-'
                    },
                    {
                        key: 'destination',
                        label: 'Destination',
                        render: (row) => row.destination_port ? `${row.destination_port.location?.name ?? '-'} - ${row.destination_port.name}` : '-'
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                fields: [],
            },

            portCharges: {
                label: 'Port Charge',
                pk: 'port_charge_id',
                listUrl: '/api/portCharges',
                createUrl: '/api/portCharges',
                updateUrl: (id) => `/api/portCharges/${id}`,
                deleteUrl: (id) => `/api/portCharges/${id}`,
                versioned: true,
                columns: [{
                        key: 'port',
                        label: 'Port',
                        render: (row) => row.port ? `${row.port.location?.name ?? '-'} - ${row.port.name}` : '-'
                    },
                    {
                        key: 'charge_type',
                        label: 'Charge Type',
                        render: (row) => row.charge_type?.name ?? '-'
                    },
                    {
                        key: 'amount',
                        label: 'Amount',
                        render: (row) => money(row.amount)
                    },
                    {
                        key: 'effective_date',
                        label: 'Effective',
                        render: (row) => formatDate(row.effective_date)
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                fields: [{
                        name: 'port_id',
                        label: 'Port',
                        type: 'select',
                        required: true,
                        optionsSource: 'ports'
                    },
                    {
                        name: 'charge_type_id',
                        label: 'Charge Type',
                        type: 'select',
                        required: true,
                        optionsSource: 'chargeTypesPort'
                    },
                    {
                        name: 'amount',
                        label: 'Amount',
                        type: 'currency',
                        required: true
                    },
                    {
                        name: 'effective_date',
                        label: 'Effective Date',
                        type: 'date',
                        required: true
                    },
                ],
                editFields: [{
                        name: 'amount',
                        label: 'Amount',
                        type: 'currency',
                        required: true
                    },
                    {
                        name: 'is_active',
                        label: 'Active',
                        type: 'checkbox'
                    },
                ],
            },

            generalCharges: {
                label: 'General Charge',
                pk: 'general_charge_id',
                listUrl: '/api/generalCharges',
                createUrl: '/api/generalCharges',
                updateUrl: (id) => `/api/generalCharges/${id}`,
                deleteUrl: (id) => `/api/generalCharges/${id}`,
                versioned: true,
                columns: [{
                        key: 'charge_type',
                        label: 'Charge Type',
                        render: (row) => row.charge_type?.name ?? '-'
                    },
                    {
                        key: 'amount',
                        label: 'Amount',
                        render: (row) => money(row.amount)
                    },
                    {
                        key: 'effective_date',
                        label: 'Effective',
                        render: (row) => formatDate(row.effective_date)
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                fields: [{
                        name: 'charge_type_id',
                        label: 'Charge Type',
                        type: 'select',
                        required: true,
                        optionsSource: 'chargeTypesGeneral'
                    },
                    {
                        name: 'amount',
                        label: 'Amount',
                        type: 'currency',
                        required: true
                    },
                    {
                        name: 'effective_date',
                        label: 'Effective Date',
                        type: 'date',
                        required: true
                    },
                ],
                editFields: [{
                        name: 'amount',
                        label: 'Amount',
                        type: 'currency',
                        required: true
                    },
                    {
                        name: 'is_active',
                        label: 'Active',
                        type: 'checkbox'
                    },
                ],
            },

            handlingFees: {
                label: 'Handling Fee',
                pk: 'handling_fee_id',
                listUrl: '/api/handlingFees',
                createUrl: '/api/handlingFees',
                updateUrl: (id) => `/api/handlingFees/${id}`,
                deleteUrl: (id) => `/api/handlingFees/${id}`,
                versioned: true,
                columns: [{
                        key: 'port',
                        label: 'Port',
                        render: (row) => row.port ? `${row.port.location?.name ?? '-'} - ${row.port.name}` : '-'
                    },
                    {
                        key: 'amount',
                        label: 'Amount',
                        render: (row) => money(row.amount)
                    },
                    {
                        key: 'effective_date',
                        label: 'Effective',
                        render: (row) => formatDate(row.effective_date)
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                fields: [{
                        name: 'port_id',
                        label: 'Port',
                        type: 'select',
                        required: true,
                        optionsSource: 'ports'
                    },
                    {
                        name: 'amount',
                        label: 'Amount',
                        type: 'currency',
                        required: true
                    },
                    {
                        name: 'effective_date',
                        label: 'Effective Date',
                        type: 'date',
                        required: true
                    },
                ],
                editFields: [{
                        name: 'amount',
                        label: 'Amount',
                        type: 'currency',
                        required: true
                    },
                    {
                        name: 'is_active',
                        label: 'Active',
                        type: 'checkbox'
                    },
                ],
            },

            truckingTariffs: {
                label: 'Trucking Tariff',
                pk: 'trucking_tariff_id',
                listUrl: '/api/truckingTariffs',
                createUrl: '/api/truckingTariffs',
                updateUrl: (id) => `/api/truckingTariffs/${id}`,
                deleteUrl: (id) => `/api/truckingTariffs/${id}`,
                versioned: true,
                columns: [{
                        key: 'area',
                        label: 'Serviceable Area',
                        render: (row) =>
                            `${row.serviceable_area?.location?.name ?? '-'} / ${row.serviceable_area?.area_name ?? '-'}`
                    },
                    {
                        key: 'delivery_type',
                        label: 'Delivery Type',
                        render: (row) => row.delivery_type?.code ?? '-'
                    },
                    {
                        key: 'amount',
                        label: 'Amount',
                        render: (row) => money(row.amount)
                    },
                    {
                        key: 'effective_date',
                        label: 'Effective',
                        render: (row) => formatDate(row.effective_date)
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                fields: [{
                        name: 'area_id',
                        label: 'Serviceable Area',
                        type: 'select',
                        required: true,
                        optionsSource: 'serviceableAreas'
                    },
                    {
                        name: 'delivery_type_id',
                        label: 'Delivery Type',
                        type: 'select',
                        required: true,
                        optionsSource: 'deliveryTypes'
                    },
                    {
                        name: 'amount',
                        label: 'Amount',
                        type: 'currency',
                        required: true
                    },
                    {
                        name: 'effective_date',
                        label: 'Effective Date',
                        type: 'date',
                        required: true
                    },
                ],
                editFields: [{
                        name: 'amount',
                        label: 'Amount',
                        type: 'currency',
                        required: true
                    },
                    {
                        name: 'is_active',
                        label: 'Active',
                        type: 'checkbox'
                    },
                ],
            },

            vatRates: {
                label: 'VAT Rate',
                pk: 'vat_rate_id',
                listUrl: '/api/vatRates',
                createUrl: '/api/vatRates',
                updateUrl: (id) => `/api/vatRates/${id}`,
                deleteUrl: (id) => `/api/vatRates/${id}`,
                versioned: true,
                columns: [{
                        key: 'tax_type',
                        label: 'Tax Type'
                    },
                    {
                        key: 'rate_percent',
                        label: 'Rate %',
                        render: (row) => `${money(row.rate_percent)}%`
                    },
                    {
                        key: 'effective_date',
                        label: 'Effective',
                        render: (row) => formatDate(row.effective_date)
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                fields: [{
                        name: 'tax_type',
                        label: 'Tax Type',
                        type: 'select',
                        required: true,
                        options: [{
                                value: 'General',
                                label: 'General'
                            },
                            {
                                value: 'VAT Inclusive',
                                label: 'VAT Inclusive'
                            },
                            {
                                value: 'VAT Exempt',
                                label: 'VAT Exempt'
                            },
                            {
                                value: 'Non-VAT',
                                label: 'Non-VAT'
                            },
                        ],
                    },
                    {
                        name: 'rate_percent',
                        label: 'Rate %',
                        type: 'number',
                        step: '0.01',
                        required: true
                    },
                    {
                        name: 'effective_date',
                        label: 'Effective Date',
                        type: 'date',
                        required: true

                    },
                ],
                editFields: [{
                        name: 'rate_percent',
                        label: 'Rate %',
                        type: 'number',
                        step: '0.01',
                        required: true
                    },
                    {
                        name: 'is_active',
                        label: 'Active',
                        type: 'checkbox'
                    },
                ],
            },

            specialCharges: {
                label: 'Special Charge',
                pk: 'special_charge_id',
                listUrl: '/api/specialCharges',
                createUrl: '/api/specialCharges',
                updateUrl: (id) => `/api/specialCharges/${id}`,
                deleteUrl: (id) => `/api/specialCharges/${id}`,
                columns: [{
                        key: 'name',
                        label: 'Name'
                    },
                    {
                        key: 'base_value',
                        label: 'Base Value',
                        render: (row) => money(row.base_value)
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                fields: [{
                        name: 'name',
                        label: 'Charge Name',
                        type: 'text',
                        required: true,
                        placeholder: 'e.g. Bullet Seal'
                    },
                    {
                        name: 'base_value',
                        label: 'Base Value',
                        type: 'currency',
                        placeholder: '0.00'
                    },
                    {
                        name: 'is_active',
                        label: 'Active',
                        type: 'checkbox',
                        default: true
                    },
                ],
            },

            cargoYards: {
                label: 'Cargo Yard',
                pk: 'cargo_yard_id',
                listUrl: '/api/cargoYards',
                createUrl: '/api/cargoYards',
                updateUrl: (id) => `/api/cargoYards/${id}`,
                deleteUrl: (id) => `/api/cargoYards/${id}`,
                columns: [{
                        key: 'name',
                        label: 'Name'
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                fields: [{
                        name: 'name',
                        label: 'CY Name',
                        type: 'text',
                        required: true,
                        placeholder: "e.g. Palawan - Puerto Princesa"
                    },
                    {
                        name: 'is_active',
                        label: 'Active',
                        type: 'checkbox',
                        default: true
                    },
                ],
            },
        };

        // Where each optionsSource pulls its dropdown list from, and how to
        // build the value/label pair for each <option>.
        const OPTION_SOURCE_MAPPING = {
            locations: {
                url: '/api/locations?per_page=200',
                value: 'location_id',
                label: (row) => row.name
            },
            ports: {
                url: '/api/ports?per_page=200',
                value: 'port_id',
                label: (row) => `${row.location?.name ?? '?'} - ${row.name}`
            },
            chargeTypes: {
                url: '/api/chargeTypes?per_page=100',
                value: 'charge_type_id',
                label: (row) => row.name
            },
            // Filtered variants so a Port Charge can only pick a PORT-applicable
            // charge type, and a General Charge only a GENERAL-applicable one.
            chargeTypesPort: {
                url: '/api/chargeTypes?applicable_to=PORT&per_page=100',
                value: 'charge_type_id',
                label: (row) => row.name
            },
            chargeTypesGeneral: {
                url: '/api/chargeTypes?applicable_to=GENERAL&per_page=100',
                value: 'charge_type_id',
                label: (row) => row.name
            },
            deliveryTypes: {
                url: '/api/deliveryTypes?per_page=100',
                value: 'delivery_type_id',
                label: (row) => `${row.code} - ${row.name}`
            },
            serviceableAreas: {
                url: '/api/serviceableAreas?per_page=200',
                value: 'area_id',
                label: (row) => `${row.location?.name ?? '?'} / ${row.area_name}`
            },
        };

        // -----------------------------------------------------------------
        // State
        // -----------------------------------------------------------------
        let activeTab = 'locations';
        let editingId = null;

        // -----------------------------------------------------------------
        // Tabs
        // -----------------------------------------------------------------
        function initTabs() {
            const tabButtons = document.querySelectorAll('#maintenanceTabs .maintenance-tab-btn');
            if (!tabButtons.length) return;

            tabButtons.forEach((btn) => {
                btn.addEventListener('click', () => showTab(btn.dataset.tab));
            });

            showTab(activeTab);
        }

        function showTab(key) {
            if (key !== 'generalLookups' && !ENTITY_CONFIG[key]) return;

            activeTab = key;

            document.querySelectorAll('#maintenanceTabs .maintenance-tab-btn').forEach((btn) => {
                const isActive = btn.dataset.tab === key;
                btn.classList.remove(...TAB_ACTIVE_CLASSES, ...TAB_INACTIVE_CLASSES);
                btn.classList.add(...(isActive ? TAB_ACTIVE_CLASSES : TAB_INACTIVE_CLASSES));
            });

            document.querySelectorAll('[data-tab-panel]').forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.tabPanel !== key);
            });

            // General Lookups isn't ENTITY_CONFIG/remoteTable-driven like the
            // other tabs - it's a nested Option -> List-of-Values view instead
            // of one flat table, so it loads itself.
            if (key === 'generalLookups') {
                loadOptions();
                return;
            }

            renderTableHead(key);
            getOrCreateTable(key).load(1);
        }

        // -----------------------------------------------------------------
        // Table rendering
        // -----------------------------------------------------------------
        function renderTableHead(key) {
            const config = ENTITY_CONFIG[key];
            const headRow = document.querySelector(`[data-table-head="${key}"]`);
            if (!headRow) return;

            const headCells = config.columns
                .map((col) =>
                    `<th class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white whitespace-nowrap">${col.label}</th>`
                )
                .join('');

            headRow.innerHTML =
                `${headCells}<th class="px-4 py-2.5 text-right text-xs font-medium uppercase tracking-wide bg-orange-500 text-white whitespace-nowrap">Actions</th>`;
        }

        function buildRow(key, config, row) {
            const cells = config.columns
                .map((col) => {
                    const value = col.render ? col.render(row) : (getValueByPath(row, col.key) ?? '-');
                    return `<td class="px-4 py-2.5 text-black dark:text-white">${value}</td>`;
                })
                .join('');

            const id = row[config.pk];

            return `
            <tr class="cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800" data-row-id="${id}">
                ${cells}
                <td class="px-4 py-2.5 text-right whitespace-nowrap">
                    <button type="button" class="p-1.5 text-zinc-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-lg transition" data-delete-id="${id}" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 pointer-events-none" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 6h18v2H3V6zm2 3h14l-1.5 12.5a1 1 0 0 1-1 .5H8a1 1 0 0 1-1-.5L5 9zm5 2v8h2v-8H10zm4 0v8h2v-8h-2zM9 4V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1h5v2H4V4h5z"/>
                        </svg>
                    </button>
                </td>
            </tr>
        `;
        }

        // -----------------------------------------------------------------
        // One createRemoteTable() instance per entity (see public/js/remote-table.js).
        // Handles fetching, pagination, and search - this file only supplies
        // the row template and rebinds the Edit/Delete buttons after each render.
        // -----------------------------------------------------------------
        const tables = {};


        function getOrCreateTable(key) {
            if (tables[key]) return tables[key];

            const config = ENTITY_CONFIG[key];

            tables[key] = window.createRemoteTable({
                url: config.listUrl,
                tableBodySelector: document.querySelector(`[data-table-body="${key}"]`),
                paginationSelector: document.querySelector(`[data-table-pagination="${key}"]`),
                searchInputSelector: document.querySelector(`#${key}SearchInput`),
                searchButtonSelector: document.querySelector(`#${key}SearchBtn`),
                emptyMessage: `No ${config.label} records yet.`,
                colspan: config.columns.length + 1,
                rowTemplate: (row) => buildRow(key, config, row),
                afterRender: () => {
                    document.querySelectorAll(`[data-table-body="${key}"] [data-row-id]`).forEach((
                        row) => {
                        row.addEventListener('click', () => openEditForm(key, row.dataset
                            .rowId));
                    });

                    document.querySelectorAll(`[data-table-body="${key}"] [data-delete-id]`).forEach((
                        btn) => {
                        btn.addEventListener('click', (e) => {
                            e.stopPropagation();
                            deleteRow(key, btn.dataset.deleteId);
                        });
                    });

                    document.querySelectorAll(`[data-table-body="${key}"] a`).forEach((link) => {
                        link.addEventListener('click', (e) => e.stopPropagation());
                    });
                },
            });

            return tables[key];
        }

        // -----------------------------------------------------------------
        // Slide-over form
        // -----------------------------------------------------------------
        function getFieldsFor(config, isEdit) {
            if (isEdit && config.editFields) return config.editFields;
            return config.fields;
        }

        async function renderFormFields(config, isEdit, existingRow = null) {
            const container = document.getElementById('maintenanceFormFields');
            const fields = getFieldsFor(config, isEdit);

            container.innerHTML = fields.map((field) => fieldWrapperHtml(field)).join('');

            // Populate select dropdowns (async) and set initial values.
            for (const field of fields) {
                const el = container.querySelector(`[name="${field.name}"]`);
                if (!el) continue;

                if (field.type === 'select' && field.optionsSource) {
                    await populateSelectOptions(el, field.optionsSource);
                }

                const currentValue = existingRow ? getValueByPath(existingRow, field.name) : field.default;

                if (field.type === 'checkbox') {
                    el.checked = Boolean(currentValue);
                } else if (currentValue !== undefined && currentValue !== null) {
                    el.value = field.type === 'currency' ? formatCurrencyDisplay(currentValue) : currentValue;
                }
            }
        }

        function fieldWrapperHtml(field) {
            if (field.type === 'checkbox') {
                return `
                <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                    <input type="checkbox" name="${field.name}" class="rounded border-zinc-300 dark:border-zinc-700 text-orange-600 focus:ring-orange-500">
                    ${field.label}
                </label>
            `;
            }

            if (field.type === 'select') {
                // Static options (e.g. a fixed PORT/GENERAL choice) vs dynamic
                // optionsSource (fetched from another endpoint) are both
                // supported - static ones render inline immediately.
                const staticOptionsHtml = (field.options ?? [])
                    .map((opt) => `<option value="${opt.value}">${opt.label}</option>`)
                    .join('');

                return `
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">${field.label}${field.required ? ' <span class="req-asterisk">*</span>' : ''}</label>
                    <select name="${field.name}" ${field.required ? 'required' : ''}
                            class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <option value="">Select ${field.label}</option>
                        ${staticOptionsHtml}
                    </select>
                </div>
            `;
            }

            if (field.type === 'currency') {
                return `
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">${field.label}${field.required ? ' <span class="req-asterisk">*</span>' : ''}</label>
                    <input type="text" inputmode="decimal" name="${field.name}" ${field.required ? 'required' : ''}
                           placeholder="${field.placeholder ?? '0.00'}"
                           class="currency-input w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
                </div>
            `;
            }

            return `
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">${field.label}${field.required ? ' <span class="req-asterisk">*</span>' : ''}</label>
                <input type="${field.type}" name="${field.name}" ${field.required ? 'required' : ''}
                       ${field.step ? `step="${field.step}"` : ''}
                       placeholder="${field.placeholder ?? ''}"
                       class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
            </div>
        `;
        }

        async function populateSelectOptions(selectEl, sourceKey) {
            const source = OPTION_SOURCE_MAPPING[sourceKey];
            if (!source) return;

            const response = await apiCall({
                mode: 'GET',
                url: source.url
            });
            if (!response.success) return;

            // ADJUST ME: change this line if your apiCall/pagination shape differs.
            const rows = response.data?.data ?? [];

            const optionsHtml = rows
                .map((row) => `<option value="${row[source.value]}">${source.label(row)}</option>`)
                .join('');

            selectEl.insertAdjacentHTML('beforeend', optionsHtml);
        }

        function collectFormData(config, isEdit) {
            const form = document.getElementById('maintenanceForm');
            const fields = getFieldsFor(config, isEdit);
            const payload = {};

            fields.forEach((field) => {
                const el = form.querySelector(`[name="${field.name}"]`);
                if (!el) return;

                if (field.type === 'checkbox') {
                    payload[field.name] = el.checked;
                } else if (field.type === 'number') {
                    payload[field.name] = el.value === '' ? null : Number(el.value);
                } else if (field.type === 'currency') {
                    const clean = parseCurrencyValue(el.value);
                    payload[field.name] = clean === '' ? null : Number(clean);
                } else {
                    payload[field.name] = el.value;
                }
            });

            return payload;
        }

        async function openAddForm(key) {
            const config = ENTITY_CONFIG[key];
            editingId = null;

            document.getElementById('maintenanceFormTitle').textContent = `Add ${config.label}`;
            renderFormFields(config, false);

            initSideModal({
                modalId: 'maintenanceFormModal'
            });
        }

        async function openEditForm(key, id) {
            if (key === 'containers') return openContainerForm(id);
            if (key === 'locations') return openLocationForm(id);
            if (key === 'lanes') return openLaneForm(id);
            const config = ENTITY_CONFIG[key];
            editingId = id;

            // Pull the row fresh so the form always reflects the latest data.
            const response = await apiCall({
                mode: 'GET',
                url: `${config.listUrl}/${id}`
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: `Unable to load this ${config.label} record. Please contact the system administrator.`,
                });
                return;
            }

            // ADJUST ME: change this line if your show() response is wrapped differently.
            const row = response.data;

            document.getElementById('maintenanceFormTitle').textContent = `Edit ${config.label}`;
            await renderFormFields(config, true, row);

            initSideModal({
                modalId: 'maintenanceFormModal'
            });
        }

        async function handleFormSubmit(event) {
            event.preventDefault();

            const config = ENTITY_CONFIG[activeTab];
            const isEdit = Boolean(editingId);
            const payload = collectFormData(config, isEdit);
            const button = document.getElementById('maintenanceFormSubmitBtn');

            const response = await apiCall({
                mode: isEdit ? 'PUT' : 'POST',
                isJson: true,
                payload,
                url: isEdit ? config.updateUrl(editingId) : config.createUrl,
                button,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: 'An unexpected error occurred. Please contact the system administrator.',
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Saved',
                message: `${config.label} ${isEdit ? 'updated' : 'added'} successfully.`,
            });

            closeSideModal('maintenanceFormModal');
            getOrCreateTable(activeTab).reload();
        }

        async function deleteRow(key, id) {
            const config = ENTITY_CONFIG[key];

            if (!confirm(`Delete this ${config.label}? This cannot be undone.`)) return;

            const response = await apiCall({
                mode: 'POST', // most apiCall wrappers proxy DELETE through POST + _method; adjust if yours supports 'DELETE' directly
                isJson: true,
                payload: {
                    _method: 'DELETE'
                },
                url: config.deleteUrl(id),
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: `Unable to delete this ${config.label}. It may still be referenced elsewhere.`,
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Deleted',
                message: `${config.label} removed.`
            });
            getOrCreateTable(key).reload();
        }

        // -----------------------------------------------------------------
        // Init - runs immediately since this script is injected after the
        // markup already exists in the SPA content area.
        // -----------------------------------------------------------------
        function init() {
            initTabs();

            document.querySelectorAll('.add-new-btn').forEach((btn) => {
                btn.addEventListener('click', () => {
                    if (btn.dataset.entity === 'containers') {
                        openContainerForm();
                    } else if (btn.dataset.entity === 'locations') {
                        openLocationForm();
                    } else if (btn.dataset.entity === 'lanes') {
                        openLaneForm();
                    } else {
                        openAddForm(btn.dataset.entity);
                    }
                });
            });

            const form = document.getElementById('maintenanceForm');
            if (form) form.addEventListener('submit', handleFormSubmit);

            const closeBtn = document.getElementById('maintenanceFormCloseBtn');
            if (closeBtn) closeBtn.addEventListener('click', () => closeSideModal('maintenanceFormModal'));

            const cancelBtn = document.getElementById('maintenanceFormCancelBtn');
            if (cancelBtn) cancelBtn.addEventListener('click', () => closeSideModal('maintenanceFormModal'));
        }

        init();

        let editingContainerId = null;

        // Size/class rows carry the existing row's id (hidden input, same
        // idRowHtml pattern as the Location modal's Ports/Serviceable Areas
        // below) so Container::syncCatalog() can rename a row in place
        // instead of deleting + recreating it - a rename never touches
        // anything already referencing that id (variants, lane tariff
        // pricing, proposals, contracts, bookings, container assets).
        function addSizeRow(value = '', id = null) {
            const wrap = document.getElementById('containerSizeRows');
            wrap.insertAdjacentHTML('beforeend', idRowHtml('id', 'size', 'e.g. 20-GP'));
            const row = wrap.lastElementChild;
            row.querySelector('[data-field="size"]').value = value;
            row.querySelector('[data-field="id"]').value = id ?? '';
        }

        function addClassRow(value = '', id = null) {
            const wrap = document.getElementById('containerClassRows');
            wrap.insertAdjacentHTML('beforeend', idRowHtml('id', 'class', 'e.g. A'));
            const row = wrap.lastElementChild;
            row.querySelector('[data-field="class"]').value = value;
            row.querySelector('[data-field="id"]').value = id ?? '';
        }

        async function removeCatalogRowOnClick(e) {
            const btn = e.target.closest('.remove-catalog-row');
            if (!btn) return;

            const row = btn.closest('[data-catalog-row]');

            // Only confirm for rows backed by an existing DB record - a
            // freshly-added blank row has no id yet and nothing to lose.
            const idInput = row.querySelector('input[type="hidden"]');
            if (idInput && idInput.value) {
                const confirmed = await window.customConfirm('Remove this entry? Any pricing built only on it (lane tariff rates, port charges, handling fees, trucking tariffs) will be deleted too once saved. This cannot be undone.');
                if (!confirmed) return;
            }

            row.remove();
        }

        document.getElementById('containerSizeRows').addEventListener('click', removeCatalogRowOnClick);
        document.getElementById('containerClassRows').addEventListener('click', removeCatalogRowOnClick);

        document.getElementById('addSizeRowBtn').addEventListener('click', () => addSizeRow());
        document.getElementById('addClassRowBtn').addEventListener('click', () => addClassRow());

        async function openContainerForm(id = null) {
            editingContainerId = id;
            document.getElementById('containerForm').reset();
            document.getElementById('containerSizeRows').innerHTML = '';
            document.getElementById('containerClassRows').innerHTML = '';
            document.getElementById('containerFormTitle').textContent = id ? 'Edit Container' : 'Add Container';

            if (id) {
                const response = await apiCall({
                    mode: 'GET',
                    url: `/api/containers/${id}`
                });
                if (!response.success) {
                    showMessage({
                        status: 'error',
                        title: 'Error',
                        message: 'Unable to load this container.'
                    });
                    return;
                }
                const row = response.data;
                document.getElementById('containerIdInput').value = row.id;
                document.getElementById('containerCodeInput').value = row.code;
                document.getElementById('containerNameInput').value = row.name;
                document.getElementById('containerActiveInput').checked = Boolean(row.is_active);
                (row.sizes ?? []).forEach((s) => addSizeRow(s.size, s.id));
                (row.classes ?? []).forEach((c) => addClassRow(c.class, c.id));
            } else {
                document.getElementById('containerIdInput').value = '';
                addSizeRow();
            }

            initSideModal({
                modalId: 'containerFormModal'
            });
        }

        document.getElementById('containerForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const sizes = collectIdRows('containerSizeRows', 'id', 'size');
            const classes = collectIdRows('containerClassRows', 'id', 'class');

            if (!sizes.length && !classes.length) {
                showMessage({
                    status: 'error',
                    title: 'Incomplete',
                    message: 'Add at least one size, or at least one class for containers with no fixed size (e.g. Loose Cargo, Rolling Cargo).'
                });
                return;
            }

            const payload = {
                code: document.getElementById('containerCodeInput').value,
                name: document.getElementById('containerNameInput').value,
                is_active: document.getElementById('containerActiveInput').checked,
                sizes,
                classes,
            };

            const isEdit = Boolean(editingContainerId);
            const button = document.getElementById('containerFormSubmitBtn');

            const response = await apiCall({
                mode: isEdit ? 'PUT' : 'POST',
                isJson: true,
                payload,
                url: isEdit ? `/api/containers/${editingContainerId}` : '/api/containers',
                button,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: 'Unable to save this container.'
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Saved',
                message: `Container ${isEdit ? 'updated' : 'added'}.`
            });
            closeSideModal('containerFormModal');
            getOrCreateTable('containers').reload();
        });

        document.getElementById('containerFormCloseBtn').addEventListener('click', () => closeSideModal(
            'containerFormModal'));
        document.getElementById('containerFormCancelBtn').addEventListener('click', () => closeSideModal(
            'containerFormModal'));

        // -----------------------------------------------------------------
        // Location form - Ports and Serviceable Areas are managed as
        // nested multi-row lists here (same catalogRowHtml pattern as the
        // Container modal's Sizes/Classes), instead of their own tabs.
        // Each row optionally carries the existing port_id/area_id (hidden
        // input) so Location::syncPorts()/syncServiceableAreas() can tell
        // an edit from a brand new row.
        // -----------------------------------------------------------------
        let editingLocationId = null;

        function idRowHtml(idField, textField, placeholder) {
            return `
        <div class="catalog-row flex items-center gap-2" data-catalog-row>
            <input type="hidden" data-field="${idField}">
            <input type="text" data-field="${textField}" required placeholder="${placeholder}"
                   class="flex-1 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
            <button type="button" class="remove-catalog-row text-zinc-400 hover:text-red-600 p-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
        }

        function addPortRow(name = '', portId = null) {
            const wrap = document.getElementById('locationPortRows');
            wrap.insertAdjacentHTML('beforeend', idRowHtml('port_id', 'name', 'e.g. Caticlan Port'));
            const row = wrap.lastElementChild;
            row.querySelector('[data-field="name"]').value = name;
            row.querySelector('[data-field="port_id"]').value = portId ?? '';
        }

        function addAreaRow(name = '', areaId = null) {
            const wrap = document.getElementById('locationAreaRows');
            wrap.insertAdjacentHTML('beforeend', idRowHtml('area_id', 'area_name', 'e.g. Cabuyao'));
            const row = wrap.lastElementChild;
            row.querySelector('[data-field="area_name"]').value = name;
            row.querySelector('[data-field="area_id"]').value = areaId ?? '';
        }

        document.getElementById('locationPortRows').addEventListener('click', removeCatalogRowOnClick);
        document.getElementById('locationAreaRows').addEventListener('click', removeCatalogRowOnClick);

        document.getElementById('addPortRowBtn').addEventListener('click', () => addPortRow());
        document.getElementById('addAreaRowBtn').addEventListener('click', () => addAreaRow());

        async function openLocationForm(id = null) {
            editingLocationId = id;
            document.getElementById('locationForm').reset();
            document.getElementById('locationPortRows').innerHTML = '';
            document.getElementById('locationAreaRows').innerHTML = '';
            document.getElementById('locationFormTitle').textContent = id ? 'Edit Location' : 'Add Location';

            if (id) {
                const response = await apiCall({
                    mode: 'GET',
                    url: `/api/locations/${id}`
                });
                if (!response.success) {
                    showMessage({
                        status: 'error',
                        title: 'Error',
                        message: 'Unable to load this location.'
                    });
                    return;
                }
                const row = response.data;
                document.getElementById('locationIdInput').value = row.location_id;
                document.getElementById('locationNameInput').value = row.name;
                document.getElementById('locationActiveInput').checked = Boolean(row.is_active);
                (row.ports ?? []).forEach((p) => addPortRow(p.name, p.port_id));
                (row.serviceable_areas ?? []).forEach((a) => addAreaRow(a.area_name, a.area_id));
            } else {
                document.getElementById('locationIdInput').value = '';
            }

            initSideModal({
                modalId: 'locationFormModal'
            });
        }

        function collectIdRows(wrapperId, idField, textField) {
            return Array.from(document.querySelectorAll(`#${wrapperId} [data-catalog-row]`))
                .map((row) => {
                    const textValue = row.querySelector(`[data-field="${textField}"]`).value.trim();
                    const idValue = row.querySelector(`[data-field="${idField}"]`).value.trim();
                    const entry = {
                        [textField]: textValue
                    };
                    if (idValue) entry[idField] = Number(idValue);
                    return entry;
                })
                .filter((entry) => entry[textField]);
        }

        document.getElementById('locationForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const payload = {
                name: document.getElementById('locationNameInput').value,
                is_active: document.getElementById('locationActiveInput').checked,
                ports: collectIdRows('locationPortRows', 'port_id', 'name'),
                serviceable_areas: collectIdRows('locationAreaRows', 'area_id', 'area_name'),
            };

            const isEdit = Boolean(editingLocationId);
            const button = document.getElementById('locationFormSubmitBtn');

            const response = await apiCall({
                mode: isEdit ? 'PUT' : 'POST',
                isJson: true,
                payload,
                url: isEdit ? `/api/locations/${editingLocationId}` : '/api/locations',
                button,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: 'Unable to save this location. A port or serviceable area removed here may still be referenced elsewhere.'
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Saved',
                message: `Location ${isEdit ? 'updated' : 'added'}.`
            });
            closeSideModal('locationFormModal');
            getOrCreateTable('locations').reload();
        });

        document.getElementById('locationFormCloseBtn').addEventListener('click', () => closeSideModal(
            'locationFormModal'));
        document.getElementById('locationFormCancelBtn').addEventListener('click', () => closeSideModal(
            'locationFormModal'));

        // -----------------------------------------------------------------
        // Lane form - Tariff Rates live here instead of their own tab (same
        // idea as Ports/Serviceable Areas inside the Location modal). Every
        // available container combination (base size-only, size+class, and
        // class-only for no-fixed-size types like Loose/Rolling Cargo) is
        // auto-listed, grouped by container type, 2 columns wide. There's no
        // explicit "add new rate version" UI: saving always writes to the
        // lane's single current/active tariff rate (creating one on first
        // save), matching how this modal is just "the lane's pricing", not a
        // rate-history editor.
        // -----------------------------------------------------------------
        let editingLaneId = null;
        let editingLaneTariffRateId = null;

        function variantLabel(variant) {
            const size = variant.container_size?.size ?? null;
            const cls = variant.container_class?.class ?? null;
            if (size && cls) return `${size} / ${cls}`;
            if (size) return `${size} (Base)`;
            if (cls) return cls;
            return 'Base';
        }

        async function renderLaneTariffGroups(existingRates = []) {
            const groupsEl = document.getElementById('laneTariffGroups');
            groupsEl.innerHTML = '<p class="text-xs text-zinc-400">Loading container combinations…</p>';

            const response = await apiCall({
                mode: 'GET',
                url: '/api/containers/variants'
            });
            if (!response.success) {
                groupsEl.innerHTML = '<p class="text-xs text-zinc-400">Unable to load container combinations.</p>';
                return;
            }

            const priceByVariant = {};
            (existingRates?.[0]?.prices ?? []).forEach((p) => {
                priceByVariant[p.container_variant_id] = p.frt;
            });
            editingLaneTariffRateId = existingRates?.[0]?.rate_id ?? null;

            const byContainer = new Map();
            response.data.forEach((variant) => {
                const key = variant.container?.id ?? 'unknown';
                if (!byContainer.has(key)) byContainer.set(key, {
                    name: variant.container?.name ?? 'Container',
                    code: variant.container?.code ?? '',
                    variants: []
                });
                byContainer.get(key).variants.push(variant);
            });

            if (!byContainer.size) {
                groupsEl.innerHTML =
                    '<p class="text-xs text-zinc-400">No containers configured yet — add one from the Containers tab first.</p>';
                return;
            }

            groupsEl.innerHTML = Array.from(byContainer.values()).map((group) => `
        <div>
            <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 mb-2">${group.name}${group.code ? ` <span class="text-zinc-400 font-normal">(${group.code})</span>` : ''}</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                ${group.variants.map((variant) => `
                <div class="flex items-center gap-3 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2">
                    <div class="flex-1 text-xs text-zinc-600 dark:text-zinc-300">${variantLabel(variant)}</div>
                    <input type="text" inputmode="decimal" placeholder="0.00"
                           data-variant-id="${variant.id}"
                           value="${formatCurrencyDisplay(priceByVariant[variant.id] ?? '')}"
                           class="currency-input w-28 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 px-2 py-1.5 text-sm focus:border-orange-500 focus:ring-orange-500">
                </div>
                `).join('')}
            </div>
        </div>
    `).join('');
        }

        function collectLaneTariffPrices() {
            return Array.from(document.querySelectorAll('#laneTariffGroups [data-variant-id]'))
                .filter((el) => parseCurrencyValue(el.value) !== '')
                .map((el) => ({
                    container_variant_id: el.dataset.variantId,
                    frt: Number(parseCurrencyValue(el.value))
                }));
        }

        async function openLaneForm(id = null) {
            editingLaneId = id;
            editingLaneTariffRateId = null;
            document.getElementById('laneForm').reset();
            document.getElementById('laneOriginPortInput').innerHTML = '<option value="">Select origin port…</option>';
            document.getElementById('laneDestinationPortInput').innerHTML =
                '<option value="">Select destination port…</option>';
            document.getElementById('laneFormTitle').textContent = id ? 'Edit Lane' : 'Add Lane';

            await Promise.all([
                populateSelectOptions(document.getElementById('laneOriginPortInput'), 'ports'),
                populateSelectOptions(document.getElementById('laneDestinationPortInput'), 'ports'),
            ]);

            let existingRates = [];

            if (id) {
                const response = await apiCall({
                    mode: 'GET',
                    url: `/api/lanes/${id}`
                });
                if (!response.success) {
                    showMessage({
                        status: 'error',
                        title: 'Error',
                        message: 'Unable to load this lane.'
                    });
                    return;
                }
                const row = response.data;
                document.getElementById('laneIdInput').value = row.lane_id;
                document.getElementById('laneOriginPortInput').value = row.origin_port_id;
                document.getElementById('laneDestinationPortInput').value = row.destination_port_id;
                document.getElementById('laneActiveInput').checked = Boolean(row.is_active);
                existingRates = row.tariff_rates ?? [];
            } else {
                document.getElementById('laneIdInput').value = '';
            }

            await renderLaneTariffGroups(existingRates);

            initSideModal({
                modalId: 'laneFormModal'
            });
        }

        document.getElementById('laneForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const isEdit = Boolean(editingLaneId);
            const button = document.getElementById('laneFormSubmitBtn');

            const lanePayload = {
                origin_port_id: Number(document.getElementById('laneOriginPortInput').value),
                destination_port_id: Number(document.getElementById('laneDestinationPortInput').value),
                is_active: document.getElementById('laneActiveInput').checked,
            };

            const laneResponse = await apiCall({
                mode: isEdit ? 'PUT' : 'POST',
                isJson: true,
                payload: lanePayload,
                url: isEdit ? `/api/lanes/${editingLaneId}` : '/api/lanes',
                button,
            });

            if (!laneResponse.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: 'Unable to save this lane.'
                });
                return;
            }

            const laneId = editingLaneId ?? laneResponse.data.lane_id;
            const prices = collectLaneTariffPrices();

            const rateResponse = await apiCall({
                mode: editingLaneTariffRateId ? 'PUT' : 'POST',
                isJson: true,
                payload: editingLaneTariffRateId ?
                    { prices } : {
                        lane_id: laneId,
                        effective_date: new Date().toISOString().slice(0, 10),
                        prices,
                    },
                url: editingLaneTariffRateId ?
                    `/api/laneTariffRates/${editingLaneTariffRateId}` : '/api/laneTariffRates',
            });

            if (!rateResponse.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: 'Lane saved, but the tariff rates could not be saved.'
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Saved',
                message: `Lane ${isEdit ? 'updated' : 'added'}.`
            });
            closeSideModal('laneFormModal');
            getOrCreateTable('lanes').reload();
        });

        document.getElementById('laneFormCloseBtn').addEventListener('click', () => closeSideModal('laneFormModal'));
        document.getElementById('laneFormCancelBtn').addEventListener('click', () => closeSideModal(
            'laneFormModal'));

        // -----------------------------------------------------------------
        // General Lookups tab - generic Option -> List-of-Values management
        // (ported from the old /page_lookupValues page). Type of Business,
        // Address Type, and Lead Source (used by the CRM lead form) all live
        // here as Options, editable without further code changes.
        // -----------------------------------------------------------------
        // One createRemoteTable() instance per Option category, keyed by
        // option_id, exactly like `tables` does for the entity tabs above.
        const lovTables = {};

        function categoryCardHtml(option) {
            return `
        <div class="category-card border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden bg-white dark:bg-zinc-900" data-option-id="${option.option_id}">
            <div class="flex items-center justify-between gap-3 px-5 py-3 bg-gradient-to-r from-orange-50 to-white dark:from-zinc-800 dark:to-zinc-900 border-b border-zinc-200 dark:border-zinc-800">
                <div>
                    <p class="text-base font-semibold text-zinc-900 dark:text-white">${option.option_name}</p>
                    ${option.option_description ? `<p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">${option.option_description}</p>` : ''}
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button data-option-id="${option.option_id}" type="button" title="Delete category"
                        class="delete-option text-zinc-400 hover:text-red-600 dark:hover:text-red-400 p-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 7h12m-1 0-1 14a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2L7 7m3 0V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2" />
                        </svg>
                    </button>
                    <button data-option-id="${option.option_id}"
                        class="add-btn inline-flex items-center gap-1.5 rounded-lg bg-orange-600 px-3.5 py-2 text-sm font-medium text-white hover:bg-orange-700 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add New
                    </button>
                </div>
            </div>

            <div class="p-4">
                <div class="flex gap-2 mb-4">
                    <input type="text" data-lov-search-input
                        placeholder="Search ${option.option_name.toLowerCase()}..."
                        class="w-full max-w-xs rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <button type="button" data-lov-search-btn
                        class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 px-3.5 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.3 5.3a7.5 7.5 0 0 0 10.5 10.5Z" />
                        </svg>
                        Search
                    </button>
                </div>

                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white whitespace-nowrap">Code</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white whitespace-nowrap">Name</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white whitespace-nowrap">Description</th>
                                <th class="px-4 py-2.5 text-right text-xs font-medium uppercase tracking-wide bg-orange-500 text-white whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800" data-lov-table-body></tbody>
                    </table>
                    <div data-lov-table-pagination></div>
                </div>
            </div>
        </div>`;
        }

        function buildLovRow(lov) {
            return `
            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                <td class="px-4 py-2.5 text-blue-600 dark:text-blue-400 font-semibold uppercase whitespace-nowrap">${lov.lov_code ?? '-'}</td>
                <td class="px-4 py-2.5 text-black dark:text-white">${lov.lov_name}</td>
                <td class="px-4 py-2.5 text-black dark:text-white">${lov.lov_description ?? '-'}</td>
                <td class="px-4 py-2.5 text-right whitespace-nowrap">
                    <button type="button" class="delete-btn p-1.5 text-zinc-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-lg transition"
                        data-option-id="${lov.lov_optionId}" data-lov-id="${lov.lov_id}" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 pointer-events-none" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 6h18v2H3V6zm2 3h14l-1.5 12.5a1 1 0 0 1-1 .5H8a1 1 0 0 1-1-.5L5 9zm5 2v8h2v-8H10zm4 0v8h2v-8h-2zM9 4V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1h5v2H4V4h5z"/>
                        </svg>
                    </button>
                </td>
            </tr>`;
        }

        async function loadOptions() {
            const options = await apiCall({
                mode: 'GET',
                url: '/api/options'
            });
            const optionsContainer = document.getElementById('optionsContainer');
            optionsContainer.innerHTML = '';

            if (!options.length) {
                optionsContainer.innerHTML =
                    `<p class="text-sm text-zinc-400 text-center py-10">No lookup categories yet. Click "Add New Option" to create one.</p>`;
                return;
            }

            options.forEach((option) => {
                optionsContainer.insertAdjacentHTML('beforeend', categoryCardHtml(option));

                const card = optionsContainer.querySelector(`[data-option-id="${option.option_id}"]`);

                lovTables[option.option_id] = window.createRemoteTable({
                    url: `/api/options/${option.option_id}/values`,
                    tableBodySelector: card.querySelector('[data-lov-table-body]'),
                    paginationSelector: card.querySelector('[data-lov-table-pagination]'),
                    searchInputSelector: card.querySelector('[data-lov-search-input]'),
                    searchButtonSelector: card.querySelector('[data-lov-search-btn]'),
                    emptyMessage: 'No values yet.',
                    colspan: 4,
                    rowTemplate: (lov) => buildLovRow(lov),
                });

                lovTables[option.option_id].load(1);
            });
        }

        const newOptionButton = document.getElementById('newOptionButton');
        const SaveOption = document.getElementById('SaveOption');
        const lovContainer = document.getElementById('lovContainer');

        newOptionButton.addEventListener('click', function() {
            initModal({
                modalId: 'NewOptionModal',
            });
        });

        SaveOption.addEventListener('click', async function() {
            const OptionName = document.getElementById('OptionName').value;
            const OptionDescription = document.getElementById('OptionDescription').value;
            const lovItems = document.querySelectorAll('.lov-item');

            const lovData = [];
            lovItems.forEach(item => {
                const lov_code = item.querySelector('.lov-code')?.value || '';
                const lov_name = item.querySelector('.lov-name')?.value || '';
                const lov_description = item.querySelector('.lov-description')?.value || '';

                lovData.push({
                    lov_code,
                    lov_name,
                    lov_description
                });
            });

            const payload = {
                option_name: OptionName,
                option_description: OptionDescription,
                lovData: lovData,
            };

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: payload,
                url: '/api/options',
                button: SaveOption
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error Saving Options',
                    message: 'There is some error saving your information. Please contact system administrator',
                });
                return;
            }

            function clearinputs() {
                document.getElementById('OptionName').value = '';
                document.getElementById('OptionDescription').value = '';
                document.getElementById('lovContainer').innerHTML = '';
            }

            showMessage({
                status: 'success',
                title: 'Option Saved!',
            });
            clearinputs();
            loadOptions();
            closemodals();
        });

        const addLovButton = document.getElementById('addLovButton');
        addLovButton.addEventListener('click', function() {
            const lovTemplate = document.createElement('div');
            lovTemplate.classList.add('lov-item', 'flex', 'p-2', 'rounded', 'border', 'border-zinc-300',
                'dark:border-zinc-700', 'gap-4', 'mb-2',
                'bg-white', 'dark:bg-zinc-800', 'text-black', 'dark:text-zinc-100');

            lovTemplate.innerHTML = `
        <div class="flex-1">
            <p>List of Value Code</p>
            <input type="text" class="lov-code w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 rounded px-2 py-1 text-black dark:text-zinc-100">
        </div>
        <div class="flex-1">
            <p>List of Value Name</p>
            <input type="text" class="lov-name w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 rounded px-2 py-1 text-black dark:text-zinc-100">
        </div>
        <div class="flex-1">
            <p>List of Value Description</p>
            <input type="text" class="lov-description w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 rounded px-2 py-1 text-black dark:text-zinc-100">
        </div>
        <div class="flex items-end">
            <button type="button" class="remove-lov aspect-square p-2 bg-red-500 text-white rounded font-black">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12m-1 0-1 14a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2L7 7m3 0V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2" />
</svg>
            </button>
        </div>
    `;

            lovContainer.appendChild(lovTemplate);
        });

        lovContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-lov')) {
                e.target.closest('.lov-item').remove();
            }
        });

        $(document).on('click', '.delete-btn', function() {
            const optionID = $(this).data('optionId');
            const lovID = $(this).data('lovId');
            deleteLOV(optionID, lovID, this);
        });

        $(document).on('click', '.delete-option', function() {
            const optionID = $(this).data('optionId');
            deleteOption(optionID, this);
        });

        $(document).on('click', '.add-btn', function() {
            addLOV($(this).data('optionId'));
        });

        function addLOV(optionID) {
            initModal({
                modalId: 'addLOVModal',
            });
            document.getElementById('OptionID').value = optionID;
        }

        const submitAddLOV = document.getElementById('SaveLOV');
        submitAddLOV.addEventListener('click', async function() {
            const addLOVCode = document.querySelector('.add-lov-code').value;
            const addLOVName = document.querySelector('.add-lov-name').value;
            const addLOVDescription = document.querySelector('.add-lov-description').value;
            const OptionID = document.getElementById('OptionID').value;

            const payload = {
                addLOVCode: addLOVCode,
                addLOVName: addLOVName,
                addLOVDescription: addLOVDescription,
                optionID: OptionID,
            };

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: payload,
                url: '/api/lov',
                button: submitAddLOV
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error Saving Headline',
                    message: 'There is some error saving your information. Please contact system administrator',
                });
                return;
            }

            document.querySelector('.add-lov-code').value = '';
            document.querySelector('.add-lov-name').value = '';
            document.querySelector('.add-lov-description').value = '';

            showMessage({
                status: 'success',
                title: 'Headline Saved!',
            });
            lovTables[OptionID]?.reload();
            closemodals();
        });

        async function deleteLOV(optionID, lovID, deleteButton) {
            const confirmed = await customConfirm('Do you really want to delete this item?');
            if (!confirmed) return;

            const response = await apiCall({
                mode: 'DELETE',
                isJson: true,
                payload: {
                    optionID,
                    lovID
                },
                url: '/api/lov',
                button: deleteButton
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error Deleting LOV item',
                    message: 'There is some error deleting LOV item. Please contact system administrator',
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'LOV Deleted!',
            });
            lovTables[optionID]?.reload();
            closemodals();
        }

        async function deleteOption(optionID, deleteButton) {
            const confirmed = await customConfirm('Do you really want to delete this item?');
            if (!confirmed) return;

            const response = await apiCall({
                mode: 'DELETE',
                isJson: true,
                payload: {
                    optionID
                },
                url: '/api/options',
                button: deleteButton
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error Deleting Option',
                    message: 'There is some error deleting Option. Please contact system administrator',
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Option Deleted!',
            });
            loadOptions();
            closemodals();
        }
    })();
</script>
