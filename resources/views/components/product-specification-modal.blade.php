{{--
    "Product Specification" modal - opened from a row on the Request for
    Proposal wizard's Build Rate Matrix tab (one shared modal reused across
    all 5 product types - Container/Rolling Cargo/Loose Cargo/Trucking/
    Charter - same "one modal, context passed in" pattern as
    AncillaryServicesModal). Left column is a real per-type field summary
    built from the actual saved product row (see logic_prospect_request_proposal.js's
    openProductSpecificationModal()/buildProductSpecSummaryRows()). Right
    column is pricing: the Rate Breakdown table's default lines are
    starting values - Save persists the Adjustment column (POSTs to a
    /productRates endpoint) and reopening the modal rehydrates the saved
    values, also flipping this row's Rate indicator on the Build Rate
    Matrix table. Below it, Ancillary Services lists whatever was added on
    the Products tab (quantity only there) - Quantity/Base Price/Adjustment
    are all editable here and saved in the same request as the Rate
    Breakdown. Nothing here adds a new ancillary line - that's Products-tab
    only.
--}}
<x-modal id="ProductSpecificationModal" max-width="lg:max-w-5xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <div>
            <p class="text-lg font-semibold text-zinc-900 dark:text-white">Product Specification</p>
            <p class="text-xs text-zinc-400 dark:text-zinc-500" id="psmSubtitle"></p>
        </div>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>

    <div class="p-5 flex flex-col md:flex-row gap-5 max-h-[75vh] overflow-y-auto">
        {{-- ============== LEFT: SUMMARY ============== --}}
        <div class="w-full md:w-64 shrink-0 space-y-2">
            <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Summary</p>
            <div id="psmSummaryGrid" class="grid grid-cols-1 gap-y-2 text-sm"></div>
        </div>

        {{-- ============== RIGHT: RATE BREAKDOWN + ANCILLARY SERVICES ============== --}}
        <div class="flex-1 min-w-0 space-y-5">
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Rate Breakdown</p>
                    <span class="text-[10px] font-medium uppercase tracking-wide text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/30 px-2 py-0.5 rounded-full">Starting values — edit Adjustment, saved on Save</span>
                </div>
                <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                    <table class="min-w-full text-xs">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            <tr>
                                <th class="px-3 py-2 text-left">Detail</th>
                                <th class="px-3 py-2 text-right">Base Rate (Standard)</th>
                                <th class="px-3 py-2 text-right">Rate Adjustment</th>
                                <th class="px-3 py-2 text-right">Total Rate</th>
                            </tr>
                        </thead>
                        <tbody id="psmRateBreakdownBody" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Ancillary Services</p>
                <p id="psmAncillaryEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500">No ancillary services added yet — add them from the Products tab.</p>
                <div id="psmAncillaryWrap" class="hidden overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                    <table class="min-w-full text-xs">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            <tr>
                                <th class="px-3 py-2 text-left">Detail</th>
                                <th class="px-3 py-2 text-right">Qty</th>
                                <th class="px-3 py-2 text-right">Base Price</th>
                                <th class="px-3 py-2 text-right">Adjustment</th>
                                <th class="px-3 py-2 text-right">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody id="psmAncillaryBody" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-zinc-200 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2">
        <button type="button" id="psmCancelBtn"
            class="px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 font-medium">
            Cancel
        </button>
        <button type="button" id="psmSaveBtn"
            class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
            Save
        </button>
    </div>
</x-modal>
