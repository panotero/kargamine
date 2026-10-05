{{--
    Standalone "Add Location" modal - opened from ProspectInfoModal's
    Locations tab. Genuinely separate from ProspectModal (per brief - not a
    reopened form), but reuses ProspectModal's location-card markup/PSGC
    cascade via window.prospectShared (see logic_prospect_modal.js) rather
    than re-implementing it. Saves by resending the FULL address list
    (existing + this new one) to POST /crm/prospects/stage1, same
    replace-all contract the main form already uses - there's no
    "append one address" endpoint.
--}}
<x-modal id="AddLocationModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Location</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4">
        <div id="almLocationCardWrap"></div>
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="almCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium">
                Cancel
            </button>
            <button type="button" id="almSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                Save
            </button>
        </div>
    </div>
</x-modal>
