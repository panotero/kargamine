<div class="container mx-auto p-3">

    {{-- PAGE HEADER --}}
    <div class="flex justify-between items-center mb-5 p-2">
        <div>
            <h1 class="text-2xl font-bold dark:text-white">Team Management</h1>
            <p class="text-zinc-500">Build the parent/child team hierarchy, and assign members and leaders</p>
        </div>
        <button id="addTeamBtn"
            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            + Add New Team
        </button>
    </div>

    {{-- TEAMS TABLE --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden">
        <table class="w-full text-sm" id="teamsTable">
            <thead>
                <tr>
                    <th
                        class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">
                        Name</th>
                    <th
                        class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">
                        Description</th>
                    <th
                        class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">
                        Members</th>
                    <th
                        class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">
                        Leaders</th>
                    <th
                        class="px-4 py-2.5 text-center text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">
                        Status</th>
                    <th
                        class="px-4 py-2.5 text-center text-xs font-medium uppercase tracking-wide bg-orange-500 text-white">
                        Action</th>
                </tr>
            </thead>
            <tbody id="teamsTbody" class="divide-y divide-zinc-100 dark:divide-zinc-800">
            </tbody>
        </table>
    </div>
</div>

{{-- ADD / EDIT TEAM MODAL --}}
<div id="teamModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center modal z-50 p-4">
    <div
        class="bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-100 w-full max-w-md max-h-[85vh] rounded-xl shadow-2xl shadow-black/10 dark:shadow-black/40 border border-zinc-200 dark:border-zinc-700 flex flex-col">

        {{-- Header --}}
        <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 shrink-0">
            <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Team Management</p>
            <p id="teamModalTitle" class="text-lg font-semibold mt-0.5">Add New Team</p>
        </div>

        {{-- Form --}}
        <form id="teamForm" class="p-5 space-y-4 overflow-y-auto">
            <input type="hidden" id="teamId">

            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Name</label>
                <input id="teamName" type="text" required
                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Description</label>
                <textarea id="teamDescription" rows="2"
                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"></textarea>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Parent Team</label>
                <select id="teamParent"
                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    <option value="">None (top-level team)</option>
                </select>
            </div>

            <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-200">
                <input id="teamActive" type="checkbox" checked class="cursor-pointer">
                Active
            </label>
        </form>

        {{-- Footer --}}
        <div class="border-t border-zinc-100 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2 shrink-0">
            <button type="button" id="cancelTeamModalBtn"
                class="px-4 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                Cancel
            </button>
            <button type="submit" form="teamForm" id="saveTeamBtn"
                class="px-4 py-1.5 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
                Save
            </button>
        </div>
    </div>
</div>

{{-- MEMBERS MODAL --}}
<div id="membersModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center modal z-50 p-4">
    <div
        class="bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-100 w-full max-w-lg max-h-[85vh] rounded-xl shadow-2xl shadow-black/10 dark:shadow-black/40 border border-zinc-200 dark:border-zinc-700 flex flex-col">

        {{-- Header --}}
        <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 shrink-0">
            <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Team Members</p>
            <p id="membersModalTitle" class="text-lg font-semibold mt-0.5">Team</p>
        </div>

        {{-- Add member --}}
        <div class="px-5 pt-4 flex items-end gap-2 shrink-0">
            <div class="flex-1 flex flex-col gap-1">
                <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Add member</label>
                <select id="addMemberSelect"
                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                </select>
            </div>
            <button type="button" id="addMemberBtn"
                class="px-4 py-2 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
                Add
            </button>
        </div>

        {{-- Members list --}}
        <div id="teamMembersList" class="p-5 space-y-2 overflow-y-auto"></div>

        {{-- Footer --}}
        <div class="border-t border-zinc-100 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2 shrink-0">
            <button type="button" id="closeMembersModalBtn"
                class="px-4 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    if (window.initTeamManagementPage) window.initTeamManagementPage();
</script>
