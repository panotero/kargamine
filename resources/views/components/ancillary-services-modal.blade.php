{{--
    Standalone "Ancillary Services" modal - opened from a saved Container/
    Rolling Cargo/Loose Cargo/Trucking row's count+View button on the
    Request for Proposal wizard's Products tab (never Charter, which gets
    its own Cargo Info/Ports modals instead). Genuinely one shared modal
    reused across those 4 parent types (not 4 duplicated modals) since the
    CRUD here is byte-for-byte identical regardless of parent - only the
    {parentType, parentId} context passed to
    window.openAncillaryServicesModal() changes. "Edit" removes the row and
    refills the form with its values (no separate update endpoint) - click
    Add again to re-save it.

    Ancillary Type is sourced from the Special Charges catalog
    (App Settings -> /page_maintenance), not a free LOV. Pricing is not
    previewed here - this tab only ever captures quantity; Base Value/
    Adjustment/Subtotal are handled later in Build Rate Matrix, see
    product-specification-modal.blade.php.
--}}
<x-modal id="AncillaryServicesModal" max-width="lg:max-w-3xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Ancillary Services</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label for="asmAncillaryType" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Ancillary Type</label>
                <select id="asmAncillaryType"
                    class="asmAncillaryTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="">Select Ancillary Type</option>
                </select>
            </div>
            <div>
                <label for="asmCargoYard" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Ancillary Location (Cargo Yard)</label>
                <select id="asmCargoYard"
                    class="asmCargoYardDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="">Select Cargo Yard</option>
                </select>
            </div>
            <div>
                <label for="asmUnit" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Ancillary Unit</label>
                <select id="asmUnit"
                    class="asmUnitDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="">Select Unit</option>
                </select>
            </div>
            <div>
                <label for="asmQuantity" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Quantity</label>
                <input type="text" inputmode="decimal" id="asmQuantity" placeholder="0" value="1"
                    class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div class="md:col-span-2">
                <label for="asmRemarks" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Remarks</label>
                <input type="text" id="asmRemarks"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
        </div>
        <div class="flex justify-end">
            <button type="button" id="asmAddBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                + Add
            </button>
        </div>

        <div class="border-t dark:border-zinc-700 pt-3 space-y-2">
            <p id="asmEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500">No ancillary services added yet.</p>
            <div id="asmListWrap" class="hidden overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                <table class="min-w-full text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        <tr>
                            <th class="px-3 py-2 text-left">Type</th>
                            <th class="px-3 py-2 text-left">Location</th>
                            <th class="px-3 py-2 text-left">Unit</th>
                            <th class="px-3 py-2 text-right">Qty</th>
                            <th class="px-3 py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="asmList" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="button" id="asmCloseBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Close
            </button>
        </div>
    </div>
</x-modal>
