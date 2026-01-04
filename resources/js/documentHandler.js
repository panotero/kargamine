function initdocumentcontroller() {
  // ---------------------- GET DOCUMENTS ----------------------
  window.getDocs = async function getDocs() {
    const authUser = window.authUser;
    if (!authUser) return;

    const userId = authUser.id;
    const userOfficeName = authUser.office?.office_code || null;
    const userApprovalType = authUser.user_config?.approval_type || null;

    const allDocsBody = document.querySelector("#allDocumentTable tbody");
    const assignedBody = document.querySelector(
      "#assignedToYouDocumentTable tbody"
    );
    if (!allDocsBody || !assignedBody) return;

    initDataTables();
    showDocsLoader(allDocsBody);
    showDocsLoader(assignedBody);

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

        if (canSeeAllDocs) appendDocumentRow(allDocsBody, doc, "all");

        const showAssigned =
          doc.recipient_id !== null
            ? doc.recipient_id == userId
            : userApprovalType === "routing" &&
              userOfficeName === doc.destination_office;

        if (showAssigned) appendDocumentRow(assignedBody, doc, "assigned");
      });

      //   redrawTable("#allDocumentTable");
      //   redrawTable("#assignedToYouDocumentTable");
    } catch (error) {
      console.error("Error fetching documents:", error);
    } finally {
      // Remove loaders
      removeDocsLoader(allDocsBody);
      removeDocsLoader(assignedBody);
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
    <tr class="border-t hover:bg-gray-50 cursor-pointer"
        data-document-id="${document_id}"
        data-document-control-number="${document_control_number}"
        data-user-id="${item.user_id || ""}"
        data-status="${status}"
        data-source="${source}">
      <td class="px-4 py-2">${document_control_number}</td>
      <td class="px-4 py-2">${document_code}</td>
      <td class="px-4 py-2">
        <select class="border rounded-full px-2 py-1 text-xs labeldropdown">
          <option ${
            document_type === "General" ? "selected" : ""
          }>General</option>
          <option ${
            document_type === "Confidential" ? "selected" : ""
          }>Confidential</option>
        </select>
      </td>
      <td class="px-2 py-2 max-w-[150px] truncate" title="${particular}">
  ${particular}
</td>
      <td class="px-4 py-2">${office_origin}</td>
      <td class="px-4 py-2">${destination_office}</td>
      <td class="px-4 py-2">${date_forwarded || "-"}</td>
      <td class="px-4 py-2">${calculateDuration(date_forwarded)}</td>
      <td class="px-4 py-2">${created_at ? created_at.split("T")[0] : "-"}</td>
      <td class="px-4 py-2">${confidentiality || "-"}</td>
      <td class="px-4 py-2">
        <div class="px-3 py-1 rounded-full text-gray-800 font-semibold text-center ${statuscolor}">
          ${status || "-"}
        </div>
      </td>
    </tr>
  `;

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

    rowNode.addEventListener("click", (e) => {
      if (e.target.classList.contains("labeldropdown")) return;
      checkActionButtons(
        item.status,
        item.recipient_id,
        item.destination_office,
        item.receipt_confirmation,
        item.revision_status,
        source
      );
      clearModalFields();
      showSkeletonLoaders();
      initModal({ modalId: "DocumentModal" });
      populateDocumentModal(document_id);
      logActivity("view", document_id, document_control_number);
    });
  }

  function initEventListeners() {
    initPDFDropzone({
      dropzoneId: "dropzone",
      fileInputId: "fileInput",
      fileInfoId: "fileInfo",
      clearBtnId: "clearSelectionBtn",
    });
    init();
    async function init() {
      const response = await fillOfficeDropdown();

      if (response) {
        document.getElementById("originOffice").value =
          window.authUser.office.office_code;
        fillDocType();
      }
    }
    function toggleOtherField(dropdownId, textboxId) {
      const dropdown = document.getElementById(dropdownId);
      const textbox = document.getElementById(textboxId);
      if (!dropdown || !textbox) return;

      dropdown.addEventListener("change", () => {
        textbox.classList.toggle("hidden", dropdown.value !== "Other");
      });
    }

    toggleOtherField("destinationOffice", "otherdestinationofficetb");
    toggleOtherField("originOffice", "otheroriginofficetb");
    toggleOtherField("documentType", "otherdoctypetb");

    const newDocBtn = document.getElementById("btnNewDocument");
    const submitBtn = document.querySelector(".submitbtn");
    const fileInput = document.getElementById("fileInput");
    const confirmationBtn = document.getElementById("btnConfirm");
    newDocBtn?.addEventListener("click", () => {
      initModal({ modalId: "modalNewDocument" });
    });
    submitBtn.addEventListener("click", async () => {
      //   console.log("submitBtn clicked");
      const modal = document.getElementById("modalNewDocument");

      if (!modal) return;

      clearModalErrors();
      const errors = [];

      if (!fileInput?.files[0]) {
        errors.push("Please upload a PDF file.");
      }

      const requiredFields = ["document_code", "subject", "signatory"];
      requiredFields.forEach((id) => {
        const el = document.getElementById(id);
        if (!el || !el.value.trim()) {
          errors.push(
            `${el?.previousElementSibling?.textContent || id} is required.`
          );
        }
      });

      const originDropdown = document.getElementById("originOffice");
      const destinationDropdown = document.getElementById("destinationOffice");
      const documentDropdown = document.getElementById("documentType");

      const otherOriginInput = document.getElementById("otheroriginoffice");
      const otherDestinationInput = document.getElementById(
        "otherdestinationoffice"
      );
      const otherDocumentInput = document.getElementById("otherdocument");

      if (
        !originDropdown.value ||
        originDropdown.value.trim() === "" ||
        originDropdown.value === "Select..."
      ) {
        errors.push("Please select an Origin Office.");
      } else if (
        originDropdown.value === "Other" &&
        (!otherOriginInput || !otherOriginInput.value.trim())
      ) {
        errors.push("Please specify the Origin Office.");
      }

      if (
        !destinationDropdown.value ||
        destinationDropdown.value.trim() === "" ||
        destinationDropdown.value === "Select..."
      ) {
        errors.push("Please select a Destination Office.");
      } else if (
        destinationDropdown.value === "Other" &&
        (!otherDestinationInput || !otherDestinationInput.value.trim())
      ) {
        errors.push("Please specify the Destination Office.");
      }

      if (
        !documentDropdown.value ||
        documentDropdown.value.trim() === "" ||
        documentDropdown.value === "Select..."
      ) {
        errors.push("Please select a Document Type.");
      } else if (
        documentDropdown.value === "Other" &&
        (!otherDocumentInput || !otherDocumentInput.value.trim())
      ) {
        errors.push("Please specify the Document Type.");
      }

      if (errors.length > 0) {
        showModalErrors(errors);
        modal.scrollTop = 0;
        return;
      }

      const formData = new FormData();
      const docFields = [
        "document_code",
        "subject",
        "documentType",
        "due_date",
        "document_date",
        "signatory",
        "remarks",
      ];

      docFields.forEach((id) => {
        const el = document.getElementById(id);
        if (!el) return;
        let value = sanitizeInput(el.value.trim());

        if (id === "subject") formData.append("particular", value);
        else if (id === "documentType") formData.append("document_type", value);
        else if (id === "document_date")
          formData.append("document_date", value);
        else if (id === "due_date") formData.append("due_date", value);
        else formData.append(id, value);
      });

      if (originDropdown.value === "Other") {
        formData.append(
          "office_origin",
          "OTHER - " + sanitizeInput(otherOriginInput.value)
        );
      } else {
        formData.append("office_origin", sanitizeInput(originDropdown.value));
      }

      if (destinationDropdown.value === "Other") {
        formData.append(
          "destination_office",
          "OTHER - " + sanitizeInput(otherDestinationInput.value)
        );
      } else {
        formData.append(
          "destination_office",
          sanitizeInput(destinationDropdown.value)
        );
      }

      if (documentDropdown.value === "Other") {
        formData.append(
          "document_type",
          "OTHER - " + sanitizeInput(otherDocumentInput.value)
        );
      } else {
        formData.append("document_type", sanitizeInput(documentDropdown.value));
      }

      formData.append("user_id", window.authUser.id);
      formData.append("document_form", "PDF");
      formData.append("file", fileInput.files[0]);
      formData.append("date_received", new Date().toISOString().split("T")[0]);

      try {
        submitBtn.disabled = true;
        submitBtn.textContent = "Submitting...";
        const result = await fetchWithRetry("/api/documents", {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
              .content,
          },
          body: formData,
        });
        console.log(result);
        if (result.response && !result.success) {
          if (
            result.response.invalid_fields &&
            Object.keys(result.response.invalid_fields).length > 0
          ) {
            const invalid = result.response.invalid_fields;

            let messages = ["Invalid input detected:"];

            Object.keys(invalid).forEach((field) => {
              invalid[field].forEach((msg) => {
                messages.push(`${field}: ${msg}`);
              });
            });

            showModalErrors(messages);
            return;
          }

          if (errors.length > 0) {
            showModalErrors(errors + "error from backend");
            modal.scrollTop = 0;
            return;
          }

          return;
        } else {
          showMessage({
            status: "success",
            message: "Document has been uploaded",
          });
        }
        resetFormModal("modalNewDocument");
        showControlNumberModal(result.docControlNumber);
        getDocs();

        // loadlastpage();
      } catch (err) {
        console.error(err);
        showModalErrors(["Unexpected error occurred."]);
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = "Submit";
      }
    });

    function sanitizeInput(str) {
      if (typeof str !== "string") return "";
      return str
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#x27;")
        .replace(/\//g, "&#x2F;");
    }

    function showModalErrors(errors) {
      const errorBox = document.getElementById("modalErrorMessage");
      const errorList = document.getElementById("modalErrorList");
      errorList.innerHTML = "";
      errors.forEach((err) => {
        const li = document.createElement("li");
        li.textContent = err;
        errorList.appendChild(li);
      });
      errorBox.classList.remove("hidden");
    }

    function clearModalErrors() {
      const errorBox = document.getElementById("modalErrorMessage");
      const errorList = document.getElementById("modalErrorList");
      errorList.innerHTML = "";
      errorBox.classList.add("hidden");
    }

    document
      .querySelectorAll(".fileInfoButton")
      .forEach((btn) =>
        btn.addEventListener("click", () =>
          initModal({ modalId: "pdfPreviewModal" })
        )
      );

    function resetFormModal(modalId) {
      const modal = document.getElementById(modalId);
      if (!modal) return;

      modal.querySelectorAll("input, select, textarea").forEach((el) => {
        switch (el.type) {
          case "checkbox":
          case "radio":
            el.checked = false;
            break;
          case "file":
            el.value = "";
            break;
          default:
            el.value = "";
        }
      });

      const fileInfo = modal.querySelector("#fileInfo");
      const clearBtn = modal.querySelector("#clearSelectionBtn");
      if (fileInfo) fileInfo.textContent = "";
      if (clearBtn) clearBtn.classList.add("hidden");

      modal.classList.add("hidden");
    }

    function showControlNumberModal(docControlNumber) {
      if (!docControlNumber) return;

      const controlModal = document.getElementById("controlNumberModal");
      const controlText = document.getElementById("controlNumberText");
      if (!controlModal || !controlText) return;

      controlText.textContent = Array.isArray(docControlNumber)
        ? docControlNumber.join(", ")
        : docControlNumber;

      controlModal.classList.add("modal-open");
    }
  }

  document.addEventListener("click", function (e) {
    const btn = document.getElementById("toggleFullLogBtn");
    const dropdown = document.getElementById("fullActivityLogContainer");

    if (!btn || !dropdown) return;

    const isClickInside = btn.contains(e.target) || dropdown.contains(e.target);

    if (!isClickInside) {
      dropdown.classList.add("hidden");
    }
  });
  window.addEventListener("scroll", () => {
    const dropdown = document.getElementById("fullActivityLogContainer");
    dropdown?.classList.add("hidden");
  });
  document
    .getElementById("toggleFullLogBtn")
    .addEventListener("click", function (e) {
      e.stopPropagation(); // prevent immediate close
      document
        .getElementById("fullActivityLogContainer")
        .classList.toggle("hidden");
    });

  const confirmButton = document.getElementById("btnConfirm");
  confirmButton.addEventListener("click", async (e) => {
    const post = {
      document_id: confirmButton.dataset.documentId,
      user_id: window.authUser.id,
    };
    const result = await fetchWithRetry("/api/documents/confirm", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: JSON.stringify(post),
    });
    if (result) {
      document.getElementById("routeDocumentBtn").classList.remove("hidden");
      confirmButton.classList.add("hidden");
      getDocs();
    }
  });

  getDocs();
  initEventListeners();
}

window.initdocumentcontroller = initdocumentcontroller;
