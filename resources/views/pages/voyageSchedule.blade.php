<div class="container mx-auto px-4 py-6">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Voyage Schedule</h1>
            <p class="text-zinc-500">SOP Step 10 (Voyage Plan) - one row per vessel leg, scoped to active vessels.</p>
        </div>
        <button type="button" id="vsAddVoyageBtn"
            class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">+ Add
            Voyage</button>
    </div>

    <div class="mb-4 grid grid-cols-2 md:grid-cols-5 gap-3">
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1">Voyage Mnemonic</label>
            <input type="text" id="vsFilterMnemonic" placeholder="e.g. Lady Callista 84-A"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1">Vessel Name</label>
            <input type="text" id="vsFilterVesselName" placeholder="e.g. Lady Callista"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1">Departure Date</label>
            <input type="date" id="vsFilterDeparture"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1">Arrival Date</label>
            <input type="date" id="vsFilterArrival"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1">Destination</label>
            <select id="vsFilterDestination"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                <option value="">All destinations</option>
            </select>
        </div>
    </div>
    <div class="mb-3 flex justify-end">
        <button type="button" id="vsClearFiltersBtn" class="text-xs text-zinc-500 hover:text-zinc-700 underline">Clear
            filters</button>
    </div>

    <x-table id="tableVoyageSchedule" />
</div>

<x-modal id="voyageFormModal">
    <form id="voyageForm">
        <div class="p-5 border-b flex justify-between items-center">
            <p class="text-lg font-semibold" id="vsFormTitle">Manage Voyage Schedule</p>
            <button type="button" class="modal-close">✕</button>
        </div>

        <div class="max-h-[75vh] overflow-y-auto p-5 text-sm text-zinc-700 dark:text-zinc-300">
            <div class="grid md:grid-cols-2 gap-6">

                <!-- Left column: vessel selection + info -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 mb-1">Vessel *</label>
                        <select id="vsVesselSelect"
                            class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                            <option value="">-- Select active vessel --</option>
                        </select>
                        <p class="text-xs text-zinc-400 mt-1">Only vessels currently tagged Active in <a href="#"
                                id="vsVesselMgmtLink" class="text-blue-600 hover:underline">Vessel Management</a> are
                            listed here.</p>
                    </div>

                    <div id="vsVesselInfoPanel"
                        class="rounded-lg border border-dashed border-zinc-300 dark:border-zinc-700 p-4 text-xs text-zinc-400">
                        Select a vessel to view its information and manage its schedule.
                    </div>
                </div>

                <!-- Right column: schedule legs for the selected vessel -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-medium text-zinc-500">Schedule (Route Legs)</label>
                        <button type="button" id="vsAddLegBtn" disabled
                            class="px-2.5 py-1 text-xs rounded-lg border border-orange-300 text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-950 disabled:opacity-40 disabled:cursor-not-allowed">+
                            Add Leg</button>
                    </div>

                    <div id="vsLegRowsContainer" class="space-y-3">
                        <p id="vsLegRowsEmpty" class="text-xs text-zinc-400 italic">No vessel selected yet.</p>
                    </div>
                </div>

            </div>

            <div class="mt-6 border-t border-zinc-200 dark:border-zinc-700 pt-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-medium text-zinc-500">Containers on This Voyage</label>
                    <a href="#" id="vsDownloadManifestBtn" target="_blank"
                        class="px-2.5 py-1 text-xs rounded-lg border border-blue-300 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950 pointer-events-none opacity-40">
                        Download Full Voyage Manifest (PDF)
                    </a>
                </div>
                <div id="vsContainersList" class="text-xs text-zinc-400 italic">Select a vessel to view its
                    containers.</div>
            </div>
        </div>

        <div class="p-5 border-t flex justify-end gap-2">
            <button type="button"
                class="modal-close px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700">Close</button>
            <button type="submit" id="vsFormSubmitBtn"
                class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">Save
                Schedule</button>
        </div>
    </form>
</x-modal>

<script>
    (function() {
        let table = null;
        let portOptionsHtml = '';
        let selectedVesselId = null;
        let selectedVesselName = '';
        let legRowSeq = 0;

        function refreshSearchable(el) {
            el?._searchableSelect?.refresh();
        }

        async function ensurePortOptions() {
            if (portOptionsHtml) return portOptionsHtml;
            const response = await apiCall({ mode: 'GET', url: '/api/ports?per_page=500' });
            if (!response.success) return '';

            const rows = response.data?.data ?? [];
            portOptionsHtml = rows.map((p) => `<option value="${p.port_id}">${p.location?.name ?? '-'} - ${p.name}</option>`).join('');
            return portOptionsHtml;
        }

        // Active-only, refetched every time the form opens so a vessel
        // that was just tagged Under Repair in Vessel Management can't be
        // picked for a new voyage.
        async function loadActiveVesselOptions(keepValue) {
            const select = document.getElementById('vsVesselSelect');
            select.innerHTML = '<option value="">Loading...</option>';

            const response = await apiCall({ mode: 'GET', url: '/api/vessels?status=1&per_page=500' });
            if (!response.success) {
                select.innerHTML = '<option value="">-- Select active vessel --</option>';
                return;
            }

            const rows = response.data?.data ?? [];
            select.innerHTML = '<option value="">-- Select active vessel --</option>' +
                rows.map((v) => `<option value="${v.id}">${v.name}${v.vessel_code ? ' (' + v.vessel_code + ')' : ''}</option>`).join('');

            if (keepValue) select.value = keepValue;

            if (window.makeSearchableSelect) window.makeSearchableSelect(select);
            refreshSearchable(select);
        }

        function toLocalInputValue(isoString) {
            if (!isoString) return '';
            const d = new Date(isoString);
            const pad = (n) => String(n).padStart(2, '0');
            return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
        }

        function statusBadge(vessel) {
            const colors = { 1: 'bg-green-100 text-green-700', 2: 'bg-amber-100 text-amber-700', 3: 'bg-red-100 text-red-700', 4: 'bg-zinc-200 text-zinc-600' };
            const labels = { 1: 'Active', 2: 'Under Repair', 3: 'Out of Service', 4: 'Decommissioned' };
            const cls = colors[vessel.status] ?? 'bg-zinc-100 text-zinc-600';
            return `<span class="px-2 py-0.5 rounded-full text-xs font-medium ${cls}">${labels[vessel.status] ?? '-'}</span>`;
        }

        function renderVesselInfo(vessel) {
            const panel = document.getElementById('vsVesselInfoPanel');
            panel.classList.remove('border-dashed', 'text-zinc-400');
            panel.innerHTML = `
                <div class="flex items-center justify-between mb-2">
                    <p class="font-semibold text-zinc-800 dark:text-zinc-100">${vessel.name}</p>
                    ${statusBadge(vessel)}
                </div>
                <dl class="space-y-1 text-xs">
                    <div class="flex justify-between"><dt class="text-zinc-400">Type</dt><dd>${vessel.vessel_type ?? '-'}</dd></div>
                    <div class="flex justify-between"><dt class="text-zinc-400">Home Port</dt><dd>${vessel.home_port ? (vessel.home_port.location?.name ?? '-') + ' - ' + vessel.home_port.name : '-'}</dd></div>
                    <div class="flex justify-between"><dt class="text-zinc-400">Engines</dt><dd>${vessel.engine_count ?? '-'}</dd></div>
                    <div class="flex justify-between"><dt class="text-zinc-400">Capacity (TEU)</dt><dd>${vessel.capacity_teu ?? '-'}</dd></div>
                    <div class="flex justify-between"><dt class="text-zinc-400">IMO No.</dt><dd>${vessel.imo_number ?? '-'}</dd></div>
                </dl>
            `;
        }

        function resetVesselInfo() {
            const panel = document.getElementById('vsVesselInfoPanel');
            panel.classList.add('border-dashed', 'text-zinc-400');
            panel.innerHTML = 'Select a vessel to view its information and manage its schedule.';
        }

        function legRowHtml(id, voyageLeg) {
            legRowSeq += 1;
            const rowId = `vsLeg${legRowSeq}`;
            return `
                <div class="vsLegRow border border-zinc-200 dark:border-zinc-700 rounded-lg p-3 space-y-2" id="${rowId}" data-leg-id="${id ?? ''}" data-voyage-leg="${voyageLeg ?? ''}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-zinc-400">Leg ${voyageLeg ?? '(new)'}</span>
                        <button type="button" class="vsRemoveLegBtn text-red-500 hover:text-red-700 text-xs">Remove</button>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <select required class="vsLegOriginSelect w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-2 py-1.5 text-xs">
                            <option value="">Origin port</option>
                        </select>
                        <select required class="vsLegDestSelect w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-2 py-1.5 text-xs">
                            <option value="">Destination port</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] text-zinc-400 mb-0.5">Est. Departure</label>
                            <input type="datetime-local" class="vsLegDeparture w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-2 py-1.5 text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] text-zinc-400 mb-0.5">Est. Arrival</label>
                            <input type="datetime-local" class="vsLegArrival w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-2 py-1.5 text-xs">
                        </div>
                    </div>
                </div>
            `;
        }

        async function addLegRow(leg) {
            document.getElementById('vsLegRowsEmpty')?.remove();

            const html = legRowHtml(leg?.id, leg?.voyage_leg);
            document.getElementById('vsLegRowsContainer').insertAdjacentHTML('beforeend', html);

            const rows = document.querySelectorAll('.vsLegRow');
            const row = rows[rows.length - 1];

            const options = await ensurePortOptions();
            const originSelect = row.querySelector('.vsLegOriginSelect');
            const destSelect = row.querySelector('.vsLegDestSelect');
            originSelect.insertAdjacentHTML('beforeend', options);
            destSelect.insertAdjacentHTML('beforeend', options);

            if (leg) {
                if (leg.origin_port_id) originSelect.value = leg.origin_port_id;
                if (leg.destination_port_id) destSelect.value = leg.destination_port_id;
                row.querySelector('.vsLegDeparture').value = toLocalInputValue(leg.estimated_departure_at);
                row.querySelector('.vsLegArrival').value = toLocalInputValue(leg.estimated_arrival_at);
            }

            if (window.makeSearchableSelect) {
                window.makeSearchableSelect(originSelect);
                window.makeSearchableSelect(destSelect);
            }

            row.querySelector('.vsRemoveLegBtn').addEventListener('click', async function() {
                if (row.dataset.legId) {
                    if (!confirm('Remove this leg from the schedule? This cannot be undone.')) return;
                    const response = await apiCall({ mode: 'DELETE', url: `/api/vesselVoyages/${row.dataset.legId}` });
                    if (!response.success) {
                        showMessage({ status: 'error', title: 'Unable to remove leg', message: response.message ?? '' });
                        return;
                    }
                    table.reload();
                }
                row.remove();
                if (!document.querySelector('.vsLegRow')) {
                    document.getElementById('vsLegRowsContainer').innerHTML = '<p id="vsLegRowsEmpty" class="text-xs text-zinc-400 italic">No legs yet - add one above.</p>';
                }
            });
        }

        async function loadVesselSchedule(vesselId) {
            document.getElementById('vsLegRowsContainer').innerHTML = '<p class="text-xs text-zinc-400 italic">Loading schedule...</p>';

            const response = await apiCall({ mode: 'GET', url: `/api/vesselVoyages?vessel_id=${vesselId}&per_page=200` });
            document.getElementById('vsLegRowsContainer').innerHTML = '';

            const legs = response.success ? (response.data?.data ?? []) : [];
            if (!legs.length) {
                document.getElementById('vsLegRowsContainer').innerHTML = '<p id="vsLegRowsEmpty" class="text-xs text-zinc-400 italic">No legs yet - add one above.</p>';
                return;
            }

            for (const leg of legs) {
                await addLegRow(leg);
            }
        }

        function renderContainersList(legs) {
            const container = document.getElementById('vsContainersList');
            if (!legs.length) {
                container.innerHTML = '<p class="text-xs text-zinc-400 italic">No legs yet - add one above.</p>';
                return;
            }

            container.innerHTML = legs.map((leg) => {
                const units = leg.units ?? [];
                const originLabel = leg.origin_port ? `${leg.origin_port.location?.name ?? '-'} - ${leg.origin_port.name}` : '-';
                const destLabel = leg.destination_port ? `${leg.destination_port.location?.name ?? '-'} - ${leg.destination_port.name}` : '-';
                const rows = units.length
                    ? units.map((u) => {
                        const isRelay = u.relay_port_id && u.relay_port_id === leg.destination_port_id;
                        return `
                            <tr>
                                <td class="px-2 py-1">${u.container_asset?.container_no ?? 'Not yet assigned'}</td>
                                <td class="px-2 py-1">${u.booking?.client?.company_name ?? '-'}</td>
                                <td class="px-2 py-1 text-green-600">Load @ ${originLabel}</td>
                                <td class="px-2 py-1 ${isRelay ? 'text-amber-600' : 'text-orange-600'}">${isRelay ? 'Relay' : 'Unload'} @ ${destLabel}</td>
                            </tr>
                        `;
                    }).join('')
                    : `<tr><td colspan="4" class="px-2 py-2 text-center text-zinc-400 italic">No containers assigned to this leg.</td></tr>`;

                return `
                    <div class="mb-3">
                        <p class="text-xs font-semibold text-zinc-600 dark:text-zinc-300 mb-1">Leg ${leg.voyage_leg}: ${originLabel} &rarr; ${destLabel} (${units.length} container${units.length === 1 ? '' : 's'})</p>
                        <table class="w-full text-xs border border-zinc-200 dark:border-zinc-700">
                            <thead class="bg-zinc-50 dark:bg-zinc-800">
                                <tr>
                                    <th class="px-2 py-1 text-left">Container No.</th>
                                    <th class="px-2 py-1 text-left">Client</th>
                                    <th class="px-2 py-1 text-left">Load</th>
                                    <th class="px-2 py-1 text-left">Unload</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                `;
            }).join('');
        }

        async function loadVesselContainers(vesselId) {
            document.getElementById('vsContainersList').innerHTML = '<p class="text-xs text-zinc-400 italic">Loading containers...</p>';
            const response = await apiCall({ mode: 'GET', url: `/api/vesselVoyages/vessel/${vesselId}/containers` });
            const legs = response.success ? (response.data ?? []) : [];
            renderContainersList(legs);
        }

        function setManifestButton(vesselId) {
            const btn = document.getElementById('vsDownloadManifestBtn');
            if (vesselId) {
                btn.href = `/api/vesselVoyages/vessel/${vesselId}/manifest`;
                btn.classList.remove('pointer-events-none', 'opacity-40');
            } else {
                btn.href = '#';
                btn.classList.add('pointer-events-none', 'opacity-40');
            }
        }

        async function selectVessel(vesselId) {
            selectedVesselId = vesselId;
            document.getElementById('vsAddLegBtn').disabled = !vesselId;
            setManifestButton(vesselId);

            if (!vesselId) {
                selectedVesselName = '';
                resetVesselInfo();
                document.getElementById('vsLegRowsContainer').innerHTML = '<p id="vsLegRowsEmpty" class="text-xs text-zinc-400 italic">No vessel selected yet.</p>';
                document.getElementById('vsContainersList').innerHTML = 'Select a vessel to view its containers.';
                return;
            }

            const response = await apiCall({ mode: 'GET', url: `/api/vessels/${vesselId}` });
            if (response.success) {
                selectedVesselName = response.data.name;
                renderVesselInfo(response.data);
            }

            await loadVesselSchedule(vesselId);
            await loadVesselContainers(vesselId);
        }

        function renderTable() {
            const thead = [
                { title: 'Voyage Mnemonic', key: 'voyage_mnemonic' },
                { title: 'Vessel', key: 'vessel.name', render: (r) => r.vessel?.name ?? '-' },
                { title: 'Leg', key: 'voyage_leg' },
                { title: 'Origin', key: 'origin_port', render: (r) => r.origin_port ? `${r.origin_port.location?.name ?? '-'} - ${r.origin_port.name}` : '-' },
                { title: 'Destination', key: 'destination_port', render: (r) => r.destination_port ? `${r.destination_port.location?.name ?? '-'} - ${r.destination_port.name}` : '-' },
                { title: 'Est. Departure', key: 'estimated_departure_at', render: (r) => r.estimated_departure_at ? formatDateTime(r.estimated_departure_at) : '-' },
                { title: 'Est. Arrival', key: 'estimated_arrival_at', render: (r) => r.estimated_arrival_at ? formatDateTime(r.estimated_arrival_at) : '-' },
                {
                    title: '',
                    key: 'id',
                    render: (r) => `<a href="/api/vesselVoyages/${r.id}/loadlist" target="_blank" onclick="event.stopPropagation()" class="text-blue-600 hover:text-blue-700 text-xs font-medium">Loadlist</a>`
                },
            ];

            return renderRemoteTable({
                url: '/api/vesselVoyages',
                tableId: 'tableVoyageSchedule',
                afterRenderFunction: handleRowClick,
                thead,
            });
        }

        function handleRowClick(row) {
            row.addEventListener('click', function() {
                const data = JSON.parse(row.dataset.row);
                openManageModal(data.vessel_id, data.vessel);
            });
        }

        async function resetModal() {
            document.getElementById('voyageForm').reset();
            document.getElementById('vsLegRowsContainer').innerHTML = '<p id="vsLegRowsEmpty" class="text-xs text-zinc-400 italic">No vessel selected yet.</p>';
            document.getElementById('vsContainersList').innerHTML = 'Select a vessel to view its containers.';
            resetVesselInfo();
            document.getElementById('vsAddLegBtn').disabled = true;
            setManifestButton(null);
            selectedVesselId = null;
            selectedVesselName = '';
        }

        async function openManageModal(vesselId, vessel) {
            await resetModal();
            document.getElementById('vsFormTitle').textContent = vessel ? `Manage Schedule - ${vessel.name}` : 'Manage Voyage Schedule';

            await Promise.all([ensurePortOptions(), loadActiveVesselOptions(vesselId ?? '')]);

            // The current vessel may no longer be Active - add it back in
            // so the modal doesn't silently blank out a valid vessel.
            const select = document.getElementById('vsVesselSelect');
            if (vesselId && !select.querySelector(`option[value="${vesselId}"]`)) {
                const label = vessel ? `${vessel.name} (not Active)` : `Vessel #${vesselId}`;
                select.insertAdjacentHTML('beforeend', `<option value="${vesselId}">${label}</option>`);
                select.value = vesselId;
                refreshSearchable(select);
            }

            initModal({ modalId: 'voyageFormModal' });

            if (vesselId) await selectVessel(vesselId);
        }

        document.getElementById('vsAddVoyageBtn').addEventListener('click', () => openManageModal(null, null));

        document.getElementById('vsVesselSelect').addEventListener('change', function() {
            selectVessel(this.value || null);
        });

        document.getElementById('vsAddLegBtn').addEventListener('click', () => addLegRow(null));

        document.getElementById('vsVesselMgmtLink').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('#voyageFormModal .modal-close').click();
            if (window.loadPage) window.loadPage({ title: 'Vessel Management', link: '/page_vessel_management' });
        });

        document.getElementById('voyageForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!selectedVesselId) {
                showMessage({ status: 'warning', title: 'Select a vessel first' });
                return;
            }

            const rows = Array.from(document.querySelectorAll('.vsLegRow'));
            if (!rows.length) {
                showMessage({ status: 'warning', title: 'Add at least one route leg' });
                return;
            }

            const usedLetters = new Set(rows.map((r) => r.dataset.voyageLeg).filter(Boolean));
            function nextLetter() {
                for (let i = 0; i < 26; i++) {
                    const letter = String.fromCharCode(65 + i);
                    if (!usedLetters.has(letter)) {
                        usedLetters.add(letter);
                        return letter;
                    }
                }
                return String(usedLetters.size + 1);
            }

            const submitBtn = document.getElementById('vsFormSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            const requests = rows.map((row) => {
                const originId = row.querySelector('.vsLegOriginSelect').value;
                const destId = row.querySelector('.vsLegDestSelect').value;
                const payload = {
                    origin_port_id: originId,
                    destination_port_id: destId,
                    estimated_departure_at: row.querySelector('.vsLegDeparture').value || null,
                    estimated_arrival_at: row.querySelector('.vsLegArrival').value || null,
                };

                const legId = row.dataset.legId;
                if (legId) {
                    return apiCall({ mode: 'PUT', isJson: true, payload, url: `/api/vesselVoyages/${legId}` });
                }

                const legLetter = nextLetter();
                payload.vessel_id = selectedVesselId;
                payload.voyage_leg = legLetter;
                payload.voyage_mnemonic = `${selectedVesselName}-${legLetter}`;
                return apiCall({ mode: 'POST', isJson: true, payload, url: '/api/vesselVoyages' });
            });

            const results = await Promise.all(requests);
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Schedule';

            const failed = results.filter((r) => !r.success);
            if (failed.length) {
                showMessage({ status: 'error', title: 'Some legs failed to save', message: failed[0].message ?? '' });
                await loadVesselSchedule(selectedVesselId);
                table.reload();
                return;
            }

            showMessage({ status: 'success', title: 'Voyage schedule saved' });
            document.querySelector('#voyageFormModal .modal-close').click();
            table.reload();
        });

        async function initFilters() {
            const destSelect = document.getElementById('vsFilterDestination');
            const options = await ensurePortOptions();
            destSelect.insertAdjacentHTML('beforeend', options);
            if (window.makeSearchableSelect) window.makeSearchableSelect(destSelect);

            function applyFilters() {
                table.setFilters({
                    voyage_mnemonic: document.getElementById('vsFilterMnemonic').value.trim(),
                    vessel_name: document.getElementById('vsFilterVesselName').value.trim(),
                    departure_date: document.getElementById('vsFilterDeparture').value,
                    arrival_date: document.getElementById('vsFilterArrival').value,
                    destination_port_id: destSelect.value,
                });
            }

            let debounceTimer;
            function debouncedApply() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(applyFilters, 400);
            }

            document.getElementById('vsFilterMnemonic').addEventListener('input', debouncedApply);
            document.getElementById('vsFilterVesselName').addEventListener('input', debouncedApply);
            document.getElementById('vsFilterDeparture').addEventListener('change', applyFilters);
            document.getElementById('vsFilterArrival').addEventListener('change', applyFilters);
            destSelect.addEventListener('change', applyFilters);

            document.getElementById('vsClearFiltersBtn').addEventListener('click', function() {
                document.getElementById('vsFilterMnemonic').value = '';
                document.getElementById('vsFilterVesselName').value = '';
                document.getElementById('vsFilterDeparture').value = '';
                document.getElementById('vsFilterArrival').value = '';
                destSelect.value = '';
                refreshSearchable(destSelect);
                applyFilters();
            });
        }

        table = renderTable();
        table.load();
        initFilters();
    })();
</script>
