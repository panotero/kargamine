<div class="container mx-auto px-4 py-6">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold">Container Inventory</h1>
            <p class="text-zinc-500">The company's physical container fleet — status, and where each
                one currently is.</p>
        </div>
        <button type="button" id="btnRegisterContainer"
            class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">
            Register Container
        </button>
    </div>

    <section class="w-full mb-5">
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" data-status=""
                class="assetStatusBtn ring-2 ring-blue-500 px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700">
                All
            </button>
            <button type="button" data-status="1"
                class="assetStatusBtn px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700">
                Available
            </button>
            <button type="button" data-status="2"
                class="assetStatusBtn px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700">
                Booked
            </button>
            <button type="button" data-status="3"
                class="assetStatusBtn px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700">
                In Transit
            </button>
            <button type="button" data-status="4"
                class="assetStatusBtn px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700">
                Under Repair
            </button>
            <button type="button" data-status="5"
                class="assetStatusBtn px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700">
                Damaged
            </button>
            <button type="button" data-status="6"
                class="assetStatusBtn px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700">
                Out of Service
            </button>

            <select id="filterVariant"
                class="ml-auto text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 px-3 py-2">
                <option value="">All variants</option>
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
        <div>
            <label class="block text-xs font-semibold text-zinc-500 mb-1">Container No. <span
                    class="text-red-500">*</span></label>
            <input type="text" name="container_no" required maxlength="20"
                class="w-full border border-zinc-200 rounded-lg px-3 py-2 uppercase dark:text-zinc-900"
                placeholder="e.g. MSCU1234567">
        </div>
        <div>
            <label class="block text-xs font-semibold text-zinc-500 mb-1">Variant (Type / Class / Size) <span
                    class="text-red-500">*</span></label>
            <select name="container_variant_id" required
                class="w-full border border-zinc-200 rounded-lg px-3 py-2 registerVariantSelect dark:text-zinc-900">
                <option value="">Select variant</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-zinc-500 mb-1">Current Port</label>
            <select name="current_port_id"
                class="w-full border border-zinc-200 rounded-lg px-3 py-2 registerPortSelect dark:text-zinc-900">
                <option value="">Not set yet</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-zinc-500 mb-1">Pier / Yard Reference</label>
            <input type="text" name="current_pier_reference" maxlength="100"
                class="w-full border border-zinc-200 rounded-lg px-3 py-2 dark:text-zinc-900"
                placeholder="e.g. Pier 3 / Yard B">
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
        <button class="modal-close">✕</button>
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

        <div>
            <p class="font-semibold text-xs text-zinc-500 uppercase mb-2">Location</p>
            <div id="caMap"
                class="w-full h-56 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center text-xs text-zinc-400">
                Loading map…
            </div>
        </div>

        <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 space-y-3" id="caActions">
            <p class="font-semibold text-xs text-zinc-500 uppercase">Actions</p>

            <div class="flex flex-wrap gap-2">
                <button type="button" id="caMarkUnderRepairBtn"
                    class="px-3 py-1.5 text-xs rounded-lg bg-amber-500 hover:bg-amber-600 text-white">
                    Mark Under Repair
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

            <div class="flex flex-wrap items-end gap-2 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                <div class="flex-1 min-w-[10rem]">
                    <label class="block text-[11px] font-semibold text-zinc-500 mb-1">Relocate to port</label>
                    <select id="caRelocatePort"
                        class="w-full border border-zinc-200 rounded-lg px-2 py-1.5 text-xs dark:text-zinc-900">
                        <option value="">Select port</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[10rem]">
                    <label class="block text-[11px] font-semibold text-zinc-500 mb-1">Pier / Yard reference</label>
                    <input type="text" id="caRelocatePierRef"
                        class="w-full border border-zinc-200 rounded-lg px-2 py-1.5 text-xs dark:text-zinc-900">
                </div>
                <button type="button" id="caRelocateBtn"
                    class="px-3 py-1.5 text-xs rounded-lg bg-zinc-700 hover:bg-zinc-800 text-white">
                    Relocate
                </button>
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

        let currentAssetId = null;

        function statusBadge(status) {
            const meta = STATUS_MAPPING[status] ?? {
                label: 'Unknown',
                classes: 'bg-zinc-100 text-zinc-500'
            };
            return `<span class="inline-flex items-center rounded-full ${meta.classes} px-2 py-0.5 text-xs font-medium">${meta.label}</span>`;
        }

        function variantLabel(variant) {
            if (!variant) return '-';
            const type = variant.container?.name ?? '-';
            const cls = variant.container_class?.class ?? '-';
            const size = variant.container_size?.size ?? '-';
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

            document.getElementById('filterVariant').insertAdjacentHTML('beforeend', options);
            document.querySelectorAll('.registerVariantSelect').forEach((el) => el.insertAdjacentHTML(
                'beforeend', options));
        }

        async function loadPortOptions() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/ports?per_page=500',
            });
            if (!response.success) return;

            const rows = response.data?.data ?? [];
            const options = rows.map((p) => `<option value="${p.port_id}">${p.name} (${p.code})</option>`).join(
                '');

            document.querySelectorAll('.registerPortSelect').forEach((el) => el.insertAdjacentHTML('beforeend',
                options));
            document.getElementById('caRelocatePort').insertAdjacentHTML('beforeend', options);
        }

        // -----------------------------------------------------------------
        // List
        // -----------------------------------------------------------------
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
                    render: (r) => r.current_port?.name ?? '-',
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

        document.querySelectorAll('.assetStatusBtn').forEach((btn) => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.assetStatusBtn').forEach((b) => b.classList.remove(
                    'ring-2', 'ring-blue-500'));
                this.classList.add('ring-2', 'ring-blue-500');
                table.setFilter('status', this.dataset.status);
            });
        });

        document.getElementById('filterVariant').addEventListener('change', function() {
            table.setFilter('container_variant_id', this.value);
        });

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
                    <div class="text-zinc-500">${h.port?.name ?? '-'}${h.pier_reference ? ' · ' + h.pier_reference : ''}
                        · ${h.source} ${h.recorded_by ? '· ' + (h.recorded_by.name ?? '') : ''}</div>
                </div>
            `).join('');
        }

        function updateActionVisibility(status) {
            document.getElementById('caMarkUnderRepairBtn').classList.toggle('hidden', [2, 3, 4].includes(status));
            document.getElementById('caMarkAvailableBtn').classList.toggle('hidden', status !== 4);
            document.getElementById('caMarkOutOfServiceBtn').classList.toggle('hidden', [2, 3].includes(status));
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

            document.getElementById('caContainerNo').textContent = asset.container_no;
            document.getElementById('caStatusBadge').innerHTML = statusBadge(asset.status);
            document.getElementById('caVariantLabel').textContent = variantLabel(asset.container_variant);
            document.getElementById('caCurrentPort').textContent = asset.current_port?.name ?? 'Not set';
            document.getElementById('caPierReference').textContent = asset.current_pier_reference ?? '-';

            updateActionVisibility(asset.status);
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
        }

        document.getElementById('caMarkUnderRepairBtn').addEventListener('click', async function() {
            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {},
                url: `/api/container-assets/${currentAssetId}/mark-under-repair`,
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
                title: 'Marked under repair'
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

        document.getElementById('caMarkOutOfServiceBtn').addEventListener('click', async function() {
            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {},
                url: `/api/container-assets/${currentAssetId}/mark-out-of-service`,
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
                title: 'Marked out of service'
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
            table.load(1);
            loadVariantOptions();
            loadPortOptions();
        }

        init();
    })();
</script>
