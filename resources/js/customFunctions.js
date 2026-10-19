// -------------------- CURRENCY INPUT FORMATTING --------------------
// Any input with class="currency-input" auto-formats with comma thousand
// separators live as the user types (on the "input" event), preserving
// caret position by counting digits rather than raw character offset so
// inserted/removed commas don't shove the cursor around. Event-delegated
// (not per-element bound), so dynamically-inserted rows in repeatable
// templates get this for free - no re-init call needed. These must be
// type="text" (not type="number") since native number inputs reject comma
// characters outright.
window.formatCurrencyDisplay = function formatCurrencyDisplay(value) {
  const clean = String(value ?? "").replace(/,/g, "").trim();
  if (clean === "" || isNaN(Number(clean))) return clean;

  const negative = clean.startsWith("-");
  const unsigned = negative ? clean.slice(1) : clean;
  const [whole, decimal] = unsigned.split(".");
  const grouped = whole.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

  return (negative ? "-" : "") + grouped + (decimal !== undefined ? "." + decimal : "");
};

window.parseCurrencyValue = function parseCurrencyValue(value) {
  return String(value ?? "").replace(/,/g, "").trim();
};

document.addEventListener(
  "input",
  (e) => {
    if (!e.target.matches || !e.target.matches(".currency-input")) return;

    const el = e.target;
    const cursorPos = el.selectionStart ?? el.value.length;
    const digitsBeforeCursor = el.value.slice(0, cursorPos).replace(/,/g, "").length;

    const formatted = formatCurrencyDisplay(el.value);
    if (formatted === el.value) return;
    el.value = formatted;

    let newPos = formatted.length;
    if (digitsBeforeCursor === 0) {
      newPos = 0;
    } else {
      let seen = 0;
      for (let i = 0; i < formatted.length; i++) {
        if (formatted[i] !== ",") {
          seen++;
          if (seen === digitsBeforeCursor) {
            newPos = i + 1;
            break;
          }
        }
      }
    }
    el.setSelectionRange(newPos, newPos);
  },
  true,
);

// -------------------- PHONE INPUT FORMATTING --------------------
// Any input with class="phone-input" auto-formats into space-separated
// digit groups live as the user types (on the "input" event), preserving
// caret position the same way the currency-input block does (counting
// digits, not raw character offsets). Event-delegated so dynamically
// inserted rows get this for free.
//
// STORAGE DECISION: phone numbers are stored digits-only in the DB.
// Formatting (spacing) is purely display/entry sugar - always strip back
// to digits with parsePhoneValue() before sending a payload.
window.formatPhoneDisplay = function formatPhoneDisplay(value) {
  const digits = String(value ?? "").replace(/\D/g, "");
  if (digits === "") return "";

  // 11-digit PH mobile starting with "09": group as XXXX XXX XXXX.
  if (digits.length === 11 && digits.startsWith("09")) {
    return `${digits.slice(0, 4)} ${digits.slice(4, 7)} ${digits.slice(7, 11)}`;
  }

  // Generic fallback: group left-to-right into blocks of 4.
  return digits.replace(/(\d{4})(?=\d)/g, "$1 ");
};

window.parsePhoneValue = function parsePhoneValue(value) {
  return String(value ?? "").replace(/\D/g, "");
};

document.addEventListener(
  "input",
  (e) => {
    if (!e.target.matches || !e.target.matches(".phone-input")) return;

    const el = e.target;
    const cursorPos = el.selectionStart ?? el.value.length;
    const digitsBeforeCursor = el.value.slice(0, cursorPos).replace(/\D/g, "").length;

    const formatted = formatPhoneDisplay(el.value);
    if (formatted === el.value) return;
    el.value = formatted;

    let newPos = formatted.length;
    if (digitsBeforeCursor === 0) {
      newPos = 0;
    } else {
      let seen = 0;
      for (let i = 0; i < formatted.length; i++) {
        if (formatted[i] !== " ") {
          seen++;
          if (seen === digitsBeforeCursor) {
            newPos = i + 1;
            break;
          }
        }
      }
    }
    el.setSelectionRange(newPos, newPos);
  },
  true,
);

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

window.closeModal = function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal || modal.classList.contains('hidden')) return;

  const forms = modal.querySelectorAll('form');
  forms.forEach((form) => form.reset());

  const inputs = modal.querySelectorAll('input, textarea, select');
  inputs.forEach((input) => {
    if (input.type === 'checkbox' || input.type === 'radio') {
      input.checked = false;
    } else if (input.type !== 'hidden') {
      input.value = '';
    }
  });

  modal.classList.add('hidden');

  // Only restore body scroll if no other .modal is still open.
  if (checkopenmodal() > 0) return;

  document.body.style.position = '';
  document.body.style.top = '';
  document.body.style.left = '';
  document.body.style.right = '';
  document.body.style.overflow = '';
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

window.getStatusBadgeClass = function getStatusBadgeClass(status) {
  switch (status) {
    case "PROSPECT":
      return "bg-gray-100 text-gray-700";

    case "QUALIFIED":
      return "bg-indigo-100 text-indigo-700";

    case "OPPORTUNITY":
      return "bg-purple-100 text-purple-700";

    case "NEGOTIATION":
      return "bg-amber-100 text-amber-700";

    case "WIN":
      return "bg-green-100 text-green-700";

    case "LOST":
      return "bg-red-100 text-red-700";

    default:
      return "bg-zinc-100 text-zinc-700";
  }
};

window.initLoading = function initLoading() {
  return `
  <style>
    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(300%);
        }
    }

    .animate-shimmer {
        animation: shimmer 1.5s infinite linear;
    }
</style>

<div class="relative h-4 w-full overflow-hidden rounded bg-gray-200">
    <div
        class="absolute inset-y-0 w-1/3 bg-gradient-to-r from-transparent via-white to-transparent animate-shimmer">
    </div>
</div>`;
};
window.loadingLine = function loadingLine() {
  return `
    <style>
    @keyframes slide-loading {
        0% {
            left: -8rem;
        }

        100% {
            left: 100%;
        }
    }

    .slide-loading {
        animation: slide-loading 2s linear infinite;
    }
</style>

<div class="relative h-4 w-full overflow-hidden">
    <div class="slide-loading absolute h-2 w-32 rounded-full bg-gray-100"></div>
</div>`;
};

// Canonical date/datetime display helpers. Built from Date parts by hand
// (NOT toLocaleString) because toLocaleString's default output inserts a
// comma between the year and the time, which the canonical shape below
// does not have: "January 01, 2001 01:00 PM".
window.MONTH_NAMES_LONG = [
  "January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December",
];

window.formatDateTime = function formatDateTime(dateString) {
  if (!dateString) return "";
  const date = new Date(dateString);
  if (isNaN(date.getTime())) return "";

  const month = window.MONTH_NAMES_LONG[date.getMonth()];
  const day = String(date.getDate()).padStart(2, "0");
  const year = date.getFullYear();

  let hours = date.getHours();
  const minutes = String(date.getMinutes()).padStart(2, "0");
  const ampm = hours >= 12 ? "PM" : "AM";
  hours = hours % 12;
  if (hours === 0) hours = 12;
  const hoursStr = String(hours).padStart(2, "0");

  return `${month} ${day}, ${year} ${hoursStr}:${minutes} ${ampm}`;
};

window.formatDate = function formatDate(dateString) {
  if (!dateString) return "";
  const date = new Date(dateString);
  if (isNaN(date.getTime())) return "";

  const month = window.MONTH_NAMES_LONG[date.getMonth()];
  const day = String(date.getDate()).padStart(2, "0");
  const year = date.getFullYear();

  return `${month} ${day}, ${year}`;
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
  document.querySelectorAll('input:not([type="radio"])').forEach((input) => {
    input.value = "";
  });

  document.querySelectorAll("select").forEach((input) => {
    input.value = "";
  });
  document.querySelectorAll("textarea").forEach((textarea) => {
    textarea.value = "";
  });
};

// Delegated show/hide-password toggle for any <button class="js-toggle-password"
// data-target="inputId"> paired with an <svg class="js-eye-icon"> inside it -
// same open/closed eye artwork as the login page, reused everywhere a
// password input appears.
window.PASSWORD_EYE_OPEN_PATHS = `
    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51
       7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431
       0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />`;

window.PASSWORD_EYE_CLOSED_PATHS = `
    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12c1.832
        4.068 5.728 7 10.066 7 1.676 0 3.285-.37
        4.712-1.034M6.228 6.228A10.45 10.45 0 0112
        5c4.38 0 8.293 2.953 10.07 7.063a10.522
        10.522 0 01-4.517 4.92M6.228 6.228L3 3m3.228
        3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0
        0a3 3 0 10-4.243-4.243m4.242 4.242L9.878
        9.878" />`;

document.addEventListener("click", (e) => {
  const btn = e.target.closest(".js-toggle-password");
  if (!btn) return;

  const input = document.getElementById(btn.dataset.target);
  const icon = btn.querySelector(".js-eye-icon");
  if (!input || !icon) return;

  const showing = input.type === "password";
  input.type = showing ? "text" : "password";
  icon.innerHTML = showing ? window.PASSWORD_EYE_CLOSED_PATHS : window.PASSWORD_EYE_OPEN_PATHS;
});
