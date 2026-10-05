// Management's Proposal Request assignment queue - resources/views/pages/proposal_requests.blade.php.
// Lists every ProposalRequest (GET /api/proposalRequests) and lets
// Management assign the prospect's CSR (assigned_to) / Relationship
// Manager (relationship_manager_id) via <x-side-modal id="assignOwnersModal">
// (POST /api/crm/prospects/{uuid}/assignment) - see
// ProposalRequestAssignmentController::index() / ProspectController::assignOwners().
// Loaded once globally (resources/js/app.js) like every other logic_*.js
// file; initProposalRequestsPage() is re-run on every SPA visit by this
// page's own bootstrap <script>, and self-guards by checking for the page
// root element.
(function () {
  const EMPTY = "—";

  // pending/assigned/for_approval/approved/signed/cancelled - mirrors
  // logic_prospect_info_modal.js's own STATUS_PILL copy (small intentional
  // duplication, same "own local cache per file" precedent used throughout
  // this codebase).
  const STATUS_PILL = {
    pending: { label: "Pending", classes: "bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-200" },
    assigned: { label: "Assigned", classes: "bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300" },
    for_approval: { label: "For Approval", classes: "bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300" },
    approved: { label: "Approved", classes: "bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300" },
    signed: { label: "Signed", classes: "bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300" },
    cancelled: { label: "Cancelled", classes: "bg-zinc-100 dark:bg-zinc-800 text-zinc-400 dark:text-zinc-500" },
  };

  let activeAssignmentStatus = "all";
  let assignableUsersOptionsHtml = "";
  let assignableUsersLoaded = false;
  let currentAssignProspectUuid = null;

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

  function refreshSearchable(el) {
    el?._searchableSelect?.refresh();
  }

  function statusPill(status) {
    const pill = STATUS_PILL[status] ?? STATUS_PILL.pending;
    return `<span class="text-xs font-semibold px-2.5 py-1 rounded-full ${pill.classes}">${pill.label}</span>`;
  }

  // ------------------------------------------------------------------
  // ASSIGNABLE USERS (shared options list for both CSR and RM selects) -
  // fetched once ever, same "fetch once, paint per open" pattern as
  // logic_prospect_modal.js's lookup caches.
  // ------------------------------------------------------------------
  async function ensureAssignableUsersLoaded() {
    if (assignableUsersLoaded) return;
    const response = await apiCall({
      mode: "GET",
      url: "/api/crm/prospects/assignable-users",
    });
    if (!response.success) return;
    assignableUsersOptionsHtml =
      '<option value="">Select User</option>' +
      response.data.map((u) => `<option value="${u.id}">${esc(u.name)}</option>`).join("");
    assignableUsersLoaded = true;
  }

  // ------------------------------------------------------------------
  // COUNTS + STATUS STRIP
  // ------------------------------------------------------------------
  function updateAssignmentCounts(counts) {
    document.getElementById("prCountAll").textContent = counts?.all ?? 0;
    document.getElementById("prCountAwaiting").textContent = counts?.awaiting ?? 0;
    document.getElementById("prCountAssigned").textContent = counts?.assigned ?? 0;
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
        render: () =>
          `<button type="button" class="pr-assign-btn text-xs font-medium px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">Assign</button>`,
      },
    ];

    return window.renderRemoteTable({
      url: "/api/proposalRequests",
      tableId: "tableProposalRequests",
      thead,
      afterRenderFunction: (row) => {
        row.querySelector(".pr-assign-btn")?.addEventListener("click", function (e) {
          e.stopPropagation();
          openAssignModal(JSON.parse(row.dataset.row));
        });
      },
      emptyMessage: () => {
        if (activeAssignmentStatus === "awaiting") return "No requests awaiting assignment.";
        if (activeAssignmentStatus === "assigned") return "No assigned requests yet.";
        return "No proposal requests yet.";
      },
    });
  }

  async function loadProposalRequests() {
    const response = await apiCall({
      mode: "GET",
      url: "/api/proposalRequests",
    });
    if (!response.success) return;
    updateAssignmentCounts(response.status_counts);
    renderTable().load(1);
  }

  // ------------------------------------------------------------------
  // ASSIGN OWNERS MODAL
  // ------------------------------------------------------------------
  async function openAssignModal(pr) {
    const modal = document.getElementById("assignOwnersModal");
    if (!modal) return;

    currentAssignProspectUuid = pr.prospect?.uuid ?? null;
    if (!currentAssignProspectUuid) {
      showMessage({ status: "error", title: "This request has no prospect on file." });
      return;
    }

    await ensureAssignableUsersLoaded();

    modal.querySelector("#assignRequestCode").textContent = pr.code ?? "";
    modal.querySelector("#assignCompanyName").textContent =
      pr.prospect?.company?.company_name || EMPTY;

    const csrSelect = modal.querySelector("#assignCsrSelect");
    const rmSelect = modal.querySelector("#assignRmSelect");
    csrSelect.innerHTML = assignableUsersOptionsHtml;
    rmSelect.innerHTML = assignableUsersOptionsHtml;
    csrSelect.value = String(pr.prospect?.user?.id ?? "");
    rmSelect.value = String(pr.prospect?.relationship_manager?.id ?? "");
    refreshSearchable(csrSelect);
    refreshSearchable(rmSelect);

    window.initSideModal({ modalId: "assignOwnersModal" });
  }

  async function saveAssignment(button) {
    const modal = document.getElementById("assignOwnersModal");
    const assignedTo = modal.querySelector("#assignCsrSelect").value;
    const relationshipManagerId = modal.querySelector("#assignRmSelect").value;

    if (!assignedTo || !relationshipManagerId) {
      showMessage({ status: "error", title: "Select both a CSR and a Relationship Manager." });
      return;
    }

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: {
        assigned_to: assignedTo,
        relationship_manager_id: relationshipManagerId,
      },
      url: `/api/crm/prospects/${currentAssignProspectUuid}/assignment`,
      button,
    });

    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Assigning",
        message: response.message ?? "",
      });
      return;
    }

    showMessage({ status: "success", title: "Owners Assigned" });
    window.closeSideModal("assignOwnersModal");
    renderTable().reload();
  }

  // ------------------------------------------------------------------
  // PUBLIC API
  // ------------------------------------------------------------------
  function initProposalRequestsPage() {
    const root = document.getElementById("ProposalRequestsPage");
    if (!root) return; // guarded: only runs on this page

    activeAssignmentStatus = "all";
    currentAssignProspectUuid = null;

    window.makeSearchableSelect(document.getElementById("assignCsrSelect"));
    window.makeSearchableSelect(document.getElementById("assignRmSelect"));

    ensureAssignableUsersLoaded();
    loadProposalRequests();

    document.querySelectorAll(".prAssignmentStatusBtn").forEach((btn) => {
      btn.addEventListener("click", function () {
        activeAssignmentStatus = this.dataset.assignmentStatus;
        document
          .querySelectorAll(".prAssignmentStatusBtn")
          .forEach((b) => b.classList.remove("ring-2", "ring-orange-500"));
        this.classList.add("ring-2", "ring-orange-500");
        renderTable().setFilter("assignment_status", activeAssignmentStatus);
      });
    });

    document.getElementById("assignSaveBtn").addEventListener("click", function () {
      saveAssignment(this);
    });
  }
  window.initProposalRequestsPage = initProposalRequestsPage;
})();
