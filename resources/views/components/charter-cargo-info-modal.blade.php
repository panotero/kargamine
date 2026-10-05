{{--
    Standalone "Charter Cargo Info" modal - opened from a saved Charter
    row's Cargo Info count+View button on the Request for Proposal
    wizard's Products tab. "Edit" removes the row and refills the form
    with its values (no separate update endpoint) - click Add again to
    re-save it.
--}}
<x-modal id="CharterCargoInfoModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Charter Cargo Info</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label for="ccimCargoType" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Cargo Type</label>
                <select id="ccimCargoType"
                    class="ccimCargoTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="">Select Cargo Type</option>
                </select>
            </div>
            <div>
                <label for="ccimCargoDescription" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Cargo Description</label>
                <input type="text" id="ccimCargoDescription"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div class="md:col-span-2">
                <label for="ccimSpecialRequirements" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Special Requirements</label>
                <input type="text" id="ccimSpecialRequirements"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
        </div>
        <div class="flex justify-end">
            <button type="button" id="ccimAddBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                + Add
            </button>
        </div>

        <div class="border-t dark:border-zinc-700 pt-3 space-y-2">
            <p id="ccimEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500">No cargo info added yet.</p>
            <div id="ccimList" class="space-y-2"></div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="button" id="ccimCloseBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium">
                Close
            </button>
        </div>
    </div>
</x-modal>
