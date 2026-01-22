document.addEventListener("DOMContentLoaded", () => {
  let lastTouchEnd = 0;

  document.addEventListener("gesturestart", function (e) {
    e.preventDefault();
  });

  document.addEventListener("dblclick", function (e) {
    e.preventDefault();
  });
});

window.initdashboard = function initdashboard() {
  let allDocuments = []; // store all fetched documents

  const statuses = ["signed", "approved", "completed"];

  window.initDashboard = async function initDashboard() {
    console.log("initdashboard");
    const authUser = window.authUser;
    if (!authUser) return;

    const userOffice = authUser.office?.office_code || null;
    const userApprovalType = authUser?.user_config.approval_type;
    // console.log(userApprovalType);

    // Fetch all documents once
    // try {
    await getActivities();
    getDocsCounts();
    const docs = await getDocData();
    const adminStatuses = [
      "signed",
      "routed",
      "pending",
      "remanded",
      "completed",
    ];
    const authSignatureStatuses = ["approved", "disapproved"];
    renderTopPriority(docs, {
      userId: authUser.id,
      userApprovalType: authUser.approval_type,
      userOfficeName: authUser.office.office_name,
      isAuthSignatory: authUser.is_auth_signatory,
      adminStatuses,
      authSignatureStatuses,
    });
    // } catch (err) {
    //   console.error(err);
    //   return;
    // }
  };

  async function getDocData() {
    //BUG ID: 1 refactored getActivityData function
    try {
      const documents = await fetchWithRetry(
        `/api/documents/getdocs/${window.authUser.office.office_code}`,
        {
          method: "GET",
          headers: {
            Accept: "application/json",
          },
        },
      );
      return documents;
      // console.log(data);
    } catch (error) {
      console.log(error);
    }
  }

  //get top 10 priority list
  async function topDocs() {
    let filterdoc = [];
    const docs = await getDocData();
    //get the last 10

    filterdoc = docs.filter(
      (doc) => doc.status && doc.status.toLowerCase() === status,
    );
  }

  async function getDocsCounts() {
    try {
      const docs = await getDocData();
      allDocuments = docs;
      console.log(docs);
      const userOffice = authUser.office.office_code;
      let total = docs.length;
      let forDiscussion = 0;
      let forSignature = 0;
      let forApproval = 0;
      let pending = 0;
      let overdue = 0;
      let remanded = 0;
      let completed = 0;
      docs.forEach((doc) => {
        // console.log(doc.status);

        switch (doc.status.toLowerCase()) {
          case "pending":
            pending++;
            break;
          case "for approval":
            forApproval++;
            break;
          case "remanded":
            remanded++;
            break;
          case "for discussion":
            forDiscussion++;
            break;
          case "completed":
            completed++;
            break;
          case "approved":
            forSignature++;
            break;
        }
      });

      overdue = checkOverDue(docs).length;

      document.getElementById("totalDocuments").textContent =
        total.toLocaleString();
      document.getElementById("forDiscussion").textContent =
        forDiscussion.toLocaleString();
      document.getElementById("forSignature").textContent =
        forSignature.toLocaleString();
      document.getElementById("pending").textContent = pending.toLocaleString();
      document.getElementById("overdue").textContent = overdue.toLocaleString();
      document.getElementById("completed").textContent =
        completed.toLocaleString();
      document.getElementById("remanded").textContent =
        remanded.toLocaleString();

      document.getElementById("forApproval").textContent =
        forApproval.toLocaleString();
    } catch (error) {
      console.error(error);
    }
  }
  // -----------------------------
  // Update Counts
  // -----------------------------
  async function getActivities() {
    try {
      const response = await getDocData();
      if (!response) throw new Error("Failed to fetch documents");
      allDocuments = response;
      // console.log(response);
      const authUser = window.authUser;
      if (!authUser) return;
      const activities = await fetchWithRetry(
        `/api/activities/byOffice/${authUser.office.office_code}`,
        {
          method: "GET",
          headers: {
            Accept: "application/json",
          },
        },
      );
      console.log(activities);
      renderActivities(activities);
    } catch (error) {
      console.error(error);
    }
  }
  function formatTimeAgo(dateString) {
    const diff = Math.floor((Date.now() - new Date(dateString)) / 1000);

    if (diff < 60) return `${diff}s ago`;
    if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
    if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
    return `${Math.floor(diff / 86400)}d ago`;
  }
  function renderActivities(activities) {
    const list = document.getElementById("activityList");
    if (!list) return;

    list.innerHTML = "";

    const recentActivities = activities
      .filter((a) => a.action !== "view")
      .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
      .slice(0, 10);

    recentActivities.forEach((activity) => {
      const fileNumber =
        activity.document?.document_control_number ?? "Unknown File";
      const userName = activity.user?.name ?? "Unknown User";
      const actionText = activity.action.replace(/_/g, " ");
      const timeAgo = formatTimeAgo(activity.created_at);

      const li = document.createElement("li");
      li.className = "py-3 flex justify-between items-start";

      li.innerHTML = `
      <div>
        <div class="text-sm font-medium">File ${fileNumber}</div>
        <div class="text-xs mt-1">
          ${actionText} by <span>${userName}</span>
        </div>
      </div>
      <div class="text-xs">${timeAgo}</div>
    `;

      list.appendChild(li);
    });
  }

  const statusbtn = document.querySelectorAll(".statusButton");
  const modalcounttable = document.getElementById("countmodaltable");
  window.selectedStatus = "all";
  window.getDocsByStatus = function getDocsByStatus() {
    getDocsCounts();
    statusbtn.forEach((e) => {
      e.addEventListener("click", function (func) {
        selectedStatus = e.dataset.status;
        //clear all tables first before populating
        //populate countmodaltable based on the status
        updatetable(selectedStatus);

        // console.log(e.dataset.status);
        initModal({
          modalId: "countModal",
        });
      });
    });
  };
  window.updatetable = async function updatetable(statusSelect = false) {
    const tables = document.querySelectorAll("table tbody");
    const docs = await getDocData();
    tables.forEach((table) => {
      table.innerHTML = "";
    });
    initDataTables();
    console.log(docs);

    // return;
    let filteredDocuments = [];
    let status = selectedStatus;
    if (statusSelect) {
      status = statusSelect;
    }
    console.log(status);
    // return;
    switch (status) {
      case "pending":
        filteredDocuments = docs.filter(
          (doc) => doc.status && doc.status.toLowerCase() === status,
        );
        break;
      case "for approval":
        filteredDocuments = docs.filter(
          (doc) => doc.status && doc.status.toLowerCase() === status,
        );
        break;
      case "completed":
        filteredDocuments = docs.filter(
          (doc) => doc.status && doc.status.toLowerCase() === status,
        );
        break;
      case "remanded":
        filteredDocuments = docs.filter(
          (doc) => doc.status && doc.status.toLowerCase() === status,
        );
        break;
      case "overdue":
        filteredDocuments = docs.filter(
          (doc) =>
            doc.due_date < today &&
            !statuses.includes(doc.status.toLowerCase()), //BUG ID: 8 reversed the statuses to negative clause
        );
        break;
      case "approved":
        filteredDocuments = docs.filter(
          (doc) => doc.status && doc.status.toLowerCase() === status,
        );
        break;
      case "all":
        filteredDocuments = docs;
        break;
    }
    //u

    if ($.fn.DataTable.isDataTable(modalcounttable)) {
      const dt = $(modalcounttable).DataTable();
      dt.clear().draw();
    }
    // console.log(filteredDocuments);
    //update alldocuments to mutated array based on the status
    filteredDocuments.forEach((doc) => {
      updaterow(doc);
    });
  };

  function updaterow(doc) {
    if (!modalcounttable) return;
    const tableBody = modalcounttable.querySelector("tbody");
    let dt = null;
    if ($.fn.DataTable.isDataTable(modalcounttable)) {
      dt = $(modalcounttable).DataTable();
    }

    let statuscolor = "bg-gray-100";
    switch (doc.status.toLowerCase()) {
      case "pending":
        statuscolor = "bg-yellow-200";
        break;
      case "for approval":
        statuscolor = "bg-yellow-100";
        break;
      case "completed":
        statuscolor = "bg-green-200";
        break;
      case "remanded":
        statuscolor = "bg-red-200";
        break;
      case "overdue":
        statuscolor = "bg-red-300";
        break;
      case "approved":
        statuscolor = "bg-blue-200";
        break;
    }
    // Build one table row matching the column headers
    const rowHtml = `
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${doc.document_control_number ?? "-"} <!-- Name -->
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${doc.document_code ?? "-"} <!-- Email -->
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${doc.particular ?? "-"} <!-- Designation -->
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${doc.office_origin} <!-- Office -->
                    </td>
                    <td class="px-4 py-2 text-center text-sm font-medium">
                        ${doc.destination_office} <!-- Pending -->
                    </td>
                    <td class="px-4 py-2">
                        <div class="px-3 py-1 bg-white rounded-full text-gray-800 font-semibold text-center ${statuscolor}">
                            ${doc.status || "-"}
                        </div>
                    </td>
                `;

    if (dt) {
      const newRow = dt.row
        .add([
          doc.document_control_number ?? "-", // Name
          doc.document_code ?? "-", // Email
          doc.particular ?? "-", // Designation
          doc.office_origin ?? "-", // Office
          doc.destination_office,
          `<div class="px-3 py-1 bg-white rounded-full text-gray-800 font-semibold text-center ${statuscolor}">
                            ${doc.status || "-"}
                        </div>`,
        ])
        .draw(false);

      const rowNode = newRow.node();
      let overdueclass = "";
      if (rowNode === null) return;
      rowNode.classList.add(
        "transition-colors",
        "duration-300",
        "hover:dark:bg-white",
        "hover:dark:text-black",
      );
      rowNode.classList.add("cursor-pointer");
      rowNode.addEventListener("click", function () {
        checkActionButtons(
          doc.status,
          doc.recipient_id,
          doc.destination_office,
          doc.receipt_confirmation,
          doc.revision_status,
        );
        clearModalFields();
        showSkeletonLoaders();
        initModal({
          modalId: "DocumentModal",
        });
        populateDocumentModal(doc.document_id);
        logActivity("view", doc.document_id, doc.document_control_number);
      });
    } else {
      const tr = document.createElement("tr");
      tr.innerHTML = rowHtml;
      modalcounttable.appendChild(tr);
    }
  }

  function updatePriorityItem(doc) {
    const container = document.getElementById("prioritylist");
    if (!container) return;

    let statuscolor = "bg-gray-100";
    switch ((doc.status || "").toLowerCase()) {
      case "pending":
        statuscolor = "bg-yellow-200";
        break;
      case "for approval":
        statuscolor = "bg-yellow-100";
        break;
      case "completed":
        statuscolor = "bg-green-200";
        break;
      case "remanded":
        statuscolor = "bg-red-200";
        break;
      case "overdue":
        statuscolor = "bg-red-300";
        break;
      case "approved":
        statuscolor = "bg-blue-200";
        break;
    }

    const card = document.createElement("div");
    card.className = `
    p-3 rounded-lg border border-gray-300 bg-white
    hover:shadow-md transition cursor-pointer
    dark:bg-gray-700 dark:border-gray-500
  `;

    card.innerHTML = `
    <div class="flex items-center justify-between">
      <h1 class="text-sm font-semibold">
        ${doc.document_control_number ?? "-"}
      </h1>
      <span class="text-xs px-2 py-0.5 rounded-full ${statuscolor}">
        ${doc.status ?? "-"}
      </span>
    </div>

    <div class="mt-1 flex justify-between text-xs text-gray-600 dark:text-gray-300">
      <span>
        forwarded by: ${doc.forwarded_by_name ?? doc.office_origin ?? "-"}
      </span>
      <span>
        ${formatTimeAgo(doc.updated_at || doc.created_at)}
      </span>
    </div>
  `;

    /** SAME CLICK BEHAVIOR AS updaterow */
    card.addEventListener("click", function () {
      checkActionButtons(
        doc.status,
        doc.recipient_id,
        doc.destination_office,
        doc.receipt_confirmation,
        doc.revision_status,
      );

      clearModalFields();
      showSkeletonLoaders();

      initModal({ modalId: "DocumentModal" });
      populateDocumentModal(doc.document_id);

      logActivity("view", doc.document_id, doc.document_control_number);
    });

    container.appendChild(card);
  }
  function isAssignedToUser(doc, context) {
    const {
      userId,
      userApprovalType,
      userOfficeName,
      isAuthSignatory,
      adminStatuses,
      authSignatureStatuses,
    } = context;

    return (
      // Directly assigned to user
      (doc.recipient_id !== null && doc.recipient_id == userId) ||
      // Routing approval (no recipient yet)
      (doc.recipient_id === null &&
        userApprovalType === "routing" &&
        userOfficeName === doc.destination_office &&
        adminStatuses.includes((doc.status || "").toLowerCase())) ||
      // Authorized signatory
      (doc.recipient_id === null &&
        authSignatureStatuses.includes((doc.status || "").toLowerCase()) &&
        isAuthSignatory === 1 &&
        userOfficeName === doc.destination_office)
    );
  }

  function renderTopPriority(docs, context) {
    const container = document.getElementById("prioritylist");
    if (!container) return;

    container.innerHTML = "";

    let docArray = [];

    if (Array.isArray(docs)) {
      docArray = docs;
    } else if (docs?.data && Array.isArray(docs.data)) {
      docArray = docs.data;
    } else {
      console.warn("renderTopPriority expected array, got:", docs);
      return;
    }

    const prioritizedDocs = docArray
      .filter((doc) => isAssignedToUser(doc, context))
      .map((doc) => ({
        ...doc,
        _priorityScore: getPriorityScore(doc),
      }))
      .sort((a, b) => a._priorityScore - b._priorityScore)
      .slice(0, 5);

    prioritizedDocs.forEach((doc) => updatePriorityItem(doc));
  }
  function daysBetween(dateA, dateB) {
    const MS_PER_DAY = 1000 * 60 * 60 * 24;
    return Math.floor((new Date(dateB) - new Date(dateA)) / MS_PER_DAY);
  }

  function getPriorityScore(doc) {
    const today = new Date();

    // Case 1: With due date
    if (doc.due_date) {
      const daysLeft = daysBetween(today, doc.due_date);

      // Overdue → highest priority
      if (daysLeft < 0) return daysLeft - 1000;

      return daysLeft;
    }

    // Case 2: No due date → based on duration since forwarded
    const forwardedDate =
      doc.date_forwarded || doc.updated_at || doc.created_at;

    if (!forwardedDate) return 9999;

    const duration = daysBetween(forwardedDate, today);

    return -duration; // longer duration = higher priority
  }

  function upsertTableRow({
    dt = null, // DataTable instance OR null
    tableBody = null, // <tbody> for non-DataTable
    rowId = null, // unique ID (optional)
    columns = [], // array of cell HTML / values
    rowClasses = [],
    onClick = null,
    rawHtml = null, // fallback for non-DataTable
  }) {
    let rowNode = null;

    //  DataTable mode
    if (dt) {
      const newRow = dt.row.add(columns).draw(false);
      rowNode = newRow.node();
    }
    //  Normal table mode
    else if (tableBody && rawHtml) {
      const tr = document.createElement("tr");
      tr.innerHTML = rawHtml;
      tableBody.appendChild(tr);
      rowNode = tr;
    }

    if (!rowNode) return;

    // Assign row id if provided
    if (rowId) {
      rowNode.dataset.rowId = rowId;
    }

    // Add classes
    rowNode.classList.add(
      "transition-colors",
      "duration-300",
      "hover:bg-gray-50",
      "dark:hover:bg-gray-700",
      ...rowClasses,
    );

    // Attach click handler
    if (typeof onClick === "function") {
      rowNode.classList.add("cursor-pointer");
      rowNode.addEventListener("click", onClick);
    }

    return rowNode;
  }

  initDataTables();

  initGraph();
  fillOfficeDropdown();
  initDashboard();
  getDocsByStatus();
};
