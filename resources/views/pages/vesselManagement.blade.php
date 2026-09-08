<div class="container mx-auto px-4 py-6">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Vessel Management</h1>
            <p class="text-zinc-500">Fleet specifications, status, and service/maintenance history.</p>
        </div>
        <button type="button" id="vmAddVesselBtn"
            class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">+ Add
            Vessel</button>
    </div>

    {{-- Status Cards --}}
    <section class="w-full my-5">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <div class="vmStatusBtn bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="all">
                <div class="w-full py-1 rounded-full bg-blue-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">ALL VESSELS</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="vmCountAll">0</p>
            </div>
            <div class="vmStatusBtn bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="1">
                <div class="w-full py-1 rounded-full bg-green-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">ACTIVE</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="vmCount1">0</p>
            </div>
            <div class="vmStatusBtn bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="2">
                <div class="w-full py-1 rounded-full bg-amber-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">UNDER REPAIR</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="vmCount2">0</p>
            </div>
            <div class="vmStatusBtn bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="3">
                <div class="w-full py-1 rounded-full bg-red-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">OUT OF SERVICE</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="vmCount3">0</p>
            </div>
            <div class="vmStatusBtn bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="4">
                <div class="w-full py-1 rounded-full bg-zinc-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">DECOMMISSIONED</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="vmCount4">0</p>
            </div>
        </div>
    </section>

    <x-table id="tableVessels" />
</div>

{{-- Add / Edit Vessel modal - specs only, status handled separately in the detail modal --}}
<x-modal id="vesselFormModal">
    <form id="vesselForm">
        <div class="p-5 border-b flex justify-between items-center">
            <p class="text-lg font-semibold" id="vmFormTitle">Add Vessel</p>
            <button type="button" class="modal-close">✕</button>
        </div>

        <div class="max-h-[70vh] overflow-y-auto p-5 space-y-4 text-sm text-zinc-700 dark:text-zinc-300">
            <input type="hidden" name="id" id="vmFormId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Vessel Name *</label>
                    <input type="text" name="name" required
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Vessel Code</label>
                    <input type="text" name="vessel_code"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Vessel Type</label>
                    <input type="text" name="vessel_type" placeholder="e.g. Container Ship, RoRo, Barge, Tugboat"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div id="vmStatusFieldWrap">
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Initial Status</label>
                    <select name="status"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                        @foreach (\App\Models\Vessel::STATUS_LABELS as $code => $label)
                            <option value="{{ $code }}" @selected($code === \App\Models\Vessel::STATUS_ACTIVE)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Home Port</label>
                    <select name="home_port_id" class="w-full vmPortSelect rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                        <option value="">-- Select port --</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">IMO Number</label>
                    <input type="text" name="imo_number"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Call Sign</label>
                    <input type="text" name="call_sign"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">MMSI Number</label>
                    <input type="text" name="mmsi_number"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Flag State</label>
                    <input type="text" name="flag_state"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Owner / Operator</label>
                    <input type="text" name="owner_operator"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Classification Society</label>
                    <input type="text" name="classification_society"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Date Manufactured</label>
                    <input type="date" name="date_manufactured"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Number of Engines</label>
                    <input type="number" min="0" name="engine_count"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Engine Power (HP)</label>
                    <input type="number" min="0" step="0.01" name="engine_power_hp"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Max Speed (knots)</label>
                    <input type="number" min="0" step="0.01" name="max_speed_knots"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Capacity (TEU)</label>
                    <input type="number" min="0" name="capacity_teu"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Gross Tonnage</label>
                    <input type="number" min="0" step="0.01" name="gross_tonnage"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Deadweight Tonnage</label>
                    <input type="number" min="0" step="0.01" name="deadweight_tonnage"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Length Overall (m)</label>
                    <input type="number" min="0" step="0.01" name="length_overall_m"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Beam (m)</label>
                    <input type="number" min="0" step="0.01" name="beam_m"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Draft (m)</label>
                    <input type="number" min="0" step="0.01" name="draft_m"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Notes</label>
                    <textarea name="notes" rows="2"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm"></textarea>
                </div>
            </div>
        </div>

        <div class="p-5 border-t flex justify-end gap-2">
            <button type="button" class="modal-close px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700">Cancel</button>
            <button type="submit" id="vmFormSubmitBtn" class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">Save Vessel</button>
        </div>
    </form>
</x-modal>

{{-- Vessel detail modal - specs summary, status + history, maintenance log --}}
<x-modal id="vesselDetailModal">
    <div class="p-5 border-b flex justify-between items-center">
        <div class="flex items-center gap-2">
            <p class="text-lg font-semibold" id="vdName">-</p>
            <span id="vdStatusBadge"></span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="vdEditBtn" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Edit
                Specs</button>
            <button type="button" class="modal-close">✕</button>
        </div>
    </div>

    <div class="max-h-[75vh] overflow-y-auto p-5 space-y-6 text-sm text-zinc-700 dark:text-zinc-300">

        {{-- Specs summary --}}
        <section>
            <p class="text-xs font-semibold text-zinc-400 uppercase mb-2">Specifications</p>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3" id="vdSpecsGrid"></div>
        </section>

        {{-- Status change --}}
        <section class="border-t pt-4">
            <p class="text-xs font-semibold text-zinc-400 uppercase mb-2">Status</p>
            <form id="vdStatusForm" class="flex flex-col md:flex-row gap-2 md:items-end">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-zinc-500 mb-1">New Status</label>
                    <select name="status" id="vdStatusSelect"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                        @foreach (\App\Models\Vessel::STATUS_LABELS as $code => $label)
                            <option value="{{ $code }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-[2]">
                    <label class="block text-xs font-medium text-zinc-500 mb-1">Notes</label>
                    <input type="text" name="notes" placeholder="Reason for change (optional)"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                </div>
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium whitespace-nowrap">Update Status</button>
            </form>

            <div class="mt-3 max-h-40 overflow-y-auto border border-zinc-200 dark:border-zinc-700 rounded-lg">
                <table class="min-w-full text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800 sticky top-0">
                        <tr>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Date</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">From</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">To</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Notes</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800" id="vdStatusHistoryBody"></tbody>
                </table>
            </div>
        </section>

        {{-- Maintenance / service history --}}
        <section class="border-t pt-4">
            <p class="text-xs font-semibold text-zinc-400 uppercase mb-2">Service &amp; Maintenance History</p>
            <form id="vdMaintenanceForm" class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-3">
                <input type="text" name="maintenance_type" placeholder="Type (e.g. Dry-dock, Engine Overhaul)" required
                    class="col-span-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                <input type="date" name="performed_at" required title="Performed On"
                    class="rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                <input type="date" name="next_due_at" title="Next Due"
                    class="rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                <input type="text" name="performed_by" placeholder="Performed by / contractor"
                    class="rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                <input type="number" min="0" step="0.01" name="cost" placeholder="Cost"
                    class="rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                <input type="text" name="description" placeholder="Description"
                    class="col-span-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                <button type="submit" class="rounded-lg bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-3 py-2">Log Record</button>
            </form>

            <div class="max-h-56 overflow-y-auto border border-zinc-200 dark:border-zinc-700 rounded-lg">
                <table class="min-w-full text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800 sticky top-0">
                        <tr>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Performed</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Type</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">By</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Cost</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Next Due</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800" id="vdMaintenanceBody"></tbody>
                </table>
            </div>
        </section>
    </div>
</x-modal>

<script>
    (function() {
        const STATUS_LABELS = @json(\App\Models\Vessel::STATUS_LABELS);
        const STATUS_COLORS = {
            1: 'bg-green-50 text-green-700',
            2: 'bg-amber-50 text-amber-700',
            3: 'bg-red-50 text-red-700',
            4: 'bg-zinc-100 text-zinc-600',
        };

        let table = null;
        let activeStatus = 'all';
        let currentVesselId = null;
        let portOptionsLoaded = false;

        function statusBadge(status) {
            const label = STATUS_LABELS[status] ?? 'Unknown';
            const classes = STATUS_COLORS[status] ?? 'bg-zinc-100 text-zinc-600';
            return `<span class="inline-flex items-center rounded-full ${classes} px-2 py-0.5 text-xs font-medium">${label}</span>`;
        }

        function money(v) {
            if (v === null || v === undefined || v === '') return '-';
            return Number(v).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        async function loadPortOptions() {
            if (portOptionsLoaded) return;
            const response = await apiCall({ mode: 'GET', url: '/api/ports?per_page=500' });
            if (!response.success) return;

            const rows = response.data?.data ?? [];
            const options = rows.map((p) => `<option value="${p.port_id}">${p.location?.name ?? '-'} - ${p.name}</option>`).join('');

            document.querySelectorAll('.vmPortSelect').forEach((el) => {
                el.insertAdjacentHTML('beforeend', options);
                if (window.makeSearchableSelect) window.makeSearchableSelect(el);
            });
            portOptionsLoaded = true;
        }

        function renderTable() {
            const thead = [
                { title: 'Name', key: 'name' },
                { title: 'Code', key: 'vessel_code', render: (r) => r.vessel_code ?? '-' },
                { title: 'Type', key: 'vessel_type', render: (r) => r.vessel_type ?? '-' },
                { title: 'Status', key: 'status', render: (r) => statusBadge(r.status) },
                { title: 'Home Port', key: 'home_port', render: (r) => r.home_port ? `${r.home_port.location?.name ?? '-'} - ${r.home_port.name}` : '-' },
                { title: 'Engines', key: 'engine_count', render: (r) => r.engine_count ?? '-' },
                { title: 'Capacity (TEU)', key: 'capacity_teu', render: (r) => r.capacity_teu ?? '-' },
                { title: 'Date Manufactured', key: 'date_manufactured', render: (r) => r.date_manufactured ? formatDate(r.date_manufactured) : '-' },
                { title: 'Last Serviced', key: 'last_serviced_at', render: (r) => r.last_serviced_at ? formatDate(r.last_serviced_at) : '-' },
            ];

            return renderRemoteTable({
                url: '/api/vessels',
                tableId: 'tableVessels',
                afterRenderFunction: handleRowClick,
                thead,
            });
        }

        function handleRowClick(row) {
            row.addEventListener('click', function() {
                const data = JSON.parse(row.dataset.row);
                openDetailModal(data.id);
            });
        }

        async function loadCounts() {
            const response = await apiCall({ mode: 'GET', url: '/api/vessels' });
            if (!response.success) return;
            Object.entries(response.status_counts).forEach(([key, count]) => {
                const el = document.getElementById(`vmCount${key}`);
                if (el) el.textContent = count;
            });
        }

        document.querySelectorAll('.vmStatusBtn').forEach((btn) => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.vmStatusBtn').forEach((b) => b.classList.remove('ring-2', 'ring-orange-500'));
                this.classList.add('ring-2', 'ring-orange-500');
                activeStatus = this.dataset.status;
                table.setFilter('status', activeStatus);
            });
        });

        // -----------------------------------------------------------------
        // Add / Edit Vessel form
        // -----------------------------------------------------------------
        function resetVesselForm() {
            const form = document.getElementById('vesselForm');
            form.reset();
            document.getElementById('vmFormId').value = '';
        }

        function openCreateModal() {
            resetVesselForm();
            document.getElementById('vmFormTitle').textContent = 'Add Vessel';
            document.getElementById('vmStatusFieldWrap').classList.remove('hidden');
            loadPortOptions();
            initModal({ modalId: 'vesselFormModal' });
        }

        function openEditModal(vessel) {
            resetVesselForm();
            document.getElementById('vmFormTitle').textContent = `Edit ${vessel.name}`;
            document.getElementById('vmStatusFieldWrap').classList.add('hidden');
            document.getElementById('vmFormId').value = vessel.id;

            const form = document.getElementById('vesselForm');
            Object.keys(vessel).forEach((key) => {
                const field = form.elements[key];
                if (!field || vessel[key] === null) return;
                field.value = key === 'date_manufactured' ? String(vessel[key]).slice(0, 10) : vessel[key];
            });

            loadPortOptions().then(() => {
                if (vessel.home_port_id) form.elements['home_port_id'].value = vessel.home_port_id;
                document.querySelectorAll('.vmPortSelect').forEach((el) => el._searchableSelect?.refresh());
            });

            initModal({ modalId: 'vesselFormModal' });
        }

        document.getElementById('vmAddVesselBtn').addEventListener('click', openCreateModal);

        document.getElementById('vesselForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const id = formData.get('id');
            const payload = Object.fromEntries(formData.entries());
            delete payload.id;

            const response = await apiCall({
                mode: id ? 'PUT' : 'POST',
                isJson: true,
                payload,
                url: id ? `/api/vessels/${id}` : '/api/vessels',
                button: document.getElementById('vmFormSubmitBtn'),
            });

            if (!response.success) {
                showMessage({ status: 'error', title: 'Unable to save vessel', message: response.message ?? '' });
                return;
            }

            showMessage({ status: 'success', title: id ? 'Vessel updated' : 'Vessel added' });
            document.querySelector('#vesselFormModal .modal-close').click();
            table.reload();
            loadCounts();
            if (currentVesselId && String(currentVesselId) === String(response.data.id)) {
                openDetailModal(currentVesselId);
            }
        });

        // -----------------------------------------------------------------
        // Vessel detail modal - specs, status, maintenance
        // -----------------------------------------------------------------
        const SPEC_FIELDS = [
            ['Vessel Type', 'vessel_type'],
            ['IMO Number', 'imo_number'],
            ['Call Sign', 'call_sign'],
            ['MMSI Number', 'mmsi_number'],
            ['Flag State', 'flag_state'],
            ['Owner / Operator', 'owner_operator'],
            ['Classification Society', 'classification_society'],
            ['Date Manufactured', 'date_manufactured', (v) => formatDate(v)],
            ['Engines', 'engine_count'],
            ['Engine Power (HP)', 'engine_power_hp'],
            ['Max Speed (knots)', 'max_speed_knots'],
            ['Capacity (TEU)', 'capacity_teu'],
            ['Gross Tonnage', 'gross_tonnage'],
            ['Deadweight Tonnage', 'deadweight_tonnage'],
            ['Length Overall (m)', 'length_overall_m'],
            ['Beam (m)', 'beam_m'],
            ['Draft (m)', 'draft_m'],
        ];

        let lastVesselPayload = null;

        function renderDetail(vessel) {
            lastVesselPayload = vessel;

            document.getElementById('vdName').textContent = vessel.name;
            document.getElementById('vdStatusBadge').innerHTML = statusBadge(vessel.status);
            document.getElementById('vdStatusSelect').value = vessel.status;

            const specsHtml = SPEC_FIELDS.map(([label, key, fmt]) => {
                const raw = vessel[key];
                const value = raw === null || raw === undefined || raw === '' ? '-' : (fmt ? fmt(raw) : raw);
                return `<div><p class="text-xs text-zinc-400">${label}</p><p class="font-medium">${value}</p></div>`;
            }).join('') + `<div><p class="text-xs text-zinc-400">Home Port</p><p class="font-medium">${vessel.home_port ? (vessel.home_port.location?.name ?? '-') + ' - ' + vessel.home_port.name : '-'}</p></div>`;
            document.getElementById('vdSpecsGrid').innerHTML = specsHtml;

            if (vessel.notes) {
                document.getElementById('vdSpecsGrid').insertAdjacentHTML('beforeend',
                    `<div class="col-span-full"><p class="text-xs text-zinc-400">Notes</p><p class="font-medium whitespace-pre-line">${vessel.notes}</p></div>`);
            }

            const histories = vessel.status_histories ?? [];
            document.getElementById('vdStatusHistoryBody').innerHTML = histories.length
                ? histories.map((h) => `
                    <tr>
                        <td class="px-3 py-2 whitespace-nowrap">${formatDateTime(h.recorded_at)}</td>
                        <td class="px-3 py-2">${h.from_status ? (STATUS_LABELS[h.from_status] ?? '-') : '-'}</td>
                        <td class="px-3 py-2">${STATUS_LABELS[h.to_status] ?? '-'}</td>
                        <td class="px-3 py-2">${h.notes ?? '-'}</td>
                        <td class="px-3 py-2 whitespace-nowrap">${h.recorded_by?.name ?? '-'}</td>
                    </tr>`).join('')
                : '<tr><td colspan="5" class="px-3 py-4 text-center text-zinc-400">No status changes logged yet.</td></tr>';

            const records = vessel.maintenance_records ?? [];
            document.getElementById('vdMaintenanceBody').innerHTML = records.length
                ? records.map((r) => `
                    <tr data-record-id="${r.id}">
                        <td class="px-3 py-2 whitespace-nowrap">${formatDate(r.performed_at)}</td>
                        <td class="px-3 py-2">${r.maintenance_type}</td>
                        <td class="px-3 py-2">${r.performed_by ?? '-'}</td>
                        <td class="px-3 py-2 whitespace-nowrap">${r.cost ? money(r.cost) : '-'}</td>
                        <td class="px-3 py-2 whitespace-nowrap">${r.next_due_at ? formatDate(r.next_due_at) : '-'}</td>
                        <td class="px-3 py-2 text-right">
                            <button type="button" class="vdDeleteRecordBtn text-red-500 hover:text-red-700 text-xs" data-record-id="${r.id}">Delete</button>
                        </td>
                    </tr>`).join('')
                : '<tr><td colspan="6" class="px-3 py-4 text-center text-zinc-400">No maintenance records logged yet.</td></tr>';
        }

        async function openDetailModal(id) {
            currentVesselId = id;
            const response = await apiCall({ mode: 'GET', url: `/api/vessels/${id}` });
            if (!response.success) {
                showMessage({ status: 'error', title: 'Unable to load vessel', message: response.message ?? '' });
                return;
            }
            renderDetail(response.data);
            initModal({ modalId: 'vesselDetailModal' });
        }

        async function refreshDetail() {
            const response = await apiCall({ mode: 'GET', url: `/api/vessels/${currentVesselId}` });
            if (!response.success) return;
            renderDetail(response.data);
            table.reload();
            loadCounts();
        }

        document.getElementById('vdEditBtn').addEventListener('click', function() {
            if (!lastVesselPayload) return;
            openEditModal(lastVesselPayload);
        });

        document.getElementById('vdStatusForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: Object.fromEntries(formData.entries()),
                url: `/api/vessels/${currentVesselId}/status`,
                button: this.querySelector('button[type="submit"]'),
            });

            if (!response.success) {
                showMessage({ status: 'error', title: 'Unable to update status', message: response.message ?? '' });
                return;
            }

            this.reset();
            showMessage({ status: 'success', title: 'Vessel status updated' });
            refreshDetail();
        });

        document.getElementById('vdMaintenanceForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: Object.fromEntries(formData.entries()),
                url: `/api/vessels/${currentVesselId}/maintenance-records`,
                button: this.querySelector('button[type="submit"]'),
            });

            if (!response.success) {
                showMessage({ status: 'error', title: 'Unable to log maintenance record', message: response.message ?? '' });
                return;
            }

            this.reset();
            showMessage({ status: 'success', title: 'Maintenance record logged' });
            refreshDetail();
        });

        document.getElementById('vdMaintenanceBody').addEventListener('click', async function(e) {
            const btn = e.target.closest('.vdDeleteRecordBtn');
            if (!btn) return;

            const response = await apiCall({
                mode: 'DELETE',
                url: `/api/vessels/${currentVesselId}/maintenance-records/${btn.dataset.recordId}`,
                button: btn,
            });

            if (!response.success) {
                showMessage({ status: 'error', title: 'Unable to delete record', message: response.message ?? '' });
                return;
            }

            showMessage({ status: 'success', title: 'Maintenance record deleted' });
            refreshDetail();
        });

        table = renderTable();
        table.setFilter('status', activeStatus);
        loadCounts();
    })();
</script>
