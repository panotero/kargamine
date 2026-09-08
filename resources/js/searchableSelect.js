// -------------------- SEARCHABLE SELECT --------------------
// Wraps a plain native <select> with hundreds of options in a
// type-to-search text input + dropdown menu, WITHOUT removing the
// <select> from the DOM. The <select> stays hidden but remains the
// single source of truth - existing code that reads `.value` off it,
// or listens for its `change` event (e.g. cascading location -> port
// filters), keeps working untouched.
//
// USAGE
// -----
//   const handle = window.makeSearchableSelect(document.querySelector('#originPortId'));
//   handle.refresh();   // call after rebuilding <option>s via innerHTML, or after
//                        // programmatically setting selectEl.value
//   handle.destroy();   // tear down (e.g. when a repeatable row is removed)
//
// The widget never dispatches `change` on refresh() - only on an actual
// user selection or on an outside-click that clears the typed text.

(function () {
  function escapeHtml(value) {
    return String(value ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  function deriveInputClass(selectEl) {
    const tokens = (selectEl.className || "").split(/\s+/).filter(Boolean);
    const kept = tokens.filter((token) => !token.endsWith("-select"));
    kept.push("searchable-select-input");
    return kept.join(" ");
  }

  let searchableSelectUid = 0;

  window.makeSearchableSelect = function makeSearchableSelect(selectEl) {
    try {
      if (!selectEl || selectEl.tagName !== "SELECT") return null;
      if (selectEl._searchableSelect) return selectEl._searchableSelect;

      selectEl.style.display = "none";

      const wrapper = document.createElement("div");
      wrapper.className = "searchable-select relative";

      const input = document.createElement("input");
      input.type = "text";
      input.autocomplete = "off";
      input.className = deriveInputClass(selectEl);

      const menu = document.createElement("div");
      menu.className =
        "searchable-select-menu absolute z-50 mt-1 w-full max-h-56 overflow-auto rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-lg hidden";

      const menuId = selectEl.id
        ? `${selectEl.id}-searchable-menu`
        : `searchable-select-menu-${++searchableSelectUid}`;
      menu.id = menuId;
      menu.setAttribute("role", "listbox");

      input.setAttribute("role", "combobox");
      input.setAttribute("aria-autocomplete", "list");
      input.setAttribute("aria-haspopup", "listbox");
      input.setAttribute("aria-expanded", "false");
      input.setAttribute("aria-controls", menuId);

      wrapper.appendChild(input);
      wrapper.appendChild(menu);

      selectEl.insertAdjacentElement("afterend", wrapper);

      let options = []; // [{ value, label }] - excludes the blank placeholder option
      let filtered = [];
      let highlightIndex = -1;

      function readOptionsFromSelect() {
        const nextOptions = [];
        let placeholderText = "";

        Array.from(selectEl.options).forEach((opt) => {
          if (opt.value === "") {
            placeholderText = opt.textContent || "";
            return;
          }
          nextOptions.push({ value: opt.value, label: opt.textContent || "" });
        });

        options = nextOptions;
        input.placeholder = placeholderText;
      }

      function currentSelectedLabel() {
        const val = selectEl.value;
        if (val === "" || val === undefined || val === null) return "";
        const found = options.find((o) => o.value === val);
        return found ? found.label : "";
      }

      function renderMenu() {
        if (!filtered.length) {
          menu.innerHTML =
            '<div class="px-2 py-1.5 text-sm text-zinc-400">No matches</div>';
          input.removeAttribute("aria-activedescendant");
          return;
        }

        menu.innerHTML = filtered
          .map((opt, idx) => {
            const highlightClass =
              idx === highlightIndex ? " bg-orange-500 text-white" : "";
            return `<div role="option" id="${menuId}-option-${idx}" data-value="${escapeHtml(opt.value)}" data-index="${idx}" class="px-2 py-1.5 text-sm cursor-pointer text-zinc-900 dark:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-700${highlightClass}">${escapeHtml(opt.label)}</div>`;
          })
          .join("");

        if (highlightIndex >= 0) {
          const highlighted = menu.querySelector(
            `[data-index="${highlightIndex}"]`,
          );
          if (highlighted && highlighted.scrollIntoView) {
            highlighted.scrollIntoView({ block: "nearest" });
          }
          input.setAttribute(
            "aria-activedescendant",
            `${menuId}-option-${highlightIndex}`,
          );
        } else {
          input.removeAttribute("aria-activedescendant");
        }
      }

      function isOpen() {
        return !menu.classList.contains("hidden");
      }

      function openMenu() {
        if (input.disabled) return;
        filtered = options.slice();
        highlightIndex = -1;
        renderMenu();
        menu.classList.remove("hidden");
        input.setAttribute("aria-expanded", "true");
      }

      function closeMenu() {
        menu.classList.add("hidden");
        input.setAttribute("aria-expanded", "false");
        input.removeAttribute("aria-activedescendant");
      }

      function applyFilter(term) {
        const needle = (term || "").toLowerCase();
        filtered = options.filter((o) => o.label.toLowerCase().includes(needle));
        highlightIndex = filtered.length ? 0 : -1;
        renderMenu();
      }

      function selectOption(opt) {
        selectEl.value = opt.value;
        selectEl.dispatchEvent(new Event("change", { bubbles: true }));
        input.value = opt.label;
        closeMenu();
      }

      function closeAndReconcile() {
        closeMenu();

        const typed = input.value;

        if (typed === "") {
          // Only fire `change` if this actually clears a real selection.
          // Dispatching unconditionally would spam the cascading
          // location -> port handlers (and their rate lookups) on every
          // stray outside-click of an already-empty field.
          if (selectEl.value !== "") {
            selectEl.value = "";
            selectEl.dispatchEvent(new Event("change", { bubbles: true }));
          }
          return;
        }

        const selectedLabel = currentSelectedLabel();
        if (typed !== selectedLabel) {
          input.value = selectedLabel;
        }
      }

      function onDocumentMouseDown(e) {
        if (!isOpen()) return;
        if (wrapper.contains(e.target)) return;
        closeAndReconcile();
      }

      input.addEventListener("focus", () => {
        openMenu();
      });

      input.addEventListener("click", () => {
        openMenu();
      });

      input.addEventListener("input", () => {
        if (!isOpen()) menu.classList.remove("hidden");
        applyFilter(input.value);
      });

      input.addEventListener("keydown", (e) => {
        if (e.key === "ArrowDown") {
          e.preventDefault();
          if (!isOpen()) {
            openMenu();
            return;
          }
          if (!filtered.length) return;
          highlightIndex = Math.min(highlightIndex + 1, filtered.length - 1);
          renderMenu();
        } else if (e.key === "ArrowUp") {
          e.preventDefault();
          if (!isOpen()) {
            openMenu();
            return;
          }
          if (!filtered.length) return;
          highlightIndex = Math.max(highlightIndex - 1, 0);
          renderMenu();
        } else if (e.key === "Enter") {
          e.preventDefault();
          if (isOpen() && highlightIndex >= 0 && filtered[highlightIndex]) {
            selectOption(filtered[highlightIndex]);
          }
        } else if (e.key === "Escape") {
          closeMenu();
          input.value = currentSelectedLabel();
        } else if (e.key === "Tab") {
          // Keyboard-only exit never produces a document mousedown, so
          // reconcile here or the menu would be left open with stale
          // typed text still showing.
          closeAndReconcile();
        }
      });

      menu.addEventListener("mousedown", (e) => {
        const item = e.target.closest('[role="option"]');
        if (!item) return;
        e.preventDefault();
        const idx = Number(item.dataset.index);
        const opt = filtered[idx];
        if (!opt) return;
        selectOption(opt);
      });

      document.addEventListener("mousedown", onDocumentMouseDown);

      function syncFromSelect() {
        readOptionsFromSelect();
        input.disabled = !!selectEl.disabled;
        input.value = currentSelectedLabel();

        // If the menu happened to be open across an option rebuild, show
        // the new full list rather than filtering it down to the single
        // just-restored label.
        if (isOpen()) {
          filtered = options.slice();
          highlightIndex = -1;
          renderMenu();
        }
      }

      function destroy() {
        document.removeEventListener("mousedown", onDocumentMouseDown);
        wrapper.remove();
        selectEl.style.display = "";
        delete selectEl._searchableSelect;
      }

      const handle = {
        refresh: syncFromSelect,
        destroy: destroy,
        input: input,
        wrapper: wrapper,
        select: selectEl,
      };

      selectEl._searchableSelect = handle;

      syncFromSelect();

      return handle;
    } catch (err) {
      console.error("makeSearchableSelect failed:", err);
      return null;
    }
  };
})();
