{{--
    Standalone "Add Location" popup opened from ProspectModal's own Identity
    tab (NOT the same modal as <x-add-location-modal /> - that one belongs to
    ProspectInfoModal and saves immediately via POST .../prospects/stage1).
    This one only adds the entry to logic_prospect_modal.js's in-memory
    pmLocations array and re-renders the summary list - the whole Identity
    tab (name/source/business type/locations) is still submitted together by
    the existing "Save Prospect Identity" button. See
    openPmAddLocationModal()/savePmLocation() in logic_prospect_modal.js.

    Reuses ProspectModal's own locationCardHtml()/initLocationPsgcCascade()
    (same file, no window.prospectShared indirection needed since both live
    in logic_prospect_modal.js).
--}}
<x-modal id="PmAddLocationModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Location</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4">
        <div id="pmAlmLocationCardWrap"></div>
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="pmAlmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="pmAlmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                Save
            </button>
        </div>
    </div>
</x-modal>
