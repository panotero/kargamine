async function populateDocumentModal(documentId) {
  try {
    const data = await fetchWithRetry(`/api/documents/${documentId}`, {
      method: "GET",
      headers: {
        Accept: "application/json",
      },
    });

    if (!data || Object.keys(data).length === 0) {
      hideModal("DocumentModal");
      showMessage({
        status: "error",
        message: "Document not found or does not exist.",
      });
      return;
    }
    document.getElementById("docId").value = data.document_id;
    setText("docControlNumber", data.document_control_number);
    setText("docStatus", data.status);
    setText("docTitle", data.particular);
    setText("docDept", data.office_origin);
    setText("docAuthor", data.signatory || "N/A");
    setText("docDate", data.date_of_document || data.date_received || "N/A");
    setText("docCode", data.document_code || "N/A");
    setText("document_id", data.document_id || "N/A");
    setText("docForm", data.document_form || "N/A");
    setText("docType", data.document_type || "N/A");
    setText("docDueDate", data.due_date || "N/A");
    setText("docDestination", data.destination_office || "N/A");
    setText("docConfidentiality", data.confidentiality || "Normal");
    setText("docRemarks", data.remarks || "-");
    populateFileList(data.files || []);
    populateActivityLog(data || []);
  } catch (error) {
    console.error("Failed to populate document modal:", error);
  }
}

function setText(id, text) {
  const el = document.getElementById(id);
  if (el) el.textContent = text || "";
}

function hideModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.classList.add("hidden");
}

function populateFileList(files) {
  const fileList = document.getElementById("fileVersionsList");
  fileList.innerHTML = "";

  if (!files.length) {
    fileList.innerHTML = `
      <li class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
        No files available
      </li>`;
    return;
  }

  files.forEach((file, index) => {
    const li = document.createElement("li");
    li.classList.add(
      "flex",
      "items-center",
      "justify-between",
      "px-4",
      "py-3",
      "hover:bg-gray-50",
      "dark:hover:bg-gray-800",
      "cursor-pointer",
      "fileInfoButton",
      "modal-open"
    );

    li.dataset.version = `v${index + 1}.0`;
    li.dataset.fileId = file.file_id;

    li.innerHTML = `
      <div>
        <p class="text-gray-900 dark:text-gray-100 font-medium">${
          file.file_name
        }.0</p>
        <p class="text-xs text-gray-500 dark:text-gray-400">
          Uploaded: ${file.uploaded_at.split(" ")[0]} by ${
      file.uploading_office
    }
        </p>
      </div>
      <a href="${file.file_path}"
         download
         class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200"
         onclick="event.stopPropagation();">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v8m0-8l-4 4m4-4l4 4"/>
        </svg>
        Download
      </a>
    `;

    li.addEventListener("click", () => openPdfModal(file.file_path));

    fileList.appendChild(li);
  });
}

async function openPdfModal(filePath) {
  initModal({ modalId: "pdfPreviewModal" });

  const baseUrl = window.location.origin;
  const pdfUrl = `${baseUrl}/${filePath}`;

  const slides = await extractPdfImages(pdfUrl);
  loadSlidesFromArray(slides);
}

function populateActivityLog(data) {
  const activityLog = document.getElementById("activityLog"); // important logs only
  const fullActivityLog = document.getElementById("fullActivityLog"); // full log history

  activityLog.innerHTML = "";
  fullActivityLog.innerHTML = "";

  if (!data.activities.length) {
    activityLog.innerHTML = `
            <div class="text-sm text-gray-500 dark:text-gray-400">
                No activity yet.
            </div>
        `;

    fullActivityLog.innerHTML = `
            <div class="text-sm text-gray-500 dark:text-gray-400">
                No logs available.
            </div>
        `;
    return;
  }

  data.activities.forEach((act) => {
    const importantDiv = document.createElement("div");
    const fullDiv = document.createElement("div");

    importantDiv.classList.add(
      "text-sm",
      "text-gray-700",
      "dark:text-gray-300",
      "border",
      "border-gray-300",
      "rounded-md",
      "p-2"
    );
    fullDiv.classList.add("text-sm", "text-gray-600", "dark:text-gray-300");

    const timeAgo = new Date(act.created_at).toLocaleString();
    const fromUser = act.from_user_id ? `User ${act.from_user_id}` : "Unknown";
    const remarks = act.final_remarks;

    let displayText = "";

    if (act.action === "route" || act.action === "upload") {
      let routeTarget = "";

      if (act.to_external == 1) {
        routeTarget = data.destination_office
          ? data.destination_office
          : "Unknown Office";
      } else {
        routeTarget = act.routed_to
          ? `User ${act.routed_to}`
          : "Unknown Recipient";
      }

      displayText = `
                <p>
                    <span class="font-semibold">${fromUser}</span>
                    routed the document to
                    <span class="font-semibold">${routeTarget}</span>
                    <span class="text-gray-500 text-xs">${timeAgo}</span>
                </p>
                <p> <span class="font-semibold">remarks: </span> ${remarks}</p>
            `;

      importantDiv.innerHTML = displayText;
      activityLog.appendChild(importantDiv);
    } else {
      const userName = act.user_id ? `User ${act.user_id}` : "Unknown";

      displayText = `
                <p>
                    <span class="font-semibold">${userName}</span>
                    ${act.action}ed the document
                    <span class="text-gray-500 text-xs">${timeAgo}</span>
                </p>
            `;

      const importantActions = [
        "approve",
        "reject",
        "receive",
        "returned",
        "review",
      ];

      if (importantActions.includes(act.action)) {
        importantDiv.innerHTML = displayText;
        activityLog.appendChild(importantDiv);
      }
    }

    fullDiv.innerHTML = displayText;
    fullActivityLog.appendChild(fullDiv);
  });
}

function initdocumentcontroller() {
  function calculateDuration(startDate, endDate) {
    if (!startDate || !endDate) return "-";
    const start = new Date(startDate);
    const end = new Date(endDate);
    if (isNaN(start) || isNaN(end)) return "-";
    const diffTime = Math.abs(end - start);
    return `${Math.ceil(diffTime / (1000 * 60 * 60 * 24))} days`;
  }

  function appendDocumentRow(tableBody, item, source = null, initTable = true) {
    if (!tableBody || !item) return;
    const routeBtn = document.getElementById("routeDocumentBtn");
    const approvalButtons = document.getElementById("approvalButtons");

    const tr = document.createElement("tr");
    tr.classList.add("border-t", "hover:bg-gray-50", "cursor-pointer");
    tr.dataset.documentId = item.document_id;
    tr.dataset.documentControlNumber = item.document_control_number;
    tr.dataset.userId = item.user_id || "";
    tr.dataset.status = item.status;
    tr.dataset.source = source;

    tr.innerHTML = `
    <td class="px-4 py-2">${item.document_code}</td>
    <td class="px-4 py-2">${item.document_control_number}</td>
    <td class="px-4 py-2">
      <select class="border rounded px-2 py-1 text-xs labeldropdown">
        <option ${
          item.document_type === "General" ? "selected" : ""
        }>General</option>
        <option ${
          item.document_type === "Confidential" ? "selected" : ""
        }>Confidential</option>
      </select>
    </td>
    <td class="px-4 py-2">${item.particular}</td>
    <td class="px-4 py-2">${item.office_origin}</td>
    <td class="px-4 py-2">${item.destination_office}</td>
    <td class="px-4 py-2">${item.date_forwarded || "-"}</td>
    <td class="px-4 py-2">123${calculateDuration(
      item.date_of_document,
      item.date_forwarded
    )}</td>
    <td class="px-4 py-2">${
      item.created_at ? item.created_at.split("T")[0] : "-"
    }</td>
    <td class="px-4 py-2">${item.confidentiality || "-"}</td>
    <td class="px-4 py-2">${item.status || "-"}</td>
  `;

    tr.classList.add("modal-open");

    tr.addEventListener("click", (e) => {
      if (e.target.classList.contains("labeldropdown")) return;

      const spanIds = [
        "docControlNumber",
        "docStatus",
        "docTitle",
        "docDept",
        "docAuthor",
        "docDate",
        "docCode",
        "document_id",
        "docForm",
        "docType",
        "docDueDate",
        "docDestination",
        "docConfidentiality",
        "docRemarks",
      ];

      spanIds.forEach((id) => {
        const el = document.getElementById(id);
        if (el) el.textContent = "";
      });

      const fileList = document.getElementById("fileVersionsList");
      if (fileList) {
        fileList.innerHTML = `
    <div class="p-3">
      ${createSkeletonLoader(4)}
    </div>
  `;
      }

      const log = document.getElementById("activityLog");
      if (log) {
        log.innerHTML = `
    <div class="p-3">
      ${createSkeletonLoader(5)}
    </div>
  `;
      }
      function createSkeletonLoader(lines = 3) {
        let skeleton = "";
        for (let i = 0; i < lines; i++) {
          skeleton += `
      <div class="h-4 bg-gray-200 rounded shimmer mb-2"></div>
    `;
        }
        return skeleton;
      }

      initModal({ modalId: "DocumentModal" });
      populateDocumentModal(tr.dataset.documentId);
      const status = item.status.toLowerCase();
      const recipient_id = item.recipient_id;
      if (source === "all" || status === "for approval") {
        routeBtn.classList.add("hidden");
        if (status === "for approval" && recipient_id === window.authUser.id)
          approvalButtons.classList.remove("hidden");
      } else {
        routeBtn.classList.remove("hidden");
        approvalButtons.classList.add("hidden");
      }

      logActivity(
        "view",
        tr.dataset.documentId,
        tr.dataset.documentControlNumber
      );
    });

    tableBody.appendChild(tr);

    if (initTable) initDataTables();
  }

  window.getDocs = async function getDocs() {
    if (!window.authUser) return;

    const userId = window.authUser.id;
    const userOfficeName = window.authUser.office?.office_name || null;
    const userApprovalType = window.authUser.user_config?.approval_type || null;

    try {
      const documents = await fetchWithRetry("/api/documents", {
        method: "GET",
        headers: {
          Accept: "application/json",
        },
      });

      const allDocsTableBody = document.querySelector(
        "#allDocumentTable tbody"
      );
      const assignedTableBody = document.querySelector(
        "#assignedToYouDocumentTable tbody"
      );

      if (!allDocsTableBody || !assignedTableBody) return;

      allDocsTableBody.innerHTML = "";
      assignedTableBody.innerHTML = "";

      documents.forEach((doc) => {
        const involvedOffices = Array.isArray(doc.involved_office)
          ? doc.involved_office
          : [];

        const canSeeAllDocs =
          !userOfficeName ||
          userOfficeName === "ODDG-PP" ||
          involvedOffices.includes(userOfficeName);
        if (canSeeAllDocs)
          appendDocumentRow(allDocsTableBody, doc, "all", false);

        let showAssigned = false;
        const recipientId = doc.recipient_id;

        if (recipientId !== null) {
          if (recipientId == userId) showAssigned = true;
        } else {
          const isRoutingUser = userApprovalType === "routing";
          const sameOffice = userOfficeName === doc.destination_office;
          if (isRoutingUser && sameOffice) showAssigned = true;
        }

        if (showAssigned)
          appendDocumentRow(assignedTableBody, doc, "assigned", false);
      });

      initDataTables();
    } catch (error) {
      console.error("Error fetching documents:", error);
    }
  };

  async function initEventListeners() {
    initPDFDropzone({
      dropzoneId: "dropzone",
      fileInputId: "fileInput",
      fileInfoId: "fileInfo",
      clearBtnId: "clearSelectionBtn",
    });
    initroute();

    fillOfficeDropdown();
    fillDocType();

    const destinationOfficedropdown =
      document.getElementById("destinationOffice");
    const originOfficedropdown = document.getElementById("originOffice");
    // console.log(originOfficedropdown);
    const documentType = document.getElementById("documentType");

    const otherdestinationoffice = document.getElementById(
      "otherdestinationofficetb"
    );
    const otheroriginoffice = document.getElementById("otheroriginofficetb");
    const otherdoctype = document.getElementById("otherdoctypetb");

    toggleOtherField(destinationOfficedropdown, otherdestinationoffice);
    toggleOtherField(originOfficedropdown, otheroriginoffice);
    toggleOtherField(documentType, otherdoctype);
    function toggleOtherField(dropdown, textbox) {
      dropdown.addEventListener("change", () => {
        textbox.classList.toggle("hidden", dropdown.value !== "Other");
      });
    }

    const submitBtn = document.querySelector(
      "#modalNewDocument button.bg-blue-600"
    );
    const fileInput = document.getElementById("fileInput");
    document.getElementById("btnNewDocument")?.addEventListener("click", () => {
      initModal({
        modalId: "modalNewDocument",
      });
    });
    submitBtn?.addEventListener("click", async () => {
      const form = document.querySelector("#modalNewDocument");
      const invalid = form.querySelector(":invalid");

      if (invalid) {
        invalid.reportValidity();
        return;
      }

      if (!fileInput.files[0]) {
        alert("Please upload a PDF file.");
        return;
      }

      const formData = new FormData();
      formData.append(
        "document_code",
        document.getElementById("document_code").value.trim()
      );
      formData.append("date_received", new Date().toISOString().split("T")[0]);
      formData.append(
        "particular",
        document.getElementById("subject").value.trim()
      );
      const originDropdown = document.getElementById("originOffice");
      const destinationDropdown = document.getElementById("destinationOffice");

      const otherOriginInput = document.getElementById("otheroriginOffice");
      const otherDestinationInput = document.getElementById(
        "otherdestinationOffice"
      );

      let originValue = originDropdown.value.trim().toLowerCase();
      let destinationValue = destinationDropdown.value.trim().toLowerCase();

      if (originValue === "other") {
        formData.append(
          "office_origin",
          "OTHER - " + (otherOriginInput.value.trim() || "")
        );
      } else {
        formData.append("office_origin", originDropdown.value);
      }

      if (destinationValue === "other") {
        formData.append(
          "destination_office",
          "OTHER - " + (otherDestinationInput.value.trim() || "")
        );
      } else {
        formData.append("destination_office", destinationDropdown.value);
      }
      formData.append("user_id", window.authUser.id);
      formData.append("document_form", "PDF");
      formData.append(
        "document_type",
        document.getElementById("documentType").value
      );
      formData.append("due_date", document.getElementById("due_date").value);
      formData.append(
        "document_date",
        document.getElementById("document_date").value
      );
      formData.append(
        "signatory",
        document.getElementById("signatory").value.trim()
      );
      formData.append(
        "remarks",
        document.getElementById("remarks").value.trim()
      );
      formData.append("file", fileInput.files[0]);

      try {
        submitBtn.disabled = true;
        submitBtn.textContent = "Submitting...";
        //4.
        const response = await fetchWithRetry("/api/documents", {
          method: "POST",
          body: formData,
        });

        const result = await response.json();

        if (!response.ok) {
          alert(
            "Server validation failed:\n" + JSON.stringify(result, null, 2)
          );
          return;
        }
        // console.log(result);

        document.getElementById("modalNewDocument").classList.add("hidden");

        const modal = document.getElementById("modalNewDocument");

        if (modal) {
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
        }

        if (result.docControlNumber) {
          const controlModal = document.getElementById("controlNumberModal");
          const controlText = document.getElementById("controlNumberText");
          controlText.textContent = Array.isArray(result.docControlNumber)
            ? result.docControlNumber.join(", ")
            : result.docControlNumber;

          controlModal.classList.add("modal-open");
        }

        getDocs();
      } catch (err) {
        console.error(err);
        alert("Unexpected error.");
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = "Submit";
      }
    });

    document.querySelectorAll(".fileInfoButton").forEach((btn) =>
      btn.addEventListener("click", () =>
        initModal({
          modalId: "pdfPreviewModal",
        })
      )
    );

    document.querySelectorAll(".routeBtn").forEach((btn) => {
      btn.addEventListener("click", () => {
        initPDFDropzone({
          dropzoneId: "routedropzone",
          fileInputId: "routefileInput",
          fileInfoId: "routefileInfo",
          clearBtnId: "clearrouteSelectionBtn",
        });
        initModal({
          modalId: "routingModal",
        });
      });
    });

    const officeSelect = document.getElementById("routeOfficeSelect");
    const userSelect = document.getElementById("routeUserSelect");
    const approvalSelect = document.getElementById("approvalSelect");
    const statusSelect = document.getElementById("routeStatusSelect");
    const internalSection = document.getElementById("internalSection");
    const externalSection = document.getElementById("externalSection");
    const pdfUploadSection = document.getElementById("pdfUploadSection");
    const currentOffice = window.authUser.office?.office_name || null;

    officeSelect?.addEventListener("change", (e) => {
      const selected = e.target.value;

      internalSection?.classList.toggle("hidden", selected !== currentOffice);
      externalSection?.classList.toggle(
        "hidden",
        selected === currentOffice || !selected
      );

      if (selected === currentOffice) {
        //5.
        fetch("/api/users")
          .then((res) => res.json())
          .then((users) => {
            const filtered = users.filter(
              (u) => u.office?.office_name === currentOffice
            );
            userSelect.innerHTML =
              `<option value="">Select User</option>` +
              filtered
                .map((u) => `<option value="${u.id}">${u.name}</option>`)
                .join("");
            approvalSelect.innerHTML = `<option value="">Select Approval Type</option><option value="pre-approval">Pre-approval</option><option value="final-approval">final-approval</option>`;
          });
      }
    });

    statusSelect?.addEventListener("change", (e) => {
      pdfUploadSection?.classList.toggle(
        "hidden",
        e.target.value !== "approved"
      );
    });
  }

  getDocs();
  initEventListeners();
}

window.initdocumentcontroller = initdocumentcontroller;
window.populateDocumentModal = populateDocumentModal;
