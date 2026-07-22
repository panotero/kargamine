/**
 * Generic remote table controller - use this instead of DataTables
 * for any table backed by a Laravel paginate() endpoint.
 *
 * Handles: fetching rows via apiCall, rendering them with a template
 * function you provide, rendering Prev/Next + page-number pagination
 * from the paginator meta, and a search bar that only triggers on
 * button click or Enter (never on input/change).
 *
 * USAGE
 * -----
 *   const portsTable = createRemoteTable({
 *       url: '/api/ports',
 *       tableBodySelector: '[data-table-body="ports"]',
 *       paginationSelector: '[data-table-pagination="ports"]',
 *       searchInputSelector: '#portsSearchInput',
 *       searchButtonSelector: '#portsSearchBtn',
 *       emptyMessage: 'No ports found.',
 *       colspan: 4,
 *       rowTemplate: (row) => `<tr>...</tr>`,
 *       afterRender: (rows) => { ... rebind edit/delete buttons ... },
 *   });
 *
 *   portsTable.load();        // initial load, page 1
 *   portsTable.reload();      // reload current page + current search term
 *   portsTable.goToPage(3);   // jump to a specific page
 *
 * OPTIONS
 * -------
 *   url                   (required) base list endpoint
 *   tableBodySelector     (required) <tbody> to render rows into
 *   rowTemplate           (required) (row) => htmlString for one <tr>
 *   paginationSelector    optional - omit to skip pagination UI entirely
 *   searchInputSelector   optional - omit to skip search wiring
 *   searchButtonSelector  optional - required if searchInputSelector is set
 *   searchParam           query param name sent to the backend (default: 'search')
 *   emptyMessage          text shown when there are no rows (default: 'No records found.')
 *   colspan               colspan for the empty-state row (default: 6)
 *   paginated             set false if the endpoint returns a plain array,
 *                         not a Laravel paginator (disables pagination UI)
 *   afterRender(rows)     optional hook - called after every render, use it
 *                         to (re)bind row-level buttons (Edit/Delete/etc.)
 *   onError(response)     optional - overrides the default showMessage() error
 */
function createRemoteTable(options) {
  const {
    url,
    tableBodySelector,
    rowTemplate,
    paginationSelector = null,
    searchInputSelector = null,
    searchButtonSelector = null,
    searchParam = "search",
    emptyMessage = "No records found.",
    colspan = 6,
    paginated = true,
    afterRender = null,
    onError = null,
  } = options;

  let currentPage = 1;
  let currentSearch = "";
  let currentFilters = {};

  function buildUrl(page) {
    const separator = url.includes("?") ? "&" : "?";

    const params = new URLSearchParams();

    params.set("page", page);

    if (currentSearch) {
      params.set(searchParam, currentSearch);
    }

    Object.entries(currentFilters).forEach(([key, value]) => {
      if (
        value !== null &&
        value !== undefined &&
        value !== "" &&
        value !== "ALL"
      ) {
        params.set(key, value);
      }
    });

    return `${url}${separator}${params.toString()}`;
  }

  async function load(page = 1) {
    currentPage = page;

    const body = tableBodySelector;
    if (!body) return;

    const response = await apiCall({
      mode: "GET",
      url: buildUrl(page),
    });

    if (!response.success) {
      if (onError) {
        onError(response);
      } else {
        showMessage({
          status: "error",
          title: "Error",
          message:
            "Unable to load the list. Please contact the system administrator.",
        });
      }
      return;
    }

    const meta = paginated ? (response.data ?? {}) : null;
    const rows = paginated ? (meta.data ?? []) : (response.data ?? []);

    if (!rows.length) {
      body.innerHTML = `<tr><td colspan="${colspan}" class="px-4 py-10 text-center text-sm text-zinc-400 dark:text-zinc-500">${emptyMessage}</td></tr>`;
      renderPagination(null);
      return;
    }

    body.innerHTML = rows.map(rowTemplate).join("");

    if (afterRender) afterRender(rows);

    renderPagination(meta);
  }

  function reload() {
    return load(currentPage);
  }

  function goToPage(page) {
    return load(page);
  }

  function search(term) {
    currentSearch = term;
    return load(1);
  }

  function setFilter(key, value) {
    if (
      value === null ||
      value === undefined ||
      value === "" ||
      value === "ALL"
    ) {
      delete currentFilters[key];
    } else {
      currentFilters[key] = value;
    }

    return load(1);
  }

  function setFilters(filters) {
    currentFilters = {
      ...currentFilters,
      ...filters,
    };

    Object.keys(currentFilters).forEach((key) => {
      const value = currentFilters[key];

      if (
        value === null ||
        value === undefined ||
        value === "" ||
        value === "ALL"
      ) {
        delete currentFilters[key];
      }
    });

    return load(1);
  }

  // -------------------------------------------------------------
  // Pagination UI
  // -------------------------------------------------------------
  function renderPagination(meta) {
    if (!paginationSelector) return;

    const container = paginationSelector;
    if (!container) return;

    if (!meta || meta.last_page <= 1) {
      container.innerHTML = "";
      return;
    }

    const info = `Showing ${meta.from ?? 0}-${meta.to ?? 0} of ${meta.total ?? 0}`;
    const pages = buildPageWindow(meta.current_page, meta.last_page);

    const pageButtons = pages
      .map((p) => {
        if (p === "...") {
          return `<span class="px-2 text-sm text-zinc-400">...</span>`;
        }

        const isActive = p === meta.current_page;

        return `
                        <button type="button" data-page="${p}"
                                class="min-w-[2rem] px-2 py-1 text-sm rounded-md ${isActive ? "bg-orange-600 text-white" : "text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800"}">
                            ${p}
                        </button>
                    `;
      })
      .join("");

    container.innerHTML = `
                <div class="flex items-center justify-between px-4 py-3">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">${info}</p>
                    <div class="flex items-center gap-1">
                        <button type="button" data-page="${meta.current_page - 1}" ${meta.prev_page_url ? "" : "disabled"}
                                class="px-2 py-1 text-sm rounded-md text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 disabled:opacity-30 disabled:cursor-not-allowed">
                            Prev
                        </button>
                        ${pageButtons}
                        <button type="button" data-page="${meta.current_page + 1}" ${meta.next_page_url ? "" : "disabled"}
                                class="px-2 py-1 text-sm rounded-md text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 disabled:opacity-30 disabled:cursor-not-allowed">
                            Next
                        </button>
                    </div>
                </div>
            `;

    container.querySelectorAll("[data-page]:not([disabled])").forEach((btn) => {
      btn.addEventListener("click", () => goToPage(Number(btn.dataset.page)));
    });
  }

  // Builds a compact page-number window: 1 ... 4 [5] 6 ... 12
  function buildPageWindow(current, last) {
    const delta = 1;
    const range = [];

    for (
      let i = Math.max(1, current - delta);
      i <= Math.min(last, current + delta);
      i++
    ) {
      range.push(i);
    }

    if (range[0] > 1) {
      if (range[0] > 2) range.unshift("...");
      range.unshift(1);
    }

    if (range[range.length - 1] < last) {
      if (range[range.length - 1] < last - 1) range.push("...");
      range.push(last);
    }

    return range;
  }

  // -------------------------------------------------------------
  // Search wiring - debounced as-you-type, plus button/Enter as
  // instant triggers. Typing resets the debounce timer each
  // keystroke; the API call only fires after a pause.
  // -------------------------------------------------------------
  const SEARCH_DEBOUNCE_MS = 400;
  let searchDebounceTimer = null;

  if (searchInputSelector) {
    const input = searchInputSelector;
    const button = searchButtonSelector;

    if (input) {
      input.addEventListener("input", () => {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => {
          search(input.value.trim());
        }, SEARCH_DEBOUNCE_MS);
      });

      input.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
          e.preventDefault();
          clearTimeout(searchDebounceTimer);
          search(input.value.trim());
        }
      });
    }

    if (button) {
      button.addEventListener("click", () => {
        clearTimeout(searchDebounceTimer);
        search(input.value.trim());
      });
    }
  }

  return {
    load,
    reload,
    goToPage,
    search,
    setFilter,
    setFilters,
  };
}

window.createRemoteTable = createRemoteTable;

window.renderRemoteTable = function renderRemoteTable(payload) {
  const { url, tableId, afterRenderFunction, thead } = payload;
  const table = document.getElementById(tableId);
  const tableSearchInput = table.querySelector(".table-search-input");
  const tableSearchButton = table.querySelector(".table-search-button");
  const tableHeader = table.querySelector(".table-header");
  const tableBody = table.querySelector(".table-body");
  const tablePagination = table.querySelector(".table-pagination");
  const header = thead;

  const headCells = header
    .map(
      (col) =>
        `<th class="px-4 py-2.5 text-left text-xs font-medium uppercase tracking-wide bg-orange-500 text-white whitespace-nowrap">${col.title}</th>`,
    )
    .join("");
  tableHeader.innerHTML = headCells;

  const tables = window.createRemoteTable({
    url: url,
    tableBodySelector: tableBody,
    paginationSelector: tablePagination,
    searchInputSelector: tableSearchInput,
    searchButtonSelector: tableSearchButton,
    emptyMessage: `No records yet.`,
    colspan: header.length,
    rowTemplate: (row) => buildRow(header, row),
    afterRender: () => {
      tableBody.querySelectorAll(".table-row").forEach((row) => {
        //funnction
        if (typeof afterRenderFunction === "function") {
          afterRenderFunction(row);
        }
      });
    },
  });

  function buildRow(header, row) {
    const cells = header
      .map((col) => {
        const value = col.render
          ? col.render(row)
          : (getValueByPath(row, col.key) ?? "-");

        return `
            <td class="px-4 py-2.5 text-zinc-900 dark:text-zinc-100 whitespace-nowrap">
                ${value}
            </td>
        `;
      })
      .join("");

    return `
    <tr class="table-row cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800"
    data-row='${JSON.stringify(row)}'>
        ${cells}
    </tr>
`;
  }

  function getValueByPath(obj, path) {
    return path.split(".").reduce((value, key) => value?.[key], obj);
  }
  return tables;
};
