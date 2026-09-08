<div class="container mx-auto px-4 py-6" id="containerAssignmentPage">

    <div class="mb-6">
        <h1 class="text-2xl font-bold">Container Assignment</h1>
        <p class="text-zinc-500">Claim a physical container for every booked cargo unit before it can be confirmed.</p>
    </div>

    {{-- Status Cards --}}
    <section class="w-full my-5">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div class="caStatusBtn border-orange-500 ring-1 ring-orange-500 bg-white dark:bg-zinc-900 border rounded-lg p-3 cursor-pointer transition"
                data-status="all">
                <p class="text-[10.5px] font-bold uppercase tracking-wide text-zinc-400 dark:text-zinc-500">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-blue-500 align-middle mr-1.5"></span>All Draft Bookings
                </p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mt-1" id="countAll">0</p>
            </div>
            <div class="caStatusBtn border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 border rounded-lg p-3 cursor-pointer transition hover:border-orange-300 dark:hover:border-orange-700"
                data-status="needs_assignment">
                <p class="text-[10.5px] font-bold uppercase tracking-wide text-zinc-400 dark:text-zinc-500">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-500 align-middle mr-1.5"></span>Needs Assignment
                </p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mt-1" id="countNeedsAssignment">0</p>
            </div>
            <div class="caStatusBtn border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 border rounded-lg p-3 cursor-pointer transition hover:border-orange-300 dark:hover:border-orange-700"
                data-status="fully_assigned">
                <p class="text-[10.5px] font-bold uppercase tracking-wide text-zinc-400 dark:text-zinc-500">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500 align-middle mr-1.5"></span>Fully Assigned
                </p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mt-1" id="countFullyAssigned">0</p>
            </div>
        </div>
    </section>

    <x-table id="tableContainerAssignment" />
</div>

{{-- Container Assignment modal - one booking's slots at a time --}}
<x-modal id="containerAssignmentModal">
    <div class="p-5 border-b flex justify-between items-center">
        <div>
            <div class="flex items-center gap-2">
                <p class="text-lg font-semibold" id="caCode">-</p>
                <span id="caAssignedBadge"></span>
            </div>
            <p class="text-xs text-zinc-400" id="caClientName">-</p>
        </div>
        <button class="modal-close">✕</button>
    </div>

    <div class="max-h-[70vh] overflow-y-auto p-5 space-y-4 text-sm text-zinc-700 dark:text-zinc-300">
        <div class="flex justify-end">
            <button type="button" id="caAutoAssignBtn"
                class="px-3 py-1.5 text-xs rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Auto-Assign
                Remaining</button>
        </div>

        <div id="caUnitsBody" class="space-y-2"></div>
    </div>
</x-modal>

{{-- Scan/confirm modal - scan or type a container, confirm its info, then
     move straight on to the next unassigned slot without closing. Mirrors
     the scan-or-type pattern already built for Pier Check-In. --}}
<x-modal id="containerScanModal" maxWidth="lg:max-w-md">
    <div class="p-5 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
        <div>
            <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Assign Container</p>
            <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100 mt-0.5" id="csUnitLabel">-</p>
        </div>
        <button type="button" id="csCloseBtn" class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">✕</button>
    </div>

    <div class="p-5">
        {{-- Capture view: scan + manual together, shown until a code resolves --}}
        <div id="csCaptureView">
            <div class="relative mx-auto max-w-xs aspect-[4/3] bg-black rounded-lg overflow-hidden">
                <video id="csVideo" class="w-full h-full object-cover" muted playsinline></video>
                <canvas id="csCanvas" class="hidden"></canvas>
            </div>
            <div class="flex justify-center gap-2 mt-3">
                <button type="button" id="csStartBtn"
                    class="px-3 py-1.5 text-xs rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Start Camera</button>
                <button type="button" id="csStopBtn"
                    class="hidden px-3 py-1.5 text-xs rounded-lg border border-zinc-300 dark:border-zinc-600">Stop Camera</button>
            </div>

            <div class="flex items-center gap-2 my-4 max-w-xs mx-auto">
                <div class="flex-1 h-px bg-zinc-200 dark:bg-zinc-700"></div>
                <span class="text-[10px] uppercase tracking-widest text-zinc-400">or enter manually</span>
                <div class="flex-1 h-px bg-zinc-200 dark:bg-zinc-700"></div>
            </div>
            <div class="flex gap-2 max-w-xs mx-auto">
                <label for="csManualInput" class="sr-only">Container ID</label>
                <input type="text" id="csManualInput" placeholder="e.g. MSCU4417820"
                    class="flex-1 border border-zinc-200 dark:border-zinc-700 rounded-lg px-2.5 py-1.5 text-xs uppercase dark:bg-zinc-800 dark:text-zinc-100">
                <button type="button" id="csManualLookupBtn"
                    class="px-3 py-1.5 text-xs rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Look Up</button>
            </div>

            <div id="csError" class="hidden mt-3 max-w-xs mx-auto rounded-lg px-3 py-2 text-xs bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400"></div>
        </div>

        {{-- Confirm view: shown once a container resolves, before it's actually assigned --}}
        <div id="csConfirmView" class="hidden text-center">
            <div class="mx-auto w-12 h-12 rounded-full bg-orange-50 dark:bg-orange-950/30 text-orange-500 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375C2.754 3.75 2.25 4.254 2.25 4.875v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </div>
            <p class="text-lg font-bold text-zinc-800 dark:text-zinc-100" id="csContainerNo">-</p>
            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1" id="csVariantLabel">-</p>

            <div id="csWarning" class="hidden mt-3 rounded-lg border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/20 px-3 py-2 text-xs text-amber-700 dark:text-amber-400 text-left"></div>

            <div class="flex justify-center gap-2 mt-5">
                <button type="button" id="csCancelBtn"
                    class="px-4 py-1.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-800">Scan Different Container</button>
                <button type="button" id="csConfirmBtn"
                    class="px-4 py-1.5 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-medium">Confirm Assign</button>
            </div>
        </div>

        {{-- All-done view: nothing left to assign on this booking --}}
        <div id="csDoneView" class="hidden text-center py-4">
            <div class="mx-auto w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">All containers assigned</p>
            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">Every slot on this booking now has a container.</p>
            <button type="button" id="csDoneCloseBtn" class="modal-close mt-4 px-4 py-1.5 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Done</button>
        </div>
    </div>
</x-modal>

<script>
    if (window.initContainerAssignmentPage) window.initContainerAssignmentPage();
</script>
