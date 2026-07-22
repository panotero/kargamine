<div class="container mx-auto p-3">

    {{-- PAGE HEADER --}}
    <div class="flex justify-between items-center mb-5 p-2">
        <div>
            <h1 class="text-2xl font-bold dark:text-white">Navigation Menus</h1>
            <p class="text-zinc-500">Manage the sidebar structure and which roles can see each menu</p>
        </div>
        <button id="addMenuBtn"
            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            + Add New Menu
        </button>
    </div>

    {{-- MENUS TABLE --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden">
        <table class="w-full text-sm" id="navMenuTable">
            <thead>
                <tr>
                    <th
                        class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">
                        Title</th>
                    <th
                        class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">
                        Page</th>
                    <th
                        class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">
                        Allowed Roles</th>
                    <th
                        class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">
                        Parent</th>
                    <th
                        class="px-4 py-2.5 text-center text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">
                        Order</th>
                    <th
                        class="px-4 py-2.5 text-center text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">
                        Action</th>
                </tr>
            </thead>
            <tbody id="navMenuTbody" class="divide-y divide-zinc-100 dark:divide-zinc-800">
            </tbody>
        </table>
    </div>
</div>

{{-- ADD / EDIT MENU MODAL --}}
<div id="menuModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center modal z-50 p-4">
    <div
        class="bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-100 w-full max-w-md max-h-[85vh] rounded-xl shadow-2xl shadow-black/10 dark:shadow-black/40 border border-zinc-200 dark:border-zinc-700 flex flex-col">

        {{-- Header --}}
        <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 shrink-0">
            <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Navigation Menus</p>
            <p id="modalTitle" class="text-lg font-semibold mt-0.5">Add New Menu</p>
        </div>

        {{-- Form --}}
        <form id="menuForm" class="p-5 space-y-4 overflow-y-auto">
            <input type="hidden" id="menuId">

            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Title</label>
                <input id="menuTitle" type="text"
                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Icon</label>
                <input id="menuIcon" type="text"
                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Link</label>
                <div class="flex items-center gap-2">
                    <span class="text-zinc-400 text-sm">/</span>
                    <input id="menuLink" type="text"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Allowed Roles</label>
                <div id="menuRolesContainer" class="grid grid-cols-2 gap-2 text-sm text-zinc-700 dark:text-zinc-200">
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Parent Menu</label>
                <select id="menuParent"
                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                </select>
            </div>
        </form>

        {{-- Footer --}}
        <div class="border-t border-zinc-100 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2 shrink-0">
            <button type="button" id="cancelModalBtn"
                class="px-4 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                Cancel
            </button>
            <button type="submit" form="menuForm" id="saveMenuBtn"
                class="px-4 py-1.5 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
                Save
            </button>
        </div>
    </div>
</div>
<script>
    if (window.initMenuSettingsPage) window.initMenuSettingsPage();
</script>
