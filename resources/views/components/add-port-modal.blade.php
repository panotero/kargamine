{{--
    Standalone "Add/Edit Port" popup - opened from inside the Location
    modal's (#locationFormModal, a side-modal) Ports table. Mirrors
    rpm-add-charter-modal.blade.php's pattern: a small <x-modal> opened on
    top of a bigger modal, filled in, saved, closes, and the row it
    produces is rendered into the table on the modal behind it. Uses
    overlay-z-index to paint above the parent side-modal (z-50) - see
    modal.blade.php's overlayZIndex prop.
--}}
<x-modal id="AddPortModal" maxWidth="lg:max-w-md" overlayZIndex="z-[60]">
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <p id="addPortModalTitle" class="text-lg font-semibold text-zinc-900 dark:text-white">Add Port</p>
        <button type="button" class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>
    <div class="p-5 space-y-4">
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Port Name <span class="req-asterisk">*</span></label>
            <input type="text" id="addPortNameInput" placeholder="e.g. Caticlan Port"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="addPortCancelBtn" class="modal-close text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Cancel
            </button>
            <button type="button" id="addPortSaveBtn" class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">
                + Add
            </button>
        </div>
    </div>
</x-modal>
