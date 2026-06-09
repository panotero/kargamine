window.initModal = function initModal({ modalId }) {
  const modal = document.getElementById(modalId);
  const closeBtn = modal?.querySelectorAll(".modal-close");

  if (!modal || !closeBtn) {
    console.warn("Missing modal elements. Check your IDs.");
    return;
  }

  // Save current scroll position
  const scrollY = window.scrollY;

  // Show modal
  modal.classList.remove("hidden");
  let openmodalcount = checkopenmodal();
  // Disable background scrolling
  document.body.style.position = "fixed";
  document.body.style.top = `-${scrollY}px`;
  document.body.style.left = "0";
  document.body.style.right = "0";
  document.body.style.overflow = "hidden";
  closeBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
      modal.classList.add("hidden");

      openmodalcount = checkopenmodal();
      if (openmodalcount > 0) {
        return;
      } else {
        // Restore scroll position and allow scrolling
        document.body.style.position = "";
        document.body.style.top = "";
        document.body.style.left = "";
        document.body.style.right = "";
        document.body.style.overflow = "";
        window.scrollTo(0, scrollY);
      }
    });
  });
};

window.initSideModal = function initSideModal({ modalId }) {
  const modal = document.getElementById(modalId);

  if (!modal) {
    console.warn("Modal not found:", modalId);
    return;
  }

  const panel = modal.querySelector(".side-modal-panel");
  const closeButtons = modal.querySelectorAll(".modal-close");

  const scrollY = window.scrollY;

  // show backdrop
  modal.classList.remove("hidden");

  requestAnimationFrame(() => {
    panel.classList.remove("translate-x-full");
  });

  document.body.style.position = "fixed";
  document.body.style.top = `-${scrollY}px`;
  document.body.style.left = "0";
  document.body.style.right = "0";
  document.body.style.overflow = "hidden";

  closeButtons.forEach((btn) => {
    btn.onclick = async function () {
      const confirmed = await customConfirm(
        "You have unsaved changes. Are you sure you want to close?",
      );
      if (!confirmed) return;
      closeSideModal(modalId, scrollY);
    };
  });

  //   modal.onclick = async function (e) {
  //     if (e.target === modal) {
  //       const confirmed = await customConfirm(
  //         "You have unsaved changes. Are you sure you want to close?",
  //       );
  //       if (!confirmed) return;
  //       closeSideModal(modalId, scrollY);
  //     }
  //   };
};

window.closeSideModal = function closeSideModal(
  modalId,
  scrollY = window.scrollY,
) {
  const modal = document.getElementById(modalId);

  if (!modal) return;

  const panel = modal.querySelector(".side-modal-panel");

  panel.classList.add("translate-x-full");

  setTimeout(() => {
    modal.classList.add("hidden");

    const openmodalcount = checkopenmodal();

    if (openmodalcount > 0) return;

    document.body.style.position = "";
    document.body.style.top = "";
    document.body.style.left = "";
    document.body.style.right = "";
    document.body.style.overflow = "";

    window.scrollTo(0, scrollY);
  }, 300);
  clearInputs();
};

function checkopenmodal() {
  const opennedmodal = document.querySelectorAll(".modal");
  let openmodalcount = 0;
  opennedmodal.forEach((mdl) => {
    if (!mdl.classList.contains("hidden")) {
      openmodalcount++;
    }
  });
  return openmodalcount;
}

window.closemodals = function closemodals() {
  const opennedmodal = document.querySelectorAll(".modal");

  opennedmodal.forEach((mdl) => {
    if (!mdl.classList.contains("hidden")) {
      // RESET FORM ELEMENTS
      const forms = mdl.querySelectorAll("form");
      forms.forEach((form) => form.reset());

      // RESET INPUTS NOT INSIDE FORM (fallback)
      const inputs = mdl.querySelectorAll("input, textarea, select");

      inputs.forEach((input) => {
        if (input.type === "checkbox" || input.type === "radio") {
          input.checked = false;
        } else if (input.type !== "hidden") {
          input.value = "";
        }
      });

      // OPTIONAL: reset custom UI (like your shipper/consignee display)
      mdl.querySelectorAll("[data-shipper], [data-consignee]").forEach((el) => {
        el.textContent = "—";
      });

      // HIDE MODAL
      mdl.classList.add("hidden");
    }
  });
};

window.renderRows = function renderRows(
  tablebodyID,
  data,
  clickableRow = false,
  functionToCallOnRowClick = null,
) {
  const tbody = document.getElementById(tablebodyID);

  if (!tbody) {
    console.error(`Table body "${tablebodyID}" not found.`);
    return;
  }

  tbody.innerHTML = "";

  if (!Array.isArray(data) || data.length === 0) {
    tbody.innerHTML = `
            <tr>
                <td colspan="100%" class="text-center py-5 text-gray-500">
                    No records found.
                </td>
            </tr>
        `;
    return;
  }

  data.forEach((row) => {
    const tr = document.createElement("tr");

    tr.className = `
            border-b border-gray-200
            hover:bg-gray-50
            transition
        `;

    let rowId = row.id ?? null;

    if (clickableRow) {
      tr.classList.add("cursor-pointer");

      tr.addEventListener("click", (e) => {
        if (e.target.closest("button")) {
          return;
        }
        if (typeof functionToCallOnRowClick === "function") {
          functionToCallOnRowClick(rowId);
        }
      });
    }

    Object.entries(row).forEach(([key, value]) => {
      // ✅ SKIP ID COLUMN
      if (key === "id") return;
      const td = document.createElement("td");
      td.className = "px-4 py-3";

      // Action column (HTML allowed)
      if (key.toLowerCase() === "action") {
        td.innerHTML = value;
      } else {
        td.textContent = value ?? "-";
      }

      tr.appendChild(td);
    });

    tbody.appendChild(tr);
  });

  //   initDataTables(10);
};

window.initLoading = function initLoading() {
  return `
  <div class="w-full h-full overflow-auto rounded-md p-2">
    <div class="mx-auto w-full rounded-md p-4 animate-pulse mt-4 min-w-max">
      <div class="w-full flex space-x-4">
        <div class="h-10 w-10 rounded-full bg-gray-200"></div>
        <div class="flex-1 space-y-6 py-1">
          <div class="h-2 rounded bg-gray-200"></div>
          <div class="space-y-3">
            <div class="grid grid-cols-3 gap-4">
              <div class="col-span-2 h-2 rounded bg-gray-200"></div>
              <div class="col-span-1 h-2 rounded bg-gray-200"></div>
            </div>
            <div class="h-2 rounded bg-gray-200"></div>
          </div>
        </div>
      </div>
    </div>
  </div>`;
};

window.formatDateTime = function formatDateTime(dateString) {
  const date = new Date(dateString);

  return date.toLocaleString("en-PH", {
    month: "short",
    day: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
    hour12: true,
  });
};
window.customConfirm = function customConfirm(message) {
  return new Promise((resolve) => {
    const modal = document.getElementById("customConfirmModal");
    const messageEl = document.getElementById("customConfirmMessage");
    const okBtn = document.getElementById("customConfirmOk");
    const cancelBtn = document.getElementById("customConfirmCancel");

    if (!modal || !messageEl || !okBtn || !cancelBtn) return;

    messageEl.textContent = message;

    modal.classList.remove("hidden");
    document.body.classList.add("overflow-hidden");

    const closeModal = (value) => {
      modal.classList.add("hidden");
      document.body.classList.remove("overflow-hidden");
      okBtn.removeEventListener("click", onOk);
      cancelBtn.removeEventListener("click", onCancel);
      resolve(value);
    };

    const onOk = () => closeModal(true);
    const onCancel = () => closeModal(false);

    okBtn.addEventListener("click", onOk);
    cancelBtn.addEventListener("click", onCancel);
  });
};

window.clearInputs = function clearInputs() {
  document.querySelectorAll("input").forEach((input) => {
    input.value = "";
  });

  document.querySelectorAll("select").forEach((input) => {
    input.value = "";
  });
  document.querySelectorAll("textarea").forEach((textarea) => {
    textarea.value = "";
  });
};
