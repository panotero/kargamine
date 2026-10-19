{{--
    Standalone "Add Origin/Destination Location" popup - opened from the
    Request for Proposal wizard's own Origin & Destination tab (its own
    "+ Add" button). Mirrors <x-add-origin-destination-modal /> (the same
    pattern used from ProspectInfoModal's own O&D tab) exactly, but with its
    own `rpmAodm*` ids so the two can't collide even though they'd never
    actually appear on the same page. Saving appends one row to the current
    Proposal Request's own locations list (POST
    .../proposalRequests/{id}/locations) and refreshes the table on the
    tab behind it - see openRpmAddOdModal()/saveRpmAddOd() in
    logic_prospect_request_proposal.js.
--}}
<x-modal id="RpmAddOdModal" max-width="lg:max-w-md">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Origin/Destination Location</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4">
        <div>
            <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-2">Location Address</p>
            <div id="rpmAodmChips" class="flex flex-wrap gap-2"></div>
            <p id="rpmAodmChipsEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500">This prospect has no saved addresses yet - fill out the form below manually.</p>
        </div>

        {{--
            Live, editable address form - built from
            window.prospectShared.locationCardHtml()/initLocationPsgcCascade()
            (same helpers the Identity tab's own address cards and
            AddLocationModal use), minus the Primary radio and Remove button
            which don't apply to a single O&D entry. Clicking a suggestion
            chip above autofills it (including the real
            province->city->barangay cascade), but it's always visible and
            editable/submittable on its own - manual entry with no chip
            clicked is allowed. See logic_prospect_request_proposal.js's
            renderRpmOdChips()/hydrateOdCard()/saveRpmAddOd().
        --}}
        <div id="rpmAodmCardWrap"></div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="rpmAodmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="rpmAodmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium disabled:opacity-40 disabled:cursor-not-allowed">
                Save
            </button>
        </div>
    </div>
</x-modal>
