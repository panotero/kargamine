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

{{-- Assign Vessel Voyage modal (SOP Step 11) --}}
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
            const label = `${first.origin_port?.code ?? '-'} &rarr; ${first.destination_port?.code ?? '-'}`;
            const sameRoute = lines.every((l) => l.origin_port_id === first.origin_port_id && l.destination_port_id === first.destination_port_id);
            return sameRoute ? label : `${label} +${lines.length - 1} more`;
        }

        function transactionDetailBadge(r) {
            const lines = r.lines ?? [];
            const complete = lines.length > 0 && lines.every((l) => l.consignee_name && l.cargo_type && l.declared_value !== null && l.delivery_date);
            return complete
                ? '<span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5 text-xs font-medium">Live</span>'
                : '<span class="inline-flex items-center rounded-full bg-amber-50 text-amber-700 px-2 py-0.5 text-xs font-medium">Tentative</span>';
        }

        // -----------------------------------------------------------------
        // Vessel Voyage assignment / Shut Out - SOP Steps 10-11. The
        // backend is the source of truth for whether a unit is actually
        // eligible (In Yard) - these buttons always show, and a
        // not-ready click just surfaces the server's error message.
        // -----------------------------------------------------------------
        let voyageUnitTarget = null;
        let voyageOptionsLoaded = false;
        let portOptionsLoaded = false;

        function voyageColumn(r) {
            const units = r.container_units ?? [];
            if (!units.length) return '-';

            return units.map((u) => {
                const containerNo = u.container_asset?.container_no ?? `Unit #${u.id}`;

                if (u.shut_out_at) {
                    return `<div class="mb-1 whitespace-nowrap">
                        <span class="inline-flex items-center rounded-full bg-red-50 text-red-700 px-2 py-0.5 text-xs font-medium">Shut Out</span>
                        ${containerNo}
                        <button type="button" class="voyage-assign-btn text-blue-600 text-xs underline ml-1" data-unit-id="${u.id}">Reassign</button>
                    </div>`;
                }

                if (u.vessel_voyage) {
                    return `<div class="mb-1 whitespace-nowrap">
                        <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5 text-xs font-medium">${u.vessel_voyage.voyage_mnemonic}</span>
                        ${containerNo}
                        <button type="button" class="voyage-shutout-btn text-red-600 text-xs underline ml-1" data-unit-id="${u.id}">Shut Out</button>
                    </div>`;
                }

                return `<div class="mb-1 whitespace-nowrap">
                    ${containerNo}
                    <button type="button" class="voyage-assign-btn text-orange-600 text-xs underline ml-1" data-unit-id="${u.id}">Assign Voyage</button>
                </div>`;
            }).join('');
        }

        async function loadVoyageOptions() {
            if (voyageOptionsLoaded) return;
            const response = await apiCall({ mode: 'GET', url: '/api/vesselVoyages?per_page=200' });
            if (!response.success) return;

            document.getElementById('vaVoyage').innerHTML = '<option value="">Select Voyage</option>' +
                (response.data.data ?? []).map(v =>
                    `<option value="${v.id}">${v.voyage_mnemonic} (${v.origin_port?.code ?? '?'} &rarr; ${v.destination_port?.code ?? '?'})</option>`
                ).join('');
            voyageOptionsLoaded = true;
        }

        async function loadPortOptions() {
            if (portOptionsLoaded) return;
            const response = await apiCall({ mode: 'GET', url: '/api/ports?per_page=200' });
            if (!response.success) return;

            document.getElementById('vaRelayPort').innerHTML = '<option value="">None</option>' +
                (response.data.data ?? []).map(p => `<option value="${p.port_id}">${p.code} - ${p.name}</option>`).join('');
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
                await Promise.all([loadVoyageOptions(), loadPortOptions()]);
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
                    title: 'Voyage',
                    key: 'container_units',
                    render: voyageColumn
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
                window.bookingFormUuid = data.uuid;
                loadPage({
                    title: 'Edit Booking',
                    link: '/page_bookingForm'
                });
            });
        }

        table = renderTable();
        table.setFilter('bucket', activeBucket);
        loadBuckets();
    })();
</script>
