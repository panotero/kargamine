{{-- Rate Maintenance - loaded into the SPA content area, not a full page --}}
<div class="max-w-7xl mx-auto px-4 py-6">

    <div class="mb-6">
        <h1 class="text-xl font-semibold text-zinc-900">Rate Maintenance</h1>
        <p class="text-sm text-zinc-500 mt-1">Manage the master data and rate tables used by the freight booking engine.
        </p>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-zinc-200">
        <nav class="flex flex-wrap gap-1 -mb-px" id="maintenanceTabs">
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 hover:text-zinc-800"
                data-tab="ports">Ports</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 hover:text-zinc-800"
                data-tab="containers">Containers</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 hover:text-zinc-800"
                data-tab="chargeTypes">Charge Types</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 hover:text-zinc-800"
                data-tab="deliveryTypes">Delivery Types</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 hover:text-zinc-800"
                data-tab="serviceableAreas">Serviceable Areas</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 hover:text-zinc-800"
                data-tab="lanes">Lanes</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 hover:text-zinc-800"
                data-tab="laneTariffRates">Lane Tariff Rates</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 hover:text-zinc-800"
                data-tab="generalCharges">General Charges</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 hover:text-zinc-800"
                data-tab="portCharges">Port Charges</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 hover:text-zinc-800"
                data-tab="handlingFees">Handling Fees</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 hover:text-zinc-800"
                data-tab="truckingTariffs">Trucking Tariffs</button>
            <button type="button"
                class="maintenance-tab-btn px-3.5 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 hover:text-zinc-800"
                data-tab="vatRates">VAT Rates</button>
        </nav>
    </div>

    {{-- Tab panels --}}
    <div class="mt-5" id="maintenanceTabPanels">
        @foreach ([
        'ports' => 'Ports',
        'containers' => 'Containers',
        'chargeTypes' => 'Charge Types',
        'deliveryTypes' => 'Delivery Types',
        'serviceableAreas' => 'Serviceable Areas',
        'lanes' => 'Lanes',
        'laneTariffRates' => 'Lane Tariff Rates',
        'generalCharges' => 'General Charges',
        'portCharges' => 'Port Charges',
        'handlingFees' => 'Handling Fees',
        'truckingTariffs' => 'Trucking Tariffs',
        'vatRates' => 'VAT Rates',
    ] as $key => $label)
            <div class="tab-panel hidden" data-tab-panel="{{ $key }}">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-medium text-zinc-900">{{ $label }}</h2>
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

                <div class="bg-white border border-zinc-200 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm">
                        <thead class="bg-zinc-50">
                            <tr data-table-head="{{ $key }}"></tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100" data-table-body="{{ $key }}"></tbody>
                    </table>
                    <x-table-pagination :id="$key" />
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Generic slide-over form - fields are injected by JS based on which entity's "Add New"/"Edit" was clicked --}}
<x-side-modal id="maintenanceFormModal">
    <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-200">
        <h3 id="maintenanceFormTitle" class="text-base font-semibold text-zinc-900">Add Item</h3>
        <button type="button" id="maintenanceFormCloseBtn" class="text-zinc-400 hover:text-zinc-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <form id="maintenanceForm" class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
        <div id="maintenanceFormFields" class="space-y-4"></div>
    </form>

    <div class="px-5 py-4 border-t border-zinc-200 flex justify-end gap-2">
        <button type="button" id="maintenanceFormCancelBtn"
            class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50">
            Cancel
        </button>
        <button type="submit" form="maintenanceForm" id="maintenanceFormSubmitBtn"
            class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700">
            Save
        </button>
    </div>
</x-side-modal>

<x-side-modal id="containerFormModal">
    <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-200">
        <h3 id="containerFormTitle" class="text-base font-semibold text-zinc-900">Add Container</h3>
        <button type="button" id="containerFormCloseBtn" class="text-zinc-400 hover:text-zinc-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <form id="containerForm" class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
        <input type="hidden" id="containerIdInput">

        <div class="grid grid-cols-2 gap-3">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-zinc-700 mb-1">
                    Container Type *
                </label>
                <input type="text" id="containerTypeInput" required
                    placeholder="e.g. Dry Van, Reefer, Open Top, Flat Rack"
                    class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Code *</label>
                <input type="text" id="containerCodeInput" required placeholder="e.g. RF-20"
                    class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Name *</label>
                <input type="text" id="containerNameInput" required placeholder="e.g. 20ft Reefer"
                    class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
            </div>
            <div class="col-span-2">
                <label class="flex items-center gap-2 text-sm text-zinc-700">
                    <input type="checkbox" id="containerActiveInput" checked
                        class="rounded border-zinc-300 text-orange-600 focus:ring-orange-500">
                    Active
                </label>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-zinc-700">Class / Size Combinations *</label>
                <button type="button" id="addVariantRowBtn"
                    class="inline-flex items-center gap-1 text-xs font-medium text-orange-600 hover:text-orange-700">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Combination
                </button>
            </div>
            <p class="text-xs text-zinc-400 mb-2">Each row is a distinct class + size combo. Prices for each combo
                are set later, per lane, in the Lane Tariff Rates tab.</p>
            <div id="containerVariantRows" class="space-y-2"></div>
        </div>
    </form>

    <div class="px-5 py-4 border-t border-zinc-200 flex justify-end gap-2">
        <button type="button" id="containerFormCancelBtn"
            class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50">
            Cancel
        </button>
        <button type="submit" form="containerForm" id="containerFormSubmitBtn"
            class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700">
            Save Container
        </button>
    </div>
</x-side-modal>




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
        const TAB_INACTIVE_CLASSES = ['border-transparent', 'text-zinc-500'];

        // -----------------------------------------------------------------
        // STATUS / badge mapping
        // -----------------------------------------------------------------
        const ACTIVE_BADGE_MAPPING = {
            true: '<span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700">Active</span>',
            false: '<span class="inline-flex items-center rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-600">Inactive</span>',
        };

        function activeBadge(value) {
            return ACTIVE_BADGE_MAPPING[Boolean(value)];
        }

        const APPLICABLE_TO_BADGE_MAPPING = {
            PORT: '<span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">Port</span>',
            GENERAL: '<span class="inline-flex items-center rounded-full bg-purple-50 px-2 py-0.5 text-xs font-medium text-purple-700">General</span>',
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
            ports: {
                label: 'Port',
                pk: 'port_id',
                listUrl: '/api/ports',
                createUrl: '/api/ports',
                updateUrl: (id) => `/api/ports/${id}`,
                deleteUrl: (id) => `/api/ports/${id}`,
                columns: [{
                        key: 'code',
                        label: 'Code'
                    },
                    {
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
                        name: 'code',
                        label: 'Port Code',
                        type: 'text',
                        required: true,
                        placeholder: 'e.g. MNL'
                    },
                    {
                        name: 'name',
                        label: 'Port Name',
                        type: 'text',
                        required: true,
                        placeholder: 'e.g. Manila'
                    },
                    {
                        name: 'is_active',
                        label: 'Active',
                        type: 'checkbox',
                        default: true
                    },
                ],
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
                        key: 'type',
                        label: 'Type',
                        render: (row) => row.type?.type ?? '-'
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

            serviceableAreas: {
                label: 'Serviceable Area',
                pk: 'area_id',
                listUrl: '/api/serviceableAreas',
                createUrl: '/api/serviceableAreas',
                updateUrl: (id) => `/api/serviceableAreas/${id}`,
                deleteUrl: (id) => `/api/serviceableAreas/${id}`,
                columns: [{
                        key: 'port',
                        label: 'Port',
                        render: (row) => row.port?.code ?? '-'
                    },
                    {
                        key: 'area_name',
                        label: 'Area Name'
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
                        name: 'area_name',
                        label: 'Area Name',
                        type: 'text',
                        required: true,
                        placeholder: 'e.g. CABUYAO LAGUNA'
                    },
                    {
                        name: 'is_active',
                        label: 'Active',
                        type: 'checkbox',
                        default: true
                    },
                ],
            },

            lanes: {
                label: 'Lane',
                pk: 'lane_id',
                listUrl: '/api/lanes',
                createUrl: '/api/lanes',
                updateUrl: (id) => `/api/lanes/${id}`,
                deleteUrl: (id) => `/api/lanes/${id}`,
                columns: [{
                        key: 'origin',
                        label: 'Origin',
                        render: (row) => row.origin_port?.code ?? '-'
                    },
                    {
                        key: 'destination',
                        label: 'Destination',
                        render: (row) => row.destination_port?.code ?? '-'
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                fields: [{
                        name: 'origin_port_id',
                        label: 'Origin Port',
                        type: 'select',
                        required: true,
                        optionsSource: 'ports'
                    },
                    {
                        name: 'destination_port_id',
                        label: 'Destination Port',
                        type: 'select',
                        required: true,
                        optionsSource: 'ports'
                    },
                    {
                        name: 'is_active',
                        label: 'Active',
                        type: 'checkbox',
                        default: true
                    },
                ],
            },

            laneTariffRates: {
                label: 'Lane Tariff Rate',
                pk: 'rate_id',
                listUrl: '/api/laneTariffRates',
                createUrl: '/api/laneTariffRates',
                updateUrl: (id) => `/api/laneTariffRates/${id}`,
                deleteUrl: (id) => `/api/laneTariffRates/${id}`,
                versioned: true, // adding = new version, editing = correction only
                columns: [{
                        key: 'lane',
                        label: 'Lane',
                        render: (row) =>
                            `${row.lane?.origin_port?.code ?? '-'} → ${row.lane?.destination_port?.code ?? '-'}`
                    },
                    {
                        key: 'frt',
                        label: 'FRT',
                        render: (row) => money(row.frt)
                    },
                    {
                        key: 'effective_date',
                        label: 'Effective'
                    },
                    {
                        key: 'expiration_date',
                        label: 'Effective'
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                // Shown when adding a brand new rate version
                fields: [{
                        name: 'lane_id',
                        label: 'Lane',
                        type: 'select',
                        required: true,
                        optionsSource: 'lanes'
                    },
                    {
                        name: 'frt',
                        label: 'FRT',
                        type: 'number',
                        step: '0.01',
                        required: true
                    },
                    {
                        name: 'bsc',
                        label: 'BSC',
                        type: 'number',
                        step: '0.01',
                        required: true
                    },
                    {
                        name: 'ra',
                        label: 'RA',
                        type: 'number',
                        step: '0.01',
                        required: true
                    },
                    {
                        name: 'gri',
                        label: 'GRI',
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
                // Shown when editing an existing row - amounts + status only,
                // matches the controller's update() which won't touch lane_id/effective_date
                editFields: [{
                        name: 'frt',
                        label: 'FRT',
                        type: 'number',
                        step: '0.01',
                        required: true
                    },
                    {
                        name: 'bsc',
                        label: 'BSC',
                        type: 'number',
                        step: '0.01',
                        required: true
                    },
                    {
                        name: 'ra',
                        label: 'RA',
                        type: 'number',
                        step: '0.01',
                        required: true
                    },
                    {
                        name: 'gri',
                        label: 'GRI',
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
                        render: (row) => row.port?.code ?? '-'
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
                        label: 'Effective'
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
                        name: 'amount',
                        label: 'Amount',
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
                        label: 'Effective'
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
                        name: 'amount',
                        label: 'Amount',
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
                        render: (row) => row.port?.code ?? '-'
                    },
                    {
                        key: 'amount',
                        label: 'Amount',
                        render: (row) => money(row.amount)
                    },
                    {
                        key: 'effective_date',
                        label: 'Effective'
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
                        name: 'amount',
                        label: 'Amount',
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
                            `${row.serviceable_area?.port?.code ?? '-'} / ${row.serviceable_area?.area_name ?? '-'}`
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
                        label: 'Effective'
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
                        name: 'amount',
                        label: 'Amount',
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

            vatRates: {
                label: 'VAT Rate',
                pk: 'vat_rate_id',
                listUrl: '/api/vatRates',
                createUrl: '/api/vatRates',
                updateUrl: (id) => `/api/vatRates/${id}`,
                deleteUrl: (id) => `/api/vatRates/${id}`,
                versioned: true,
                columns: [{
                        key: 'rate_percent',
                        label: 'Rate %',
                        render: (row) => `${money(row.rate_percent)}%`
                    },
                    {
                        key: 'effective_date',
                        label: 'Effective'
                    },
                    {
                        key: 'is_active',
                        label: 'Status',
                        render: (row) => activeBadge(row.is_active)
                    },
                ],
                fields: [{
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
        };

        // Where each optionsSource pulls its dropdown list from, and how to
        // build the value/label pair for each <option>.
        const OPTION_SOURCE_MAPPING = {
            ports: {
                url: '/api/ports?per_page=100',
                value: 'port_id',
                label: (row) => `${row.code} - ${row.name}`
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
            lanes: {
                url: '/api/lanes?per_page=100',
                value: 'lane_id',
                label: (row) => `${row.origin_port?.code ?? '?'} → ${row.destination_port?.code ?? '?'}`
            },
            serviceableAreas: {
                url: '/api/serviceableAreas?per_page=200',
                value: 'area_id',
                label: (row) => `${row.port?.code ?? '?'} / ${row.area_name}`
            },
        };

        // -----------------------------------------------------------------
        // State
        // -----------------------------------------------------------------
        let activeTab = 'ports';
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
            if (!ENTITY_CONFIG[key]) return;

            activeTab = key;

            document.querySelectorAll('#maintenanceTabs .maintenance-tab-btn').forEach((btn) => {
                const isActive = btn.dataset.tab === key;
                btn.classList.remove(...TAB_ACTIVE_CLASSES, ...TAB_INACTIVE_CLASSES);
                btn.classList.add(...(isActive ? TAB_ACTIVE_CLASSES : TAB_INACTIVE_CLASSES));
            });

            document.querySelectorAll('[data-tab-panel]').forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.tabPanel !== key);
            });

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
                    `<th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-500 uppercase tracking-wide">${col.label}</th>`
                )
                .join('');

            headRow.innerHTML =
                `${headCells}<th class="px-4 py-2.5 text-right text-xs font-medium text-zinc-500 uppercase tracking-wide">Actions</th>`;
        }

        function buildRow(key, config, row) {
            const cells = config.columns
                .map((col) => {
                    const value = col.render ? col.render(row) : (getValueByPath(row, col.key) ?? '-');
                    return `<td class="px-4 py-2.5 text-zinc-700">${value}</td>`;
                })
                .join('');

            const id = row[config.pk];

            return `
            <tr>
                ${cells}
                <td class="px-4 py-2.5 text-right whitespace-nowrap">
                    <button type="button" class="text-orange-600 hover:text-orange-700 text-sm font-medium mr-3" data-edit-id="${id}">Edit</button>
                    <button type="button" class="text-zinc-400 hover:text-red-600 text-sm font-medium" data-delete-id="${id}">Delete</button>
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
                    document.querySelectorAll(`[data-table-body="${key}"] [data-edit-id]`).forEach((
                        btn) => {
                        btn.addEventListener('click', () => openEditForm(key, btn.dataset
                            .editId));
                    });

                    document.querySelectorAll(`[data-table-body="${key}"] [data-delete-id]`).forEach((
                        btn) => {
                        btn.addEventListener('click', () => deleteRow(key, btn.dataset
                            .deleteId));
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
                    el.value = currentValue;
                }
            }
        }

        function fieldWrapperHtml(field) {
            if (field.type === 'checkbox') {
                return `
                <label class="flex items-center gap-2 text-sm text-zinc-700">
                    <input type="checkbox" name="${field.name}" class="rounded border-zinc-300 text-orange-600 focus:ring-orange-500">
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
                    <label class="block text-sm font-medium text-zinc-700 mb-1">${field.label}${field.required ? ' *' : ''}</label>
                    <select name="${field.name}" ${field.required ? 'required' : ''}
                            class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
                        <option value="">Select ${field.label}</option>
                        ${staticOptionsHtml}
                    </select>
                </div>
            `;
            }

            return `
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">${field.label}${field.required ? ' *' : ''}</label>
                <input type="${field.type}" name="${field.name}" ${field.required ? 'required' : ''}
                       ${field.step ? `step="${field.step}"` : ''}
                       placeholder="${field.placeholder ?? ''}"
                       class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
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

            if (key === 'laneTariffRates') await renderLaneTariffPricingGrid(null);

            initSideModal({
                modalId: 'maintenanceFormModal'
            });
        }

        async function openEditForm(key, id) {
            if (key === 'containers') return openContainerForm(id);
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
            if (key === 'laneTariffRates') await renderLaneTariffPricingGrid(row);

            initSideModal({
                modalId: 'maintenanceFormModal'
            });
        }

        async function handleFormSubmit(event) {
            event.preventDefault();

            const config = ENTITY_CONFIG[activeTab];
            const isEdit = Boolean(editingId);
            const payload = collectFormData(config, isEdit);
            if (activeTab === 'laneTariffRates') {
                payload.prices = collectLaneTariffPrices();
            }
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
        let containerClassOptionsHtml = '';
        let containerSizeOptionsHtml = '';

        async function loadContainerLookups() {
            const [typesRes, classesRes, sizesRes] = await Promise.all([
                apiCall({
                    mode: 'GET',
                    url: '/api/containerTypes'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/containerClasses'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/containerSizes'
                }),
            ]);

            const typeSelect = document.getElementById('containerTypeSelect');
            if (classesRes.success) {
                containerClassOptionsHtml = classesRes.data.map((c) =>
                    `<option value="${c.id}">${c.class}</option>`).join('');
            }
            if (sizesRes.success) {
                containerSizeOptionsHtml = sizesRes.data.map((s) =>
                    `<option value="${s.id}">${s.size}</option>`).join('');
            }
        }

        function variantRowHtml() {
            return `
        <div class="variant-row flex items-center gap-2" data-variant-row>
            <select data-field="container_class_id" required
                    class="flex-1 rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
                <option value="">Select Class</option>${containerClassOptionsHtml}
            </select>
            <select data-field="container_size_id" required
                    class="flex-1 rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
                <option value="">Select Size</option>${containerSizeOptionsHtml}
            </select>
            <button type="button" class="remove-variant-row text-zinc-400 hover:text-red-600 p-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
        }

        function addVariantRow(selectedClassId = '', selectedSizeId = '') {
            const wrap = document.getElementById('containerVariantRows');
            wrap.insertAdjacentHTML('beforeend', variantRowHtml());
            const row = wrap.lastElementChild;
            if (selectedClassId) row.querySelector('[data-field="container_class_id"]').value = selectedClassId;
            if (selectedSizeId) row.querySelector('[data-field="container_size_id"]').value = selectedSizeId;
        }

        document.getElementById('containerVariantRows').addEventListener('click', (e) => {
            const btn = e.target.closest('.remove-variant-row');
            if (btn) btn.closest('[data-variant-row]').remove();
        });

        document.getElementById('addVariantRowBtn').addEventListener('click', () => addVariantRow());

        async function openContainerForm(id = null) {
            editingContainerId = id;
            document.getElementById('containerForm').reset();
            document.getElementById('containerVariantRows').innerHTML = '';
            document.getElementById('containerFormTitle').textContent = id ? 'Edit Container' : 'Add Container';

            await loadContainerLookups();

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
                document.getElementById('containerTypeSelect').value = row.container_type_id;
                document.getElementById('containerCodeInput').value = row.code;
                document.getElementById('containerNameInput').value = row.name;
                document.getElementById('containerActiveInput').checked = Boolean(row.is_active);
                (row.variants ?? []).forEach((v) => addVariantRow(v.container_class_id, v.container_size_id));
            } else {
                document.getElementById('containerIdInput').value = '';
                addVariantRow();
            }

            initSideModal({
                modalId: 'containerFormModal'
            });
        }

        function collectContainerVariants() {
            return Array.from(document.querySelectorAll('#containerVariantRows [data-variant-row]')).map((row) => ({
                container_class_id: row.querySelector('[data-field="container_class_id"]').value,
                container_size_id: row.querySelector('[data-field="container_size_id"]').value,
            }));
        }

        document.getElementById('containerForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const variants = collectContainerVariants();
            if (!variants.length || variants.some((v) => !v.container_class_id || !v
                    .container_size_id)) {
                showMessage({
                    status: 'error',
                    title: 'Incomplete',
                    message: 'Add at least one complete class + size combination.'
                });
                return;
            }

            const payload = {
                container_type_id: document.getElementById('containerTypeSelect').value,
                code: document.getElementById('containerCodeInput').value,
                name: document.getElementById('containerNameInput').value,
                is_active: document.getElementById('containerActiveInput').checked,
                variants,
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

        async function renderLaneTariffPricingGrid(existingRow = null) {
            const fieldsContainer = document.getElementById('maintenanceFormFields');

            const wrapper = document.createElement('div');
            wrapper.id = 'laneTariffPricingWrapper';
            wrapper.className = 'space-y-2 border-t border-zinc-200 pt-4';
            wrapper.innerHTML = `
        <label class="block text-sm font-medium text-zinc-700 mb-1">Container Pricing (FRT per combination)</label>
        <p class="text-xs text-zinc-400 mb-2">Set the freight rate for each container class/size combination on this lane.</p>
        <div id="laneTariffPricingRows" class="space-y-2 max-h-64 overflow-y-auto pr-1"></div>
    `;
            fieldsContainer.appendChild(wrapper);

            const response = await apiCall({
                mode: 'GET',
                url: '/api/containers/variants'
            });
            if (!response.success) return;

            const priceByVariant = {};
            (existingRow?.prices ?? []).forEach((p) => {
                priceByVariant[p.container_variant_id] = p.frt;
            });

            const rows = document.getElementById('laneTariffPricingRows');
            rows.innerHTML = response.data.map((variant) => `
        <div class="flex items-center gap-3 bg-zinc-50 border border-zinc-200 rounded-lg px-3 py-2">
            <div class="flex-1 text-xs text-zinc-600">
                <span class="font-medium text-zinc-800">${variant.container?.name ?? '-'}</span>
                — ${variant.container_class?.class ?? '-'} / ${variant.container_size?.size ?? '-'}
            </div>
            <input type="number" step="0.01" min="0" placeholder="0.00"
                   data-variant-id="${variant.id}"
                   value="${priceByVariant[variant.id] ?? ''}"
                   class="w-32 rounded-lg border border-zinc-300 px-2 py-1.5 text-sm focus:border-orange-500 focus:ring-orange-500">
        </div>
    `).join('') || '<p class="text-xs text-zinc-400">No containers configured yet — add one from the Containers tab first.</p>';
        }

        function collectLaneTariffPrices() {
            return Array.from(document.querySelectorAll('#laneTariffPricingRows [data-variant-id]'))
                .filter((el) => el.value !== '')
                .map((el) => ({
                    container_variant_id: el.dataset.variantId,
                    frt: Number(el.value)
                }));
        }
    })();
</script>
