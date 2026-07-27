<div class="container mx-auto px-4 py-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Bookings</h1>
            <p class="text-sm text-zinc-500 mt-1">Client shipments — from Draft through Delivered.</p>
        </div>
        <button type="button" id="btnNewBooking"
            class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">
            + New Booking
        </button>
    </div>

    {{-- Status Cards --}}
    <section class="w-full my-5">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
            <div class="bookingStatusBtn max-md:col-span-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm cursor-pointer ring-2 ring-blue-500"
                data-status="">
                <div class="w-full py-1 rounded-full bg-blue-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">ALL</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="countAll">0</p>
            </div>
            <div class="bookingStatusBtn bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="1">
                <div class="w-full py-1 rounded-full bg-zinc-400"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">DRAFT</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="countDraft">0</p>
            </div>
            <div class="bookingStatusBtn bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="2">
                <div class="w-full py-1 rounded-full bg-blue-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">CONFIRMED</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="countConfirmed">0</p>
            </div>
            <div class="bookingStatusBtn bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="3">
                <div class="w-full py-1 rounded-full bg-indigo-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">IN TRANSIT</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="countInTransit">0</p>
            </div>
            <div class="bookingStatusBtn bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="4">
                <div class="w-full py-1 rounded-full bg-teal-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">DELIVERED</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="countDelivered">0</p>
            </div>
            <div class="bookingStatusBtn bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="5">
                <div class="w-full py-1 rounded-full bg-emerald-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">COMPLETED</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="countCompleted">0</p>
            </div>
            <div class="bookingStatusBtn bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="6">
                <div class="w-full py-1 rounded-full bg-red-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">CANCELLED</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="countCancelled">0</p>
            </div>
        </div>
    </section>

    <x-table id="tableBookings" />
</div>

{{-- View Booking modal --}}
<x-modal id="viewBookingModal">
    <div class="p-5 border-b flex justify-between items-center">
        <div>
            <div class="flex items-center gap-2">
                <p class="text-lg font-semibold" id="vbCode">-</p>
                <span id="vbStatusBadge"></span>
            </div>
            <p class="text-xs text-zinc-400" id="vbClientName">-</p>
        </div>
        <button class="modal-close">✕</button>
    </div>

    <div class="max-h-[70vh] overflow-y-auto p-5 space-y-5 text-sm text-zinc-700 dark:text-zinc-300">
        <div id="vbInfoContainer" class="grid grid-cols-2 gap-3 text-sm"></div>

        <div>
            <p class="font-semibold text-sm text-zinc-700 dark:text-zinc-200 mb-2">Cargo Lines</p>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Container</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Qty</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Containers</th>
                            <th class="px-3 py-2 text-right text-zinc-500 uppercase">FRT</th>
                            <th class="px-3 py-2 text-right text-zinc-500 uppercase">Discount</th>
                            <th class="px-3 py-2 text-right text-zinc-500 uppercase">Line Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800" id="vbLinesBody"></tbody>
                </table>
            </div>
        </div>

        <div id="vbChargesContainer" class="grid grid-cols-2 gap-3 text-sm border-t border-zinc-100 dark:border-zinc-800 pt-3"></div>

        <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 space-y-3" id="vbActions">
            <p class="font-semibold text-xs text-zinc-500 uppercase">Actions</p>
            <div class="flex flex-wrap gap-2">
                <button type="button" id="vbConfirmBtn" class="px-3 py-1.5 text-xs rounded-lg bg-blue-600 hover:bg-blue-700 text-white">Confirm Booking</button>
                <button type="button" id="vbMarkInTransitBtn" class="px-3 py-1.5 text-xs rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Mark In Transit</button>
                <button type="button" id="vbMarkDeliveredBtn" class="px-3 py-1.5 text-xs rounded-lg bg-teal-600 hover:bg-teal-700 text-white">Mark Delivered</button>
                <button type="button" id="vbMarkCompletedBtn" class="px-3 py-1.5 text-xs rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white">Close Out</button>
                <button type="button" id="vbCancelBtn" class="px-3 py-1.5 text-xs rounded-lg bg-red-600 hover:bg-red-700 text-white">Cancel Booking</button>
                <a href="#" id="vbDownloadBolBtn" target="_blank" class="hidden px-3 py-1.5 text-xs rounded-lg bg-zinc-700 hover:bg-zinc-800 text-white">Download Bill of Lading</a>
            </div>

            <div id="vbCancelReasonPanel" class="hidden pt-2 border-t border-zinc-100 dark:border-zinc-800 space-y-2">
                <label class="block text-[11px] font-semibold text-zinc-500 uppercase">Cancellation Reason</label>
                <textarea id="vbCancelReasonInput" rows="2" maxlength="500"
                    class="w-full border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-xs bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100"
                    placeholder="Why is this booking being cancelled?"></textarea>
                <div class="flex gap-2">
                    <button type="button" id="vbCancelConfirmBtn" class="px-3 py-1.5 text-xs rounded-lg bg-red-600 hover:bg-red-700 text-white">Confirm Cancellation</button>
                    <button type="button" id="vbCancelDismissBtn" class="px-3 py-1.5 text-xs rounded-lg border">Nevermind</button>
                </div>
            </div>
        </div>

        <div id="vbInvoiceSection" class="hidden border border-zinc-200 dark:border-zinc-700 rounded-lg p-4">
            <p class="font-semibold text-xs text-zinc-500 uppercase mb-2">Invoice</p>
            <div id="vbInvoiceBody" class="text-sm"></div>
        </div>

        <div>
            <p class="font-semibold text-xs text-zinc-500 uppercase mb-2">Status Timeline</p>
            <div id="vbTimeline" class="space-y-2 text-xs"></div>
        </div>
    </div>

    <div class="border-t px-5 py-4 flex justify-end gap-2">
        <button class="modal-close border px-4 py-2 rounded-lg text-sm">Close</button>
    </div>
</x-modal>

<script>
    (function() {
        const STATUS_MAPPING = {
            1: { label: 'Draft', classes: 'bg-zinc-100 text-zinc-600' },
            2: { label: 'Confirmed', classes: 'bg-blue-50 text-blue-700' },
            3: { label: 'In Transit', classes: 'bg-indigo-50 text-indigo-700' },
            4: { label: 'Delivered', classes: 'bg-teal-50 text-teal-700' },
            5: { label: 'Completed', classes: 'bg-emerald-50 text-emerald-700' },
            6: { label: 'Cancelled', classes: 'bg-red-50 text-red-700' },
        };

        const INVOICE_STATUS_LABELS = { 1: 'Draft', 2: 'Sent', 3: 'Paid', 4: 'Void' };

        let currentBookingUuid = null;
        let table = null;

        function statusBadge(status) {
            const meta = STATUS_MAPPING[status] ?? { label: 'Unknown', classes: 'bg-zinc-100 text-zinc-500' };
            return `<span class="inline-flex items-center rounded-full ${meta.classes} px-2 py-0.5 text-xs font-medium">${meta.label}</span>`;
        }

        function money(v) {
            return Number(v ?? 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // -----------------------------------------------------------------
        // List
        // -----------------------------------------------------------------
        async function loadBookings() {
            const response = await apiCall({ mode: 'GET', url: '/api/bookings' });
            if (!response.success) return;
            updateCounts(response.status_counts);
            table.load(1);
        }

        function updateCounts(counts) {
            if (!counts) return;
            document.getElementById('countAll').textContent = counts.all ?? 0;
            document.getElementById('countDraft').textContent = counts.draft ?? 0;
            document.getElementById('countConfirmed').textContent = counts.confirmed ?? 0;
            document.getElementById('countInTransit').textContent = counts.in_transit ?? 0;
            document.getElementById('countDelivered').textContent = counts.delivered ?? 0;
            document.getElementById('countCompleted').textContent = counts.completed ?? 0;
            document.getElementById('countCancelled').textContent = counts.cancelled ?? 0;
        }

        function renderTable() {
            const thead = [
                { title: 'Code', key: 'code', render: (r) => r.code ?? '-' },
                { title: 'Client', key: 'client.company_name', render: (r) => r.client?.company_name ?? '-' },
                { title: 'Lane', key: 'lane', render: (r) => `${r.lane?.origin_port?.code ?? '-'} &rarr; ${r.lane?.destination_port?.code ?? '-'}` },
                { title: 'Delivery Type', key: 'delivery_type.name', render: (r) => r.delivery_type?.name ?? '-' },
                { title: 'Booking Date', key: 'booking_date' },
                { title: 'Status', key: 'status', render: (r) => statusBadge(r.status) },
                { title: 'Grand Total', key: 'grand_total_snapshot', render: (r) => money(r.grand_total_snapshot) },
            ];

            return renderRemoteTable({
                url: '/api/bookings',
                tableId: 'tableBookings',
                afterRenderFunction: handleRowClick,
                thead,
            });
        }

        function handleRowClick(row) {
            row.addEventListener('click', function() {
                const data = JSON.parse(row.dataset.row);
                if (Number(data.status) === 1) {
                    window.bookingFormUuid = data.uuid;
                    loadPage({ title: 'Edit Booking', link: '/page_bookingForm' });
                    return;
                }
                openViewBooking(data.uuid);
            });
        }

        document.querySelectorAll('.bookingStatusBtn').forEach((btn) => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.bookingStatusBtn').forEach((b) => b.classList.remove('ring-2', 'ring-blue-500'));
                this.classList.add('ring-2', 'ring-blue-500');
                table.setFilter('status', this.dataset.status);
            });
        });

        document.getElementById('btnNewBooking').addEventListener('click', function() {
            window.bookingFormUuid = null;
            loadPage({ title: 'New Booking', link: '/page_bookingForm' });
        });

        // -----------------------------------------------------------------
        // View modal
        // -----------------------------------------------------------------
        async function openViewBooking(uuid) {
            const response = await apiCall({ mode: 'GET', url: `/api/bookings/${uuid}` });

            if (!response.success) {
                showMessage({ status: 'error', title: 'Error', message: 'Unable to load this booking.' });
                return;
            }

            currentBookingUuid = uuid;
            const booking = response.data;

            document.getElementById('vbCode').textContent = booking.code;
            document.getElementById('vbStatusBadge').innerHTML = statusBadge(booking.status);
            document.getElementById('vbClientName').textContent = `${booking.client?.company_name ?? '-'} (${booking.client?.customer_code ?? '-'})`;

            document.getElementById('vbInfoContainer').innerHTML = `
                <div><span class="text-zinc-400">Lane:</span> <div class="font-medium">${booking.lane?.origin_port?.name ?? '-'} &rarr; ${booking.lane?.destination_port?.name ?? '-'}</div></div>
                <div><span class="text-zinc-400">Delivery Type:</span> <div class="font-medium">${booking.delivery_type?.name ?? '-'}</div></div>
                <div><span class="text-zinc-400">Booking Date:</span> <div class="font-medium">${booking.booking_date ?? '-'}</div></div>
                <div><span class="text-zinc-400">Contract:</span> <div class="font-medium">${booking.client_contract?.code ?? 'Spot rate'}</div></div>
            `;

            const lineRows = (booking.lines ?? []).map((line) => {
                const variantLabel = `${line.container?.name ?? '-'} / ${line.container_class?.class ?? '-'} / ${line.container_size?.size ?? '-'}`;
                const units = (line.container_units ?? []).map((u) => u.container_asset?.container_no ?? 'Not yet assigned').join(', ');
                const discount = line.discount_type_snapshot
                    ? (line.discount_type_snapshot === 'percentage' ? `${Number(line.discount_value_snapshot).toFixed(2)}%` : money(line.discount_value_snapshot))
                    : '-';

                return `
                    <tr>
                        <td class="px-3 py-2">${variantLabel} &times; ${line.quantity}</td>
                        <td class="px-3 py-2">${line.quantity}</td>
                        <td class="px-3 py-2">${units || '-'}</td>
                        <td class="px-3 py-2 text-right">${money(line.frt_snapshot)}</td>
                        <td class="px-3 py-2 text-right">${discount}</td>
                        <td class="px-3 py-2 text-right font-semibold">${money(line.line_total)}</td>
                    </tr>
                `;
            }).join('');

            document.getElementById('vbLinesBody').innerHTML = lineRows ||
                '<tr><td colspan="6" class="px-3 py-4 text-center text-zinc-400">No cargo lines.</td></tr>';

            const portChargesTotal = (booking.port_charges ?? []).reduce((sum, c) => sum + Number(c.amount_snapshot ?? 0), 0);

            document.getElementById('vbChargesContainer').innerHTML = `
                <div><span class="text-zinc-400">Port Charges:</span> <div class="font-medium">${money(portChargesTotal)}</div></div>
                <div><span class="text-zinc-400">Trucking:</span> <div class="font-medium">${money(booking.trucking_snapshot)}</div></div>
                <div><span class="text-zinc-400">VAT:</span> <div class="font-medium">${money(booking.vat_amount_snapshot)}</div></div>
                <div><span class="text-zinc-400">Grand Total:</span> <div class="font-bold text-base">${money(booking.grand_total_snapshot)}</div></div>
            `;

            renderActions(booking);
            renderInvoice(booking);
            renderTimeline(booking.status_history ?? []);

            initModal({ modalId: 'viewBookingModal' });
        }

        function renderActions(booking) {
            const status = Number(booking.status);

            toggle('vbConfirmBtn', status === 1);
            toggle('vbMarkInTransitBtn', status === 2);
            toggle('vbMarkDeliveredBtn', status === 3);
            toggle('vbMarkCompletedBtn', status === 4);
            toggle('vbCancelBtn', status === 1 || status === 2);
            toggle('vbCancelReasonPanel', false);
            document.getElementById('vbCancelReasonInput').value = '';

            const bolBtn = document.getElementById('vbDownloadBolBtn');
            if (status >= 2 && booking.bill_of_lading) {
                bolBtn.classList.remove('hidden');
                bolBtn.href = `/api/bookings/${booking.uuid}/bol`;
            } else {
                bolBtn.classList.add('hidden');
            }
        }

        function toggle(id, visible) {
            document.getElementById(id).classList.toggle('hidden', !visible);
        }

        function renderInvoice(booking) {
            const section = document.getElementById('vbInvoiceSection');

            if (!booking.invoice) {
                section.classList.add('hidden');
                return;
            }

            section.classList.remove('hidden');
            document.getElementById('vbInvoiceBody').innerHTML = `
                <div class="grid grid-cols-3 gap-3">
                    <div><span class="text-zinc-400">Invoice #:</span> <div class="font-medium">${booking.invoice.invoice_number}</div></div>
                    <div><span class="text-zinc-400">Amount:</span> <div class="font-medium">${money(booking.invoice.amount)}</div></div>
                    <div><span class="text-zinc-400">Status:</span> <div class="font-medium">${INVOICE_STATUS_LABELS[booking.invoice.status] ?? '-'}</div></div>
                </div>
            `;
        }

        function renderTimeline(history) {
            const el = document.getElementById('vbTimeline');

            if (!history.length) {
                el.innerHTML = '<p class="text-zinc-400">No history yet.</p>';
                return;
            }

            el.innerHTML = history.map((h) => `
                <div class="border border-zinc-100 dark:border-zinc-800 rounded-lg px-3 py-2">
                    <div class="flex justify-between">
                        <span class="font-medium">${STATUS_MAPPING[h.from_status]?.label ?? 'Created'} &rarr; ${STATUS_MAPPING[h.to_status]?.label ?? '-'}</span>
                        <span class="text-zinc-400">${h.changed_at ?? ''}</span>
                    </div>
                    <div class="text-zinc-500">By ${h.changed_by?.name ?? 'System'}${h.note ? ' · ' + h.note : ''}</div>
                </div>
            `).join('');
        }

        async function performAction(url, payload, successTitle) {
            const response = await apiCall({ mode: 'POST', isJson: true, payload: payload ?? {}, url });

            if (!response.success) {
                showMessage({ status: 'error', title: 'Unable to complete action', message: response.message ?? '' });
                return;
            }

            showMessage({ status: 'success', title: successTitle });
            await openViewBooking(currentBookingUuid);
            await loadBookings();
        }

        document.getElementById('vbConfirmBtn').addEventListener('click', () =>
            performAction(`/api/bookings/${currentBookingUuid}/confirm`, {}, 'Booking confirmed'));

        document.getElementById('vbMarkInTransitBtn').addEventListener('click', () =>
            performAction(`/api/bookings/${currentBookingUuid}/mark-in-transit`, {}, 'Marked In Transit'));

        document.getElementById('vbMarkDeliveredBtn').addEventListener('click', () =>
            performAction(`/api/bookings/${currentBookingUuid}/mark-delivered`, {}, 'Marked Delivered'));

        document.getElementById('vbMarkCompletedBtn').addEventListener('click', () =>
            performAction(`/api/bookings/${currentBookingUuid}/mark-completed`, {}, 'Booking closed out'));

        document.getElementById('vbCancelBtn').addEventListener('click', function() {
            document.getElementById('vbCancelReasonPanel').classList.remove('hidden');
            document.getElementById('vbCancelReasonInput').focus();
        });

        document.getElementById('vbCancelDismissBtn').addEventListener('click', function() {
            document.getElementById('vbCancelReasonPanel').classList.add('hidden');
            document.getElementById('vbCancelReasonInput').value = '';
        });

        document.getElementById('vbCancelConfirmBtn').addEventListener('click', function() {
            const reason = document.getElementById('vbCancelReasonInput').value.trim();

            if (!reason) {
                document.getElementById('vbCancelReasonInput').focus();
                return;
            }

            performAction(`/api/bookings/${currentBookingUuid}/cancel`, { reason }, 'Booking cancelled');
        });

        // -----------------------------------------------------------------
        // Init
        // -----------------------------------------------------------------
        function init() {
            table = renderTable();
            loadBookings();
        }

        init();
    })();
</script>
