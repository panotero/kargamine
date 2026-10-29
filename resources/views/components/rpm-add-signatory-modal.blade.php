{{--
    Standalone "Add Authorized Signatory" popup - opened from the Request
    for Proposal wizard's own Company Details tab ("+ Add Authorized"
    button). Mirrors <x-rpm-add-od-modal /> exactly (its own ids so the two
    never collide, even though they'd never actually appear at the same
    time). Picking a Contact prefills Name/Position/Email/Contact Number
    (see signatoryContactChange() in logic_prospect_request_proposal.js)
    but every field stays fully editable, and the Contact picker itself is
    optional - leaving it blank and typing all four fields is a valid
    fully-manual signatory. Whatever's in these four fields at Save time is
    what gets saved on this signatory (its own snapshot, not re-read from
    the contact later) - and on final submit, that snapshot syncs back into
    the picked contact's position/email/mobile, or becomes a brand new
    contact if none was picked. See ProspectController::submitProposalRequest().
    Saving appends one row to the current Proposal Request's own
    signatories list (POST .../proposalRequests/{id}/signatories) and
    refreshes the list on the tab behind it - see
    openRpmAddSignatoryModal()/addSignatory() in
    logic_prospect_request_proposal.js.
--}}
<x-modal id="RpmAddSignatoryModal" max-width="lg:max-w-md">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Authorized Signatory</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4">
        <div>
            <label for="rpmSignatoryContact" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Contact</label>
            <select id="rpmSignatoryContact"
                class="rpmSignatoryDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                <option value="">Select Contact (optional - or type below manually)</option>
            </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="rpmSignatoryName" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Name <span class="req-asterisk">*</span></label>
                <input type="text" id="rpmSignatoryName"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label for="rpmSignatoryPosition" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Position</label>
                <input type="text" id="rpmSignatoryPosition"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label for="rpmSignatoryEmail" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Email</label>
                <input type="email" id="rpmSignatoryEmail"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label for="rpmSignatoryContactNumber" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Contact Number</label>
                <input type="text" id="rpmSignatoryContactNumber"
                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
            </div>
        </div>
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="rpmAsmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="rpmAsmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium disabled:opacity-40 disabled:cursor-not-allowed">
                Save
            </button>
        </div>
    </div>
</x-modal>
