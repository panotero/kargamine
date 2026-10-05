// Shared list/table/detail-modal logic for the two Client Proposals pages -
// resources/views/pages/proposals.blade.php ("Approvals") and
// resources/views/pages/proposal_signed_contracts.blade.php ("Signed /
// Contracts"). Both pages call the SAME /api/clientProposals* endpoints and
// share the exact #ClientProposalModal id/body markup (Lead Info recap,
// Rates table, Additional Charges, decision info) - they only differ in
// default status-pill filter and which action buttons the modal's footer
// shows, controlled by a `window.PROPOSALS_PAGE_MODE` global ('approvals' |
// 'signed_contracts') that each page sets in its own tiny bootstrap
// <script> BEFORE calling window.initClientProposalsPage() (see the bottom
// of each .blade.php file) - default 'approvals' if unset.
//
// This file (like every other logic_*.js) is loaded once globally (see
// resources/js/app.js) - initClientProposalsPage() is what actually runs
// per SPA visit, and self-guards on #tableClientProposals (the <x-table>
// root shared by both pages) so it's a no-op everywhere else.
//
// Every DOM lookup for a button/section that only exists on ONE of the two
// pages (decision buttons vs. signed/contract buttons, some of the pill
// count spans) is null-guarded (`?.`) rather than assumed present - see
// toggle() and bindEvents() below.
(function () {
  const STATUS_LABEL = {
    1: "Pending RM Approval",
    2: "Approved",
    3: "Disapproved",
    4: "Accepted",
    5: "Rejected",
    6: "Cancelled",
    7: "Pending Manager Approval",
  };
  const STATUS_BADGE = {
    1: "bg-amber-100 text-amber-600",
    2: "bg-green-100 text-green-700",
    3: "bg-red-100 text-red-600",
    4: "bg-blue-100 text-blue-700",
    5: "bg-zinc-200 text-zinc-600",
    6: "bg-zinc-200 text-zinc-600",
    7: "bg-amber-100 text-amber-600",
  };

  let currentProposalId = null;
  let currentProposalLead = null;
  let currentProposalDetail = null;
  let activeStatusFilter = "all";

  // Create Contract modal state - only ever populated/used on the
  // Signed/Contracts page (that markup doesn't exist on Approvals), but
  // defined unconditionally here per this file's "keep it all in one
  // mode-agnostic file" brief.
  let rateOverrides = {};
  let editingRateId = null;

  function toggle(id, visible) {
    document.getElementById(id)?.classList.toggle("hidden", !visible);
  }

  async function loadProposals() {
    const response = await apiCall({
      mode: "GET",
      url: "/api/clientProposals",
    });

    if (!response.success) return;

    updateProposalCounts(response.status_counts);
    const table = renderTable();
    if (activeStatusFilter === "all") {
      table.load(1);
    } else {
      table.setFilter("status", activeStatusFilter);
    }
  }

  function updateProposalCounts(counts) {
    document.getElementById("countAll") && (document.getElementById("countAll").textContent = counts.all);
    document.getElementById("countPending") && (document.getElementById("countPending").textContent = counts.pending);
    document.getElementById("countPendingManager") && (document.getElementById("countPendingManager").textContent = counts.pending_manager ?? 0);
    document.getElementById("countApproved") && (document.getElementById("countApproved").textContent = counts.approved);
    document.getElementById("countDisapproved") && (document.getElementById("countDisapproved").textContent = counts.disapproved);
    document.getElementById("countAccepted") && (document.getElementById("countAccepted").textContent = counts.accepted);
    document.getElementById("countRejected") && (document.getElementById("countRejected").textContent = counts.rejected);
    document.getElementById("countCancelled") && (document.getElementById("countCancelled").textContent = counts.cancelled ?? 0);
    document.getElementById("countAwaitingDecision") && (document.getElementById("countAwaitingDecision").textContent = counts.awaiting_decision ?? 0);
  }

  function statusPill(status) {
    return `<span class="text-xs font-semibold px-2.5 py-1 rounded-full ${STATUS_BADGE[status] ?? 'bg-zinc-100 text-zinc-500'}">${STATUS_LABEL[status] ?? 'Unknown'}</span>`;
  }

  function renderTable() {
    const thead = [
      { title: "Code", key: "code" },
      { title: "Client", key: "client.company_name", render: (r) => r.client?.company_name ?? "-" },
      { title: "Customer Code", key: "client.customer_code", render: (r) => r.client?.customer_code ?? "-" },
      { title: "Status", key: "status", render: (r) => statusPill(r.status) },
      { title: "Decided By", key: "decided_by.name", render: (r) => r.decided_by?.name ?? "-" },
      { title: "Created", key: "created_at", render: (r) => formatDateTime(r.created_at) },
    ];

    return renderRemoteTable({
      url: "/api/clientProposals",
      tableId: "tableClientProposals",
      afterRenderFunction: (row) =>
        row.addEventListener("click", function () {
          openProposalModal(JSON.parse(row.dataset.row).id);
        }),
      thead: thead,
      emptyMessage: () => {
        if (activeStatusFilter === "all") return "No proposals yet.";
        const label = (STATUS_LABEL[activeStatusFilter] ?? "").toLowerCase();
        return `No ${label} proposals.`;
      },
    });
  }

  function markActivePill(value) {
    document.querySelectorAll(".proposalStatusBtn").forEach((b) => b.classList.remove("ring-2", "ring-orange-500"));
    document.querySelector(`.proposalStatusBtn[data-status="${value}"]`)?.classList.add("ring-2", "ring-orange-500");
  }

  function bindPillClicks() {
    document.querySelectorAll(".proposalStatusBtn").forEach((btn) => {
      btn.addEventListener("click", function () {
        activeStatusFilter = this.dataset.status;
        markActivePill(activeStatusFilter);
        renderTable().setFilter("status", activeStatusFilter);
      });
    });
  }

  // Exposed globally so notificationController.js's data.modal_fn hook
  // (fired after navigating here from a proposal notification) can call it
  // directly by name - works from either page since both share the same
  // #ClientProposalModal id/body.
  window.openProposalModal = openProposalModal;

  async function openProposalModal(id) {
    const response = await apiCall({
      mode: "GET",
      url: `/api/clientProposals/${id}`,
    });
    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error",
        message: "Unable to load this proposal.",
      });
      return;
    }

    const p = response.data;
    currentProposalId = p.id;
    currentProposalLead = p.prospect ?? null;
    currentProposalDetail = p;

    document.getElementById("cpmCode").textContent = p.code;
    document.getElementById("cpmClientName").textContent =
      `${p.client?.company_name ?? "-"} (${p.client?.customer_code ?? "-"})`;
    document.getElementById("cpmStatusBadge").innerHTML = statusPill(p.status);

    // Client-scoped proposals don't carry prospect_id directly - fall back
    // to the client's originating lead, same as ClientProposal::ownerUser().
    const leadInfo = p.prospect ?? p.client?.prospect ?? null;
    const leadInfoEl = document.getElementById("cpmLeadInfo");
    if (leadInfo) {
      document.getElementById("cpmLeadContact").textContent =
        leadInfo.position ? `${leadInfo.contact_name} (${leadInfo.position})` : (leadInfo.contact_name ?? "-");
      document.getElementById("cpmLeadCompany").textContent = leadInfo.company?.company_name ?? "-";
      document.getElementById("cpmLeadMobile").textContent = leadInfo.mobile ?? "-";
      document.getElementById("cpmLeadEmail").textContent = leadInfo.email ?? "-";
      document.getElementById("cpmLeadSource").textContent = leadInfo.source ?? "-";
      document.getElementById("cpmLeadAssignedTo").textContent = leadInfo.user?.name ?? "-";
      document.getElementById("cpmLeadInfoTag").classList.toggle("hidden", Boolean(p.client_id));
      leadInfoEl.classList.remove("hidden");
    } else {
      leadInfoEl.classList.add("hidden");
    }

    const decisionInfo = document.getElementById("cpmDecisionInfo");
    if (p.decided_by) {
      decisionInfo.textContent =
        `${STATUS_LABEL[p.status]} by ${p.decided_by.name} on ${formatDateTime(p.decided_at)}${p.decision_remarks ? " — " + p.decision_remarks : ""}`;
      decisionInfo.classList.remove("hidden");
    } else {
      decisionInfo.classList.add("hidden");
    }

    document.getElementById("cpmRatesBody").innerHTML = p.rates.map((r) => {
      const ancillary = r.ancillary_services ?? [];
      const ancillaryRow = ancillary.length ? `
                <tr class="border-t">
                    <td colspan="6" class="py-1 text-[11px] text-zinc-500">
                        <span class="font-semibold">Ancillary Services:</span>
                        ${ancillary.map((s) => `${s.required_service ?? "-"}${s.quantity ? " x" + s.quantity : ""}${s.unit ? " " + s.unit : ""}${s.location ? " (" + s.location + ")" : ""}`).join("; ")}
                    </td>
                </tr>` : "";

      return `
            <tr class="border-t">
                <td class="py-1.5">
                    ${r.origin_port ? (r.origin_port.location?.name ?? "-") + " - " + r.origin_port.name : "-"}${r.origin_pickup_area ? ` <span class="text-zinc-400">(Pickup: ${r.origin_pickup_area.area_name})</span>` : ""}
                    → ${r.destination_port ? (r.destination_port.location?.name ?? "-") + " - " + r.destination_port.name : "-"}${r.destination_pickup_area ? ` <span class="text-zinc-400">(Drop-off: ${r.destination_pickup_area.area_name})</span>` : ""}
                </td>
                <td class="py-1.5">${r.container?.name ?? "-"} / ${r.container_class?.class ?? "-"} / ${r.container_size?.size ?? "-"}</td>
                <td class="py-1.5 text-right">${r.min_van_qty ?? "-"}</td>
                <td class="py-1.5 text-right">${Number(r.base_rate).toLocaleString()}</td>
                <td class="py-1.5 text-right">${adjustmentDisplay(r.discount_type, r.discount_value)}</td>
                <td class="py-1.5 text-right font-semibold">${Number(r.final_rate).toLocaleString()}</td>
            </tr>${ancillaryRow}`;
    }).join("");

    const additionalCharges = [
      ["include_special_charges", "Special Charges"],
      ["include_port_charges", "Port Charges"],
      ["include_handling_fee", "Handling Fee"],
      ["include_general_charges", "General Charges"],
    ].filter(([field]) => p[field]);
    const chargesEl = document.getElementById("cpmAdditionalCharges");
    chargesEl.classList.toggle("hidden", additionalCharges.length === 0);
    chargesEl.querySelector("div").innerHTML = additionalCharges.map(([, label]) => `
            <span class="text-[11px] font-semibold px-2 py-1 rounded-full bg-orange-100 text-orange-700 dark:bg-orange-950/40 dark:text-orange-400">${label}</span>
        `).join("");

    // Buttons are permission-gated server-side (p.can_approve / p.can_reject)
    // AND status-gated here - both checks matter, one is authorization, the
    // other is workflow state. Every toggle() below is null-safe, since a
    // given button only exists in the DOM on one of the two pages (see
    // PROPOSALS_PAGE_MODE branching in each page's Blade file) - toggling a
    // missing id is simply a no-op.
    // Status 1 = Pending RM Approval, 7 = Pending Manager Approval - both
    // are "pending" stages now, each gated by its own permission
    // (p.can_approve is stage-aware server-side, see ClientProposal::canBeApprovedBy()).
    toggle("cpmApproveBtn", [1, 7].includes(p.status) && p.can_approve);
    toggle("cpmDisapproveBtn", [1, 7].includes(p.status) && p.can_approve);
    toggle("cpmRejectBtn", [1, 2, 7].includes(p.status) && p.can_reject);
    toggle("cpmCancelBtn", [1, 7].includes(p.status) && p.can_cancel);
    toggle("cpmSignedSection", p.status === 2 && p.can_upload_signed);
    toggle("cpmDownloadLink", [2, 4].includes(p.status));
    // A lead-scoped accepted proposal (no client yet) has nowhere to attach
    // a contract - it needs to become a client first.
    const hasActiveContract = Boolean(p.active_contract);
    toggle("cpmCreateContractBtn", p.status === 4 && Boolean(p.client_id) && !hasActiveContract);
    toggle("cpmViewContractBtn", hasActiveContract);
    toggle("cpmCreateClientMasterBtn", p.status === 4 && !p.client_id && Boolean(p.prospect_id));

    const downloadLink = document.getElementById("cpmDownloadLink");
    if (downloadLink && [2, 4].includes(p.status)) {
      downloadLink.href = `/api/clientProposals/${p.id}/pdf`;
    }

    initModal({
      modalId: "ClientProposalModal",
    });
  }

  async function decisionAction(action, successMessage, button) {
    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: {},
      url: `/api/clientProposals/${currentProposalId}/${action}`,
      button,
    });

    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error",
        message: response.message ?? "Action failed.",
      });
      return;
    }

    showMessage({
      status: "success",
      title: successMessage,
    });
    closemodals();
    renderTable().reload();
  }

  // -----------------------------------------------------------------
  // Create Contract modal - rate lines are copied from the proposal
  // read-only by default; a pencil unlocks a row for a confirmed edit.
  // Only reachable from the Signed/Contracts page's #cpmCreateContractBtn
  // (see bindEvents()) - defined unconditionally here per this file's
  // single mode-agnostic-file brief.
  // -----------------------------------------------------------------
  function ccOriginalValues(rate) {
    return {
      min_van_qty: rate.min_van_qty ?? null,
      base_rate: Number(rate.base_rate),
      discount_type: rate.discount_type ?? null,
      discount_value: Number(rate.discount_value ?? 0),
      final_rate: Number(rate.final_rate),
    };
  }

  function ccCurrentValues(rate) {
    return rateOverrides[rate.id] ? { ...rateOverrides[rate.id] } : ccOriginalValues(rate);
  }

  function adjustmentDisplay(type, value) {
    if (!type) return "-";
    const labels = {
      percentage: "Discount",
      fixed: "Discount",
      increase_percentage: "Increase",
      increase_fixed: "Increase",
    };
    const isPercent = type === "percentage" || type === "increase_percentage";
    const amount = isPercent ? `${value}%` : Number(value).toLocaleString();
    return `${labels[type] ?? type} ${amount}`;
  }

  function ccDiscountDisplay(values) {
    return adjustmentDisplay(values.discount_type, values.discount_value);
  }

  function describeRateChange(rate) {
    const original = ccOriginalValues(rate);
    const current = rateOverrides[rate.id];
    if (!current) return "";
    const fieldLabels = { min_van_qty: "Min Qty", base_rate: "Base Rate", discount_type: "Adjustment Type", discount_value: "Adjustment Value", final_rate: "Final Rate" };
    const diffs = [];
    for (const key of Object.keys(fieldLabels)) {
      if (original[key] !== current[key]) {
        diffs.push(`${fieldLabels[key]}: ${original[key] ?? "-"} → ${current[key] ?? "-"}`);
      }
    }
    return diffs.join(", ");
  }

  function renderRateRow(rate, editing) {
    const lane = `${rate.origin_port ? (rate.origin_port.location?.name ?? "-") + " - " + rate.origin_port.name : "-"} → ${rate.destination_port ? (rate.destination_port.location?.name ?? "-") + " - " + rate.destination_port.name : "-"}`;
    const variant = `${rate.container?.name ?? "-"} / ${rate.container_class?.class ?? "-"} / ${rate.container_size?.size ?? "-"}`;
    const values = ccCurrentValues(rate);
    const edited = Boolean(rateOverrides[rate.id]);

    if (!editing) {
      return `
                <tr data-rate-id="${rate.id}" class="border-l-2 border-transparent hover:border-orange-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                    <td class="py-1.5 px-2">${lane}</td>
                    <td class="py-1.5 px-2">${variant}</td>
                    <td class="py-1.5 px-2 text-right">${values.min_van_qty ?? "-"}</td>
                    <td class="py-1.5 px-2 text-right">${Number(values.base_rate).toLocaleString()}</td>
                    <td class="py-1.5 px-2 text-right">${ccDiscountDisplay(values)}</td>
                    <td class="py-1.5 px-2 text-right font-semibold">
                        ${Number(values.final_rate).toLocaleString()}
                        ${edited ? `<span class="ml-1 text-[10px] font-normal text-amber-600 cursor-help" title="${describeRateChange(rate).replace(/"/g, "&quot;")}">(edited)</span>` : ""}
                    </td>
                    <td class="py-1.5 px-2 text-right">
                        <button type="button" class="cc-edit-btn text-base leading-none px-1.5 py-1 rounded text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800" title="Edit this rate">✎</button>
                    </td>
                </tr>`;
    }

    return `
            <tr data-rate-id="${rate.id}">
                <td class="py-1.5 px-2">${lane}</td>
                <td class="py-1.5 px-2">${variant}</td>
                <td class="py-1.5 px-2">
                    <input type="number" min="1" step="1" placeholder="None" class="cc-input-minqty w-16 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${values.min_van_qty ?? ""}">
                </td>
                <td class="py-1.5 px-2">
                    <input type="text" inputmode="decimal" class="cc-input-base currency-input w-24 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${formatCurrencyDisplay(values.base_rate)}">
                </td>
                <td class="py-1.5 px-2">
                    <div class="flex items-center gap-1 justify-end">
                        <select class="cc-input-disctype border rounded px-1 py-1 text-xs dark:text-zinc-900">
                            <option value="" ${!values.discount_type ? "selected" : ""}>None</option>
                            <option value="percentage" ${values.discount_type === "percentage" ? "selected" : ""}>Discount (%)</option>
                            <option value="fixed" ${values.discount_type === "fixed" ? "selected" : ""}>Discount (Fixed)</option>
                            <option value="increase_percentage" ${values.discount_type === "increase_percentage" ? "selected" : ""}>Increase (%)</option>
                            <option value="increase_fixed" ${values.discount_type === "increase_fixed" ? "selected" : ""}>Increase (Fixed)</option>
                        </select>
                        <input type="text" inputmode="decimal" class="cc-input-discval currency-input w-16 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${formatCurrencyDisplay(values.discount_value)}">
                    </div>
                </td>
                <td class="py-1.5 px-2">
                    <input type="text" inputmode="decimal" class="cc-input-final currency-input w-24 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${formatCurrencyDisplay(values.final_rate)}">
                </td>
                <td class="py-1.5 px-2 text-right whitespace-nowrap">
                    <div class="flex items-center justify-end gap-1.5">
                        <button type="button" class="cc-apply-btn text-base leading-none px-1.5 py-1 rounded text-green-600 hover:text-green-700 hover:bg-green-50 dark:hover:bg-green-950/40" title="Apply">✓</button>
                        <button type="button" class="cc-cancel-btn text-base leading-none px-1.5 py-1 rounded text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800" title="Cancel">✕</button>
                    </div>
                </td>
            </tr>`;
  }

  function renderContractRatesTable() {
    const body = document.getElementById("ccRatesBody");
    if (!body) return;
    body.innerHTML = (currentProposalDetail?.rates ?? [])
      .map((r) => renderRateRow(r, editingRateId === r.id))
      .join("");
  }

  // Base rate / discount type / discount value all feed Final Rate, same as
  // the Add Proposal / Add Contract row builders elsewhere - without this,
  // editing the discount changed nothing the user could see or save, since
  // Final Rate is what actually gets sent as the override.
  function recomputeCcFinalRate(row) {
    const base = parseFloat(parseCurrencyValue(row.querySelector(".cc-input-base").value)) || 0;
    const type = row.querySelector(".cc-input-disctype").value;
    const value = parseFloat(parseCurrencyValue(row.querySelector(".cc-input-discval").value)) || 0;
    const finalInput = row.querySelector(".cc-input-final");

    let final = base;
    if (type === "percentage") final = Math.max(0, base - (base * value / 100));
    if (type === "fixed") final = Math.max(0, base - value);
    if (type === "increase_percentage") final = base + (base * value / 100);
    if (type === "increase_fixed") final = base + value;

    finalInput.value = formatCurrencyDisplay(final.toFixed(2));
  }

  // -----------------------------------------------------------------
  // Event bindings - re-run on every SPA visit (initClientProposalsPage()),
  // against whichever subset of buttons the CURRENT page's Blade markup
  // actually renders. Every lookup is null-guarded with `?.` since roughly
  // half of these ids only exist on one of the two pages.
  // -----------------------------------------------------------------
  function bindEvents() {
    document.getElementById("cpmApproveBtn")?.addEventListener("click", function () {
      decisionAction("approve", "Proposal approved", this);
    });
    document.getElementById("cpmDisapproveBtn")?.addEventListener("click", function () {
      decisionAction("disapprove", "Proposal disapproved", this);
    });
    document.getElementById("cpmRejectBtn")?.addEventListener("click", async function () {
      const confirmed = await customConfirm("Reject this proposal? This cannot be undone.");
      if (confirmed) decisionAction("reject", "Proposal rejected", this);
    });
    document.getElementById("cpmCancelBtn")?.addEventListener("click", async function () {
      const confirmed = await customConfirm("Cancel this pending proposal? This cannot be undone.");
      if (confirmed) decisionAction("cancel", "Proposal cancelled", this);
    });

    document.getElementById("cpmUploadSignedBtn")?.addEventListener("click", async function () {
      const fileInput = document.getElementById("cpmSignedFile");
      if (!fileInput.files.length) {
        showMessage({
          status: "error",
          title: "Select a file first",
        });
        return;
      }

      const formData = new FormData();
      formData.append("signed_document", fileInput.files[0]);

      const response = await apiCall({
        mode: "POST",
        isJson: false,
        payload: formData,
        url: `/api/clientProposals/${currentProposalId}/attachSigned`,
        button: this,
      });

      if (!response.success) {
        showMessage({
          status: "error",
          title: "Error",
          message: response.message ?? "Upload failed.",
        });
        return;
      }

      showMessage({
        status: "success",
        title: "Signed document uploaded — proposal accepted!",
      });
      closemodals();
      renderTable().reload();
    });

    document.getElementById("cpmCreateContractBtn")?.addEventListener("click", function () {
      rateOverrides = {};
      editingRateId = null;

      document.getElementById("ccProposalCode").textContent = currentProposalDetail.code;
      document.getElementById("ccClientName").textContent = currentProposalDetail.client?.company_name ?? "-";
      document.getElementById("ccValidFrom").value = "";
      document.getElementById("ccValidTo").value = "";
      document.getElementById("ccSignedDate").value = "";
      renderContractRatesTable();

      initModal({ modalId: "createContractModal" });
    });

    document.getElementById("cpmViewContractBtn")?.addEventListener("click", function () {
      window.contractsOpenId = currentProposalDetail?.active_contract?.id ?? null;
      closemodals();
      loadPage({ title: "Contracts", link: "/page_contracts" });
    });

    const ccRatesBody = document.getElementById("ccRatesBody");
    ccRatesBody?.addEventListener("input", function (e) {
      if (e.target.matches(".cc-input-base, .cc-input-discval")) {
        recomputeCcFinalRate(e.target.closest("tr"));
      }
    });

    ccRatesBody?.addEventListener("change", function (e) {
      if (e.target.matches(".cc-input-disctype")) {
        recomputeCcFinalRate(e.target.closest("tr"));
      }
    });

    ccRatesBody?.addEventListener("click", async function (e) {
      const row = e.target.closest("tr");
      if (!row) return;

      const rateId = Number(row.dataset.rateId);
      const rate = (currentProposalDetail?.rates ?? []).find((r) => r.id === rateId);
      if (!rate) return;

      if (e.target.closest(".cc-edit-btn")) {
        editingRateId = rateId;
        renderContractRatesTable();
        return;
      }

      if (e.target.closest(".cc-cancel-btn")) {
        editingRateId = null;
        renderContractRatesTable();
        return;
      }

      if (e.target.closest(".cc-apply-btn")) {
        const minQtyRaw = row.querySelector(".cc-input-minqty").value;
        const newValues = {
          min_van_qty: minQtyRaw === "" ? null : Number(minQtyRaw),
          base_rate: Number(parseCurrencyValue(row.querySelector(".cc-input-base").value)),
          discount_type: row.querySelector(".cc-input-disctype").value || null,
          discount_value: Number(parseCurrencyValue(row.querySelector(".cc-input-discval").value) || 0),
          final_rate: Number(parseCurrencyValue(row.querySelector(".cc-input-final").value)),
        };
        const original = ccOriginalValues(rate);
        const changed = newValues.min_van_qty !== original.min_van_qty ||
          newValues.base_rate !== original.base_rate ||
          newValues.discount_type !== original.discount_type ||
          newValues.discount_value !== original.discount_value ||
          newValues.final_rate !== original.final_rate;

        if (changed) {
          rateOverrides[rateId] = newValues;
        } else {
          delete rateOverrides[rateId];
        }

        editingRateId = null;
        renderContractRatesTable();
      }
    });

    document.getElementById("ccSaveBtn")?.addEventListener("click", async function () {
      const validFrom = document.getElementById("ccValidFrom").value;
      const validTo = document.getElementById("ccValidTo").value;

      if (!validFrom || !validTo) {
        showMessage({ status: "error", title: "Valid From and Valid To are required" });
        return;
      }

      const payload = {
        signed_date: document.getElementById("ccSignedDate").value || null,
        valid_from: validFrom,
        valid_to: validTo,
        rate_overrides: rateOverrides,
      };

      const response = await apiCall({
        mode: "POST",
        isJson: true,
        payload,
        url: `/api/clientProposals/${currentProposalDetail.id}/contract`,
        button: this,
      });

      if (!response.success) {
        showMessage({
          status: "error",
          title: "Unable to create contract",
          message: response.message ?? "",
        });
        return;
      }

      showMessage({ status: "success", title: "Contract created" });
      closemodals();
      await openProposalModal(currentProposalDetail.id);
      renderTable().reload();
    });

    document.getElementById("cpmCreateClientMasterBtn")?.addEventListener("click", async function () {
      const lead = currentProposalLead;
      if (!lead) return;

      const codeResponse = await apiCall({
        mode: "GET",
        url: `/api/crm/prospects/${lead.uuid}/customerCode`,
        button: this,
      });

      if (!codeResponse.success) {
        showMessage({
          status: "error",
          title: "Error",
          message: "Unable to generate a customer code for this lead.",
        });
        return;
      }

      window.clientMasterFormUuid = null;
      window.clientMasterFormLeadId = lead.id;
      window.clientMasterFormPrefill = {
        customer_code: codeResponse.data.customer_code,
        company_name: lead.company?.company_name ?? "",
        industry: lead.company?.type_of_business ?? "",
        addresses: lead.addresses ?? [],
      };

      closemodals();
      loadPage({
        title: "New Client Master Data",
        link: "/page_clientMasterForm",
      });
    });
  }

  // ------------------------------------------------------------------
  // PUBLIC API
  // ------------------------------------------------------------------
  // Re-run on every SPA visit to either page (see each page's own tiny
  // bootstrap <script>) - self-guards on #tableClientProposals, the
  // <x-table> root shared by both.
  function initClientProposalsPage() {
    const table = document.getElementById("tableClientProposals");
    if (!table) return; // guarded: only runs on these two pages

    const mode = window.PROPOSALS_PAGE_MODE || "approvals";

    currentProposalId = null;
    currentProposalLead = null;
    currentProposalDetail = null;
    rateOverrides = {};
    editingRateId = null;
    activeStatusFilter = mode === "signed_contracts" ? "2" : "all";

    bindPillClicks();
    if (mode === "signed_contracts") markActivePill(activeStatusFilter);

    loadProposals();
    bindEvents();
  }
  window.initClientProposalsPage = initClientProposalsPage;
})();
