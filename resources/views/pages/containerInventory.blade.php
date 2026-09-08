<div class="container mx-auto px-4 py-6">

    <div class="flex items-start justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold">Container Inventory</h1>
            <p class="text-zinc-500">The company's physical container fleet — status, and where each
                one currently is.</p>
        </div>
        <div class="flex items-center gap-5">
            {{-- Header stats derived purely from the already-loaded
                 status_counts, no extra API call beyond refreshCounts(). --}}
            <div class="text-right">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Available
                    Now</p>
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400" id="countAvailableStat">0</p>
            </div>
            <span class="w-px h-8 bg-zinc-200 dark:bg-zinc-700"></span>
            <div class="text-right">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Needs
                    Attention</p>
                <p class="text-2xl font-bold text-amber-600 dark:text-amber-400" id="countNeedsAttentionStat">0</p>
            </div>
            <button type="button" id="btnPrintQrLabels"
                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18h12v3H6zM4 9h16v7H4z" />
                </svg>
                Print QR Labels
            </button>
            <button type="button" id="btnRegisterContainer"
                class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">
                + Register Container
            </button>
        </div>
    </div>

    {{-- Status chips grouped by two independent axes: operational rotation vs.
         condition. Not an arrow-connected progression - these aren't a single
         linear sequence, so plain pills in two clusters (not a directional
         strip) is the correct treatment. Border/text hues match this file's
         STATUS_MAPPING badge colors so chips and table badges read as one
         color system. "All" is a reset action, not a state, so it gets the
         neutral dashed treatment. --}}
    <section class="w-full mb-4">
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" data-status=""
                class="assetStatusBtn ring-2 ring-orange-500 inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-dashed border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-zinc-500 dark:text-zinc-400 text-xs font-semibold hover:bg-zinc-50 dark:hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1">
                <span>All</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 rounded-full bg-zinc-100 dark:bg-zinc-800"
                    id="countAll">0</span>
            </button>

            <span class="mx-1 h-6 w-px bg-zinc-200 dark:bg-zinc-700"></span>

            <span class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">In
                Rotation</span>

            <button type="button" data-status="1"
                class="assetStatusBtn inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-emerald-300 dark:border-emerald-700 bg-white dark:bg-zinc-900 text-emerald-700 dark:text-emerald-300 text-xs font-semibold hover:bg-emerald-50 dark:hover:bg-emerald-950/30 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1">
                <span>Available</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 rounded-full bg-emerald-100 dark:bg-emerald-900/40"
                    id="countAvailable">0</span>
            </button>
            <button type="button" data-status="2"
                class="assetStatusBtn inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-blue-300 dark:border-blue-700 bg-white dark:bg-zinc-900 text-blue-700 dark:text-blue-300 text-xs font-semibold hover:bg-blue-50 dark:hover:bg-blue-950/30 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1">
                <span>Booked</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 rounded-full bg-blue-100 dark:bg-blue-900/40"
                    id="countBooked">0</span>
            </button>
            <button type="button" data-status="3"
                class="assetStatusBtn inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-indigo-300 dark:border-indigo-700 bg-white dark:bg-zinc-900 text-indigo-700 dark:text-indigo-300 text-xs font-semibold hover:bg-indigo-50 dark:hover:bg-indigo-950/30 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1">
                <span>In Transit</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 rounded-full bg-indigo-100 dark:bg-indigo-900/40"
                    id="countInTransit">0</span>
            </button>

            <span class="mx-1 h-6 w-px bg-zinc-200 dark:bg-zinc-700"></span>

            <span class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Needs
                Attention</span>

            <button type="button" data-status="4"
                class="assetStatusBtn inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-amber-300 dark:border-amber-700 bg-white dark:bg-zinc-900 text-amber-700 dark:text-amber-300 text-xs font-semibold hover:bg-amber-50 dark:hover:bg-amber-950/30 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1">
                <span>Under Repair</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 rounded-full bg-amber-100 dark:bg-amber-900/40"
                    id="countUnderRepair">0</span>
            </button>
            <button type="button" data-status="5"
                class="assetStatusBtn inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-red-300 dark:border-red-700 bg-white dark:bg-zinc-900 text-red-700 dark:text-red-300 text-xs font-semibold hover:bg-red-50 dark:hover:bg-red-950/30 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1">
                <span>Damaged</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 rounded-full bg-red-100 dark:bg-red-900/40"
                    id="countDamaged">0</span>
            </button>
            <button type="button" data-status="6"
                class="assetStatusBtn inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-zinc-500 dark:text-zinc-400 text-xs font-semibold hover:bg-zinc-50 dark:hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1">
                <span>Out of Service</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 rounded-full bg-zinc-100 dark:bg-zinc-800"
                    id="countOutOfService">0</span>
            </button>
        </div>
    </section>

    {{-- Filters --}}
    <section class="w-full mb-5 flex flex-wrap items-end justify-end gap-3">
        <div>
            <label for="filterVariant"
                class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Container
                Type</label>
            <select id="filterVariant"
                class="w-56 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 px-3 py-2">
                <option value="">All variants</option>
            </select>
        </div>
        <div>
            <label for="filterPort"
                class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Location</label>
            <select id="filterPort"
                class="w-56 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 px-3 py-2">
                <option value="">All locations</option>
            </select>
        </div>
    </section>

    <x-table id="tableContainerAssets" />
</div>

{{-- Register Container modal --}}
<x-modal id="registerContainerAssetModal">
    <div class="p-5 border-b flex justify-between items-center">
        <p class="text-lg font-semibold">Register Container</p>
        <button class="modal-close">✕</button>
    </div>
    <form id="registerContainerAssetForm" class="p-5 space-y-4 text-sm">
        <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
            <label for="regContainerNo"
                class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Container
                No. <span class="text-red-500">*</span></label>
            <input type="text" id="regContainerNo" name="container_no" required maxlength="20"
                class="w-full border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 uppercase bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100"
                placeholder="e.g. MSCU1234567">
        </div>
        <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
            <label for="regVariant"
                class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Variant
                (Type / Class / Size) <span class="text-red-500">*</span></label>
            <select id="regVariant" name="container_variant_id" required
                class="w-full border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 register-variant-select bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100">
                <option value="">Select variant</option>
            </select>
        </div>
        <div>
            <label for="regPort"
                class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Current
                Port</label>
            <select id="regPort" name="current_port_id"
                class="w-full border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 register-port-select bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100">
                <option value="">Not set yet</option>
            </select>
        </div>
        <div>
            <label for="regPier"
                class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Pier
                / Yard Reference</label>
            <select id="regPier" name="current_pier_reference"
                class="w-full border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 register-pier-select bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100">
                <option value="">Not set yet</option>
            </select>
        </div>
        <div>
            <label for="regConditionNotes"
                class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Condition
                Notes (optional)</label>
            <textarea id="regConditionNotes" name="condition_notes" rows="3"
                class="w-full border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100"
                placeholder="e.g. Minor dent on left door, noted at intake."></textarea>
        </div>

        <div class="border-t pt-4 flex justify-end gap-2">
            <button type="button" class="modal-close border px-4 py-2 rounded-lg text-sm">Cancel</button>
            <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">
                Register
            </button>
        </div>
    </form>
</x-modal>

{{-- Container detail modal --}}
<x-modal id="viewContainerAssetModal">
    <div class="p-5 border-b flex justify-between items-center">
        <div>
            <div class="flex items-center gap-2">
                <p class="text-lg font-semibold" id="caContainerNo">-</p>
                <span id="caStatusBadge"></span>
            </div>
            <p class="text-xs text-zinc-400" id="caVariantLabel">-</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="caPrintQrBtn"
                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18h12v3H6zM4 9h16v7H4z" />
                </svg>
                Print QR
            </button>
            <button class="modal-close">✕</button>
        </div>
    </div>

    <div class="max-h-[70vh] overflow-y-auto p-5 space-y-5 text-sm text-zinc-700 dark:text-zinc-300">
        <div class="grid grid-cols-2 gap-3">
            <div><span class="text-zinc-400">Current Port:</span>
                <div class="font-medium" id="caCurrentPort">-</div>
            </div>
            <div><span class="text-zinc-400">Pier / Yard:</span>
                <div class="font-medium" id="caPierReference">-</div>
            </div>
        </div>

        {{-- Condition notes - only shown when populated. Amber/warning tint. --}}
        <div id="caConditionNotesWrap"
            class="hidden rounded-lg border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/20 p-3">
            <p class="text-[11px] font-medium uppercase tracking-widest text-amber-700 dark:text-amber-400 mb-1">Condition
                Notes</p>
            <p class="text-sm text-amber-900 dark:text-amber-200 whitespace-pre-line" id="caConditionNotes"></p>
        </div>

        <div>
            <p class="font-semibold text-xs text-zinc-500 uppercase mb-2">Location</p>
            <div id="caMap"
                class="w-full h-56 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center text-xs text-zinc-400">
                Loading map…
            </div>
        </div>

        {{-- Locked-state panel: shown instead of the Actions card when a
             booking currently controls this container (Booked / In Transit).
             Informational, not actionable - dashed neutral border. --}}
        <div id="caLockedPanel"
            class="hidden rounded-lg border border-dashed border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-800/50 p-4">
            <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Locked</p>
            <p class="text-sm text-zinc-600 dark:text-zinc-300" id="caLockedText"></p>
        </div>

        <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 space-y-4" id="caActions">
            {{-- Change Condition group --}}
            <div class="space-y-2">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Change
                    Condition</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" id="caMarkUnderRepairBtn"
                        class="px-3 py-1.5 text-xs rounded-lg bg-amber-600 hover:bg-amber-700 text-white">
                        Mark Under Repair
                    </button>
                    <button type="button" id="caMarkDamagedBtn"
                        class="px-3 py-1.5 text-xs rounded-lg border border-red-600 text-red-600 dark:text-red-400 dark:border-red-500 hover:bg-red-50 dark:hover:bg-red-950/30">
                        Mark Damaged
                    </button>
                    <button type="button" id="caMarkAvailableBtn"
                        class="px-3 py-1.5 text-xs rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white">
                        Mark Available
                    </button>
                    <button type="button" id="caMarkOutOfServiceBtn"
                        class="px-3 py-1.5 text-xs rounded-lg bg-red-600 hover:bg-red-700 text-white">
                        Mark Out of Service
                    </button>
                </div>
            </div>

            {{-- Move group --}}
            <div class="space-y-2 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Move</p>
                <div class="flex flex-wrap items-end gap-2">
                    <div class="flex-1 min-w-[10rem]">
                        <label for="caRelocatePort" class="block text-[11px] font-semibold text-zinc-500 mb-1">Relocate to
                            port</label>
                        <select id="caRelocatePort"
                            class="w-full border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-xs relocate-port-select bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100">
                            <option value="">Select port</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[10rem]">
                        <label for="caRelocatePierRef" class="block text-[11px] font-semibold text-zinc-500 mb-1">Pier /
                            Yard reference</label>
                        <select id="caRelocatePierRef"
                            class="w-full border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-xs relocate-pier-select bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100">
                            <option value="">Not set</option>
                        </select>
                    </div>
                    <button type="button" id="caRelocateBtn"
                        class="px-3 py-1.5 text-xs rounded-lg bg-zinc-700 hover:bg-zinc-800 text-white">
                        Relocate
                    </button>
                </div>
            </div>
        </div>

        <div>
            <p class="font-semibold text-xs text-zinc-500 uppercase mb-2">Location &amp; Status History</p>
            <div id="caHistoryList" class="space-y-2 text-xs"></div>
        </div>
    </div>

    <div class="border-t px-5 py-4 flex justify-end gap-2">
        <button class="modal-close border px-4 py-2 rounded-lg text-sm">Close</button>
    </div>
</x-modal>

{{-- Reason-capture modal, reused for Mark Under Repair / Mark Damaged /
     Mark Out of Service. The pending action is tracked in a module-level
     variable; the Confirm button's color reflects which action is pending. --}}
<x-modal id="conditionReasonModal" max-width="lg:max-w-[28rem]">
    <div class="p-5 border-b flex justify-between items-center">
        <p class="text-lg font-semibold" id="conditionReasonTitle">Change Condition</p>
        <button class="modal-close">✕</button>
    </div>
    <div class="p-5 space-y-4 text-sm">
        <div>
            <label for="conditionReasonText"
                class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Reason
                (optional)</label>
            <textarea id="conditionReasonText" rows="4"
                class="w-full border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100"
                placeholder="Add context for this status change (stored as condition notes)."></textarea>
        </div>
        <div class="border-t pt-4 flex justify-end gap-2">
            <button type="button" class="modal-close border px-4 py-2 rounded-lg text-sm">Cancel</button>
            <button type="button" id="conditionReasonConfirm" class="px-4 py-2 text-sm rounded-lg">Confirm</button>
        </div>
    </div>
</x-modal>

<script>
    (function() {
        const STATUS_MAPPING = {
            1: {
                label: 'Available',
                classes: 'bg-emerald-50 text-emerald-700'
            },
            2: {
                label: 'Booked',
                classes: 'bg-blue-50 text-blue-700'
            },
            3: {
                label: 'In Transit',
                classes: 'bg-indigo-50 text-indigo-700'
            },
            4: {
                label: 'Under Repair',
                classes: 'bg-amber-50 text-amber-700'
            },
            5: {
                label: 'Damaged',
                classes: 'bg-red-50 text-red-700'
            },
            6: {
                label: 'Out of Service',
                classes: 'bg-zinc-100 text-zinc-500'
            },
        };

        // Condition-change actions surfaced through the shared reason modal.
        // Confirm-button styling differentiates severity: repair reads
        // corrective (solid amber), damaged reads investigative (red outline),
        // out-of-service reads most severe (solid red).
        const CONDITION_ACTIONS = {
            under_repair: {
                endpoint: 'mark-under-repair',
                title: 'Mark Under Repair',
                confirmLabel: 'Mark Under Repair',
                successTitle: 'Marked under repair',
                confirmClass: 'bg-amber-600 hover:bg-amber-700 text-white',
            },
            damaged: {
                endpoint: 'mark-damaged',
                title: 'Mark Damaged',
                confirmLabel: 'Mark Damaged',
                successTitle: 'Marked damaged',
                confirmClass: 'border border-red-600 text-red-600 dark:text-red-400 dark:border-red-500 hover:bg-red-50 dark:hover:bg-red-950/30',
            },
            out_of_service: {
                endpoint: 'mark-out-of-service',
                title: 'Mark Out of Service',
                confirmLabel: 'Mark Out of Service',
                successTitle: 'Marked out of service',
                confirmClass: 'bg-red-600 hover:bg-red-700 text-white',
            },
        };
        const CONFIRM_BASE_CLASS = 'px-4 py-2 text-sm rounded-lg';

        let currentAssetId = null;
        let currentAssetContainerNo = null;
        let currentAssetVariantLabel = null;
        let pendingConditionAction = null;

        function refreshSearchable(el) {
            el?._searchableSelect?.refresh();
        }

        function statusBadge(status) {
            const meta = STATUS_MAPPING[status] ?? {
                label: 'Unknown',
                classes: 'bg-zinc-100 text-zinc-500'
            };
            return `<span class="inline-flex items-center rounded-full ${meta.classes} px-2 py-0.5 text-xs font-medium">${meta.label}</span>`;
        }

        // A null class/size on a container variant is a deliberate "this
        // doesn't apply" state (base-price line / no fixed size), not missing
        // data - render it as such rather than a bare dash.
        function variantLabel(variant) {
            if (!variant) return '-';
            const type = variant.container?.name ?? '-';
            const cls = variant.container_class?.class ?? 'No class';
            const size = variant.container_size?.size ?? 'No size';
            return `${type} / ${cls} / ${size}`;
        }

        // -----------------------------------------------------------------
        // Dropdowns (variants + ports)
        // -----------------------------------------------------------------
        async function loadVariantOptions() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/containers/variants',
            });
            if (!response.success) return;

            const options = (response.data ?? []).map((v) =>
                `<option value="${v.id}">${variantLabel({
                    container: v.container,
                    container_class: v.container_class,
                    container_size: v.container_size,
                })}</option>`
            ).join('');

            const filterVariant = document.getElementById('filterVariant');
            filterVariant.insertAdjacentHTML('beforeend', options);
            refreshSearchable(filterVariant);

            document.querySelectorAll('.register-variant-select').forEach((el) => {
                el.insertAdjacentHTML('beforeend', options);
                refreshSearchable(el);
            });
        }

        async function loadPortOptions() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/ports?per_page=500',
            });
            if (!response.success) return;

            const rows = response.data?.data ?? [];
            const options = rows.map((p) => `<option value="${p.port_id}">${p.location?.name ?? '-'} - ${p.name}</option>`).join(
                '');

            const filterPort = document.getElementById('filterPort');
            filterPort.insertAdjacentHTML('beforeend', options);
            refreshSearchable(filterPort);

            document.querySelectorAll('.register-port-select').forEach((el) => {
                el.insertAdjacentHTML('beforeend', options);
                refreshSearchable(el);
            });

            const relocatePort = document.getElementById('caRelocatePort');
            relocatePort.insertAdjacentHTML('beforeend', options);
            refreshSearchable(relocatePort);
        }

        async function loadCargoYardOptions() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/cargoYards?per_page=500',
            });
            if (!response.success) return;

            const rows = (response.data?.data ?? []).filter((y) => y.is_active);
            const options = rows.map((y) => `<option value="${y.name}">${y.name}</option>`).join('');

            document.querySelectorAll('.register-pier-select').forEach((el) => {
                el.insertAdjacentHTML('beforeend', options);
                refreshSearchable(el);
            });

            const relocatePier = document.getElementById('caRelocatePierRef');
            relocatePier.insertAdjacentHTML('beforeend', options);
            refreshSearchable(relocatePier);
        }

        // -----------------------------------------------------------------
        // Status counts (chips + header stats). Fetched with every active
        // filter EXCEPT status, so switching status chips never changes the
        // counts and the other filters stay reflected. Backend already scopes
        // status_counts this way - we just mirror the non-status filters here.
        // -----------------------------------------------------------------
        async function refreshCounts() {
            const params = new URLSearchParams();
            params.set('per_page', '1');

            const variant = document.getElementById('filterVariant').value;
            const port = document.getElementById('filterPort').value;
            const searchInput = document.querySelector('#tableContainerAssets .table-search-input');
            const search = searchInput ? searchInput.value.trim() : '';

            if (variant) params.set('container_variant_id', variant);
            if (port) params.set('current_port_id', port);
            if (search) params.set('search', search);

            const response = await apiCall({
                mode: 'GET',
                url: `/api/container-assets?${params.toString()}`,
            });
            if (!response.success) return;
            updateCounts(response.status_counts);
        }

        function updateCounts(counts) {
            if (!counts) return;

            document.getElementById('countAll').textContent = counts.all ?? 0;
            document.getElementById('countAvailable').textContent = counts.available ?? 0;
            document.getElementById('countBooked').textContent = counts.booked ?? 0;
            document.getElementById('countInTransit').textContent = counts.in_transit ?? 0;
            document.getElementById('countUnderRepair').textContent = counts.under_repair ?? 0;
            document.getElementById('countDamaged').textContent = counts.damaged ?? 0;
            document.getElementById('countOutOfService').textContent = counts.out_of_service ?? 0;

            document.getElementById('countAvailableStat').textContent = counts.available ?? 0;
            document.getElementById('countNeedsAttentionStat').textContent = (counts.under_repair ?? 0) + (counts
                .damaged ?? 0);
        }

        // -----------------------------------------------------------------
        // List
        // -----------------------------------------------------------------
        let currentStatusFilter = '';

        // Two empty-state treatments (VISUALS.md). An unfiltered empty fleet
        // is a real actionable gap - a prompting state with a CTA. A filtered
        // result with zero matches is just "nothing in this bucket" - quiet.
        function assetsEmptyMessage() {
            if (!currentStatusFilter) {
                return `
                    <div class="border-2 border-dashed border-orange-300 dark:border-orange-800 bg-orange-50 dark:bg-orange-950/20 rounded-xl p-6 flex flex-col items-center text-center gap-2">
                        <span class="text-[11px] font-semibold uppercase tracking-widest text-orange-600 dark:text-orange-400">⚠ Nothing here yet</span>
                        <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">No containers registered yet. Add your first one to start tracking the fleet.</p>
                        <button type="button" class="js-empty-register-container mt-1 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">+ Register Container</button>
                    </div>`;
            }

            const label = (STATUS_MAPPING[currentStatusFilter]?.label ?? '').toLowerCase();
            return `<span class="text-sm text-zinc-400 dark:text-zinc-500">No ${label} containers match these filters.</span>`;
        }

        function renderTable() {
            const thead = [{
                    title: 'Container No.',
                    key: 'container_no',
                },
                {
                    title: 'Variant',
                    key: 'container_variant',
                    render: (r) => variantLabel(r.container_variant),
                },
                {
                    title: 'Status',
                    key: 'status',
                    render: (r) => statusBadge(r.status),
                },
                {
                    title: 'Current Port',
                    key: 'current_port.name',
                    render: (r) => r.current_port ? (r.current_port.location?.name ?? '-') + ' - ' + r.current_port.name : '-',
                },
                {
                    title: 'Pier / Yard',
                    key: 'current_pier_reference',
                    render: (r) => r.current_pier_reference ?? '-',
                },
            ];

            return renderRemoteTable({
                url: '/api/container-assets',
                tableId: 'tableContainerAssets',
                afterRenderFunction: handleRowClick,
                emptyMessage: assetsEmptyMessage,
                thead,
            });
        }

        let table = null;

        function handleRowClick(row) {
            row.addEventListener('click', function() {
                const data = JSON.parse(row.dataset.row);
                openViewContainerAsset(data.id);
            });
        }

        // Prompting empty-state CTA. Delegated off the table element (part of
        // the swapped SPA fragment) so nothing leaks across page loads.
        document.getElementById('tableContainerAssets').addEventListener('click', function(e) {
            if (e.target.closest('.js-empty-register-container')) {
                initModal({
                    modalId: 'registerContainerAssetModal'
                });
            }
        });

        document.querySelectorAll('.assetStatusBtn').forEach((btn) => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.assetStatusBtn').forEach((b) => b.classList.remove(
                    'ring-2', 'ring-orange-500'));
                this.classList.add('ring-2', 'ring-orange-500');
                currentStatusFilter = this.dataset.status;
                table.setFilter('status', this.dataset.status);
            });
        });

        document.getElementById('filterVariant').addEventListener('change', function() {
            table.setFilter('container_variant_id', this.value);
            refreshCounts();
        });

        document.getElementById('filterPort').addEventListener('change', function() {
            table.setFilter('current_port_id', this.value);
            refreshCounts();
        });

        // Keep counts meaningful when the user searches, too. Additive listener
        // alongside the table's own search wiring - debounced independently.
        const searchInput = document.querySelector('#tableContainerAssets .table-search-input');
        if (searchInput) {
            let countsSearchTimer = null;
            searchInput.addEventListener('input', function() {
                clearTimeout(countsSearchTimer);
                countsSearchTimer = setTimeout(refreshCounts, 500);
            });
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(countsSearchTimer);
                    refreshCounts();
                }
            });
        }

        // -----------------------------------------------------------------
        // Register
        // -----------------------------------------------------------------
        document.getElementById('btnRegisterContainer').addEventListener('click', function() {
            initModal({
                modalId: 'registerContainerAssetModal'
            });
        });

        document.getElementById('registerContainerAssetForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const payload = Object.fromEntries(formData.entries());
            payload.container_no = (payload.container_no || '').toUpperCase();

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload,
                url: '/api/container-assets',
                button: this.querySelector('button[type="submit"]'),
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to register container',
                    message: response.message ?? 'Please check the form and try again.',
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Container registered',
                message: `${payload.container_no} was added to inventory.`,
            });

            this.reset();
            document.querySelector('#registerContainerAssetModal .modal-close').click();
            table.reload();
            refreshCounts();
        });

        // -----------------------------------------------------------------
        // Detail modal + map
        // -----------------------------------------------------------------
        // The map itself is rendered by resources/js/containerAssetMap.js
        // (window.renderContainerAssetMap) - that file is part of the Vite
        // module graph and lazy-loads Leaflet via a real dynamic import();
        // this inline page script can't do that itself since it isn't
        // processed by Vite.

        function renderHistory(history) {
            const list = document.getElementById('caHistoryList');

            if (!history || !history.length) {
                list.innerHTML = '<p class="text-zinc-400">No recorded movement yet.</p>';
                return;
            }

            list.innerHTML = history.map((h) => `
                <div class="border border-zinc-100 dark:border-zinc-800 rounded-lg px-3 py-2">
                    <div class="flex justify-between">
                        <span class="font-medium">${h.status_at_time}</span>
                        <span class="text-zinc-400">${h.recorded_at ?? ''}</span>
                    </div>
                    <div class="text-zinc-500">${h.port ? (h.port.location?.name ?? '-') + ' - ' + h.port.name : '-'}${h.pier_reference ? ' · ' + h.pier_reference : ''}
                        · ${h.source} ${h.recorded_by ? '· ' + (h.recorded_by.name ?? '') : ''}</div>
                </div>
            `).join('');
        }

        // Booked (2) / In Transit (3) are controlled by an active booking, so
        // the whole Actions card is swapped for an explanatory locked panel.
        function updateActionVisibility(asset) {
            const status = asset.status;
            const locked = [2, 3].includes(status);

            document.getElementById('caActions').classList.toggle('hidden', locked);
            const lockedPanel = document.getElementById('caLockedPanel');
            lockedPanel.classList.toggle('hidden', !locked);

            if (locked) {
                const code = asset.active_booking_unit?.booking?.code ?? null;
                document.getElementById('caLockedText').textContent = code ?
                    `This container is reserved by Booking ${code}. Its status is controlled by that booking — release it there first if you need to change its condition or location here.` :
                    'This container is reserved by an active booking. Its status is controlled by that booking — release it there first if you need to change its condition or location here.';
                return;
            }

            // Change-condition buttons (see CONDITION_ACTIONS). Each is hidden
            // for statuses where the transition is a guaranteed no-op/422.
            document.getElementById('caMarkUnderRepairBtn').classList.toggle('hidden', [2, 3, 4].includes(status));
            document.getElementById('caMarkDamagedBtn').classList.toggle('hidden', [2, 3, 5].includes(status));
            document.getElementById('caMarkOutOfServiceBtn').classList.toggle('hidden', [2, 3, 6].includes(status));
            // Mark Available: from Under Repair (4) or Damaged (5) only.
            document.getElementById('caMarkAvailableBtn').classList.toggle('hidden', ![4, 5].includes(status));
        }

        async function openViewContainerAsset(id) {
            const response = await apiCall({
                mode: 'GET',
                url: `/api/container-assets/${id}`,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: 'Unable to load this container.',
                });
                return;
            }

            currentAssetId = id;
            const asset = response.data;

            currentAssetContainerNo = asset.container_no;
            currentAssetVariantLabel = variantLabel(asset.container_variant);

            document.getElementById('caContainerNo').textContent = asset.container_no;
            document.getElementById('caStatusBadge').innerHTML = statusBadge(asset.status);
            document.getElementById('caVariantLabel').textContent = currentAssetVariantLabel;
            document.getElementById('caCurrentPort').textContent = asset.current_port ? `${asset.current_port.location?.name ?? '-'} - ${asset.current_port.name}` : 'Not set';
            document.getElementById('caPierReference').textContent = asset.current_pier_reference ?? '-';

            const notes = (asset.condition_notes ?? '').trim();
            document.getElementById('caConditionNotes').textContent = notes;
            document.getElementById('caConditionNotesWrap').classList.toggle('hidden', !notes);

            updateActionVisibility(asset);
            renderHistory(asset.location_history);

            initModal({
                modalId: 'viewContainerAssetModal'
            });

            // Render after the modal is visible so the map container has real dimensions.
            setTimeout(() => window.renderContainerAssetMap('caMap', asset.current_port), 50);
        }

        async function refreshCurrentAsset() {
            if (!currentAssetId) return;
            await openViewContainerAsset(currentAssetId);
            table.reload();
            refreshCounts();
        }

        // -----------------------------------------------------------------
        // QR labels - the QR just encodes container_no, the same value shown
        // everywhere else (see resources/js/containerAssetQr.js). Single
        // print reprints whichever container is currently open; bulk print
        // covers every row the current filter/search is showing.
        // -----------------------------------------------------------------
        document.getElementById('caPrintQrBtn').addEventListener('click', function() {
            if (!currentAssetContainerNo) return;
            window.printContainerAssetQrLabels([{
                container_no: currentAssetContainerNo,
                variant_label: currentAssetVariantLabel,
            }]);
        });

        document.getElementById('btnPrintQrLabels').addEventListener('click', function() {
            const rows = Array.from(document.querySelectorAll('#tableContainerAssets tr[data-row]'));
            const items = rows.map((row) => {
                const asset = JSON.parse(row.dataset.row);
                return {
                    container_no: asset.container_no,
                    variant_label: variantLabel(asset.container_variant),
                };
            });
            window.printContainerAssetQrLabels(items);
        });

        // -----------------------------------------------------------------
        // Condition-change actions (reason modal)
        // -----------------------------------------------------------------
        function openConditionModal(actionKey) {
            const cfg = CONDITION_ACTIONS[actionKey];
            if (!cfg) return;

            pendingConditionAction = actionKey;
            document.getElementById('conditionReasonTitle').textContent = cfg.title;
            document.getElementById('conditionReasonText').value = '';

            const confirmBtn = document.getElementById('conditionReasonConfirm');
            confirmBtn.className = `${CONFIRM_BASE_CLASS} ${cfg.confirmClass}`;
            confirmBtn.textContent = cfg.confirmLabel;

            initModal({
                modalId: 'conditionReasonModal'
            });
        }

        document.getElementById('caMarkUnderRepairBtn').addEventListener('click', () => openConditionModal(
            'under_repair'));
        document.getElementById('caMarkDamagedBtn').addEventListener('click', () => openConditionModal('damaged'));
        document.getElementById('caMarkOutOfServiceBtn').addEventListener('click', () => openConditionModal(
            'out_of_service'));

        document.getElementById('conditionReasonConfirm').addEventListener('click', async function() {
            const cfg = CONDITION_ACTIONS[pendingConditionAction];
            if (!cfg || !currentAssetId) return;

            const reason = document.getElementById('conditionReasonText').value.trim();

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {
                    reason: reason || null
                },
                url: `/api/container-assets/${currentAssetId}/${cfg.endpoint}`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to update status',
                    message: response.message ?? ''
                });
                return;
            }

            document.querySelector('#conditionReasonModal .modal-close').click();
            showMessage({
                status: 'success',
                title: cfg.successTitle
            });
            refreshCurrentAsset();
        });

        document.getElementById('caMarkAvailableBtn').addEventListener('click', async function() {
            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {},
                url: `/api/container-assets/${currentAssetId}/mark-available`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to update status',
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Marked available'
            });
            refreshCurrentAsset();
        });

        document.getElementById('caRelocateBtn').addEventListener('click', async function() {
            const portId = document.getElementById('caRelocatePort').value;
            const pierRef = document.getElementById('caRelocatePierRef').value;

            if (!portId) {
                showMessage({
                    status: 'error',
                    title: 'Select a port first'
                });
                return;
            }

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {
                    port_id: portId,
                    pier_reference: pierRef || null
                },
                url: `/api/container-assets/${currentAssetId}/relocate`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to relocate',
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Container relocated'
            });
            refreshCurrentAsset();
        });

        // -----------------------------------------------------------------
        // Init
        // -----------------------------------------------------------------
        function init() {
            table = renderTable();

            // Long-list selects -> searchable comboboxes. Applied before option
            // loading; each loader calls refreshSearchable() after it repopulates.
            makeSearchableSelect(document.getElementById('filterVariant'));
            makeSearchableSelect(document.getElementById('filterPort'));
            document.querySelectorAll('.register-variant-select').forEach((el) => makeSearchableSelect(el));
            document.querySelectorAll('.register-port-select').forEach((el) => makeSearchableSelect(el));
            document.querySelectorAll('.register-pier-select').forEach((el) => makeSearchableSelect(el));
            makeSearchableSelect(document.getElementById('caRelocatePort'));
            makeSearchableSelect(document.getElementById('caRelocatePierRef'));

            table.load(1);
            loadVariantOptions();
            loadPortOptions();
            loadCargoYardOptions();
            refreshCounts();
        }

        init();
    })();
</script>
