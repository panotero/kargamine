<div class="container mx-auto px-4 py-6">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold">Bookings</h1>
            <p class="text-zinc-500">Client shipments — from Draft through Delivered.</p>
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
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Route</th>
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

        <div id="vbChargesContainer"
            class="grid grid-cols-2 gap-3 text-sm border-t border-zinc-100 dark:border-zinc-800 pt-3"></div>

        <div>
            <p class="font-semibold text-sm text-zinc-700 dark:text-zinc-200 mb-2">Dispatch (ATW / CAN)</p>
            <div class="space-y-2" id="vbDispatchBody"></div>
        </div>

        <div>
            <p class="font-semibold text-sm text-zinc-700 dark:text-zinc-200 mb-2">CV Assignment</p>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Container</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Proforma BL No.</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Waybill No.</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Seal No.</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800" id="vbCvAssignmentBody"></tbody>
                </table>
            </div>
        </div>

        <div>
            <p class="font-semibold text-sm text-zinc-700 dark:text-zinc-200 mb-2">EIR (Equipment Interchange Receipt)
            </p>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Container</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">EIR Out</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">EIR In</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800" id="vbEirBody"></tbody>
                </table>
            </div>
        </div>

        <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 space-y-3" id="vbActions">
            <p class="font-semibold text-xs text-zinc-500 uppercase">Actions</p>
            <div class="flex flex-wrap gap-2">
                <button type="button" id="vbConfirmBtn"
                    class="px-3 py-1.5 text-xs rounded-lg bg-blue-600 hover:bg-blue-700 text-white">Confirm
                    Booking</button>
                <button type="button" id="vbMarkInTransitBtn"
                    class="px-3 py-1.5 text-xs rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Mark In
                    Transit</button>
                <button type="button" id="vbMarkDeliveredBtn"
                    class="px-3 py-1.5 text-xs rounded-lg bg-teal-600 hover:bg-teal-700 text-white">Mark
                    Delivered</button>
                <button type="button" id="vbMarkCompletedBtn"
                    class="px-3 py-1.5 text-xs rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white">Close
                    Out</button>
                <button type="button" id="vbCancelBtn"
                    class="px-3 py-1.5 text-xs rounded-lg bg-red-600 hover:bg-red-700 text-white">Cancel
                    Booking</button>
                <a href="#" id="vbDownloadBolBtn" target="_blank"
                    class="hidden px-3 py-1.5 text-xs rounded-lg bg-zinc-700 hover:bg-zinc-800 text-white">Download
                    Bill of Lading</a>
            </div>

            <div id="vbCancelReasonPanel" class="hidden pt-2 border-t border-zinc-100 dark:border-zinc-800 space-y-2">
                <label class="block text-[11px] font-semibold text-zinc-500 uppercase">Cancellation Reason</label>
                <textarea id="vbCancelReasonInput" rows="2" maxlength="500"
                    class="w-full border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-xs bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100"
                    placeholder="Why is this booking being cancelled?"></textarea>
                <div class="flex gap-2">
                    <button type="button" id="vbCancelConfirmBtn"
                        class="px-3 py-1.5 text-xs rounded-lg bg-red-600 hover:bg-red-700 text-white">Confirm
                        Cancellation</button>
                    <button type="button" id="vbCancelDismissBtn"
                        class="px-3 py-1.5 text-xs rounded-lg border">Nevermind</button>
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

{{-- Generate ATW/CAN modal (SOP Step 3) --}}
<x-modal id="dispatchDocumentModal">
    <div class="p-5 border-b flex justify-between items-center">
        <p class="text-lg font-semibold">Generate <span id="ddType">ATW</span></p>
        <button class="modal-close">✕</button>
    </div>
    <div class="p-5 space-y-3 text-sm max-h-[70vh] overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Trip Type</label>
                <select id="ddTripType" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    <option value="">Select</option>
                    <option value="Tandem">Tandem</option>
                    <option value="Tandem Foul">Tandem Foul</option>
                    <option value="Single">Single</option>
                    <option value="Single Foul">Single Foul</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Trailer Capacity</label>
                <input type="text" id="ddTrailerCapacity" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Number of Convan/Flat Rack</label>
                <input type="number" min="0" id="ddConvanCount" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Convan/Flat Rack Size</label>
                <input type="text" id="ddConvanSize" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Authorized Trucker</label>
                <input type="text" id="ddTrucker" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Plate Number</label>
                <input type="text" id="ddPlateNumber" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Authorized Driver</label>
                <input type="text" id="ddDriver" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Helper</label>
                <input type="text" id="ddHelper" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div class="md:col-span-2">
                <label class="text-[11px] text-zinc-400 uppercase">Coordinator/Checker</label>
                <input type="text" id="ddCoordinator" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div class="md:col-span-2 flex gap-4">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" id="ddSinglePickup"> Single Pickup
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" id="ddAdvancePullOut"> Advance Pull Out
                </label>
            </div>
        </div>

        <div class="border-t border-zinc-100 dark:border-zinc-800 pt-3">
            <label class="text-[11px] text-zinc-400 uppercase block mb-1.5">Cargo CY Operations</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] text-zinc-400 uppercase">CY - Empty Pull Out</label>
                    <input type="datetime-local" id="ddCyEmptyPullOut" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                </div>
                <div>
                    <label class="text-[11px] text-zinc-400 uppercase">CY - Stuffing Activity</label>
                    <input type="datetime-local" id="ddCyStuffing" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                </div>
                <div>
                    <label class="text-[11px] text-zinc-400 uppercase">CY - Stripping Activity</label>
                    <input type="datetime-local" id="ddCyStripping" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                </div>
                <div>
                    <label class="text-[11px] text-zinc-400 uppercase">CY - Delivery of Cargo</label>
                    <input type="datetime-local" id="ddCyDelivery" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                </div>
                <div>
                    <label class="text-[11px] text-zinc-400 uppercase">TO - Est. Departure</label>
                    <input type="datetime-local" id="ddEstDeparture" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                </div>
                <div>
                    <label class="text-[11px] text-zinc-400 uppercase">TO - Est. Arrival</label>
                    <input type="datetime-local" id="ddEstArrival" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                </div>
            </div>
        </div>
    </div>
    <div class="border-t px-5 py-4 flex justify-end gap-2">
        <button class="modal-close border px-4 py-2 rounded-lg text-sm">Cancel</button>
        <button type="button" id="ddSaveBtn"
            class="px-4 py-2 rounded-lg text-sm bg-orange-500 hover:bg-orange-600 text-white">Generate</button>
    </div>
</x-modal>

{{-- Issue EIR Out/In modal (SOP Steps 5 & 9) --}}
<x-modal id="eirModal">
    <div class="p-5 border-b flex justify-between items-center">
        <p class="text-lg font-semibold">Issue EIR <span id="eirDirectionLabel">Out</span></p>
        <button class="modal-close">✕</button>
    </div>
    <div class="p-5 space-y-3 text-sm max-h-[70vh] overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Damage Codes</label>
                <input type="text" id="eirDamageCodes" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div class="hidden" id="eirConvanClassField">
                <label class="text-[11px] text-zinc-400 uppercase">ConVan Class</label>
                <select id="eirConvanClass" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    <option value="">Select Class</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="text-[11px] text-zinc-400 uppercase">Damage Remarks</label>
                <textarea id="eirDamageRemarks" rows="2" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900"></textarea>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Upload Convan Checklist</label>
                <input type="file" id="eirChecklistFile" accept=".pdf,.jpg,.jpeg,.png,.webp" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <p class="text-[11px] text-zinc-400 mt-1" id="eirChecklistStatus"></p>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Upload Damage Photos</label>
                <input type="file" id="eirPhotoFiles" accept=".jpg,.jpeg,.png,.webp" multiple class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <p class="text-[11px] text-zinc-400 mt-1" id="eirPhotosStatus"></p>
            </div>
            <div class="md:col-span-2" id="eirShipperRepField">
                <label class="text-[11px] text-zinc-400 uppercase">Shipper's Representative / Driver's Name</label>
                <input type="text" id="eirShipperRep" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div class="md:col-span-2" id="eirDriverIdField">
                <label class="text-[11px] text-zinc-400 uppercase">Shipper's Rep / Driver's ID Photo <span
                        id="eirDriverIdRequiredNote" class="hidden text-red-500">(required for this route)</span></label>
                <input type="file" id="eirDriverIdFile" accept=".jpg,.jpeg,.png,.webp" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <p class="text-[11px] text-zinc-400 mt-1" id="eirDriverIdStatus"></p>
            </div>
        </div>
    </div>
    <div class="border-t px-5 py-4 flex justify-end gap-2">
        <button class="modal-close border px-4 py-2 rounded-lg text-sm">Cancel</button>
        <button type="button" id="eirSaveBtn"
            class="px-4 py-2 rounded-lg text-sm bg-orange-500 hover:bg-orange-600 text-white">Issue</button>
    </div>
</x-modal>

<script>
    (function() {
        const STATUS_MAPPING = {
            1: {
                label: 'Draft',
                classes: 'bg-zinc-100 text-zinc-600'
            },
            2: {
                label: 'Confirmed',
                classes: 'bg-blue-50 text-blue-700'
            },
            3: {
                label: 'In Transit',
                classes: 'bg-indigo-50 text-indigo-700'
            },
            4: {
                label: 'Delivered',
                classes: 'bg-teal-50 text-teal-700'
            },
            5: {
                label: 'Completed',
                classes: 'bg-emerald-50 text-emerald-700'
            },
            6: {
                label: 'Cancelled',
                classes: 'bg-red-50 text-red-700'
            },
        };

        const INVOICE_STATUS_LABELS = {
            1: 'Draft',
            2: 'Sent',
            3: 'Paid',
            4: 'Void'
        };

        let currentBookingUuid = null;
        let table = null;

        function statusBadge(status) {
            const meta = STATUS_MAPPING[status] ?? {
                label: 'Unknown',
                classes: 'bg-zinc-100 text-zinc-500'
            };
            return `<span class="inline-flex items-center rounded-full ${meta.classes} px-2 py-0.5 text-xs font-medium">${meta.label}</span>`;
        }

        function money(v) {
            return Number(v ?? 0).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // -----------------------------------------------------------------
        // List
        // -----------------------------------------------------------------
        async function loadBookings() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/bookings'
            });
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

        // Route/delivery mode now live per cargo line, not on the booking
        // header - a booking can ship to more than one destination, so the
        // list shows the first line's route/mode and flags it if the rest
        // of the lines don't all agree.
        function routeSummary(r) {
            const lines = r.lines ?? [];
            if (!lines.length) return '-';
            const first = lines[0];
            const label = `${first.origin_port?.code ?? '-'} &rarr; ${first.destination_port?.code ?? '-'}`;
            const sameRoute = lines.every((l) => l.origin_port_id === first.origin_port_id && l.destination_port_id === first.destination_port_id);
            return sameRoute ? label : `${label} +${lines.length - 1} more`;
        }

        function deliveryTypeSummary(r) {
            const lines = r.lines ?? [];
            if (!lines.length) return '-';
            const first = lines[0];
            const sameType = lines.every((l) => l.delivery_type_id === first.delivery_type_id);
            return sameType ? (first.delivery_type?.name ?? '-') : 'Mixed';
        }

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
                    title: 'Delivery Type',
                    key: 'lines',
                    render: deliveryTypeSummary
                },
                {
                    title: 'Booking Date',
                    key: 'booking_date'
                },
                {
                    title: 'Status',
                    key: 'status',
                    render: (r) => statusBadge(r.status)
                },
                {
                    title: 'Grand Total',
                    key: 'grand_total_snapshot',
                    render: (r) => money(r.grand_total_snapshot)
                },
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
                    loadPage({
                        title: 'Edit Booking',
                        link: '/page_bookingForm'
                    });
                    return;
                }
                openViewBooking(data.uuid);
            });
        }

        document.querySelectorAll('.bookingStatusBtn').forEach((btn) => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.bookingStatusBtn').forEach((b) => b.classList.remove(
                    'ring-2', 'ring-blue-500'));
                this.classList.add('ring-2', 'ring-blue-500');
                table.setFilter('status', this.dataset.status);
            });
        });

        document.getElementById('btnNewBooking').addEventListener('click', function() {
            window.bookingFormUuid = null;
            loadPage({
                title: 'New Booking',
                link: '/page_bookingForm'
            });
        });

        // -----------------------------------------------------------------
        // View modal
        // -----------------------------------------------------------------
        async function openViewBooking(uuid) {
            const response = await apiCall({
                mode: 'GET',
                url: `/api/bookings/${uuid}`
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: 'Unable to load this booking.'
                });
                return;
            }

            currentBookingUuid = uuid;
            const booking = response.data;

            document.getElementById('vbCode').textContent = booking.code;
            document.getElementById('vbStatusBadge').innerHTML = statusBadge(booking.status);
            document.getElementById('vbClientName').textContent =
                `${booking.client?.company_name ?? '-'} (${booking.client?.customer_code ?? '-'})`;

            document.getElementById('vbInfoContainer').innerHTML = `
                <div><span class="text-zinc-400">Booking Date:</span> <div class="font-medium">${booking.booking_date ?? '-'}</div></div>
                <div><span class="text-zinc-400">Contract:</span> <div class="font-medium">${booking.client_contract?.code ?? 'Spot rate'}</div></div>
            `;

            // Route + delivery mode live per cargo line now - each row
            // ships to whatever origin/destination that line was booked for.
            const lineRows = (booking.lines ?? []).map((line) => {
                const variantLabel =
                    `${line.container?.name ?? '-'} / ${line.container_class?.class ?? '-'} / ${line.container_size?.size ?? '-'}`;
                const units = (line.container_units ?? []).map((u) => u.container_asset?.container_no ??
                    'Not yet assigned').join(', ');
                const discount = line.discount_type_snapshot ?
                    (line.discount_type_snapshot === 'percentage' ?
                        `${Number(line.discount_value_snapshot).toFixed(2)}%` : money(line
                            .discount_value_snapshot)) :
                    '-';
                const route = `${line.origin_port?.code ?? '-'} &rarr; ${line.destination_port?.code ?? '-'}`;

                return `
                    <tr>
                        <td class="px-3 py-2">
                            <div>${route}</div>
                            <div class="text-zinc-400">${line.delivery_type?.name ?? '-'}</div>
                        </td>
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
                '<tr><td colspan="7" class="px-3 py-4 text-center text-zinc-400">No cargo lines.</td></tr>';

            const portChargesTotal = (booking.port_charges ?? []).reduce((sum, c) => sum + Number(c
                .amount_snapshot ?? 0), 0);

            document.getElementById('vbChargesContainer').innerHTML = `
                <div><span class="text-zinc-400">Port Charges:</span> <div class="font-medium">${money(portChargesTotal)}</div></div>
                <div><span class="text-zinc-400">Trucking:</span> <div class="font-medium">${money(booking.trucking_snapshot)}</div></div>
                <div><span class="text-zinc-400">VAT:</span> <div class="font-medium">${money(booking.vat_amount_snapshot)}</div></div>
                <div><span class="text-zinc-400">Grand Total:</span> <div class="font-bold text-base">${money(booking.grand_total_snapshot)}</div></div>
            `;

            renderActions(booking);
            renderInvoice(booking);
            renderTimeline(booking.status_history ?? []);
            renderDispatch(booking);
            renderCvAssignment(booking);
            renderEir(booking);

            initModal({
                modalId: 'viewBookingModal'
            });
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

        // -----------------------------------------------------------------
        // Dispatch (ATW/CAN) + CV Assignment - SOP Steps 3-4
        // -----------------------------------------------------------------
        let dispatchLineTarget = null;

        // Mirrors BookingLine::needsAtw() - "door" on the origin leg (or the
        // client's always_route_atw override) means ATW, otherwise CAN.
        function lineNeedsAtw(line, booking) {
            return !!(line.delivery_type?.includes_origin_trucking) || !!(booking.client?.always_route_atw);
        }

        function renderDispatch(booking) {
            const lines = booking.lines ?? [];
            const body = document.getElementById('vbDispatchBody');

            if (!lines.length) {
                body.innerHTML = '<p class="text-zinc-400 text-xs">No cargo lines.</p>';
                return;
            }

            body.innerHTML = lines.map((line) => {
                const route = `${line.origin_port?.code ?? '-'} &rarr; ${line.destination_port?.code ?? '-'}`;
                const doc = line.dispatch_document;

                if (doc) {
                    const badgeClasses = doc.document_type === 'ATW' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700';

                    return `
                        <div class="border border-zinc-100 dark:border-zinc-800 rounded-lg px-3 py-2 flex items-center justify-between">
                            <div>
                                <span class="text-zinc-400 text-xs">${route}</span>
                                <span class="ml-2 inline-flex items-center rounded-full ${badgeClasses} px-2 py-0.5 text-xs font-medium">${doc.document_type} &middot; ${doc.document_number}</span>
                            </div>
                            <span class="text-zinc-400 text-xs">${doc.generated_at ?? ''}</span>
                        </div>
                    `;
                }

                const docType = lineNeedsAtw(line, booking) ? 'ATW' : 'CAN';

                return `
                    <div class="border border-zinc-100 dark:border-zinc-800 rounded-lg px-3 py-2 flex items-center justify-between">
                        <span class="text-zinc-400 text-xs">${route}</span>
                        <button type="button" class="dispatch-generate-btn px-3 py-1.5 text-xs rounded-lg bg-orange-500 hover:bg-orange-600 text-white"
                            data-line-id="${line.id}" data-doc-type="${docType}">Generate ${docType}</button>
                    </div>
                `;
            }).join('');

            body.querySelectorAll('.dispatch-generate-btn').forEach((btn) => {
                btn.addEventListener('click', function() {
                    openDispatchModal(this.dataset.lineId, this.dataset.docType);
                });
            });
        }

        function openDispatchModal(lineId, docType) {
            dispatchLineTarget = lineId;
            document.getElementById('ddType').textContent = docType;

            [
                'ddTripType', 'ddTrailerCapacity', 'ddConvanCount', 'ddConvanSize', 'ddTrucker',
                'ddPlateNumber', 'ddDriver', 'ddHelper', 'ddCoordinator', 'ddEstDeparture', 'ddEstArrival',
                'ddCyEmptyPullOut', 'ddCyStuffing', 'ddCyStripping', 'ddCyDelivery',
            ].forEach((id) => document.getElementById(id).value = '');
            document.getElementById('ddSinglePickup').checked = false;
            document.getElementById('ddAdvancePullOut').checked = false;

            initModal({ modalId: 'dispatchDocumentModal' });
        }

        document.getElementById('ddSaveBtn').addEventListener('click', async function() {
            const payload = {
                trip_type: document.getElementById('ddTripType').value || null,
                trailer_capacity: document.getElementById('ddTrailerCapacity').value || null,
                convan_count: document.getElementById('ddConvanCount').value || null,
                convan_size: document.getElementById('ddConvanSize').value || null,
                authorized_trucker: document.getElementById('ddTrucker').value || null,
                plate_number: document.getElementById('ddPlateNumber').value || null,
                authorized_driver: document.getElementById('ddDriver').value || null,
                helper: document.getElementById('ddHelper').value || null,
                coordinator_checker: document.getElementById('ddCoordinator').value || null,
                is_single_pickup: document.getElementById('ddSinglePickup').checked,
                is_advance_pull_out: document.getElementById('ddAdvancePullOut').checked,
                cy_empty_pull_out_at: document.getElementById('ddCyEmptyPullOut').value || null,
                cy_stuffing_activity_at: document.getElementById('ddCyStuffing').value || null,
                cy_stripping_activity_at: document.getElementById('ddCyStripping').value || null,
                cy_delivery_of_cargo_at: document.getElementById('ddCyDelivery').value || null,
                estimated_departure_at: document.getElementById('ddEstDeparture').value || null,
                estimated_arrival_at: document.getElementById('ddEstArrival').value || null,
            };

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload,
                url: `/api/booking-lines/${dispatchLineTarget}/dispatch-document`,
                button: this,
            });

            if (!response.success) {
                showMessage({ status: 'error', title: 'Unable to generate document', message: response.message ?? '' });
                return;
            }

            showMessage({ status: 'success', title: 'Document generated' });
            document.querySelector('#dispatchDocumentModal .modal-close').click();
            await openViewBooking(currentBookingUuid);
        });

        function renderCvAssignment(booking) {
            const units = (booking.lines ?? []).flatMap((line) => line.container_units ?? []);
            const body = document.getElementById('vbCvAssignmentBody');

            if (!units.length) {
                body.innerHTML = '<tr><td colspan="5" class="px-3 py-4 text-center text-zinc-400">No containers assigned yet.</td></tr>';
                return;
            }

            body.innerHTML = units.map((unit) => `
                <tr data-unit-id="${unit.id}">
                    <td class="px-3 py-2">${unit.container_asset?.container_no ?? '-'}</td>
                    <td class="px-3 py-2">
                        <input type="text" class="cv-proforma-bl w-full border rounded px-1.5 py-1 text-xs dark:text-zinc-900" value="${unit.proforma_bl_number ?? ''}">
                    </td>
                    <td class="px-3 py-2">
                        <input type="text" class="cv-waybill w-full border rounded px-1.5 py-1 text-xs dark:text-zinc-900" value="${unit.waybill_number ?? ''}">
                    </td>
                    <td class="px-3 py-2">
                        <input type="text" class="cv-seal w-full border rounded px-1.5 py-1 text-xs dark:text-zinc-900" value="${unit.seal_no ?? ''}">
                    </td>
                    <td class="px-3 py-2">
                        <button type="button" class="cv-save-btn px-2 py-1 text-xs rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Save</button>
                    </td>
                </tr>
            `).join('');

            body.querySelectorAll('.cv-save-btn').forEach((btn) => {
                btn.addEventListener('click', async function() {
                    const row = this.closest('tr');
                    const unitId = row.dataset.unitId;

                    const response = await apiCall({
                        mode: 'PUT',
                        isJson: true,
                        payload: {
                            proforma_bl_number: row.querySelector('.cv-proforma-bl').value || null,
                            waybill_number: row.querySelector('.cv-waybill').value || null,
                            seal_no: row.querySelector('.cv-seal').value || null,
                        },
                        url: `/api/booking-container-units/${unitId}/cv-assignment`,
                        button: this,
                    });

                    if (!response.success) {
                        showMessage({ status: 'error', title: 'Unable to save CV assignment', message: response.message ?? '' });
                        return;
                    }

                    showMessage({ status: 'success', title: 'CV assignment saved' });
                });
            });
        }

        // -----------------------------------------------------------------
        // EIR Out/In - SOP Steps 5 & 9
        // -----------------------------------------------------------------
        let eirUnitTarget = null;
        let eirDirectionTarget = null;
        let eirChecklistPath = null;
        let eirPhotoPaths = [];
        let eirDriverIdPath = null;
        let containerClassesLoaded = false;

        function renderEir(booking) {
            const body = document.getElementById('vbEirBody');
            const rows = [];

            (booking.lines ?? []).forEach((line) => {
                // SOP: driver/shipper-rep ID photo required for Pier-origin,
                // non-Trigo lines - the same condition that routes a line to CAN.
                const driverIdRequired = !lineNeedsAtw(line, booking);

                (line.container_units ?? []).forEach((unit) => {
                    const outCell = unit.eir_out ?
                        `<span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5 text-xs font-medium">Issued ${unit.eir_out.issued_at ?? ''}</span>` :
                        `<button type="button" class="eir-issue-btn px-2 py-1 text-xs rounded-lg bg-orange-500 hover:bg-orange-600 text-white" data-unit-id="${unit.id}" data-direction="OUT" data-driver-id-required="${driverIdRequired ? '1' : '0'}">Issue EIR Out</button>`;

                    let inCell;
                    if (unit.eir_in) {
                        inCell = `<span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5 text-xs font-medium">Issued ${unit.eir_in.issued_at ?? ''}</span>`;
                    } else if (!unit.actual_gate_in_at) {
                        inCell = '<span class="text-zinc-400">Not gated in yet</span>';
                    } else {
                        inCell = `<button type="button" class="eir-issue-btn px-2 py-1 text-xs rounded-lg bg-blue-600 hover:bg-blue-700 text-white" data-unit-id="${unit.id}" data-direction="IN" data-driver-id-required="0">Issue EIR In</button>`;
                    }

                    rows.push(`
                        <tr>
                            <td class="px-3 py-2">${unit.container_asset?.container_no ?? '-'}</td>
                            <td class="px-3 py-2">${outCell}</td>
                            <td class="px-3 py-2">${inCell}</td>
                        </tr>
                    `);
                });
            });

            body.innerHTML = rows.join('') ||
                '<tr><td colspan="3" class="px-3 py-4 text-center text-zinc-400">No containers assigned yet.</td></tr>';

            body.querySelectorAll('.eir-issue-btn').forEach((btn) => {
                btn.addEventListener('click', function() {
                    openEirModal(this.dataset.unitId, this.dataset.direction, this.dataset.driverIdRequired === '1');
                });
            });
        }

        async function loadContainerClassesIntoSelect() {
            if (containerClassesLoaded) return;

            const response = await apiCall({ mode: 'GET', url: '/api/containerClasses?per_page=200' });
            if (!response.success) return;

            const select = document.getElementById('eirConvanClass');
            select.innerHTML = '<option value="">Select Class</option>' +
                (response.data.data ?? []).map(c => `<option value="${c.id}">${c.class}</option>`).join('');
            containerClassesLoaded = true;
        }

        async function openEirModal(unitId, direction, driverIdRequired) {
            eirUnitTarget = unitId;
            eirDirectionTarget = direction;
            eirChecklistPath = null;
            eirPhotoPaths = [];
            eirDriverIdPath = null;

            document.getElementById('eirDirectionLabel').textContent = direction === 'OUT' ? 'Out' : 'In';
            document.getElementById('eirDamageCodes').value = '';
            document.getElementById('eirDamageRemarks').value = '';
            document.getElementById('eirChecklistFile').value = '';
            document.getElementById('eirChecklistStatus').textContent = '';
            document.getElementById('eirPhotoFiles').value = '';
            document.getElementById('eirPhotosStatus').textContent = '';
            document.getElementById('eirShipperRep').value = '';
            document.getElementById('eirDriverIdFile').value = '';
            document.getElementById('eirDriverIdStatus').textContent = '';

            const isOut = direction === 'OUT';
            document.getElementById('eirShipperRepField').classList.toggle('hidden', !isOut);
            document.getElementById('eirDriverIdField').classList.toggle('hidden', !isOut);
            document.getElementById('eirConvanClassField').classList.toggle('hidden', isOut);
            document.getElementById('eirDriverIdRequiredNote').classList.toggle('hidden', !(isOut && driverIdRequired));

            if (!isOut) await loadContainerClassesIntoSelect();

            initModal({ modalId: 'eirModal' });
        }

        async function uploadEirFile(file) {
            const formData = new FormData();
            formData.append('file', file);

            const response = await apiCall({
                mode: 'POST',
                isJson: false,
                payload: formData,
                url: '/api/booking-container-units/eir-upload',
            });

            return response.success ? response.data.path : null;
        }

        document.getElementById('eirChecklistFile').addEventListener('change', async function() {
            const file = this.files[0];
            if (!file) return;
            document.getElementById('eirChecklistStatus').textContent = 'Uploading...';
            eirChecklistPath = await uploadEirFile(file);
            document.getElementById('eirChecklistStatus').textContent = eirChecklistPath ?
                `Uploaded: ${file.name}` : 'Upload failed.';
        });

        document.getElementById('eirPhotoFiles').addEventListener('change', async function() {
            const files = Array.from(this.files);
            if (!files.length) return;
            document.getElementById('eirPhotosStatus').textContent = 'Uploading...';
            const paths = await Promise.all(files.map(uploadEirFile));
            eirPhotoPaths = paths.filter(Boolean);
            document.getElementById('eirPhotosStatus').textContent = `Uploaded ${eirPhotoPaths.length} photo(s).`;
        });

        document.getElementById('eirDriverIdFile').addEventListener('change', async function() {
            const file = this.files[0];
            if (!file) return;
            document.getElementById('eirDriverIdStatus').textContent = 'Uploading...';
            eirDriverIdPath = await uploadEirFile(file);
            document.getElementById('eirDriverIdStatus').textContent = eirDriverIdPath ?
                `Uploaded: ${file.name}` : 'Upload failed.';
        });

        document.getElementById('eirSaveBtn').addEventListener('click', async function() {
            const isOut = eirDirectionTarget === 'OUT';

            const payload = {
                damage_codes: document.getElementById('eirDamageCodes').value || null,
                damage_remarks: document.getElementById('eirDamageRemarks').value || null,
                convan_checklist_path: eirChecklistPath,
                damage_photo_paths: eirPhotoPaths,
            };

            if (isOut) {
                payload.shipper_representative_name = document.getElementById('eirShipperRep').value || null;
                payload.driver_id_photo_path = eirDriverIdPath;
            } else {
                payload.convan_class_id = document.getElementById('eirConvanClass').value || null;
            }

            const url = `/api/booking-container-units/${eirUnitTarget}/eir-${isOut ? 'out' : 'in'}`;

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload,
                url,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: `Unable to issue EIR ${isOut ? 'Out' : 'In'}`,
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({ status: 'success', title: `EIR ${isOut ? 'Out' : 'In'} issued` });
            document.querySelector('#eirModal .modal-close').click();
            await openViewBooking(currentBookingUuid);
        });

        async function performAction(url, payload, successTitle) {
            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: payload ?? {},
                url
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to complete action',
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({
                status: 'success',
                title: successTitle
            });
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

            performAction(`/api/bookings/${currentBookingUuid}/cancel`, {
                reason
            }, 'Booking cancelled');
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
