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
