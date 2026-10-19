{{--
    Standalone "Add Charter" popup opened from ProspectInfoModal's own
    Requirements tab (Charters table's own "+ Add Charter" button). Targets
    its own `arm*` field ids - see pim-add-freight-modal.blade.php's header
    comment for why. Each booking owns its own repeatable Cargo list and
    Port list, entered inline here. Immediate/single-row append - saving
    POSTs the whole charter (with its nested cargo[]/ports[] arrays)
    straight to POST .../prospects/{uuid}/proposalRequest/charters (see
    savePimCharter() in logic_prospect_add_modals.js), reloads this
    prospect's data, then closes.
--}}
<x-modal id="PimAddCharterModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Charter</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
        <div>
            <div class="flex justify-between items-center mb-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Cargo <span class="req-asterisk">*</span></p>
                <button type="button" id="armAddCharterCargoBtn"
                    class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                    + Add Cargo
                </button>
            </div>
            <div id="armCharterCargoContainer" class="space-y-2"></div>
        </div>

        <div class="border-t dark:border-zinc-700 pt-3">
            <div class="flex justify-between items-center mb-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Ports <span class="req-asterisk">*</span></p>
                <button type="button" id="armAddCharterPortBtn"
                    class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                    + Add Port
                </button>
            </div>
            <div id="armCharterPortsContainer" class="space-y-2"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 border-t dark:border-zinc-700 pt-3">
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="armCharterStartDate" class="text-[11px] text-zinc-400 uppercase">Charter Start Date <span class="req-asterisk">*</span></label>
                <input type="date" id="armCharterStartDate" data-field="charter_start_date"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="armCharterEndDate" class="text-[11px] text-zinc-400 uppercase">Charter End Date <span class="req-asterisk">*</span></label>
                <input type="date" id="armCharterEndDate" data-field="charter_end_date"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label for="armCharterDeclaredValue" class="text-[11px] text-zinc-400 uppercase">Declared Value</label>
                <input type="text" inputmode="decimal" id="armCharterDeclaredValue" data-field="declared_value"
                    class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label for="armCharterServiceId" class="text-[11px] text-zinc-400 uppercase">Charter Type</label>
                <select id="armCharterServiceId" data-field="charter_service_id"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Charter Type (optional)</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="text-[11px] text-zinc-400 uppercase">Weight</label>
                <div class="flex gap-2 md:w-1/3">
                    <input type="text" inputmode="decimal" id="armCharterWeight" data-field="weight"
                        class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <select id="armCharterWeightUnit" data-field="weight_unit"
                        class="w-28 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Unit</option>
                        <option value="kg">Kilogram</option>
                        <option value="mt">Metric Ton</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="pimChmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="pimChmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                + Add Requirement
            </button>
        </div>
    </div>
</x-modal>
