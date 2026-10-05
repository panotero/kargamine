// "My Requests" - the assigned CSR/Relationship Manager's own Proposal
// Request workspace, resources/views/pages/proposal_requests_mine.blade.php.
// Lists every ProposalRequest assigned to the current user (GET
// /api/proposalRequests/mine, ProposalRequestAssignmentController::mine())
// and opens the Request for Proposal wizard (components/request-proposal-modal.blade.php
// + logic_prospect_request_proposal.js) for an "assigned" row, or a
// read-only Proposal Request Summary recap for any other status. The TEMP
// CSR's "Request for Proposal" button (logic_prospect_info_modal.js) only
// mints the request now - this page is where it actually gets filled out.
// Loaded once globally (resources/js/app.js) like every other logic_*.js
// file; initProposalRequestsMinePage() is re-run on every SPA visit by this
// page's own bootstrap <script>, and self-guards by checking for the page
// root element.
(function () {
  const EMPTY = "—";

  // assigned/for_approval/approved/signed/cancelled - "pending" never
  // appears here since it means "not yet assigned to anyone." Mirrors
  // logic_proposal_requests.js's own STATUS_PILL copy (small intentional
  // duplication, same "own local cache per file" precedent used throughout
  // this codebase).
  const STATUS_PILL = {
    assigned: { label: "Assigned", classes: "bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300" },
    for_approval: { label: "For Approval", classes: "bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300" },
    approved: { label: "Approved", classes: "bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300" },
    signed: { label: "Signed", classes: "bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300" },
    cancelled: { label: "Cancelled", classes: "bg-zinc-100 dark:bg-zinc-800 text-zinc-400 dark:text-zinc-500" },
  };

  let activeStatus = "all";
  // This page's own copy of "the current prospect payload" - set whenever a
  // row is opened, exposed via window.prospectInfo so the wizard
  // (logic_prospect_request_proposal.js) can refresh itself after a save.
  // See that file's header comment: every logic_*.js file loads globally,
  // so each page must only claim window.prospectInfo while its own root
  // element is present (set inside initProposalRequestsMinePage() below,
  // never at module-top-level).
  let currentLead = null;

  function esc(v) {
    return String(v ?? "").replace(
      /[&<>"']/g,
      (c) =>
        ({
          "&": "&amp;",
          "<": "&lt;",
          ">": "&gt;",
          '"': "&quot;",
          "'": "&#39;",
        })[c],
    );
  }

  function statusPill(status) {
    const pill = STATUS_PILL[status] ?? STATUS_PILL.assigned;
    return `<span class="text-xs font-semibold px-2.5 py-1 rounded-full ${pill.classes}">${pill.label}</span>`;
  }

  // ------------------------------------------------------------------
  // COUNTS + STATUS STRIP
  // ------------------------------------------------------------------
  function updateCounts(counts) {
    document.getElementById("prmCountAll").textContent = counts?.all ?? 0;
    document.getElementById("prmCountAssigned").textContent = counts?.assigned ?? 0;
    document.getElementById("prmCountForApproval").textContent = counts?.for_approval ?? 0;
    document.getElementById("prmCountApproved").textContent = counts?.approved ?? 0;
    document.getElementById("prmCountSigned").textContent = counts?.signed ?? 0;
    document.getElementById("prmCountCancelled").textContent = counts?.cancelled ?? 0;
  }

  // ------------------------------------------------------------------
  // TABLE
  // ------------------------------------------------------------------
  function renderTable() {
    const thead = [
      { title: "RQ Code", key: "code" },
      {
        title: "Company",
        key: "prospect.company.company_name",
        render: (r) => esc(r.prospect?.company?.company_name || EMPTY),
      },
      { title: "Status", key: "status", render: (r) => statusPill(r.status) },
      {
        title: "Assigned CSR",
        key: "prospect.user.name",
        render: (r) => esc(r.prospect?.user?.name || EMPTY),
      },
      {
        title: "RM",
        key: "prospect.relationship_manager.name",
        render: (r) => esc(r.prospect?.relationship_manager?.name || EMPTY),
      },
      { title: "Created", key: "created_at", render: (r) => formatDateTime(r.created_at) },
      {
        title: "",
        key: "actions",
        render: (r) =>
          r.status === "assigned"
            ? `<button type="button" class="prm-cancel-btn text-red-500 hover:text-red-600 text-xs font-medium">Cancel</button>`
            : "",
      },
    ];

    return window.renderRemoteTable({
      url: "/api/proposalRequests/mine",
      tableId: "tableProposalRequestsMine",
      thead,
      afterRenderFunction: (row) => {
        const pr = JSON.parse(row.dataset.row);
        row.addEventListener("click", function () {
          openRow(pr);
        });
        row.querySelector(".prm-cancel-btn")?.addEventListener("click", function (e) {
          e.stopPropagation();
          cancelProposalRequestRow(pr, this);
        });
      },
      emptyMessage: () => {
        if (activeStatus === "all") return "No proposal requests assigned to you yet.";
        return `No ${(STATUS_PILL[activeStatus]?.label ?? activeStatus).toLowerCase()} requests.`;
      },
    });
  }

  async function loadRequests() {
    const response = await apiCall({
      mode: "GET",
      url: "/api/proposalRequests/mine",
    });
    if (!response.success) return;
    updateCounts(response.status_counts);
    renderTable().load(1);
  }

  // ------------------------------------------------------------------
  // OPEN A ROW - wizard (assigned) or read-only summary (everything else)
  // ------------------------------------------------------------------
  async function openRow(pr) {
    const uuid = pr.prospect?.uuid;
    if (!uuid) {
      showMessage({ status: "error", title: "This request has no prospect on file." });
      return;
    }

    const response = await apiCall({ mode: "GET", url: `/api/crm/prospects/${uuid}` });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Loading Prospect", message: response.message ?? "" });
      return;
    }
    currentLead = response.data;

    if (pr.status === "assigned") {
      window.openRequestProposalModal?.(currentLead, pr.id);
    } else {
      openProposalRequestSummary(pr);
    }
  }

  // ------------------------------------------------------------------
  // PROPOSAL REQUEST SUMMARY MODAL (read-only recap + Cancel for_approval)
  // ------------------------------------------------------------------
  function openProposalRequestSummary(pr) {
    const modal = document.getElementById("ProposalRequestSummaryModal");
    if (!modal || !currentLead) return;

    modal.querySelector("#prsmCode").textContent = pr.code ?? "";
    modal.querySelector("#prsmRecap").innerHTML =
      window.buildProposalRequestRecapHtml?.(currentLead, pr) ?? "";

    const actions = modal.querySelector("#prsmActions");
    actions.innerHTML =
      pr.status === "for_approval"
        ? `<button type="button" id="prsmCancelBtn" class="text-sm px-3 py-1.5 rounded-lg border border-red-300 dark:border-red-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 font-medium">Cancel Request</button>`
        : "";
    actions.querySelector("#prsmCancelBtn")?.addEventListener("click", function () {
      cancelProposalRequest(pr, this);
    });

    window.initModal({ modalId: "ProposalRequestSummaryModal" });
  }

  async function cancelProposalRequest(pr, button) {
    const lead = currentLead;
    if (!lead) return;

    const confirmed = await customConfirm(
      "Cancel this proposal request? This cannot be undone.",
    );
    if (!confirmed) return;

    const response = await apiCall({
      mode: "POST",
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${pr.id}/cancel`,
      button,
    });
    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Cancelling",
        message: response.message ?? "",
      });
      return;
    }

    showMessage({ status: "success", title: "Proposal Request Cancelled" });
    window.closeModal("ProposalRequestSummaryModal");
    renderTable().reload();
  }

  // Only ever shown on an "assigned" (pre-submission) row - unlike the old
  // hard-delete this never cascades, it just flips status to "cancelled"
  // via the same endpoint the Proposal Request Summary modal uses for a
  // "for_approval" row (ProspectController::cancelProposalRequest, which
  // now also accepts "assigned" in addition to "for_approval"). Doesn't
  // depend on currentLead/openRow() having run first - uses the uuid
  // already present on the row's own payload.
  async function cancelProposalRequestRow(pr, button) {
    const uuid = pr.prospect?.uuid;
    if (!uuid) return;

    const confirmed = await customConfirm(
      "Cancel this proposal request? This cannot be undone.",
    );
    if (!confirmed) return;

    const response = await apiCall({
      mode: "POST",
      url: `/api/crm/prospects/${uuid}/proposalRequests/${pr.id}/cancel`,
      button,
    });
    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Cancelling",
        message: response.message ?? "",
      });
      return;
    }

    showMessage({ status: "success", title: "Proposal Request Cancelled" });
    renderTable().reload();
  }

  // ------------------------------------------------------------------
  // PUBLIC API
  // ------------------------------------------------------------------
  function initProposalRequestsMinePage() {
    const root = document.getElementById("ProposalRequestsMinePage");
    if (!root) return; // guarded: only runs on this page

    activeStatus = "all";
    currentLead = null;

    // Page-scoped claim of window.prospectInfo - see file header comment.
    window.prospectInfo = {
      getCurrentLead: () => currentLead,
      reload: async (uuid) => {
        const response = await apiCall({ mode: "GET", url: `/api/crm/prospects/${uuid}` });
        if (response.success) currentLead = response.data;
        return response;
      },
    };

    loadRequests();

    document.querySelectorAll(".prmStatusBtn").forEach((btn) => {
      btn.addEventListener("click", function () {
        activeStatus = this.dataset.status;
        document
          .querySelectorAll(".prmStatusBtn")
          .forEach((b) => b.classList.remove("ring-2", "ring-orange-500"));
        this.classList.add("ring-2", "ring-orange-500");
        renderTable().setFilter("status", activeStatus);
      });
    });
  }
  window.initProposalRequestsMinePage = initProposalRequestsMinePage;
})();
