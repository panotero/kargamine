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
    try {
      // getActivitiesCounts();
      getDocsCounts();
      await getDocData();
    } catch (err) {
      console.error(err);
      return;
    }
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
        }
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
      (doc) => doc.status && doc.status.toLowerCase() === status
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

        switch ((doc.status || "").toLowerCase()) {
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
          case "complete":
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
  async function getActivitiesCounts(filteredDocs = null) {
    try {
      //BUG ID: 1
      const response = await getDocData();
      if (!response) throw new Error("Failed to fetch documents");
      allDocuments = response;
      // console.log(response);
      const authUser = window.authUser;
      if (!authUser) return;

      const userOffice = authUser.office?.office_code || null;
      const docs = filteredDocs || allDocuments;

      const filtered = docs.filter((doc) => {
        if (userOffice === "ODDG-PP") return true;
        return doc.destination_office === userOffice;
      });

      let total = filtered.length;
      let routed = 0;
      let approved = 0;
      let disapproved = 0;
      let fordiscussion = 0;
      let completed = 0;

      filtered.forEach((doc) => {});
    } catch (error) {
      console.error(error);
    }
  }

  const statusbtn = document.querySelectorAll(".statusButton");
  const financeTable = document.getElementById("financeTable");
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
          (doc) => doc.status && doc.status.toLowerCase() === status
        );
        break;
      case "for approval":
        filteredDocuments = docs.filter(
          (doc) => doc.status && doc.status.toLowerCase() === status
        );
        break;
      case "completed":
        filteredDocuments = docs.filter(
          (doc) => doc.status && doc.status.toLowerCase() === status
        );
        break;
      case "remanded":
        filteredDocuments = docs.filter(
          (doc) => doc.status && doc.status.toLowerCase() === status
        );
        break;
      case "overdue":
        filteredDocuments = docs.filter(
          (doc) =>
            doc.due_date < today && !statuses.includes(doc.status.toLowerCase()) //BUG ID: 8 reversed the statuses to negative clause
        );
        break;
      case "approved":
        filteredDocuments = docs.filter(
          (doc) => doc.status && doc.status.toLowerCase() === status
        );
        break;
      case "all":
        filteredDocuments = docs;
        break;
    }
    //u

    if ($.fn.DataTable.isDataTable(financeTable)) {
      const dt = $(financeTable).DataTable();
      dt.clear().draw();
    }
    // console.log(filteredDocuments);
    //update alldocuments to mutated array based on the status
    filteredDocuments.forEach((doc) => {
      updaterow(doc);
    });
  };

  function updaterow(doc) {
    if (!financeTable) return;
    const tableBody = financeTable.querySelector("tbody");
    let dt = null;
    if ($.fn.DataTable.isDataTable(financeTable)) {
      dt = $(financeTable).DataTable();
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
        "hover:dark:text-black"
      );
      rowNode.classList.add("cursor-pointer");
      rowNode.addEventListener("click", function () {
        checkActionButtons(
          doc.status,
          doc.recipient_id,
          doc.destination_office,
          doc.receipt_confirmation,
          doc.revision_status
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
      console.error(
        "selected table is not datatable please initiate datatable"
      );
    }
  }

  initDataTables();

  initGraph();
  fillOfficeDropdown();
  initDashboard();
  getDocsByStatus();
};
