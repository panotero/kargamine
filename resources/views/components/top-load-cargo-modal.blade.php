{{--
    Standalone "Top Load Cargo" modal - opened from a saved Rolling Cargo
    row's count+View button on the Request for Proposal wizard's Products
    tab (Rolling Cargo only - Loose Cargo doesn't get this section). "Edit"
    removes the row and refills the form with its values (no separate
    update endpoint) - click Add again to re-save it.
--}}
<x-modal id="TopLoadCargoModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Top Load Cargo</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label for="tlcmType" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Top Load Type</label>
                <select id="tlcmType"
                    class="tlcmCargoTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="">Select Type</option>
                </select>
            </div>
            <div>
                <label for="tlcmDetails" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Details</label>
                <input type="text" id="tlcmDetails"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label for="tlcmQuantity" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Quantity</label>
                <input type="number" id="tlcmQuantity"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label for="tlcmUnits" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Units</label>
                <select id="tlcmUnits"
                    class="tlcmUnitDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="">Select Unit</option>
                </select>
            </div>
            <div>
                <label for="tlcmRevenueTon" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Revenue Ton (RT)</label>
                <input type="number" step="0.01" id="tlcmRevenueTon"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label for="tlcmRevenueTonUnit" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Revenue Ton (RT) Unit</label>
                <select id="tlcmRevenueTonUnit"
                    class="tlcmRtUnitDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1"></select>
            </div>
            <div class="md:col-span-2">
                <label for="tlcmMeasurement" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Measurement</label>
                <input type="text" id="tlcmMeasurement"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
        </div>
        <div class="flex justify-end">
            <button type="button" id="tlcmAddBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                + Add
            </button>
        </div>

        <div class="border-t dark:border-zinc-700 pt-3 space-y-2">
            <p id="tlcmEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500">No top load cargo added yet.</p>
            <div id="tlcmList" class="space-y-2"></div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="button" id="tlcmCloseBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium">
                Close
            </button>
        </div>
    </div>
</x-modal>
