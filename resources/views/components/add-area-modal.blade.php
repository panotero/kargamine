{{--
    Standalone "Add/Edit Serviceable Area" popup - same idea as
    add-port-modal.blade.php. Carries the Province/Municipality PSGC
    cascade (wired once here instead of once per row, see maintenance
    page's bindAreaCascade()) - picking both composes "Province -
    Municipality" into the Area Name input below, which stays directly
    editable by hand and is the only field actually submitted.
--}}
<x-modal id="AddAreaModal" maxWidth="lg:max-w-md" overlayZIndex="z-[60]">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p id="addAreaModalTitle" class="text-lg font-semibold text-zinc-900 dark:text-white">Add Serviceable Area</p>
        <button type="button" class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4">
        <p class="text-xs text-zinc-400">Pick Province then Municipality, or type the Area Name directly.</p>

        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Province</label>
                <select id="addAreaProvinceSelect"
                    class="area-province-select w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-2 py-1.5 text-sm">
                    <option value="">Province</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Municipality</label>
                <select id="addAreaMunicipalitySelect" disabled
                    class="area-municipality-select w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-2 py-1.5 text-sm">
                    <option value="">Municipality</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Area Name <span class="req-asterisk">*</span></label>
            <input type="text" id="addAreaNameInput" placeholder="e.g. Laguna - Binan"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="addAreaCancelBtn" class="modal-close text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="addAreaSaveBtn" class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                + Add
            </button>
        </div>
    </div>
</x-modal>
