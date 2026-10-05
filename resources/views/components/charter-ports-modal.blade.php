{{--
    Standalone "Charter Ports" modal - opened from a saved Charter row's
    Ports count+View button on the Request for Proposal wizard's Products
    tab. Ports are sequentially labeled "PORT 1", "PORT 2"... by
    sort_order (server-assigned = current count on add, renumbered on
    delete) - the last one is relabeled "FINAL PORT" purely as a display
    convention, no stored is_final flag. "Edit" removes the row and
    refills the form (which moves it to the end of the sequence on re-add -
    an accepted tradeoff for this secondary feature, not built as a true
    in-place update).
--}}
<x-modal id="CharterPortsModal" min-width="lg:min-w-[60vw]" max-width="lg:max-w-4xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Charter Ports</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label for="cpmPort" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Port Name</label>
                <select id="cpmPort"
                    class="cpmPortDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="">Select Port</option>
                </select>
            </div>
            <div>
                <label for="cpmPortChargeAccount" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Port Charges Account</label>
                <select id="cpmPortChargeAccount"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="direct">Direct Payment by Charterer/Shipper/Cargo Owner</option>
                    <option value="invoice">Invoice to Charterer/Shipper/Cargo Owner</option>
                </select>
            </div>
            <div>
                <label for="cpmPortChargeAmount" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Amount to be Charged</label>
                <input type="number" step="0.01" id="cpmPortChargeAmount"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1 disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
            </div>

            <div class="md:col-span-3 border-t dark:border-zinc-700 pt-3">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Cargoes for Loading</p>
            </div>
            <div>
                <label for="cpmCargoesLoading" class="text-[11px] text-zinc-400 uppercase">Cargoes for Loading</label>
                <input type="number" id="cpmCargoesLoading"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1 disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
            </div>
            <div>
                <label for="cpmMeasurementLoading" class="text-[11px] text-zinc-400 uppercase">Cargo Measurement</label>
                <input type="text" id="cpmMeasurementLoading"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1 disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
            </div>
            <div>
                <label for="cpmRtUnitLoading" class="text-[11px] text-zinc-400 uppercase">Revenue Ton Unit</label>
                <select id="cpmRtUnitLoading"
                    class="cpmRtUnitDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1 disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-zinc-100 dark:disabled:bg-zinc-900"></select>
            </div>

            <div class="md:col-span-3 border-t dark:border-zinc-700 pt-3">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Cargoes for Unloading</p>
            </div>
            <div>
                <label for="cpmCargoesUnloading" class="text-[11px] text-zinc-400 uppercase">Cargoes for Unloading</label>
                <input type="number" id="cpmCargoesUnloading"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1 disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
            </div>
            <div>
                <label for="cpmMeasurementUnloading" class="text-[11px] text-zinc-400 uppercase">Cargo Measurement</label>
                <input type="text" id="cpmMeasurementUnloading"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1 disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
            </div>
            <div>
                <label for="cpmRtUnitUnloading" class="text-[11px] text-zinc-400 uppercase">Revenue Ton Unit</label>
                <select id="cpmRtUnitUnloading"
                    class="cpmRtUnitDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1 disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-zinc-100 dark:disabled:bg-zinc-900"></select>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="button" id="cpmAddBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                + Add Port
            </button>
        </div>

        <div class="border-t dark:border-zinc-700 pt-3 space-y-2">
            <p id="cpmEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500">No ports added yet.</p>
            <div id="cpmList" class="space-y-2"></div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="button" id="cpmCloseBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium">
                Close
            </button>
        </div>
    </div>
</x-modal>
