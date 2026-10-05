{{--
    Standalone "Add Contact" modal - opened from ProspectInfoModal's
    Contacts tab. Genuinely separate from ProspectModal, but reuses its
    contact-card markup (title/name/channels) via window.prospectShared
    (see logic_prospect_modal.js). The address dropdown is built fresh here
    from the Info modal's own cached prospect data, not ProspectModal's -
    they can be out of sync if that modal was never opened this session.
    Saves by resending the FULL contact list (existing + this new one) to
    POST /crm/prospects/{uuid}/contacts, same replace-all contract the main
    form already uses - there's no "append one contact" endpoint.
--}}
<x-modal id="AddContactModal" max-width="lg:max-w-2xl">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p class="text-lg font-semibold text-zinc-900 dark:text-white">Add Contact</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4">
        <div id="acmContactCardWrap"></div>
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="acmCancelBtn"
                class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium">
                Cancel
            </button>
            <button type="button" id="acmSaveBtn"
                class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                Save
            </button>
        </div>
    </div>
</x-modal>
