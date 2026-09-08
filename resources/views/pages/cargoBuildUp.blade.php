<div class="container mx-auto px-4 py-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold">Cargo Build-Up</h1>
        <p class="text-zinc-500">Booking status board - SOP Step 2 through 11, phased in as each stage is built.</p>
    </div>

    {{-- 13-bucket status board. Rendered from the API rather than hardcoded
         here, since the tracked/untracked split - and their counts - are the
         backend's call, not the view's. --}}
    <section class="w-full mb-6">
        <div id="bucketGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3"></div>
    </section>

    <x-table id="tableCargoBuildUp" />
</div>

{{-- Booking Details modal - shown for Live/Confirmed bookings instead of
     navigating away, since there's nothing left to edit on the booking
     form itself at that point; Tentative bookings still route to the
     Booking form (see isLive() below). --}}
<x-modal id="bookingDetailsModal">
    <div class="p-5 border-b flex justify-between items-center">
        <div>
            <p class="text-lg font-semibold" id="bdTitle">Booking Details</p>
            <p class="text-xs text-zinc-400 mt-0.5" id="bdSubtitle"></p>
        </div>
        <button class="modal-close">✕</button>
    </div>
    <div class="max-h-[70vh] overflow-y-auto p-5 text-sm text-zinc-700 dark:text-zinc-300">
        <p class="text-xs font-medium text-zinc-500 mb-2">Containers on This Booking</p>
        <div id="bdContainersList"></div>
    </div>
    <div class="border-t px-5 py-4 flex justify-end">
        <button class="modal-close border px-4 py-2 rounded-lg text-sm">Close</button>
    </div>
</x-modal>

{{-- Assign Vessel Voyage modal (SOP Step 11) - opened on top of
     bookingDetailsModal, voyage options scoped to the unit's own route. --}}
<x-modal id="voyageAssignModal">
    <div class="p-5 border-b flex justify-between items-center">
        <p class="text-lg font-semibold">Assign Vessel Voyage</p>
        <button class="modal-close">✕</button>
    </div>
    <div class="p-5 space-y-3 text-sm">
        <div>
            <label class="text-[11px] text-zinc-400 uppercase">Vessel Voyage</label>
            <select id="vaVoyage" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <option value="">Select Voyage</option>
            </select>
        </div>
        <div>
            <label class="text-[11px] text-zinc-400 uppercase">Equivalent TEU <span class="normal-case text-zinc-300">(Flat Rack / Rolling / Loose Cargo only)</span></label>
            <input type="number" step="0.01" min="0" id="vaEquivalentTeu" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
        </div>
        <div>
            <label class="text-[11px] text-zinc-400 uppercase">Relay Port <span class="normal-case text-zinc-300">(if needed)</span></label>
            <select id="vaRelayPort" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <option value="">None</option>
            </select>
        </div>
    </div>
    <div class="border-t px-5 py-4 flex justify-end gap-2">
        <button class="modal-close border px-4 py-2 rounded-lg text-sm">Cancel</button>
        <button type="button" id="vaSaveBtn"
            class="px-4 py-2 rounded-lg text-sm bg-orange-500 hover:bg-orange-600 text-white">Assign</button>
    </div>
</x-modal>

<script>
    (function() {
        let table = null;
        let activeBucket = 'cargo_build_up';

        function tileHtml(bucket) {
            const isActive = bucket.key === activeBucket;
            const trackedClasses = bucket.tracked
                ? `cursor-pointer bg-white dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 ${isActive ? 'ring-2 ring-orange-500' : ''}`
                : 'cursor-not-allowed bg-zinc-50 dark:bg-zinc-900/50 border-dashed border-zinc-200 dark:border-zinc-800 opacity-60';

            const countHtml = bucket.tracked
                ? `<p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">${bucket.count ?? 0}</p>`
                : `<p class="text-2xl font-bold text-zinc-300 dark:text-zinc-700">&mdash;</p>`;

            return `
                <div class="bucketBtn border rounded-xl p-4 shadow-sm ${trackedClasses}"
                     data-bucket="${bucket.key}" data-tracked="${bucket.tracked ? '1' : '0'}"
                     title="${bucket.description}">
                    <div class="w-full py-1 rounded-full ${bucket.tracked ? 'bg-orange-500' : 'bg-zinc-300 dark:bg-zinc-700'}"></div>
                    <p class="text-[11px] text-zinc-400 font-semibold mt-2 uppercase tracking-wide">${bucket.label}</p>
                    ${countHtml}
                    ${bucket.tracked ? '' : '<p class="text-[10px] text-zinc-400 mt-1">Not yet tracked</p>'}
                </div>`;
        }

        async function loadBuckets() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/cargo-build-up',
            });
            if (!response.success) return;

            const grid = document.getElementById('bucketGrid');
            grid.innerHTML = response.data.map(tileHtml).join('');

            grid.querySelectorAll('.bucketBtn').forEach((btn) => {
                btn.addEventListener('click', function() {
                    if (this.dataset.tracked !== '1') {
                        showMessage({
                            status: 'warning',
                            title: 'Not yet tracked',
                            message: this.title || 'This stage isn\'t built yet - it lands in a later phase.',
                        });
                        return;
                    }

                    // These two are where the actual scanning work happens -
                    // send the CSR to Pier Check-In instead of just filtering
                    // the read-only table below.
                    if (this.dataset.bucket === 'for_gate_out' || this.dataset.bucket === 'pickup_in_transit') {
                        loadPage({
                            title: 'Pier Check-In',
                            link: '/page_pier_checkin'
                        });
                        return;
                    }

                    activeBucket = this.dataset.bucket;
                    grid.querySelectorAll('.bucketBtn').forEach((b) => b.classList.remove('ring-2', 'ring-orange-500'));
                    this.classList.add('ring-2', 'ring-orange-500');
                    table.setFilter('bucket', activeBucket);
                });
            });
        }

        function money(v) {
            return Number(v ?? 0).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function routeSummary(r) {
            const lines = r.lines ?? [];
            if (!lines.length) return '-';
            const first = lines[0];
            const label = `${first.origin_port ? (first.origin_port.location?.name ?? '-') + ' - ' + first.origin_port.name : '-'} &rarr; ${first.destination_port ? (first.destination_port.location?.name ?? '-') + ' - ' + first.destination_port.name : '-'}`;
            const sameRoute = lines.every((l) => l.origin_port_id === first.origin_port_id && l.destination_port_id === first.destination_port_id);
            return sameRoute ? label : `${label} +${lines.length - 1} more`;
        }

        // Mirrors Booking::scopeLive() - Draft is Tentative, Confirmed (or
        // further along the lifecycle) is Live. Status 1 = Draft.
        function isLive(r) {
            const lines = r.lines ?? [];
            return lines.length > 0 && Number(r.status) !== 1;
        }

        function transactionDetailBadge(r) {
            return isLive(r)
                ? '<span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5 text-xs font-medium">Live</span>'
                : '<span class="inline-flex items-center rounded-full bg-amber-50 text-amber-700 px-2 py-0.5 text-xs font-medium">Tentative</span>';
        }

        // -----------------------------------------------------------------
        // Booking Details modal (containers + Assign Voyage / Shut Out /
        // Load-Unload List per unit) - opened for Live or Confirmed
        // bookings instead of navigating to the Booking form. SOP Steps
        // 10-11. The backend is the source of truth for whether a unit is
        // actually eligible (In Yard) - these buttons always show, and a
        // not-ready click just surfaces the server's error message.
        // -----------------------------------------------------------------
        let voyageUnitTarget = null;
        let currentBookingUuid = null;
        let portOptionsLoaded = false;

        function containerRowHtml(u) {
            const containerNo = u.container_asset?.container_no ?? `Unit #${u.id}`;
            const type = u.booking_line
                ? `${u.booking_line.container?.name ?? '-'} / ${u.booking_line.container_class?.class ?? '-'} / ${u.booking_line.container_size?.size ?? '-'}`
                : '-';
            const originLabel = u.origin_port ? `${u.origin_port.location?.name ?? '-'} - ${u.origin_port.name}` : '-';
            const destLabel = u.destination_port ? `${u.destination_port.location?.name ?? '-'} - ${u.destination_port.name}` : '-';

            let voyageCell;
            if (u.shut_out_at) {
                voyageCell = `<span class="inline-flex items-center rounded-full bg-red-50 text-red-700 px-2 py-0.5 text-xs font-medium">Shut Out</span>
                    <button type="button" class="voyage-assign-btn text-blue-600 text-xs underline ml-1" data-unit-id="${u.id}" data-origin="${u.origin_port_id ?? ''}" data-destination="${u.destination_port_id ?? ''}">Reassign</button>`;
            } else if (u.vessel_voyage) {
                voyageCell = `<span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5 text-xs font-medium">${u.vessel_voyage.voyage_mnemonic}</span>
                    <button type="button" class="voyage-shutout-btn text-red-600 text-xs underline ml-1" data-unit-id="${u.id}">Shut Out</button>`;
            } else {
                voyageCell = `<span class="text-zinc-400 text-xs">Unassigned</span>
                    <button type="button" class="voyage-assign-btn text-orange-600 text-xs underline ml-1" data-unit-id="${u.id}" data-origin="${u.origin_port_id ?? ''}" data-destination="${u.destination_port_id ?? ''}">Assign Voyage</button>`;
            }

            const loadlistBtn = u.vessel_voyage
                ? `<a href="/api/vesselVoyages/${u.vessel_voyage.id}/loadlist" target="_blank" class="text-blue-600 hover:text-blue-700 text-xs font-medium">Load/Unload List</a>`
                : `<span class="text-zinc-300 text-xs">&mdash;</span>`;

            return `
                <tr class="border-t border-zinc-100 dark:border-zinc-800">
                    <td class="px-2 py-1.5">${containerNo}</td>
                    <td class="px-2 py-1.5">${type}</td>
                    <td class="px-2 py-1.5 whitespace-nowrap">${originLabel} &rarr; ${destLabel}</td>
                    <td class="px-2 py-1.5 whitespace-nowrap">${voyageCell}</td>
                    <td class="px-2 py-1.5">${loadlistBtn}</td>
                </tr>
            `;
        }

        function renderBookingDetails(booking) {
            currentBookingUuid = booking.uuid;
            document.getElementById('bdTitle').textContent = booking.code ?? 'Booking Details';
            const routeText = routeSummary(booking).replace(/&rarr;/g, '→');
            document.getElementById('bdSubtitle').textContent = `${booking.client?.company_name ?? '-'} • ${routeText}`;

            const units = booking.container_units ?? [];
            const rows = units.length
                ? units.map(containerRowHtml).join('')
                : `<tr><td colspan="5" class="text-center text-zinc-400 italic py-3">No container units on this booking yet.</td></tr>`;

            document.getElementById('bdContainersList').innerHTML = `
                <table class="w-full text-xs border border-zinc-200 dark:border-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-2 py-1.5 text-left">Container No.</th>
                            <th class="px-2 py-1.5 text-left">Type</th>
                            <th class="px-2 py-1.5 text-left">Route</th>
                            <th class="px-2 py-1.5 text-left">Voyage</th>
                            <th class="px-2 py-1.5 text-left">Load/Unload List</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            `;
        }

        function openBookingDetailsModal(booking) {
            renderBookingDetails(booking);
            initModal({ modalId: 'bookingDetailsModal' });
        }

        async function refreshBookingDetails() {
            if (!currentBookingUuid) return;
            const response = await apiCall({ mode: 'GET', url: `/api/cargo-build-up/bookings/${currentBookingUuid}` });
            if (response.success) renderBookingDetails(response.data);
        }

        // Voyage options are re-fetched per click, scoped to that unit's
        // own origin/destination - "assignment of voyage is based on the
        // route of the booking" rather than picking from every voyage.
        async function loadVoyageOptions(originPortId, destinationPortId) {
            const select = document.getElementById('vaVoyage');
            select.innerHTML = '<option value="">Loading...</option>';

            let url = '/api/vesselVoyages?per_page=200';
            if (originPortId) url += `&origin_port_id=${originPortId}`;
            if (destinationPortId) url += `&destination_port_id=${destinationPortId}`;

            const response = await apiCall({ mode: 'GET', url });
            const rows = response.success ? (response.data.data ?? []) : [];

            if (!rows.length) {
                select.innerHTML = '<option value="">No voyages found for this route yet</option>';
                return;
            }

            select.innerHTML = '<option value="">Select Voyage</option>' +
                rows.map(v =>
                    `<option value="${v.id}">${v.voyage_mnemonic} (${v.origin_port ? (v.origin_port.location?.name ?? '?') + ' - ' + v.origin_port.name : '?'} &rarr; ${v.destination_port ? (v.destination_port.location?.name ?? '?') + ' - ' + v.destination_port.name : '?'})</option>`
                ).join('');
        }

        async function loadPortOptions() {
            if (portOptionsLoaded) return;
            const response = await apiCall({ mode: 'GET', url: '/api/ports?per_page=200' });
            if (!response.success) return;

            document.getElementById('vaRelayPort').innerHTML = '<option value="">None</option>' +
                (response.data.data ?? []).map(p => `<option value="${p.port_id}">${p.location?.name ?? '-'} - ${p.name}</option>`).join('');
            portOptionsLoaded = true;
        }

        // Capture phase (the `true` below) so this runs BEFORE the table
        // row's own bubble-phase click listener (which navigates to Edit
        // Booking) - stopPropagation() here keeps that row navigation
        // from firing when one of these buttons is the actual target.
        document.addEventListener('click', async function(e) {
            const assignBtn = e.target.closest('.voyage-assign-btn');
            const shutOutBtnCheck = e.target.closest('.voyage-shutout-btn');
            if (assignBtn || shutOutBtnCheck) {
                e.stopPropagation();
            }

            if (assignBtn) {
                voyageUnitTarget = assignBtn.dataset.unitId;
                document.getElementById('vaVoyage').value = '';
                document.getElementById('vaEquivalentTeu').value = '';
                document.getElementById('vaRelayPort').value = '';
                await Promise.all([
                    loadVoyageOptions(assignBtn.dataset.origin, assignBtn.dataset.destination),
                    loadPortOptions(),
                ]);
                initModal({ modalId: 'voyageAssignModal' });
                return;
            }

            const shutOutBtn = e.target.closest('.voyage-shutout-btn');
            if (shutOutBtn) {
                const response = await apiCall({
                    mode: 'POST',
                    url: `/api/booking-container-units/${shutOutBtn.dataset.unitId}/shut-out`,
                    button: shutOutBtn,
                });

                if (!response.success) {
                    showMessage({ status: 'error', title: 'Unable to shut out', message: response.message ?? '' });
                    return;
                }

                showMessage({ status: 'success', title: 'Tagged Shut Out' });
                table.reload();
                loadBuckets();
                refreshBookingDetails();
            }
        }, true);

        document.getElementById('vaSaveBtn').addEventListener('click', async function() {
            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {
                    vessel_voyage_id: document.getElementById('vaVoyage').value || null,
                    equivalent_teu: document.getElementById('vaEquivalentTeu').value || null,
                    relay_port_id: document.getElementById('vaRelayPort').value || null,
                },
                url: `/api/booking-container-units/${voyageUnitTarget}/assign-voyage`,
                button: this,
            });

            if (!response.success) {
                showMessage({ status: 'error', title: 'Unable to assign voyage', message: response.message ?? '' });
                return;
            }

            showMessage({ status: 'success', title: 'Voyage assigned' });
            document.querySelector('#voyageAssignModal .modal-close').click();
            table.reload();
            loadBuckets();
            refreshBookingDetails();
        });

        function renderTable() {
            const thead = [{
                    title: 'Code',
                    key: 'code',
                    render: (r) => r.code ?? '-'
                },
                {
                    title: 'Client',
                    key: 'client.company_name',
                    render: (r) => r.client?.company_name ?? '-'
                },
                {
                    title: 'Route',
                    key: 'lines',
                    render: routeSummary
                },
                {
                    title: 'Lines',
                    key: 'lines',
                    render: (r) => (r.lines ?? []).length
                },
                {
                    title: 'Transaction Details',
                    key: 'lines',
                    render: transactionDetailBadge
                },
                {
                    title: 'Booking Date',
                    key: 'booking_date'
                },
                {
                    title: 'Grand Total',
                    key: 'grand_total_snapshot',
                    render: (r) => money(r.grand_total_snapshot)
                },
            ];

            return renderRemoteTable({
                url: '/api/cargo-build-up/bookings',
                tableId: 'tableCargoBuildUp',
                afterRenderFunction: handleRowClick,
                thead,
            });
        }

        function handleRowClick(row) {
            row.addEventListener('click', function() {
                const data = JSON.parse(row.dataset.row);

                if (!isLive(data)) {
                    window.bookingFormUuid = data.uuid;
                    loadPage({
                        title: 'Edit Booking',
                        link: '/page_bookingForm'
                    });
                    return;
                }

                openBookingDetailsModal(data);
            });
        }

        table = renderTable();
        table.setFilter('bucket', activeBucket);
        loadBuckets();
    })();
</script>
