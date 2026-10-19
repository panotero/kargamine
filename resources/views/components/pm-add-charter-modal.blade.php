{{--
    Standalone "Add Charter" popup opened from ProspectModal's own
    Requirements tab (Charters table's own "+ Add Charter" button). Each
    booking owns its own repeatable Cargo list and Port list, entered inline
    here and summarized as one table row (see charterTableRowHtml() in
    logic_prospect_modal.js). Immediate/single-row append - saving POSTs the
    whole charter (with its nested cargo[]/ports[] arrays) straight to
    POST .../prospects/{uuid}/proposalRequest/charters (see addCharter()),
    splices the returned row into the Charters table on the main tab, then
    closes.
--}}
<x-modal id="PmAddCharterModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Charter</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
        <div>
            <div class="flex justify-between items-center mb-2">
                <p
                    class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                    Cargo <span class="req-asterisk">*</span></p>
                <button type="button" id="pmAddCharterCargoBtn"
                    class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                    + Add Cargo
                </button>
            </div>
            <div id="pmCharterCargoContainer" class="space-y-2"></div>
        </div>

        <div class="border-t dark:border-zinc-700 pt-3">
            <div class="flex justify-between items-center mb-2">
                <p
                    class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                    Ports <span class="req-asterisk">*</span></p>
                <button type="button" id="pmAddCharterPortBtn"
                    class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                    + Add Port
                </button>
            </div>
            <div id="pmCharterPortsContainer" class="space-y-2"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 border-t dark:border-zinc-700 pt-3">
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="pmCharterStartDate" class="text-[11px] text-zinc-400 uppercase">Charter Start
                    Date <span class="req-asterisk">*</span></label>
                <input type="date" id="pmCharterStartDate" data-field="charter_start_date"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="pmCharterEndDate" class="text-[11px] text-zinc-400 uppercase">Charter End Date
                    <span class="req-asterisk">*</span></label>
                <input type="date" id="pmCharterEndDate" data-field="charter_end_date"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label for="pmCharterDeclaredValue" class="text-[11px] text-zinc-400 uppercase">Declared
                    Value</label>
                <input type="text" inputmode="decimal" id="pmCharterDeclaredValue"
                    data-field="declared_value"
                    class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label for="pmCharterServiceId" class="text-[11px] text-zinc-400 uppercase">Charter
                    Type</label>
                <select id="pmCharterServiceId" data-field="charter_service_id"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Charter Type (optional)</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="text-[11px] text-zinc-400 uppercase">Weight</label>
                <div class="flex gap-2 md:w-1/3">
                    <input type="text" inputmode="decimal" id="pmCharterWeight" data-field="weight"
                        class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <select id="pmCharterWeightUnit" data-field="weight_unit"
                        class="w-28 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Unit</option>
                        <option value="kg">Kilogram</option>
                        <option value="mt">Metric Ton</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="pmChmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="pmChmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                + Add Requirement
            </button>
        </div>
    </div>
</x-modal>
