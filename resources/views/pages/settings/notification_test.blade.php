<div class="max-w-3xl mx-auto p-5">
    <h1 class="text-2xl font-semibold mb-1 text-zinc-900 dark:text-zinc-100">Notification Test</h1>
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
        Dev-only tool to manually create notifications and verify targeting modes (specific user, role, or
        department) before any real feature wires into the notification module.
    </p>

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow-sm p-5 space-y-4">
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Target mode</label>
            <select id="notifTestTargetType"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 px-3 py-2 text-sm">
                <option value="user">Specific user</option>
                <option value="role">Role</option>
                <option value="department">Department</option>
            </select>
        </div>

        <div id="notifTestTargetUserWrap">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">User</label>
            <select id="notifTestTargetUser"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 px-3 py-2 text-sm">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="notifTestTargetRoleWrap" class="hidden">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Role</label>
            <select id="notifTestTargetRole"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 px-3 py-2 text-sm">
                @foreach ($roles as $role)
                    <option value="{{ $role->role_name }}">{{ $role->role_name }}</option>
                @endforeach
            </select>
        </div>

        <div id="notifTestTargetDepartmentWrap" class="hidden">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Department</label>
            <select id="notifTestTargetDepartment"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 px-3 py-2 text-sm">
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Title</label>
            <input type="text" id="notifTestTitle" placeholder="e.g. New Proposal"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Message</label>
            <textarea id="notifTestMessage" rows="2" placeholder="e.g. A new proposal was created for review."
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 px-3 py-2 text-sm"></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Link title
                    (optional)</label>
                <input type="text" id="notifTestLinkTitle" placeholder="e.g. Client Proposals"
                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Link URL
                    (optional)</label>
                <input type="text" id="notifTestLinkUrl" placeholder="e.g. /page_proposals"
                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 px-3 py-2 text-sm">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="button" id="notifTestSendBtn"
                class="inline-flex items-center px-5 py-2.5 bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                Send test notification
            </button>
        </div>
    </div>

    <div id="notifTestResult" class="mt-6"></div>
</div>

<script>
    (function() {
        const targetType = document.getElementById('notifTestTargetType');
        const userWrap = document.getElementById('notifTestTargetUserWrap');
        const roleWrap = document.getElementById('notifTestTargetRoleWrap');
        const departmentWrap = document.getElementById('notifTestTargetDepartmentWrap');
        const sendBtn = document.getElementById('notifTestSendBtn');
        const resultEl = document.getElementById('notifTestResult');

        const WRAPS = { user: userWrap, role: roleWrap, department: departmentWrap };

        targetType.addEventListener('change', () => {
            Object.entries(WRAPS).forEach(([mode, el]) => {
                el.classList.toggle('hidden', mode !== targetType.value);
            });
        });

        sendBtn.addEventListener('click', async () => {
            const mode = targetType.value;
            const target = { type: mode };

            if (mode === 'user') target.user_id = Number(document.getElementById('notifTestTargetUser').value);
            if (mode === 'role') target.role = document.getElementById('notifTestTargetRole').value;
            if (mode === 'department') target.department_id = Number(document.getElementById('notifTestTargetDepartment').value);

            const payload = {
                title: document.getElementById('notifTestTitle').value,
                message: document.getElementById('notifTestMessage').value,
                type: 'test.manual',
                link: {
                    title: document.getElementById('notifTestLinkTitle').value || null,
                    url: document.getElementById('notifTestLinkUrl').value || null,
                },
                target,
            };

            const res = await window.apiCall({
                mode: 'POST',
                url: '/api/notifications/test-send',
                payload,
                button: sendBtn,
            });

            if (!res || res.success !== true) {
                resultEl.innerHTML = `<div class="text-sm text-red-600 dark:text-red-400">Failed to send. Check console/response for details.</div>`;
                return;
            }

            resultEl.innerHTML = `
                <div class="text-sm text-green-700 dark:text-green-400 font-medium">
                    Created ${res.created_count} notification(s).
                </div>
                <ul class="mt-2 text-xs text-zinc-600 dark:text-zinc-400 space-y-1">
                    ${res.notifications.map(n => `<li>user_id=${n.user_id} — "${n.title}"</li>`).join('')}
                </ul>
            `;
        });
    })();
</script>
