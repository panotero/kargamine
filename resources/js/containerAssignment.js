import jsQR from "jsqr";

// Bootstrapped by a one-line inline <script> in containerAssignment.blade.php,
// since pages are injected into #content via AJAX (see navmenu.js
// loadPage()) and this module is bundled globally rather than fetched with
// the page fragment - a plain top-level IIFE only ever runs once, at
// initial app boot, before #containerAssignmentPage exists in the DOM. See
// resources/js/pierCheckin.js for the scan/manual pattern this mirrors.
window.initContainerAssignmentPage = function initContainerAssignmentPage() {
  const page = document.getElementById("containerAssignmentPage");
  if (!page) return;

  let table = null;
  let activeStatus = "all";
  let currentBookingUuid = null;
  let lastBookingPayload = null;

  function money(v) {
    return Number(v ?? 0).toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
  }

  function routeSummary(r) {
    const lines = r.lines ?? [];
    if (!lines.length) return "-";
    const first = lines[0];
    const label = `${first.origin_port ? (first.origin_port.location?.name ?? "-") + " - " + first.origin_port.name : "-"} &rarr; ${first.destination_port ? (first.destination_port.location?.name ?? "-") + " - " + first.destination_port.name : "-"}`;
    const sameRoute = lines.every(
      (l) => l.origin_port_id === first.origin_port_id && l.destination_port_id === first.destination_port_id,
    );
    return sameRoute ? label : `${label} +${lines.length - 1} more`;
  }

  function assignmentProgress(r) {
    const units = (r.lines ?? []).flatMap((l) => l.container_units ?? []);
    const assigned = units.filter((u) => u.container_asset_id).length;
    const total = units.length;

    if (total === 0) return "-";

    const complete = assigned === total;
    const badgeClasses = complete ? "bg-emerald-50 text-emerald-700" : "bg-amber-50 text-amber-700";

    return `<span class="inline-flex items-center rounded-full ${badgeClasses} px-2 py-0.5 text-xs font-medium">${assigned} / ${total} assigned</span>`;
  }

  function containerTypeSummary(r) {
    const lines = r.lines ?? [];
    if (!lines.length) return "-";
    const labels = [...new Set(lines.map((l) => l.container?.name ?? l.container_variant?.name ?? "Container"))];
    return labels.join(", ");
  }

  function renderTable() {
    const thead = [
      { title: "Code", key: "code", render: (r) => r.code ?? "-" },
      { title: "Client", key: "client.company_name", render: (r) => r.client?.company_name ?? "-" },
      { title: "Route", key: "lines", render: routeSummary },
      { title: "Container Type", key: "lines", render: containerTypeSummary },
      { title: "Assignment", key: "lines", render: assignmentProgress },
      { title: "Booking Date", key: "booking_date" },
      { title: "Grand Total", key: "grand_total_snapshot", render: (r) => money(r.grand_total_snapshot) },
    ];

    const rendered = renderRemoteTable({
      url: "/api/container-assignment",
      tableId: "tableContainerAssignment",
      afterRenderFunction: handleRowClick,
      thead,
    });

    // renderRemoteTable's shared header builder hardcodes an orange fill
    // (matches most other tables in the app); this page's approved mockup
    // uses a plain muted-uppercase header instead, so override just here
    // rather than changing the shared component for every other page.
    document.querySelectorAll("#tableContainerAssignment .table-header th").forEach((th) => {
      th.className =
        "px-3 py-2 text-left text-[10.5px] font-bold uppercase tracking-wide text-zinc-400 dark:text-zinc-500 border-b border-zinc-200 dark:border-zinc-700 whitespace-nowrap";
    });

    return rendered;
  }

  function handleRowClick(row) {
    row.addEventListener("click", function () {
      const data = JSON.parse(row.dataset.row);
      openAssignmentModal(data);
    });
  }

  async function loadCounts() {
    const response = await apiCall({ mode: "GET", url: "/api/container-assignment" });
    if (!response.success) return;
    document.getElementById("countAll").textContent = response.status_counts.all;
    document.getElementById("countNeedsAssignment").textContent = response.status_counts.needs_assignment;
    document.getElementById("countFullyAssigned").textContent = response.status_counts.fully_assigned;
  }

  const STATUS_BTN_ACTIVE = ["border-orange-500", "ring-1", "ring-orange-500"];
  const STATUS_BTN_INACTIVE = ["border-zinc-200", "dark:border-zinc-700"];

  document.querySelectorAll(".caStatusBtn").forEach((btn) => {
    btn.addEventListener("click", function () {
      document.querySelectorAll(".caStatusBtn").forEach((b) => {
        b.classList.remove(...STATUS_BTN_ACTIVE);
        b.classList.add(...STATUS_BTN_INACTIVE);
      });
      this.classList.remove(...STATUS_BTN_INACTIVE);
      this.classList.add(...STATUS_BTN_ACTIVE);
      activeStatus = this.dataset.status;
      table.setFilter("status", activeStatus);
    });
  });

  // -----------------------------------------------------------------
  // Booking modal - one row per BookingContainerUnit slot. "Assign" opens
  // a small dropdown anchored to that row's button (Scan Container QR /
  // Enter Container ID); either opens the scan/confirm modal below.
  // -----------------------------------------------------------------
  const QR_ICON =
    '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7V5a1 1 0 0 1 1-1h2M20 7V5a1 1 0 0 0-1-1h-2M4 17v2a1 1 0 0 0 1 1h2M20 17v2a1 1 0 0 0-1 1h-2M8 8h1v1H8zM15 8h1v1h-1zM8 15h1v1H8zM12 8v2M12 14v2M15 15h2v2h-2z" /></svg>';
  const KEYBOARD_ICON =
    '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10" /></svg>';

  function unitLabelFor(unit) {
    return `Unit #${unit.unit_index}${unit.gate_pass_code ? " (" + unit.gate_pass_code + ")" : ""}`;
  }

  function unitRowHtml(line, unit) {
    const route = `${line.origin_port ? (line.origin_port.location?.name ?? "-") + " - " + line.origin_port.name : "-"} &rarr; ${line.destination_port ? (line.destination_port.location?.name ?? "-") + " - " + line.destination_port.name : "-"}`;
    const containerLabel = line.container?.name ?? line.container_variant?.name ?? "-";
    const unitLabel = unitLabelFor(unit);
    const meta = `${containerLabel} &middot; Unit #${unit.unit_index}${unit.gate_pass_code ? " &middot; " + unit.gate_pass_code : ""}`;

    const actionArea = unit.container_asset
      ? `<span class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/30 px-2.5 py-1 text-xs font-bold text-emerald-700 dark:text-emerald-400">&check; ${unit.container_asset.container_no}</span>
         <button type="button" class="ca-unassign-btn px-2.5 py-1 text-xs rounded-lg border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30" data-unit-id="${unit.id}">Unassign</button>`
      : `<div class="relative ca-chooser-wrap">
            <button type="button" class="ca-assign-toggle-btn px-2.5 py-1 text-xs rounded-lg border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-800" data-unit-id="${unit.id}" data-unit-label="${unitLabel}">Assign</button>
            <div class="ca-chooser hidden absolute right-0 top-full mt-1.5 z-20 w-56 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-lg p-1.5 text-left">
                <button type="button" class="ca-choose-scan-btn w-full flex items-center gap-2.5 rounded-lg px-2.5 py-2 hover:bg-orange-50 dark:hover:bg-orange-950/20 text-left" data-unit-id="${unit.id}" data-unit-label="${unitLabel}">
                    <span class="w-8 h-8 shrink-0 rounded-lg bg-orange-50 dark:bg-orange-950/30 text-orange-600 dark:text-orange-400 flex items-center justify-center">${QR_ICON}</span>
                    <span>
                        <span class="block text-xs font-semibold text-zinc-800 dark:text-zinc-100">Scan Container QR</span>
                        <span class="block text-[10.5px] text-zinc-400 dark:text-zinc-500">Use the device camera</span>
                    </span>
                </button>
                <button type="button" class="ca-choose-manual-btn w-full flex items-center gap-2.5 rounded-lg px-2.5 py-2 hover:bg-orange-50 dark:hover:bg-orange-950/20 text-left" data-unit-id="${unit.id}" data-unit-label="${unitLabel}">
                    <span class="w-8 h-8 shrink-0 rounded-lg bg-orange-50 dark:bg-orange-950/30 text-orange-600 dark:text-orange-400 flex items-center justify-center">${KEYBOARD_ICON}</span>
                    <span>
                        <span class="block text-xs font-semibold text-zinc-800 dark:text-zinc-100">Enter Container ID</span>
                        <span class="block text-[10.5px] text-zinc-400 dark:text-zinc-500">Type the number manually</span>
                    </span>
                </button>
            </div>
         </div>`;

    return `
        <div class="unit-slot-card flex items-center justify-between gap-3 rounded-lg border border-zinc-200 dark:border-zinc-700 px-3.5 py-3" data-unit-id="${unit.id}" data-line-id="${line.id}">
            <div class="min-w-0">
                <p class="text-xs font-semibold text-zinc-800 dark:text-zinc-100 truncate">${route}</p>
                <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-0.5">${meta}</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">${actionArea}</div>
        </div>`;
  }

  function closeAllChoosers() {
    document.querySelectorAll(".ca-chooser").forEach((el) => el.classList.add("hidden"));
  }

  document.addEventListener("click", (e) => {
    if (!e.target.closest(".ca-chooser-wrap")) closeAllChoosers();
  });

  function renderModalBody(booking) {
    lastBookingPayload = booking;

    const rows = (booking.lines ?? []).flatMap((line) =>
      (line.container_units ?? []).map((unit) => unitRowHtml(line, unit)),
    );

    document.getElementById("caUnitsBody").innerHTML = rows.length
      ? rows.join("")
      : '<p class="text-center text-sm text-zinc-400 py-6">No cargo units on this booking.</p>';

    const units = (booking.lines ?? []).flatMap((l) => l.container_units ?? []);
    const assigned = units.filter((u) => u.container_asset_id).length;
    const badge = document.getElementById("caAssignedBadge");
    const complete = units.length > 0 && assigned === units.length;
    badge.innerHTML = `<span class="inline-flex items-center rounded-full ${complete ? "bg-emerald-50 text-emerald-700" : "bg-amber-50 text-amber-700"} px-2 py-0.5 text-xs font-medium">${assigned} / ${units.length} assigned</span>`;
  }

  async function openAssignmentModal(booking) {
    currentBookingUuid = booking.uuid;
    document.getElementById("caCode").textContent = booking.code ?? "-";
    document.getElementById("caClientName").textContent = booking.client?.company_name ?? "-";
    renderModalBody(booking);
    initModal({ modalId: "containerAssignmentModal" });
  }

  async function refreshBookingList() {
    const response = await apiCall({ mode: "GET", url: `/api/bookings/${currentBookingUuid}` });
    if (!response.success) return;
    renderModalBody(response.data);
    table.reload();
    loadCounts();
  }

  function findNextUnassignedUnit() {
    const units = (lastBookingPayload?.lines ?? []).flatMap((l) => l.container_units ?? []);
    return units.find((u) => !u.container_asset_id) ?? null;
  }

  document.getElementById("caUnitsBody").addEventListener("click", async function (e) {
    const toggleBtn = e.target.closest(".ca-assign-toggle-btn");
    const chooseScanBtn = e.target.closest(".ca-choose-scan-btn");
    const chooseManualBtn = e.target.closest(".ca-choose-manual-btn");
    const unassignBtn = e.target.closest(".ca-unassign-btn");

    if (toggleBtn) {
      const chooser = toggleBtn.parentElement.querySelector(".ca-chooser");
      const willOpen = chooser.classList.contains("hidden");
      closeAllChoosers();
      if (willOpen) chooser.classList.remove("hidden");
      return;
    }

    if (chooseScanBtn) {
      closeAllChoosers();
      openScanModal(chooseScanBtn.dataset.unitId, chooseScanBtn.dataset.unitLabel, true);
      return;
    }

    if (chooseManualBtn) {
      closeAllChoosers();
      openScanModal(chooseManualBtn.dataset.unitId, chooseManualBtn.dataset.unitLabel, false);
      return;
    }

    if (unassignBtn) {
      const response = await apiCall({
        mode: "POST",
        url: `/api/container-assignment/units/${unassignBtn.dataset.unitId}/unassign`,
        button: unassignBtn,
      });

      if (!response.success) {
        showMessage({ status: "error", title: "Unable to unassign container", message: response.message ?? "" });
        return;
      }

      showMessage({ status: "success", title: "Container released" });
      refreshBookingList();
    }
  });

  document.getElementById("caAutoAssignBtn").addEventListener("click", async function () {
    const response = await apiCall({
      mode: "POST",
      url: `/api/container-assignment/bookings/${currentBookingUuid}/auto-assign`,
      button: this,
    });

    if (!response.success) {
      showMessage({ status: "error", title: "Unable to auto-assign", message: response.message ?? "" });
      return;
    }

    if (response.shortfalls?.length) {
      showMessage({
        status: "warning",
        title: "Partially assigned",
        message: "Not enough available containers for one or more lines. Assign those manually.",
      });
    } else {
      showMessage({ status: "success", title: "Containers auto-assigned" });
    }

    renderModalBody(response.data);
    table.reload();
    loadCounts();
  });

  // -----------------------------------------------------------------
  // Scan/confirm modal - scan or type a container_no, look it up (without
  // assigning yet), show its info + any type/status warning, then Confirm
  // actually assigns it. On success this advances straight to the next
  // unassigned unit on the same booking instead of closing, so the yard
  // crew can work through a whole booking without re-opening anything.
  // -----------------------------------------------------------------
  let activeUnitId = null;
  let pendingLookup = null;
  let stream = null;
  let scanning = false;
  let awaitingConfirm = false;
  let submitting = false;

  const csUnitLabel = document.getElementById("csUnitLabel");
  const csCaptureView = document.getElementById("csCaptureView");
  const csConfirmView = document.getElementById("csConfirmView");
  const csDoneView = document.getElementById("csDoneView");
  const csError = document.getElementById("csError");
  const csVideo = document.getElementById("csVideo");
  const csCanvas = document.getElementById("csCanvas");
  const csCtx = csCanvas.getContext("2d", { willReadFrequently: true });
  const csStartBtn = document.getElementById("csStartBtn");
  const csStopBtn = document.getElementById("csStopBtn");
  const csManualInput = document.getElementById("csManualInput");
  const csManualLookupBtn = document.getElementById("csManualLookupBtn");
  const csContainerNo = document.getElementById("csContainerNo");
  const csVariantLabel = document.getElementById("csVariantLabel");
  const csWarning = document.getElementById("csWarning");
  const csCancelBtn = document.getElementById("csCancelBtn");
  const csConfirmBtn = document.getElementById("csConfirmBtn");

  function showCaptureView() {
    csCaptureView.classList.remove("hidden");
    csConfirmView.classList.add("hidden");
    csDoneView.classList.add("hidden");
  }

  function showConfirmView() {
    csCaptureView.classList.add("hidden");
    csConfirmView.classList.remove("hidden");
    csDoneView.classList.add("hidden");
  }

  function showDoneView() {
    stopScanning();
    csCaptureView.classList.add("hidden");
    csConfirmView.classList.add("hidden");
    csDoneView.classList.remove("hidden");
  }

  function openScanModal(unitId, label, autoStartCamera) {
    activeUnitId = unitId;
    pendingLookup = null;
    awaitingConfirm = false;
    csUnitLabel.textContent = label;
    csManualInput.value = "";
    csError.classList.add("hidden");
    showCaptureView();
    initModal({ modalId: "containerScanModal" });
    if (autoStartCamera) startScanning();
    else csManualInput.focus();
  }

  function closeScanModal() {
    stopScanning();
    activeUnitId = null;
    pendingLookup = null;
    awaitingConfirm = false;
  }

  document.getElementById("csCloseBtn").addEventListener("click", closeScanModal);
  document.getElementById("csDoneCloseBtn").addEventListener("click", closeScanModal);

  // ---- Camera scanning (same approach as pierCheckin.js) -----------------
  async function startScanning() {
    try {
      stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
    } catch (e) {
      showMessage({
        status: "error",
        title: "Camera unavailable",
        message: "Use manual entry instead.",
      });
      return;
    }

    csVideo.srcObject = stream;
    csVideo.setAttribute("playsinline", true);
    await csVideo.play();

    scanning = true;
    csStartBtn.classList.add("hidden");
    csStopBtn.classList.remove("hidden");
    requestAnimationFrame(tick);
  }

  function stopScanning() {
    scanning = false;

    if (stream) {
      stream.getTracks().forEach((t) => t.stop());
      stream = null;
    }

    if (csStartBtn) csStartBtn.classList.remove("hidden");
    if (csStopBtn) csStopBtn.classList.add("hidden");
  }

  function tick() {
    if (!scanning) return;

    // Paused (not stopped) while a lookup result is awaiting Confirm, so
    // the camera stays warm but doesn't fire a second lookup mid-review.
    if (!awaitingConfirm && csVideo.readyState === csVideo.HAVE_ENOUGH_DATA) {
      csCanvas.width = csVideo.videoWidth;
      csCanvas.height = csVideo.videoHeight;
      csCtx.drawImage(csVideo, 0, 0, csCanvas.width, csCanvas.height);

      const imageData = csCtx.getImageData(0, 0, csCanvas.width, csCanvas.height);
      const code = jsQR(imageData.data, imageData.width, imageData.height);

      if (code && code.data) {
        awaitingConfirm = true;
        lookupContainer(code.data);
      }
    }

    requestAnimationFrame(tick);
  }

  csStartBtn.addEventListener("click", startScanning);
  csStopBtn.addEventListener("click", stopScanning);

  csManualLookupBtn.addEventListener("click", () => {
    const code = csManualInput.value.trim();
    if (!code) return;
    lookupContainer(code, csManualLookupBtn);
  });
  csManualInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      csManualLookupBtn.click();
    }
  });

  async function lookupContainer(containerNo, button) {
    if (submitting || !activeUnitId) return;
    submitting = true;

    const response = await apiCall({
      mode: "GET",
      url: `/api/container-assignment/units/${activeUnitId}/lookup?container_no=${encodeURIComponent(containerNo)}`,
      button,
    });

    submitting = false;

    if (!response.success) {
      csError.textContent = response.message ?? "Unable to look up that container.";
      csError.classList.remove("hidden");
      awaitingConfirm = false;
      return;
    }

    csError.classList.add("hidden");
    pendingLookup = response.data;
    csContainerNo.textContent = pendingLookup.container_no;
    csVariantLabel.textContent = pendingLookup.variant_label;

    const warnings = [];
    if (!pendingLookup.type_matches) {
      warnings.push("This container is not the same type/class/size this cargo line calls for.");
    }
    if (!pendingLookup.is_available) {
      warnings.push(`This container is currently "${pendingLookup.status_label}", not Available.`);
    }
    csWarning.textContent = warnings.join(" ");
    csWarning.classList.toggle("hidden", warnings.length === 0);

    showConfirmView();
  }

  csCancelBtn.addEventListener("click", () => {
    pendingLookup = null;
    awaitingConfirm = false;
    csManualInput.value = "";
    csError.classList.add("hidden");
    showCaptureView();
    if (scanning) requestAnimationFrame(tick);
  });

  csConfirmBtn.addEventListener("click", async () => {
    if (!activeUnitId || !pendingLookup) return;

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: { container_no: pendingLookup.container_no },
      url: `/api/container-assignment/units/${activeUnitId}/assign`,
      button: csConfirmBtn,
    });

    if (!response.success) {
      showMessage({ status: "error", title: "Unable to assign container", message: response.message ?? "" });
      return;
    }

    showMessage({ status: "success", title: "Container assigned" });
    await advanceAfterAssign();
  });

  async function advanceAfterAssign() {
    const response = await apiCall({ mode: "GET", url: `/api/bookings/${currentBookingUuid}` });
    if (response.success) renderModalBody(response.data);
    table.reload();
    loadCounts();

    pendingLookup = null;
    awaitingConfirm = false;

    const nextUnit = findNextUnassignedUnit();

    if (nextUnit) {
      activeUnitId = String(nextUnit.id);
      csUnitLabel.textContent = unitLabelFor(nextUnit);
      csManualInput.value = "";
      csError.classList.add("hidden");
      showCaptureView();
      if (scanning) requestAnimationFrame(tick);
    } else {
      activeUnitId = null;
      showDoneView();
    }
  }

  table = renderTable();
  table.setFilter("status", activeStatus);
  loadCounts();
};
