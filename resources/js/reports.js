window.initdocumentreport = function initdocumentreport() {
  // ---------------------- GET DOCUMENTS ----------------------
  window.getDocs = async function getDocs() {
    const authUser = window.authUser;
    if (!authUser) return;

    const userId = authUser.id;
    const userOfficeName = authUser.office?.office_code || null;
    const userApprovalType = authUser.user_config?.approval_type || null;

    const reportsocumentsTable = document.querySelector(
      "#allDocumentTable tbody"
    );
    if (!reportsocumentsTable) return;

    initDataTables();
    showDocsLoader(reportsocumentsTable);

    try {
      const documents = await fetchWithRetry(
        `/api/documents/getdocs/${window.authUser.office.office_code}`,
        {
          method: "GET",
          headers: { Accept: "application/json" },
        }
      );

      clearTable("#allDocumentTable");
      clearTable("#assignedToYouDocumentTable");

      documents.forEach((doc) => {
        const involvedOffices = Array.isArray(doc.involved_office)
          ? doc.involved_office
          : [];
        const canSeeAllDocs =
          !userOfficeName ||
          userOfficeName === "ODDG-PP" ||
          involvedOffices.includes(userOfficeName);

        if (canSeeAllDocs) appendDocumentRow(reportsocumentsTable, doc, "all");
      });

      //   redrawTable("#allDocumentTable");
      //   redrawTable("#assignedToYouDocumentTable");
    } catch (error) {
      console.error("Error fetching documents:", error);
    } finally {
      // Remove loaders
      removeDocsLoader(reportsocumentsTable);
    }
  };

  // ---------------------- LOADER ----------------------
  function getDocsLoaderRow(colCount = 11) {
    const tr = document.createElement("tr");
    tr.id = "docsLoaderRow";
    tr.innerHTML = `
    <td colspan="${colCount}" class="py-6">
      <div class="flex justify-center items-center">
        <div class="animate-spin h-8 w-8 border-4 border-gray-300 border-t-gray-600 rounded-full"></div>
        <span class="ml-3 text-sm text-gray-500">Loading documents…</span>
      </div>
    </td>
  `;
    return tr;
  }

  function showDocsLoader(tableBody) {
    if (!tableBody) return;
    tableBody.innerHTML = "";
    tableBody.appendChild(getDocsLoaderRow());
  }

  function getNoDocsRow(colCount = 11, message = "No documents found.") {
    const tr = document.createElement("tr");
    tr.className = "no-docs-row";
    tr.innerHTML = `
    <td colspan="${colCount}" class="py-6 text-center text-sm text-gray-500">
      ${message}
    </td>
  `;
    return tr;
  }

  function removeDocsLoader(tableBody) {
    if (!tableBody) return;
    const loader = tableBody.querySelector("#docsLoaderRow");
    if (loader) loader.remove();
  }

  // ---------------------- DATA TABLE HELPERS ----------------------
  function clearTable(selector) {
    if ($.fn.DataTable.isDataTable(selector)) {
      $(selector).DataTable().clear();
    } else {
      const tbody = document.querySelector(`${selector} tbody`);
      if (tbody) tbody.innerHTML = "";
    }
  }

  function redrawTable(selector) {
    if ($.fn.DataTable.isDataTable(selector)) {
      $(selector).DataTable().draw(false);
    }
  }

  // ---------------------- APPEND ROW ----------------------
  function appendDocumentRow(tableBody, item, source = null) {
    if (!tableBody || !item) return;

    const table = tableBody.closest("table");
    const dt =
      table && $.fn.DataTable.isDataTable(table) ? $(table).DataTable() : null;

    const {
      document_id,
      document_code,
      document_control_number,
      document_type,
      particular,
      office_origin,
      destination_office,
      date_forwarded,
      created_at,
      confidentiality,
      status,
    } = item;

    const dueDate = item.due_date
      ? new Date(item.due_date).toLocaleDateString("en-US", {
          month: "short",
          day: "2-digit",
          year: "numeric",
        })
      : "-";

    const dateUploaded = item.date_forwarded
      ? new Date(item.date_forwarded).toLocaleString("en-US", {
          month: "short",
          day: "2-digit",
          year: "numeric",
          hour: "numeric",
          minute: "2-digit",
          hour12: true,
        })
      : "-";

    const duration =
      item.date_received && item.date_forwarded
        ? Math.ceil(
            (new Date(item.date_forwarded) - new Date(item.date_received)) /
              (1000 * 60 * 60 * 24)
          ) + " days"
        : "-";
    let statuscolor = "bg-gray-100";
    switch (status?.toLowerCase()) {
      case "pending":
        statuscolor = "bg-yellow-200";
        break;
      case "for approval":
        statuscolor = "bg-yellow-100";
        break;
      case "complete":
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
    const rowHtml = `
  <td class="px-4 py-2">${item.document_control_number}</td>
  <td class="px-4 py-2">${item.document_code}</td>
  <td class="px-4 py-2">${item.label || "-"}</td>
  <td class="px-4 py-2">${item.particular || "-"}</td>
  <td class="px-4 py-2">${item.office_origin || "-"}</td>
  <td class="px-4 py-2">${item.destination_office || "-"}</td>
  <td class="px-4 py-2">${dueDate}</td>
  <td class="px-4 py-2">${duration}</td>
  <td class="px-4 py-2">${dateUploaded}</td>
  <td class="px-4 py-2">${item.confidentiality || "-"}</td>
  <td class="px-4 py-2">
    <div class="px-3 py-1 rounded-full text-gray-800 font-semibold text-center ${statuscolor}">
      ${status || "-"}
    </div>
  </td>
  <td class="px-3 py-3">${item.user_name || ""}</td>
  <td class="px-3 py-3">${item.recipient_name || ""}</td>
  <td class="px-3 py-3">${item.document_form || ""}</td>
  <td class="px-3 py-3">${item.document_type || ""}</td>
  <td class="px-3 py-3">${item.date_of_document || ""}</td>
  <td class="px-3 py-3">${item.signatory || ""}</td>
  <td class="px-3 py-3">${item.confirmed_by_name || ""}</td>
  <td class="px-3 py-3">${JSON.stringify(item.involved_office) || ""}</td>
  <td class="px-3 py-3">${item.action_taken || ""}</td>
  <td class="px-3 py-3">${item.remarks || ""}</td>
`;
    //     const rowHtml = `
    //     <tr class="border-t hover:bg-gray-50 cursor-pointer"
    //         data-document-id="${document_id}"
    //         data-document-control-number="${document_control_number}"
    //         data-user-id="${item.user_id || ""}"
    //         data-status="${status}"
    //         data-source="${source}">
    //       <td class="px-4 py-2">${document_control_number}</td>
    //       <td class="px-4 py-2">${document_code}</td>
    //       <td class="px-4 py-2">
    //         <select class="border rounded-full px-2 py-1 text-xs labeldropdown">
    //           <option ${
    //             document_type === "General" ? "selected" : ""
    //           }>General</option>
    //           <option ${
    //             document_type === "Confidential" ? "selected" : ""
    //           }>Confidential</option>
    //         </select>
    //       </td>
    //       <td class="px-2 py-2 max-w-[150px] truncate" title="${particular}">
    //   ${particular}
    // </td>
    //       <td class="px-4 py-2">${office_origin}</td>
    //       <td class="px-4 py-2">${destination_office}</td>
    //       <td class="px-4 py-2">${date_forwarded || "-"}</td>
    //       <td class="px-4 py-2">${calculateDuration(date_forwarded)}</td>
    //       <td class="px-4 py-2">${created_at ? created_at.split("T")[0] : "-"}</td>
    //       <td class="px-4 py-2">${confidentiality || "-"}</td>
    //       <td class="px-4 py-2">
    //         <div class="px-3 py-1 rounded-full text-gray-800 font-semibold text-center ${statuscolor}">
    //           ${status || "-"}
    //         </div>
    //       </td>
    //     </tr>
    //   `;

    let rowNode;
    if (dt) {
      const row = dt.row.add($(rowHtml));
      rowNode = row.node();
      dt.draw(false);
    } else {
      const temp = document.createElement("tbody");
      temp.innerHTML = rowHtml;
      rowNode = temp.firstElementChild;
      tableBody.appendChild(rowNode);
    }
  }

  function parseDateSafe(dateString) {
    return new Date(dateString.replace(" ", "T"));
  }
  function calculateDuration(dateForwarded) {
    const start = parseDateSafe(dateForwarded);
    const end = new Date();

    if (isNaN(start.getTime())) {
      console.error("Invalid date:", dateForwarded);
      return "Invalid date";
    }

    let diffMs = end.getTime() - start.getTime();
    if (diffMs < 0) diffMs = 0;

    const totalMinutes = Math.floor(diffMs / 60000);
    const totalHours = Math.floor(totalMinutes / 60);
    const days = Math.floor(totalHours / 24);
    const hours = totalHours % 24;
    const minutes = totalMinutes % 60;

    let result = [];
    if (days > 0) result.push(`${days} day${days > 1 ? "s" : ""}`);
    if (hours > 0) result.push(`${hours} hour${hours > 1 ? "s" : ""}`);
    result.push(`${minutes} min`);

    return result.join(" ");
  }
  getDocs();
};
