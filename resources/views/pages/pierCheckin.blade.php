<div class="container mx-auto px-4 py-6" id="pierCheckinPage">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Pier Check-In</h1>
        <p class="text-zinc-500">Scan a container's gate pass code to confirm it gating out or back in.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
            <p class="font-semibold text-sm mb-3">Scan</p>
            <div class="relative bg-black rounded-lg overflow-hidden aspect-video">
                <video id="pcVideo" class="w-full h-full object-cover" muted playsinline></video>
            </div>
            <canvas id="pcCanvas" class="hidden"></canvas>
            <div class="flex gap-2 mt-3">
                <button type="button" id="pcStartScanBtn"
                    class="px-3 py-1.5 text-xs rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Start
                    Camera</button>
                <button type="button" id="pcStopScanBtn"
                    class="hidden px-3 py-1.5 text-xs rounded-lg bg-zinc-600 hover:bg-zinc-700 text-white">Stop
                    Camera</button>
            </div>

            <div class="mt-4 border-t border-zinc-100 dark:border-zinc-800 pt-3">
                <p class="text-[11px] text-zinc-400 uppercase mb-1">Or Enter Code Manually</p>
                <div class="flex gap-2">
                    <input type="text" id="pcManualCode" placeholder="GP-BK-2026-0001-01"
                        class="flex-1 border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    <button type="button" id="pcManualSubmitBtn"
                        class="px-3 py-1.5 text-xs rounded-lg bg-blue-600 hover:bg-blue-700 text-white">Confirm</button>
                </div>
            </div>

            <div id="pcResultBanner" class="hidden mt-3 rounded-lg px-3 py-2 text-sm font-medium"></div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <p class="font-semibold text-sm">Pending Gate Actions</p>
                <button type="button" id="pcPrintListBtn" class="px-3 py-1.5 text-xs rounded-lg border">Print
                    List</button>
            </div>
            <div class="overflow-x-auto max-h-[60vh] overflow-y-auto">
                <table class="min-w-full border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800 sticky top-0">
                        <tr>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Booking</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Container</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Route</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800" id="pcPendingBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
