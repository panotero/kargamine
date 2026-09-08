<div class="container mx-auto px-4 py-6">

    <div class="flex items-start justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold">Bookings</h1>
            <p class="text-zinc-500">Client shipments — from Draft through Delivered.</p>
        </div>
        <div class="flex items-center gap-5">
            {{-- Header stat: Drafts not yet moved forward - the bookings that
                 still need someone to finish and confirm them. Derived purely
                 from the already-loaded status_counts, no extra API call. --}}
            <div id="draftStatWrap" class="hidden text-right">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Drafts to
                    complete</p>
                <p class="text-xl font-bold text-amber-600 dark:text-amber-400" id="countDraftStat">0</p>
            </div>
            <button type="button" id="btnNewBooking"
                class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">
                + New Booking
            </button>
        </div>
    </div>

    {{-- Lifecycle progression strip. Draft -> Confirmed -> In Transit ->
         Delivered -> Completed is a genuine linear sequence; Cancelled is a
         terminal off-ramp reachable from Draft or Confirmed, so it sits apart
         after a divider. "All" is a reset action, not a workflow state, so it
         gets the neutral dashed treatment. Border/text hues match this file's
         STATUS_MAPPING badge colors so the strip and the table badges read as
         one color system. --}}
    <section class="w-full my-5">
        <div class="flex flex-wrap items-center gap-2">
            <button type="button"
                class="bookingStatusBtn inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-dashed border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-zinc-500 dark:text-zinc-400 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-800"
                data-status="">
                <span class="font-medium">All</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 text-xs font-semibold rounded-full bg-zinc-100 dark:bg-zinc-800"
                    id="countAll">0</span>
            </button>

            <span class="mx-1 h-6 w-px bg-zinc-200 dark:bg-zinc-700"></span>

            <button type="button"
                class="bookingStatusBtn inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-800"
                data-status="1">
                <span class="font-medium">Draft</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 text-xs font-semibold rounded-full bg-zinc-100 dark:bg-zinc-800"
                    id="countDraft">0</span>
            </button>
            <span class="text-zinc-300 dark:text-zinc-600 select-none">&rarr;</span>
            <button type="button"
                class="bookingStatusBtn inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-blue-300 dark:border-blue-700 bg-white dark:bg-zinc-900 text-blue-700 dark:text-blue-300 text-sm hover:bg-blue-50 dark:hover:bg-blue-950/30"
                data-status="2">
                <span class="font-medium">Confirmed</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/40"
                    id="countConfirmed">0</span>
            </button>
            <span class="text-zinc-300 dark:text-zinc-600 select-none">&rarr;</span>
            <button type="button"
                class="bookingStatusBtn inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-indigo-300 dark:border-indigo-700 bg-white dark:bg-zinc-900 text-indigo-700 dark:text-indigo-300 text-sm hover:bg-indigo-50 dark:hover:bg-indigo-950/30"
                data-status="3">
                <span class="font-medium">In Transit</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 text-xs font-semibold rounded-full bg-indigo-100 dark:bg-indigo-900/40"
                    id="countInTransit">0</span>
            </button>
            <span class="text-zinc-300 dark:text-zinc-600 select-none">&rarr;</span>
            <button type="button"
                class="bookingStatusBtn inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-teal-300 dark:border-teal-700 bg-white dark:bg-zinc-900 text-teal-700 dark:text-teal-300 text-sm hover:bg-teal-50 dark:hover:bg-teal-950/30"
                data-status="4">
                <span class="font-medium">Delivered</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 text-xs font-semibold rounded-full bg-teal-100 dark:bg-teal-900/40"
                    id="countDelivered">0</span>
            </button>
            <span class="text-zinc-300 dark:text-zinc-600 select-none">&rarr;</span>
            <button type="button"
                class="bookingStatusBtn inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-emerald-300 dark:border-emerald-700 bg-white dark:bg-zinc-900 text-emerald-700 dark:text-emerald-300 text-sm hover:bg-emerald-50 dark:hover:bg-emerald-950/30"
                data-status="5">
                <span class="font-medium">Completed</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 text-xs font-semibold rounded-full bg-emerald-100 dark:bg-emerald-900/40"
                    id="countCompleted">0</span>
            </button>

            <span class="mx-1 h-6 w-px bg-zinc-200 dark:bg-zinc-700"></span>

            <button type="button"
                class="bookingStatusBtn inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-dashed border-red-300 dark:border-red-700 bg-white dark:bg-zinc-900 text-red-700 dark:text-red-300 text-sm hover:bg-red-50 dark:hover:bg-red-950/30"
                data-status="6">
                <span class="font-medium">Cancelled</span>
                <span
                    class="inline-flex items-center justify-center min-w-[1.25rem] px-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/40"
                    id="countCancelled">0</span>
            </button>
        </div>
    </section>

    <x-table id="tableBookings" />
</div>

{{-- View Booking modal - rail (reference facts) + tabbed working pane,
     same pattern as the CRM Lead Info modal. --}}
<x-modal id="viewBookingModal" max-width="lg:max-w-[1100px]">

    {{-- Header --}}
    <div class="p-5 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center">
        <div>
            <div class="flex items-center gap-2">
                <p class="text-lg font-semibold" id="vbCode">-</p>
                <span id="vbStatusBadge"></span>
            </div>
            <p class="text-xs text-zinc-400" id="vbClientName">-</p>
        </div>
        <button class="modal-close text-zinc-400 hover:text-zinc-600">✕</button>
    </div>

    {{-- Plain-language "what's next" action strip. Hidden unless this
         booking currently has a concrete blocking step waiting on the
         viewer (dispatch doc, CV paperwork, EIR in/out). --}}
    <div id="vbActionStrip"
        class="hidden mx-5 mt-4 flex items-center justify-between gap-3 rounded-xl border border-amber-300 dark:border-amber-700 bg-amber-50 dark:bg-amber-950/20 px-4 py-3">
        <div class="flex items-start gap-2">
            <span class="text-amber-600 dark:text-amber-400 shrink-0">⚠</span>
            <p class="text-sm text-amber-800 dark:text-amber-200" id="vbActionStripText"></p>
        </div>
        <button type="button" id="vbActionStripBtn"
            class="hidden shrink-0 px-3 py-1.5 text-xs rounded-lg bg-amber-500 hover:bg-amber-600 text-white"></button>
    </div>

    <div class="flex gap-5 p-5 max-h-[70vh]">

        {{-- ============== RAIL ============== --}}
        <div class="w-[17rem] shrink-0 overflow-y-auto flex flex-col gap-4 pr-1">
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-3">
                    Reference</p>
                <div class="flex flex-col gap-3 text-sm">
                    <div>
                        <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            Client</p>
                        <p class="font-medium text-zinc-800 dark:text-zinc-200" id="vbRailClient">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            Contract</p>
                        <p class="font-medium text-zinc-800 dark:text-zinc-200" id="vbRailContract">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            Booking Date</p>
                        <p class="font-medium text-zinc-800 dark:text-zinc-200" id="vbRailBookingDate">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            Grand Total</p>
                        <p class="font-bold text-base text-zinc-900 dark:text-zinc-100" id="vbRailGrandTotal">-</p>
                    </div>
                </div>
            </div>

            {{-- Invoice - small always-visible reference line, hidden when
                 the booking has no invoice yet. --}}
            <div id="vbInvoiceSection"
                class="hidden bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-2">
                    Invoice</p>
                <div id="vbInvoiceBody" class="text-sm"></div>
            </div>
        </div>

        {{-- ============== RIGHT PANE ============== --}}
        <div class="flex-1 min-w-0 flex flex-col">

            {{-- Tab bar --}}
            <div class="flex border-b border-zinc-200 dark:border-zinc-700 mb-4 shrink-0">
                <button type="button" id="vbTabCargo"
                    class="vb-tab-btn px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition border-orange-500 text-orange-600">
                    Cargo &amp; Details
                </button>
                <button type="button" id="vbTabDispatch"
                    class="vb-tab-btn px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">
                    Dispatch
                </button>
                <button type="button" id="vbTabContainers"
                    class="vb-tab-btn px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">
                    Containers &amp; EIR
                </button>
                <button type="button" id="vbTabTimeline"
                    class="vb-tab-btn px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">
                    Timeline
                </button>
            </div>

            {{-- One shared scroll region --}}
            <div class="flex-1 overflow-y-auto pr-0.5 text-sm text-zinc-700 dark:text-zinc-300">

                {{-- TAB: Cargo & Details --}}
                <div id="vbPaneCargo" class="vb-tabcontent space-y-5">
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

                    {{-- Per-line cargo + transaction details (consignee, cargo type,
                         declared value, delivery dates, ...) - kept editable regardless
                         of booking status since none of it affects locked-in pricing,
                         unlike the rest of the Booking form which is Draft-only. --}}
                    <div>
                        <p class="font-semibold text-sm text-zinc-700 dark:text-zinc-200 mb-2">Cargo &amp; Transaction
                            Details</p>
                        <div class="space-y-3" id="vbLineDetailsContainer"></div>
                    </div>

                    <div id="vbChargesContainer"
                        class="grid grid-cols-2 gap-3 text-sm border-t border-zinc-100 dark:border-zinc-800 pt-3"></div>
                </div>

                {{-- TAB: Dispatch --}}
                <div id="vbPaneDispatch" class="vb-tabcontent hidden space-y-4">
                    <div class="text-xs text-zinc-500 dark:text-zinc-400 space-y-1">
                        <p><span class="font-semibold text-zinc-700 dark:text-zinc-200">Trucking Authorization
                                (ATW)</span> — lets the truck enter the yard to pick up the container.</p>
                        <p><span class="font-semibold text-zinc-700 dark:text-zinc-200">Return Authorization
                                (CAN)</span> — lets the empty container be returned after delivery.</p>
                    </div>
                    <div class="space-y-2" id="vbDispatchBody"></div>
                </div>

                {{-- TAB: Containers & EIR (CV assignment + condition records) --}}
                <div id="vbPaneContainers" class="vb-tabcontent hidden space-y-5">
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
                                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800" id="vbCvAssignmentBody">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <p class="font-semibold text-sm text-zinc-700 dark:text-zinc-200">Condition Records (EIR)</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-2">A photo/condition record made each time
                            a container leaves (Check-Out) or returns to (Check-In) the yard.</p>
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs">
                                <thead class="bg-zinc-50 dark:bg-zinc-800">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-zinc-500 uppercase">Container</th>
                                        <th class="px-3 py-2 text-left text-zinc-500 uppercase">Condition Check-Out
                                            (EIR)</th>
                                        <th class="px-3 py-2 text-left text-zinc-500 uppercase">Condition Check-In
                                            (EIR)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800" id="vbEirBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- TAB: Timeline --}}
                <div id="vbPaneTimeline" class="vb-tabcontent hidden">
                    <p class="font-semibold text-xs text-zinc-500 uppercase mb-2">Status Timeline</p>
                    <div id="vbTimeline" class="space-y-2 text-xs"></div>
                </div>

            </div>
        </div>
    </div>

    {{-- Persistent footer: booking-level actions (visible on every tab). --}}
    <div class="border-t border-zinc-200 dark:border-zinc-700 px-5 py-4" id="vbActions">
        <div id="vbCancelReasonPanel" class="hidden mb-3 pb-3 border-b border-zinc-100 dark:border-zinc-800 space-y-2">
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

        <div class="flex flex-wrap items-center justify-between gap-2">
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
            <button class="modal-close border px-4 py-2 rounded-lg text-sm">Close</button>
        </div>
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
        // Mirrors the active status pill's data-status so the empty-state
        // renderer (called with no args by createRemoteTable) can tell a
        // true-empty list from a filtered-to-zero result.
        let currentStatusFilter = '';

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

            // Header stat: drafts not yet moved forward. Shown only when there
            // are any, so it reads as a real "needs attention" nudge.
            const draftCount = counts.draft ?? 0;
            document.getElementById('countDraftStat').textContent = draftCount;
            document.getElementById('draftStatWrap').classList.toggle('hidden', draftCount === 0);
        }

        // Route/delivery mode now live per cargo line, not on the booking
        // header - a booking can ship to more than one destination, so the
        // list shows the first line's route/mode and flags it if the rest
        // of the lines don't all agree.
        function routeSummary(r) {
            const lines = r.lines ?? [];
            if (!lines.length) return '-';
            const first = lines[0];
            const label = `${first.origin_port ? (first.origin_port.location?.name ?? '-') + ' - ' + first.origin_port.name : '-'} &rarr; ${first.destination_port ? (first.destination_port.location?.name ?? '-') + ' - ' + first.destination_port.name : '-'}`;
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
                {
                    // Row-click intent cue: Draft rows open the edit form,
                    // every other status opens the read-only view modal - two
                    // visually identical rows otherwise did different things
                    // on click with no visible hint.
                    title: '',
                    key: 'status',
                    render: (r) => Number(r.status) === 1 ?
                        '<span class="text-xs font-medium text-orange-600">Edit &rarr;</span>' :
                        '<span class="text-xs text-zinc-400">View &rarr;</span>'
                },
            ];

            return renderRemoteTable({
                url: '/api/bookings',
                tableId: 'tableBookings',
                afterRenderFunction: handleRowClick,
                emptyMessage: bookingsEmptyMessage,
                thead,
            });
        }

        // Two empty-state treatments (VISUALS.md). An unfiltered empty list is
        // a real actionable gap - a prompting state with a CTA. A filtered
        // result with zero matches is just "nothing in this bucket" - quiet.
        function bookingsEmptyMessage() {
            if (!currentStatusFilter) {
                return `
                    <div class="border-2 border-dashed border-orange-300 dark:border-orange-800 bg-orange-50 dark:bg-orange-950/20 rounded-xl p-6 flex flex-col items-center text-center gap-2">
                        <span class="text-[11px] font-semibold uppercase tracking-widest text-orange-600 dark:text-orange-400">⚠ Nothing here yet</span>
                        <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">No bookings yet. Create your first one to get started.</p>
                        <button type="button" class="js-empty-new-booking mt-1 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">+ New Booking</button>
                    </div>`;
            }

            const label = (STATUS_MAPPING[currentStatusFilter]?.label ?? '').toLowerCase();
            return `<span class="text-sm text-zinc-400 dark:text-zinc-500">No ${label} bookings.</span>`;
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
                    'ring-2', 'ring-orange-500'));
                this.classList.add('ring-2', 'ring-orange-500');
                currentStatusFilter = this.dataset.status;
                table.setFilter('status', this.dataset.status);
            });
        });

        function startNewBooking() {
            window.bookingFormUuid = null;
            loadPage({
                title: 'New Booking',
                link: '/page_bookingForm'
            });
        }

        document.getElementById('btnNewBooking').addEventListener('click', startNewBooking);

        // Delegated so the prompting empty-state's CTA works even though it's
        // rendered inside the table body (not through afterRender). Scoped to
        // the table element - part of the swapped SPA fragment - so nothing
        // leaks across page loads.
        document.getElementById('tableBookings').addEventListener('click', function(e) {
            if (e.target.closest('.js-empty-new-booking')) startNewBooking();
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

            // Rail reference facts (don't change per tab).
            document.getElementById('vbRailClient').textContent =
                `${booking.client?.company_name ?? '-'} (${booking.client?.customer_code ?? '-'})`;
            document.getElementById('vbRailContract').textContent = booking.client_contract?.code ?? 'Spot rate';
            document.getElementById('vbRailBookingDate').textContent = booking.booking_date ?? '-';
            document.getElementById('vbRailGrandTotal').textContent = money(booking.grand_total_snapshot);

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
                const route = `${line.origin_port ? (line.origin_port.location?.name ?? '-') + ' - ' + line.origin_port.name : '-'} &rarr; ${line.destination_port ? (line.destination_port.location?.name ?? '-') + ' - ' + line.destination_port.name : '-'}`;

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

            renderLineDetails(booking);

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
            renderActionStrip(booking);

            // Always land on the most-referenced tab when (re)opening.
            vbSetActiveTab('Cargo');

            initModal({
                modalId: 'viewBookingModal'
            });
        }

        // -----------------------------------------------------------------
        // Tabs
        // -----------------------------------------------------------------
        const VB_TABS = ['Cargo', 'Dispatch', 'Containers', 'Timeline'];

        function vbSetActiveTab(tab) {
            VB_TABS.forEach((t) => {
                const btn = document.getElementById(`vbTab${t}`);
                const pane = document.getElementById(`vbPane${t}`);
                if (!btn || !pane) return;
                const active = t === tab;
                btn.classList.toggle('border-orange-500', active);
                btn.classList.toggle('text-orange-600', active);
                btn.classList.toggle('border-transparent', !active);
                btn.classList.toggle('text-zinc-500', !active);
                pane.classList.toggle('hidden', !active);
            });
        }

        VB_TABS.forEach((t) => {
            document.getElementById(`vbTab${t}`)?.addEventListener('click', () => vbSetActiveTab(t));
        });

        // -----------------------------------------------------------------
        // Plain-language "what's next" action strip. Derived entirely from the
        // already-loaded booking JSON by replicating the SOP scope logic
        // (forAtw/forCvAssignment/forDocumentation/... on Booking) client-side
        // against the dispatch_document / container_units / eir flags the show
        // endpoint already returns - no new backend field or API call. Only
        // Live bookings (Confirmed/In Transit/Delivered) reach this modal with
        // a blocking step; when nothing is blocking the strip stays hidden.
        // -----------------------------------------------------------------
        function computeNextAction(booking) {
            const status = Number(booking.status);
            // Completed/Cancelled - nothing to prompt. (Draft never reaches
            // this modal; Draft rows open the edit form instead.)
            if (status !== 2 && status !== 3 && status !== 4) return null;

            const lines = booking.lines ?? [];

            // Step 3: lines still needing their dispatch document (ATW/CAN).
            const linesNeedingDispatch = lines.filter((l) => !l.dispatch_document);
            if (linesNeedingDispatch.length) {
                const n = linesNeedingDispatch.length;
                return {
                    html: `Waiting on you: generate the trucking/return authorization (ATW / CAN) for <b>${n}</b> cargo line${n > 1 ? 's' : ''} so the containers can leave the yard.`,
                    button: { label: 'Go to Dispatch', tab: 'Dispatch' },
                };
            }

            // Every remaining step is per container unit whose line is dispatched.
            const dispatchedUnits = lines
                .filter((l) => l.dispatch_document)
                .flatMap((l) => l.container_units ?? []);

            // Step 4: units still missing their CV paperwork.
            const unitsNeedingCv = dispatchedUnits.filter((u) => !u.proforma_bl_number || !u.waybill_number);
            if (unitsNeedingCv.length) {
                const n = unitsNeedingCv.length;
                return {
                    html: `Fill in the shipping paperwork (Proforma BL / Waybill) for <b>${n}</b> container${n > 1 ? 's' : ''} before they can be checked out.`,
                    button: { label: 'Go to Containers & EIR', tab: 'Containers' },
                };
            }

            // Steps 5-6: units needing their condition check-out (EIR Out).
            const unitsNeedingEirOut = dispatchedUnits.filter((u) => !u.eir_out);
            if (unitsNeedingEirOut.length) {
                const n = unitsNeedingEirOut.length;
                return {
                    html: `Do the condition check-out (EIR) for <b>${n}</b> container${n > 1 ? 's' : ''} before they leave the yard.`,
                    button: { label: 'Go to Containers & EIR', tab: 'Containers' },
                };
            }

            // Step 9: units physically back in the yard but missing EIR In.
            const unitsNeedingEirIn = dispatchedUnits.filter((u) => u.actual_gate_in_at && !u.eir_in);
            if (unitsNeedingEirIn.length) {
                const n = unitsNeedingEirIn.length;
                return {
                    html: `Do the condition check-in (EIR) for <b>${n}</b> returned container${n > 1 ? 's' : ''}.`,
                    button: { label: 'Go to Containers & EIR', tab: 'Containers' },
                };
            }

            return null;
        }

        function renderActionStrip(booking) {
            const strip = document.getElementById('vbActionStrip');
            const next = computeNextAction(booking);

            if (!next) {
                strip.classList.add('hidden');
                return;
            }

            document.getElementById('vbActionStripText').innerHTML = next.html;

            const btn = document.getElementById('vbActionStripBtn');
            if (next.button) {
                btn.textContent = next.button.label;
                btn.classList.remove('hidden');
                // Assigned (not addEventListener) so re-rendering never stacks handlers.
                btn.onclick = () => vbSetActiveTab(next.button.tab);
            } else {
                btn.classList.add('hidden');
                btn.onclick = null;
            }

            strip.classList.remove('hidden');
        }

        // -----------------------------------------------------------------
        // Per-line cargo/transaction details - editable regardless of
        // booking status (see updateLineDetails() on the backend), since
        // this is the only path to move a line from Tentative to Live on
        // the Cargo Build-Up board once the rest of the form is locked.
        // -----------------------------------------------------------------
        const LINE_DETAIL_FIELDS = [
            { field: 'consignee_name', label: 'Consignee Name', type: 'text' },
            { field: 'consignee_contact_person', label: 'Consignee Contact Person', type: 'text' },
            { field: 'consignee_contact_number', label: 'Consignee Contact Number', type: 'text' },
            { field: 'consignee_address', label: 'Consignee Address', type: 'text' },
            { field: 'cargo_type', label: 'Cargo Type / Content', type: 'text' },
            { field: 'declared_value', label: 'Declared Value', type: 'number' },
            { field: 'description', label: 'Description', type: 'text' },
            { field: 'weight_kg', label: 'Weight (kg)', type: 'number' },
            { field: 'volume_cbm', label: 'Volume (m&sup3;)', type: 'number' },
            { field: 'delivery_date', label: 'Delivery Date', type: 'date' },
            { field: 'first_delivery_date', label: 'First Delivery Date', type: 'date' },
            { field: 'last_delivery_date', label: 'Last Delivery Date', type: 'date' },
            { field: 'delivery_date_notes', label: 'Notes for Delivery Date', type: 'text' },
            { field: 'other_cargo_details', label: 'Other Cargo Details', type: 'text' },
        ];

        function lineHasTransactionDetails(line) {
            return !!(line.consignee_name && line.cargo_type && line.declared_value !== null && line
                .delivery_date);
        }

        function lineDetailsBadge(line) {
            return lineHasTransactionDetails(line) ?
                '<span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5 text-xs font-medium">Live</span>' :
                '<span class="inline-flex items-center rounded-full bg-amber-50 text-amber-700 px-2 py-0.5 text-xs font-medium">Tentative</span>';
        }

        function renderLineDetails(booking) {
            const container = document.getElementById('vbLineDetailsContainer');
            const lines = booking.lines ?? [];

            if (!lines.length) {
                container.innerHTML = '<p class="text-zinc-400 text-xs italic">No cargo lines.</p>';
                return;
            }

            container.innerHTML = lines.map((line) => {
                const variantLabel =
                    `${line.container?.name ?? '-'} / ${line.container_class?.class ?? '-'} / ${line.container_size?.size ?? '-'}`;

                const fieldsHtml = LINE_DETAIL_FIELDS.map((f) => `
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">${f.label}</label>
                        <input type="${f.type}" ${f.type === 'number' ? 'step="0.01" min="0"' : ''}
                            data-field="${f.field}"
                            class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                `).join('');

                return `
                    <div class="line-details-card border border-zinc-200 dark:border-zinc-700 rounded-lg p-3" data-line-id="${line.id}">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-xs font-semibold text-zinc-500">${variantLabel} &times; ${line.quantity}</p>
                            ${lineDetailsBadge(line)}
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">${fieldsHtml}</div>
                        <div class="flex justify-end mt-2">
                            <button type="button" class="line-details-save-btn px-3 py-1.5 text-xs rounded-lg bg-blue-600 hover:bg-blue-700 text-white">Save</button>
                        </div>
                    </div>
                `;
            }).join('');

            // Set values via the DOM property rather than baking them into
            // the HTML attribute above - avoids any HTML-escaping concerns
            // for free-text fields like consignee address/notes.
            container.querySelectorAll('.line-details-card').forEach((card) => {
                const line = lines.find((l) => String(l.id) === card.dataset.lineId);
                if (!line) return;

                card.querySelectorAll('[data-field]').forEach((input) => {
                    input.value = line[input.dataset.field] ?? '';
                });
            });
        }

        document.getElementById('vbLineDetailsContainer').addEventListener('click', async function(e) {
            const btn = e.target.closest('.line-details-save-btn');
            if (!btn) return;

            const card = btn.closest('.line-details-card');
            const payload = {};
            card.querySelectorAll('[data-field]').forEach((input) => {
                payload[input.dataset.field] = input.value || null;
            });

            const response = await apiCall({
                mode: 'PUT',
                isJson: true,
                payload,
                url: `/api/bookings/${currentBookingUuid}/lines/${card.dataset.lineId}`,
                button: btn,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to save cargo details',
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({ status: 'success', title: 'Cargo details saved' });
            renderLineDetails(response.data);
            loadBookings();
        });

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
                <p class="font-medium text-zinc-800 dark:text-zinc-200">${booking.invoice.invoice_number}
                    <span class="text-zinc-400">· ${INVOICE_STATUS_LABELS[booking.invoice.status] ?? '-'}</span></p>
                <p class="text-xs text-zinc-400 mt-0.5">${money(booking.invoice.amount)}</p>
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
                const route = `${line.origin_port ? (line.origin_port.location?.name ?? '-') + ' - ' + line.origin_port.name : '-'} &rarr; ${line.destination_port ? (line.destination_port.location?.name ?? '-') + ' - ' + line.destination_port.name : '-'}`;
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

        async function performAction(url, payload, successTitle, button) {
            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: payload ?? {},
                url,
                button,
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

        document.getElementById('vbConfirmBtn').addEventListener('click', function() {
            performAction(`/api/bookings/${currentBookingUuid}/confirm`, {}, 'Booking confirmed', this);
        });

        document.getElementById('vbMarkInTransitBtn').addEventListener('click', function() {
            performAction(`/api/bookings/${currentBookingUuid}/mark-in-transit`, {}, 'Marked In Transit', this);
        });

        document.getElementById('vbMarkDeliveredBtn').addEventListener('click', function() {
            performAction(`/api/bookings/${currentBookingUuid}/mark-delivered`, {}, 'Marked Delivered', this);
        });

        document.getElementById('vbMarkCompletedBtn').addEventListener('click', function() {
            performAction(`/api/bookings/${currentBookingUuid}/mark-completed`, {}, 'Booking closed out', this);
        });

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
            }, 'Booking cancelled', this);
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
