{{--
    Standalone "Add Charter" popup - opened from the Request for Proposal
    wizard's own Products tab (Charters table's own "+ Add Charter" button).
    Main fields only - Cargo Info/Ports are added afterward via a saved
    row's own count+View button (see the 4 sub-item modals included at the
    bottom of request-proposal-modal.blade.php). Saving POSTs to
    .../proposalRequests/{id}/products/charters (see saveRpmCharter()) and
    appends the row to the table on the tab behind it, then closes.
--}}
<x-modal id="RpmAddCharterModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Charter</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
        <div id="rpmCharterForm" class="grid grid-cols-1 gap-3">
            <div>
                <label for="rpmChVesselName" class="text-[11px] text-zinc-400 uppercase">Vessel Name</label>
                <input type="text" id="rpmChVesselName" data-field="vessel_name"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label for="rpmChDeadWeight" class="text-[11px] text-zinc-400 uppercase">Vessel Dead Weight</label>
                <input type="text" inputmode="decimal" id="rpmChDeadWeight" data-field="vessel_dead_weight"
                    class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label for="rpmChLoadingDate" class="text-[11px] text-zinc-400 uppercase">Loading Date</label>
                <input type="date" id="rpmChLoadingDate" data-field="loading_date"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label for="rpmChStartDate" class="text-[11px] text-zinc-400 uppercase">Charter Start Date</label>
                <input type="date" id="rpmChStartDate" data-field="charter_start_date"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label for="rpmChEndDate" class="text-[11px] text-zinc-400 uppercase">Charter End Date</label>
                <input type="date" id="rpmChEndDate" data-field="charter_end_date"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label for="rpmChLaytimeLoading" class="text-[11px] text-zinc-400 uppercase">Laytime - Loading (days)</label>
                <input type="number" id="rpmChLaytimeLoading" data-field="laytime_loading_days"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label for="rpmChLaytimeUnloading" class="text-[11px] text-zinc-400 uppercase">Laytime - Unloading (days)</label>
                <input type="number" id="rpmChLaytimeUnloading" data-field="laytime_unloading_days"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="flex items-center gap-2 mt-5">
                <input type="checkbox" id="rpmChDemurrage" data-field="demurrage_charges" class="rounded border-zinc-300 dark:border-zinc-700">
                <label for="rpmChDemurrage" class="text-sm text-zinc-700 dark:text-zinc-200">Demurrage Charges</label>
            </div>
            <div class="flex items-center gap-2 mt-5">
                <input type="checkbox" id="rpmChLashing" data-field="lashing_service" class="rounded border-zinc-300 dark:border-zinc-700">
                <label for="rpmChLashing" class="text-sm text-zinc-700 dark:text-zinc-200">Lashing Service</label>
            </div>
            <div class="flex items-center gap-2 mt-5">
                <input type="checkbox" id="rpmChInsurance" data-field="insurance_services" class="rounded border-zinc-300 dark:border-zinc-700">
                <label for="rpmChInsurance" class="text-sm text-zinc-700 dark:text-zinc-200">Insurance Services</label>
            </div>
            <div>
                <label for="rpmChOtherCharges" class="text-[11px] text-zinc-400 uppercase">Other Charges</label>
                <input type="text" id="rpmChOtherCharges" data-field="other_charges"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label for="rpmChCharterServiceId" class="text-[11px] text-zinc-400 uppercase">Charter Type</label>
                <select id="rpmChCharterServiceId" data-field="charter_service_id"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select Charter Type (optional)</option>
                </select>
            </div>
            <div>
                <label for="rpmChDeclaredValue" class="text-[11px] text-zinc-400 uppercase">Declared Value</label>
                <input type="text" inputmode="decimal" id="rpmChDeclaredValue" data-field="declared_value"
                    class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label for="rpmChWeight" class="text-[11px] text-zinc-400 uppercase">Weight</label>
                <div class="flex gap-2">
                    <input type="text" inputmode="decimal" id="rpmChWeight" data-field="weight"
                        class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    <select id="rpmChWeightUnit" data-field="weight_unit"
                        class="w-24 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-2 text-sm">
                        <option value="kg">kg</option>
                        <option value="mt">MT</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="rpmChCargoManifest" class="text-[11px] text-zinc-400 uppercase">Cargo Manifest</label>
                {{-- Prepared for a future upload endpoint - deliberately not wired to anything yet. --}}
                <input type="file" id="rpmChCargoManifest"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="rpmAchmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="rpmAchmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium disabled:opacity-40 disabled:cursor-not-allowed">
                + Add
            </button>
        </div>
    </div>
</x-modal>
