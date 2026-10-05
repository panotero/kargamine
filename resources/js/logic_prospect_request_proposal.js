// "Request for Proposal" wizard modal - opened from the assigned
// CSR/Relationship Manager's "My Requests" page under Proposals (see
// logic_proposal_requests_mine.js). Deliberately its own file/IIFE (same
// reasoning as logic_prospect_add_modals.js's duplication of small
// display-only helpers rather than reaching into logic_prospect_modal.js's
// closure). The Proposal Request row is always minted eagerly by the TEMP
// CSR before this modal ever opens (see the Prospect Info modal's Contact
// Summary button, logic_prospect_info_modal.js) - this file always receives
// a known, non-null proposalRequestId and never mints one itself. It's a
// wholly separate concept from the prospect's own Requirements ("prospect
// products") - see logic_prospect_modal.js.
// window.prospectInfo is the single source of truth for the current
// prospect payload: every mutating action here calls
// window.prospectInfo.reload(uuid) then re-reads
// window.prospectInfo.getCurrentLead() rather than keeping its own copy.
// Whichever page this wizard is opened from defines that pair itself,
// scoped to its own root element (logic_proposal_requests_mine.js's
// initProposalRequestsMinePage(), or logic_prospect_info_modal.js's
// initProspectInfoModal() - every logic_*.js file loads globally, so each
// page must only claim window.prospectInfo while its own DOM is present).
(function () {
  const EMPTY = "—";
  const RPM_TABS = ["CompanyDetails", "OriginDestination", "Products", "Confirmation"];

  // Fixed Service Type list (replaces the Delivery Types LOV on every
  // product form's Service Type select) - value IS the label string,
  // POSTed as `service_type`, not an id. Mirrors logic_prospect_modal.js's
  // own copy (kept duplicated per this file's "own local cache" precedent -
  // see the Products-tab lookups section below).
  const SERVICE_TYPES = ["Door - Door", "Door - Pier", "Pier - Door", "Pier - Pier"];
  const serviceTypeOptionsHtml =
    '<option value="">Select Service Type</option>' +
    SERVICE_TYPES.map((t) => `<option value="${t}">${t}</option>`).join("");

  let rpmLead = null;
  // Which of this prospect's (possibly several) Proposal Requests this
  // wizard instance is editing - set explicitly by openRequestProposalModal(),
  // never inferred from a singular "the" request, since multiple Draft/
  // Pending requests can exist for the same prospect at once.
  let rpmProposalRequestId = null;
  // Currently chip-selected prospect address (if any) seeding the Origin &
  // Destination form's fields - see renderOdChips()/hydrateOdCard(). Purely
  // a convenience autofill source and an optional "seeded from" reference
  // sent alongside the Add payload; the form itself is always editable and
  // clicking a chip never removes it from the suggestion list (each Add
  // creates its own independent snapshot entry, see addOdLocation()).
  // Cleared after a successful add and on tab (re)open.
  let rpmSelectedOdLocationId = null;
  // The single live address-form card built via
  // window.prospectShared.locationCardHtml()/initLocationPsgcCascade() for
  // the Origin & Destination tab - (re)built once per tab-open, not on
  // every renderOriginDestination() call, so PSGC's /provinces endpoint
  // isn't re-fetched on every incidental lead refresh.
  let rpmOdCard = null;

  function getMyProposalRequest(lead) {
    return (lead?.proposal_requests ?? []).find((pr) => pr.id === rpmProposalRequestId) ?? null;
  }

  function esc(v) {
    return String(v ?? "").replace(/[&<>"']/g, (c) => ({
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#39;",
    }[c]));
  }

  function formatLocationLabel(loc) {
    const line = [loc.address_no, loc.address_building, loc.address_street, loc.address_barangay, loc.address_town_city, loc.address_province]
      .filter(Boolean)
      .join(", ") || "Unnamed location";
    return loc.address_type ? `${loc.address_type} — ${line}` : line;
  }

  function formatContactName(contact) {
    return [contact.title, contact.first_name, contact.middle_name, contact.last_name].filter(Boolean).join(" ") || EMPTY;
  }

  function refreshSearchable(el) {
    el?._searchableSelect?.refresh();
  }

  // ------------------------------------------------------------------
  // TAB SWITCHING
  // ------------------------------------------------------------------
  function switchRpmTab(modal, tab) {
    RPM_TABS.forEach((t) => {
      modal.querySelector(`[data-rpm-pane="${t}"]`)?.classList.toggle("hidden", t !== tab);
      const btn = modal.querySelector(`[data-rpm-tab="${t}"]`);
      if (!btn) return;
      const active = t === tab;
      btn.classList.toggle("border-orange-500", active);
      btn.classList.toggle("text-orange-600", active);
      btn.classList.toggle("dark:text-orange-400", active);
      btn.classList.toggle("border-transparent", !active);
      btn.classList.toggle("text-zinc-500", !active);
      btn.classList.toggle("dark:text-zinc-400", !active);
    });
  }

  function bindRpmTabs(modal) {
    RPM_TABS.forEach((t) => {
      modal.querySelector(`[data-rpm-tab="${t}"]`)?.addEventListener("click", () => switchRpmTab(modal, t));
    });
  }

  // ------------------------------------------------------------------
  // SHARED: location dropdowns (Company Details' address picker) - sourced
  // from this prospect's own addresses, not the master Locations catalog.
  // Origin & Destination has its own chip+preview picker instead (see
  // renderOdChips() below).
  // ------------------------------------------------------------------
  function populateLocationDropdowns(modal, lead) {
    const options = (lead.addresses ?? [])
      .map((loc) => `<option value="${loc.id}">${esc(formatLocationLabel(loc))}</option>`)
      .join("");
    modal.querySelectorAll(".rpmLocationDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = `<option value="">Select Location</option>${options}`;
      el.value = current;
      refreshSearchable(el);
    });
  }

  function populateSignatoryDropdown(modal, lead) {
    const alreadyAdded = new Set((getMyProposalRequest(lead)?.signatories ?? []).map((s) => s.prospect_contact_id));
    const options = (lead.contacts ?? [])
      .filter((c) => !alreadyAdded.has(c.id))
      .map((c) => `<option value="${c.id}">${esc(formatContactName(c))}</option>`)
      .join("");
    const el = modal.querySelector("#rpmSignatoryContact");
    el.innerHTML = `<option value="">Select Contact</option>${options}`;
    refreshSearchable(el);
  }

  // ------------------------------------------------------------------
  // TAB: COMPANY DETAILS
  // ------------------------------------------------------------------
  function renderSignatoriesList(modal, lead) {
    const signatories = getMyProposalRequest(lead)?.signatories ?? [];
    modal.querySelector("#rpmSignatoriesEmpty").classList.toggle("hidden", signatories.length > 0);
    modal.querySelector("#rpmSignatoriesList").innerHTML = signatories
      .map(
        (s) => `
      <div class="flex justify-between items-center border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2">
          <p class="text-sm text-zinc-800 dark:text-zinc-100">${esc(formatContactName(s.prospect_contact ?? {}))}</p>
          <button type="button" class="rpm-remove-signatory text-red-500 text-xs font-medium shrink-0" data-id="${s.id}">✕ Remove</button>
      </div>`
      )
      .join("");

    modal.querySelectorAll(".rpm-remove-signatory").forEach((btn) => {
      btn.addEventListener("click", () => removeSignatory(Number(btn.dataset.id)));
    });
  }

  function renderCompanyDetails(modal, lead) {
    const companyDetail = getMyProposalRequest(lead)?.company_detail ?? null;

    modal.querySelector("#rpmAssignedCsr").textContent = lead.user?.name || EMPTY;
    modal.querySelector("#rpmAssignedRm").textContent = lead.relationship_manager?.name || EMPTY;
    modal.querySelector("#rpmCompanyNameLabel").textContent = lead.client_type === "corporate" ? "Company Name" : "Client Name";
    modal.querySelector("#rpmCompanyName").value = companyDetail?.company_name ?? lead.company?.company_name ?? "";

    const locationSelect = modal.querySelector("#rpmCompanyLocation");
    locationSelect.value = String(companyDetail?.prospect_location_id ?? "");
    refreshSearchable(locationSelect);

    populateSignatoryDropdown(modal, lead);
    renderSignatoriesList(modal, lead);
  }

  async function saveCompanyDetails(modal, button) {
    const lead = rpmLead;
    if (!lead) return;

    const payload = {
      company_name: modal.querySelector("#rpmCompanyName").value.trim() || null,
      prospect_location_id: modal.querySelector("#rpmCompanyLocation").value || null,
    };

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${rpmProposalRequestId}/companyDetails`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Saving", message: response.message ?? "" });
      return;
    }

    showMessage({ status: "success", title: "Company Details Saved" });
    await refreshLead(modal);
  }

  async function addSignatory(button) {
    const lead = rpmLead;
    if (!lead) return;

    const modal = document.getElementById("RequestProposalModal");
    const contactId = modal.querySelector("#rpmSignatoryContact").value;
    if (!contactId) {
      showMessage({ status: "error", title: "Select a contact first." });
      return;
    }

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: { prospect_contact_id: contactId },
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${rpmProposalRequestId}/signatories`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Adding", message: response.message ?? "" });
      return;
    }

    showMessage({ status: "success", title: "Authorized Signatory Added" });
    await refreshLead(modal);
  }

  async function removeSignatory(id) {
    const lead = rpmLead;
    if (!lead) return;

    const confirmed = await customConfirm("Remove this authorized signatory?");
    if (!confirmed) return;

    const modal = document.getElementById("RequestProposalModal");
    const response = await apiCall({
      mode: "DELETE",
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${rpmProposalRequestId}/signatories/${id}`,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Removing", message: response.message ?? "" });
      return;
    }

    showMessage({ status: "success", title: "Authorized Signatory Removed" });
    await refreshLead(modal);
  }

  // ------------------------------------------------------------------
  // TAB: ORIGIN & DESTINATION
  // ------------------------------------------------------------------
  function odRowHtml(row) {
    return `
    <tr data-id="${row.id}">
        <td class="px-3 py-2">${esc(row.address_type || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_no || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_building || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_street || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_country || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_province || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_town_city || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_barangay || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_postal_code || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.location_mnemonic || EMPTY)}</td>
        <td class="px-3 py-2"><button type="button" class="rpm-remove-od text-red-500 text-xs font-medium" data-id="${row.id}">✕ Remove</button></td>
    </tr>`;
  }

  // Builds the single live address-form card (once per tab-open, cached in
  // rpmOdCard - see its declaration above) using the same shared helpers
  // AddLocationModal already uses for exactly this "one-off editable
  // address, not a repeatable list" shape. Trimmed of the Remove button
  // (nothing to remove - there's only ever one card) and the Primary radio
  // (doesn't apply to an O&D entry).
  function ensureOdCard(modal) {
    const wrap = modal.querySelector("#rpmOdCardWrap");
    if (rpmOdCard && wrap.contains(rpmOdCard)) return rpmOdCard;

    wrap.innerHTML = window.prospectShared.locationCardHtml(0);
    const card = wrap.firstElementChild;
    card.querySelector(".pm-remove-location")?.remove();
    card.querySelector(".pm-primary-radio")?.closest("label")?.remove();
    window.prospectShared.initLocationPsgcCascade(card);
    rpmOdCard = card;
    return card;
  }

  // Autofills the card from a clicked suggestion chip's address - mirrors
  // logic_prospect_modal.js's hydrateLocationCard() (not exported via
  // window.prospectShared, so replicated here), minus the Primary-radio
  // line which doesn't exist on this trimmed card. The form stays fully
  // editable afterward - this is a starting point, not a lock.
  async function hydrateOdCard(card, loc) {
    ["address_no", "address_building", "address_street", "address_postal_code"].forEach((field) => {
      const el = card.querySelector(`[data-field="${field}"]`);
      if (el) el.value = loc[field] ?? "";
    });

    const typeSelect = card.querySelector('[data-field="address_type"]');
    if (typeSelect) typeSelect.value = loc.address_type ?? "";

    const countrySelect = card.querySelector('[data-field="address_country"]');
    if (countrySelect) countrySelect.value = loc.address_country || "Philippines";

    const { loadCities, loadBarangays } = card._locationLookups ?? {};
    const provinceSelect = card.querySelector('[data-field="address_province"]');
    const citySelect = card.querySelector('[data-field="address_town_city"]');
    const barangaySelect = card.querySelector('[data-field="address_barangay"]');

    if (loc.address_province && provinceSelect) {
      provinceSelect.value = loc.address_province;
      refreshSearchable(provinceSelect);

      if (provinceSelect.tagName === "SELECT" && loadCities) {
        const provinceCode = provinceSelect.selectedOptions[0]?.dataset.code;
        await loadCities(provinceCode);

        const freshCitySelect = card.querySelector('[data-field="address_town_city"]');
        if (loc.address_town_city && freshCitySelect) {
          freshCitySelect.value = loc.address_town_city;
          refreshSearchable(freshCitySelect);

          if (freshCitySelect.tagName === "SELECT" && loadBarangays) {
            const cityCode = freshCitySelect.selectedOptions[0]?.dataset.code;
            await loadBarangays(cityCode);

            const freshBarangaySelect = card.querySelector('[data-field="address_barangay"]');
            if (loc.address_barangay && freshBarangaySelect) {
              freshBarangaySelect.value = loc.address_barangay;
              refreshSearchable(freshBarangaySelect);
            }
          }
        }
      } else {
        // Already degraded to manual text inputs - just fill the values.
        if (loc.address_town_city && citySelect) citySelect.value = loc.address_town_city;
        if (loc.address_barangay && barangaySelect) barangaySelect.value = loc.address_barangay;
      }
    }
  }

  function collectOdCard(card) {
    const obj = {};
    card.querySelectorAll("[data-field]").forEach((el) => {
      obj[el.dataset.field] = el.value;
    });
    return obj;
  }

  // Suggestion chips for ALL of this prospect's own addresses - never
  // filtered by what's already been added (each Add below creates its own
  // independent snapshot entry, so the same seed address can legitimately
  // start more than one). Clicking a chip is a pure convenience autofill;
  // it does not append a row by itself - the "+ Add" button
  // (addOdLocation()) does that, and works even with no chip ever clicked.
  function renderOdChips(modal, lead) {
    const addresses = lead.addresses ?? [];

    modal.querySelector("#rpmOdChipsEmpty").classList.toggle("hidden", addresses.length > 0);
    modal.querySelector("#rpmOdChips").innerHTML = addresses
      .map((loc) => {
        const active = loc.id === rpmSelectedOdLocationId;
        const activeClasses = active
          ? "border-orange-500 text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-950/20"
          : "border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 hover:border-orange-400 dark:hover:border-orange-500";
        return `<button type="button" class="rpm-od-chip px-3 py-1.5 rounded-lg border text-sm font-medium ${activeClasses}" data-id="${loc.id}" aria-pressed="${active}">${esc(formatLocationLabel(loc))}</button>`;
      })
      .join("");

    modal.querySelectorAll(".rpm-od-chip").forEach((btn) => {
      btn.addEventListener("click", async () => {
        rpmSelectedOdLocationId = Number(btn.dataset.id);
        renderOdChips(modal, lead);
        const loc = addresses.find((a) => a.id === rpmSelectedOdLocationId);
        if (loc) await hydrateOdCard(ensureOdCard(modal), loc);
      });
    });
  }

  function renderOriginDestination(modal, lead) {
    const rows = getMyProposalRequest(lead)?.locations ?? [];
    modal.querySelector("#rpmOdEmpty").classList.toggle("hidden", rows.length > 0);
    modal.querySelector("#rpmOdTableWrap").classList.toggle("hidden", rows.length === 0);
    modal.querySelector("#rpmOdTableBody").innerHTML = rows.map(odRowHtml).join("");

    modal.querySelectorAll(".rpm-remove-od").forEach((btn) => {
      btn.addEventListener("click", () => removeOdLocation(Number(btn.dataset.id)));
    });

    ensureOdCard(modal);
    renderOdChips(modal, lead);
  }

  async function addOdLocation(button) {
    const lead = rpmLead;
    if (!lead) return;

    const modal = document.getElementById("RequestProposalModal");
    const payload = { ...collectOdCard(ensureOdCard(modal)), prospect_location_id: rpmSelectedOdLocationId };

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${rpmProposalRequestId}/locations`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Adding", message: response.message ?? "" });
      return;
    }

    showMessage({ status: "success", title: "Location Added" });
    rpmSelectedOdLocationId = null;
    rpmOdCard = null; // next render builds a fresh blank card for the next entry
    await refreshLead(modal);
  }

  async function removeOdLocation(id) {
    const lead = rpmLead;
    if (!lead) return;

    const confirmed = await customConfirm("Remove this origin/destination location?");
    if (!confirmed) return;

    const modal = document.getElementById("RequestProposalModal");
    const response = await apiCall({
      mode: "DELETE",
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${rpmProposalRequestId}/locations/${id}`,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Removing", message: response.message ?? "" });
      return;
    }

    showMessage({ status: "success", title: "Location Removed" });
    await refreshLead(modal);
  }

  // ------------------------------------------------------------------
  // TAB: PROPOSAL REQUEST CONFIRMATION
  // ------------------------------------------------------------------
  // ------------------------------------------------------------------
  // Read-only recap builder - shared by this wizard's own Confirmation tab
  // AND the "My Requests" page's read-only Proposal Request Summary modal
  // (see logic_proposal_requests_mine.js's openProposalRequestSummary()).
  // Exposed on window because that modal has no access to this file's closure.
  // Pure display: never wires any click handlers, since the summary modal
  // context has none of this wizard's own handlers/module state.
  // ------------------------------------------------------------------
  function recapTableHtml(title, headers, bodyRowsHtml) {
    return `
    <div class="space-y-2">
        <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">${esc(title)}</p>
        <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
            <table class="min-w-full text-xs">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                    <tr>${headers.map((h) => `<th class="px-3 py-2 text-left">${esc(h)}</th>`).join("")}</tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">${bodyRowsHtml}</tbody>
            </table>
        </div>
    </div>`;
  }

  function odRecapRowHtml(row) {
    return `
    <tr>
        <td class="px-3 py-2">${esc(row.address_type || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_no || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_building || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_street || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_country || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_province || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_town_city || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_barangay || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.address_postal_code || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.location_mnemonic || EMPTY)}</td>
    </tr>`;
  }

  // ------------------------------------------------------------------
  // PRODUCTS RECAP - grouped, running-numbered line items (replaces the
  // old one-table-per-raw-row-list recap). A "line item" is one distinct
  // (product type [+subtype] + route) combination - rows sharing the same
  // merge key collapse into a single line with quantity summed. Charter is
  // the one exception: never merged, one line per charter row (its "route"
  // is its first/last port call, its "qty" a cargo-line-count string, not
  // a real countable unit like the others).
  // ------------------------------------------------------------------
  const LINE_ITEM_TYPE_ORDER = ["CV", "FR", "RF", "RC", "LC", "TR", "CH"];
  // Same dot-color convention as logic_prospect_modal.js's
  // CONTAINER_TYPE_DOT_COLOR (CV/FR/RF/LC/RC) - duplicated here rather than
  // reached into (not exposed via window.prospectShared, and this file
  // already duplicates other small lookups per its own header comment) -
  // plus two new colors for Trucking/Charter, which have no prior mapping.
  const LINE_ITEM_TYPE_META = {
    CV: { label: "Container Van", dot: "bg-orange-500" },
    FR: { label: "Flat Rack", dot: "bg-amber-500" },
    RF: { label: "Reefer Van", dot: "bg-cyan-500" },
    RC: { label: "Rolling Cargo", dot: "bg-blue-500" },
    LC: { label: "Break Bulk Cargo (BB)", dot: "bg-purple-500" },
    TR: { label: "Trucking", dot: "bg-emerald-500" },
    CH: { label: "Charter", dot: "bg-rose-500" },
  };
  // Builds the flat, pre-numbering list of line items in display order
  // (merge groups in first-seen order, Charter rows appended unmerged) -
  // see the brief's merge-key table for exactly what collapses into what.
  function collectProductLineItems(pr) {
    const merged = new Map();
    const items = [];

    function addOrMerge(key, base, qtyDelta) {
      const existing = merged.get(key);
      if (existing) {
        existing.qty += qtyDelta;
        return;
      }
      const item = { ...base, qty: qtyDelta };
      merged.set(key, item);
      items.push(item);
    }

    (pr?.product_containers ?? []).forEach((row) => {
      const origin = row.origin_location?.location_mnemonic || EMPTY;
      const dest = row.destination_location?.location_mnemonic || EMPTY;
      const size = row.container_size?.size || EMPTY;
      addOrMerge(
        `container_${row.container_type}_${size}_${origin}_${dest}`,
        { typeCode: row.container_type, detail: size, origin, dest },
        Number(row.quantity) || 0
      );
    });

    (pr?.product_rolling_cargo ?? []).forEach((row) => {
      const origin = row.origin_location?.location_mnemonic || EMPTY;
      const dest = row.destination_location?.location_mnemonic || EMPTY;
      const cargoType = row.cargo_type || EMPTY;
      addOrMerge(
        `rolling_${cargoType}_${origin}_${dest}`,
        { typeCode: "RC", detail: cargoType, origin, dest },
        Number(row.cargo_quantity) || 0
      );
    });

    (pr?.product_loose_cargo ?? []).forEach((row) => {
      const origin = row.origin_location?.location_mnemonic || EMPTY;
      const dest = row.destination_location?.location_mnemonic || EMPTY;
      const cargoType = row.cargo_type || EMPTY;
      addOrMerge(
        `loose_${cargoType}_${origin}_${dest}`,
        { typeCode: "LC", detail: cargoType, origin, dest },
        Number(row.cargo_quantity) || 0
      );
    });

    (pr?.product_truckings ?? []).forEach((row) => {
      const origin = row.origin_location?.location_mnemonic || EMPTY;
      const dest = row.destination_location?.location_mnemonic || EMPTY;
      addOrMerge(
        `trucking_${row.trucking_cargo_type}_${origin}_${dest}`,
        { typeCode: "TR", detail: row.trucking_cargo_type || EMPTY, origin, dest },
        Number(row.quantity) || 0
      );
    });

    (pr?.product_charters ?? []).forEach((row) => {
      const ports = row.ports ?? [];
      const origin = ports[0]?.port?.name || EMPTY;
      const dest = ports.length ? ports[ports.length - 1]?.port?.name || EMPTY : EMPTY;
      const cargoCount = (row.cargo_items ?? []).length;
      items.push({
        typeCode: "CH",
        detail: row.vessel_name || EMPTY,
        origin,
        dest,
        qty: `${cargoCount} cargo line${cargoCount === 1 ? "" : "s"}`,
      });
    });

    return items;
  }

  function lineItemRouteHtml(origin, dest) {
    const pill = (text) =>
      `<span class="px-2 py-0.5 rounded-full border border-zinc-300 dark:border-zinc-700 text-[11px] text-zinc-700 dark:text-zinc-200 whitespace-nowrap">${esc(text || EMPTY)}</span>`;
    return `<div class="flex flex-wrap items-center gap-1.5">${pill(origin)}<span class="text-orange-500 font-bold">&rarr;</span>${pill(dest)}</div>`;
  }

  function lineItemRowHtml(item) {
    return `
    <tr>
        <td class="px-3 py-2 font-mono text-zinc-700 dark:text-zinc-200 whitespace-nowrap">${esc(item.itemNo)}</td>
        <td class="px-3 py-2">${esc(item.detail || EMPTY)}</td>
        <td class="px-3 py-2">${lineItemRouteHtml(item.origin, item.dest)}</td>
        <td class="px-3 py-2 text-right font-semibold">${esc(item.qty)}</td>
    </tr>`;
  }

  // Grouped tables + a SINGLE running Item No. counter across every line
  // item on the page (not reset per type group), per brief.
  function buildProductsRecapBlock(pr) {
    const items = collectProductLineItems(pr);

    if (!items.length) {
      return `
      <div class="space-y-2">
          <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Products</p>
          <p class="text-sm text-zinc-400 dark:text-zinc-500">No products added.</p>
      </div>`;
    }

    const code = pr?.code || "RQ-YY-XXXXXX";
    let counter = 0;
    items.forEach((item) => {
      counter += 1;
      item.itemNo = `${code}-${String(counter).padStart(2, "0")}`;
    });

    const tables = LINE_ITEM_TYPE_ORDER.filter((type) => items.some((i) => i.typeCode === type))
      .map((type) => {
        const meta = LINE_ITEM_TYPE_META[type];
        const rows = items.filter((i) => i.typeCode === type);
        return `
        <div class="space-y-2">
            <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full ${meta.dot} shrink-0"></span>${esc(meta.label)}
            </p>
            <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                <table class="min-w-full table-fixed text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        <tr>
                            <th class="w-[20%] px-3 py-2 text-left">Item No.</th>
                            <th class="w-[16%] px-3 py-2 text-left">Detail</th>
                            <th class="w-[44%] px-3 py-2 text-left">Route</th>
                            <th class="w-[20%] px-3 py-2 text-right">Qty</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">${rows.map(lineItemRowHtml).join("")}</tbody>
                </table>
            </div>
        </div>`;
      })
      .join("");

    return `
    <div class="space-y-4">
        <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Products</p>
        ${tables}
    </div>`;
  }

  function buildProposalRequestRecapHtml(lead, pr) {
    const companyDetail = pr?.company_detail ?? null;
    const signatories = pr?.signatories ?? [];
    const locations = pr?.locations ?? [];
    const nameLabel = lead.client_type === "corporate" ? "Company Name" : "Client Name";

    const companyRows = [
      [nameLabel, companyDetail?.company_name || EMPTY],
      ["Location Address", companyDetail?.prospect_location ? formatLocationLabel(companyDetail.prospect_location) : EMPTY],
      ["Assigned CSR", lead.user?.name || EMPTY],
      ["Assigned Relationship Manager", lead.relationship_manager?.name || EMPTY],
      ["Authorized Signatories", signatories.length ? signatories.map((s) => formatContactName(s.prospect_contact ?? {})).join(", ") : EMPTY],
    ];

    const companyBlock = `
    <div class="space-y-3">
        <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Company Details</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            ${companyRows
              .map(
                ([label, value]) => `
            <div>
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">${esc(label)}</p>
                <p class="text-sm text-zinc-800 dark:text-zinc-100 mt-0.5">${esc(value)}</p>
            </div>`
              )
              .join("")}
        </div>
    </div>`;

    const odBlock = locations.length
      ? recapTableHtml(
          "Origin & Destination",
          ["Location Type", "No./Unit No.", "Building", "Street", "Country", "Province", "Town/City", "Barangay", "Postal Code", "Location Mnemonic"],
          locations.map(odRecapRowHtml).join("")
        )
      : `
    <div class="space-y-2">
        <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Origin & Destination</p>
        <p class="text-sm text-zinc-400 dark:text-zinc-500">No origin/destination locations added.</p>
    </div>`;

    const productsBlock = buildProductsRecapBlock(pr);

    return [companyBlock, odBlock, productsBlock].filter(Boolean).join("");
  }
  window.buildProposalRequestRecapHtml = buildProposalRequestRecapHtml;

  function renderConfirmation(modal, lead) {
    const pr = getMyProposalRequest(lead);
    modal.querySelector("#rpmConfirmationRecap").innerHTML = buildProposalRequestRecapHtml(lead, pr);
    modal.querySelector("#rpmSubmittedStatus").textContent = pr?.submitted_at ? `Submitted on ${pr.submitted_at}` : "";
  }

  async function submitProposalRequest(button) {
    const lead = rpmLead;
    if (!lead) return;
    if (!rpmProposalRequestId) {
      showMessage({ status: "error", title: "Nothing to Submit", message: "Save some details first (e.g. Company Details) before submitting." });
      return;
    }

    const modal = document.getElementById("RequestProposalModal");
    const response = await apiCall({
      mode: "POST",
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${rpmProposalRequestId}/submit`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Cannot Submit", message: response.message ?? "" });
      return;
    }

    showMessage({ status: "success", title: "Proposal Request Submitted" });
    await refreshLead(modal);
  }

  // Mirrors the server-side 409 guard (a request can't be filled out until
  // Management has assigned both a CSR and an RM - see
  // ProspectController::assignOwners()) purely so a click doesn't dead-end
  // in a confusing validation error. Read-only browsing and closing the
  // modal always still work; only the mutating controls below are disabled.
  const RPM_GATED_CONTROL_IDS = [
    "rpmSaveCompanyDetailsBtn",
    "rpmAddSignatoryBtn",
    "rpmAddOdBtn",
    "rpmAddProductBtn",
    "rpmSubmitBtn",
  ];
  function applyAssignmentGating(modal, lead) {
    const awaiting = !lead.is_fully_assigned;
    modal.querySelector("#rfpAwaitingAssignmentBanner").classList.toggle("hidden", !awaiting);
    RPM_GATED_CONTROL_IDS.forEach((id) => {
      const el = modal.querySelector(`#${id}`);
      if (el) el.disabled = awaiting;
    });
  }

  // ------------------------------------------------------------------
  // PUBLIC API
  // ------------------------------------------------------------------
  function renderAll(modal, lead) {
    rpmLead = lead;
    modal.querySelector("#rpmRequirementsCode").textContent = getMyProposalRequest(lead)?.code ?? "";
    populateLocationDropdowns(modal, lead);
    renderCompanyDetails(modal, lead);
    renderOriginDestination(modal, lead);
    renderProducts(modal, lead);
    renderConfirmation(modal, lead);
    applyAssignmentGating(modal, lead);
  }

  // Re-fetches the shared prospect payload via whichever page's own
  // window.prospectInfo.reload is currently defined (single source of
  // truth - see file header) then re-renders every tab here from the
  // refreshed data.
  async function refreshLead(modal) {
    if (!rpmLead) return;
    await window.prospectInfo.reload(rpmLead.uuid);
    const lead = window.prospectInfo.getCurrentLead();
    if (lead) renderAll(modal, lead);
  }

  // ==================================================================
  // TAB: PRODUCTS
  // ==================================================================
  // Small lookups/constants this tab needs that aren't already cached by
  // window.prospectShared (ports/cargo type/trucking cargo type/container
  // catalog ARE reused from there via ensureContainerLookupsLoaded()).
  // Own local cache, same "duplicate small lookups per file" precedent
  // used throughout this session.
  const RT_UNIT_OPTIONS_HTML = '<option value="CBM">CBM (Cubic Meter)</option><option value="MT">MT (Metric Ton)</option>';
  const TERMS_OF_PAYMENT_STANDARD = [
    "Cash Payment",
    "Payment Prior to Vessel Loading",
    "7/15/30/45/60/90 Days After Delivery of Cargoes",
    "7/15/30/45/60/90 Days After Vessel Arrival at Destination",
  ];
  const TERMS_OF_PAYMENT_CHARTER = [
    "50% - upon signing of contract, before loading of CARGO",
    "50% - full payment upon arrival of VESSEL at DISCHARGE PORT, but prior to unloading of CARGO at DISCHARGE PORT",
    "Full payment upon signing of contract, before loading of CARGO",
  ];
  const PRODUCT_PANES = {
    container: "rpmProductPaneContainer",
    rolling_cargo: "rpmProductPaneRollingCargo",
    loose_cargo: "rpmProductPaneLooseCargo",
    trucking: "rpmProductPaneTrucking",
    charter: "rpmProductPaneCharter",
  };
  const PRODUCT_ENDPOINTS = {
    container: { url: "containers", form: "#rpmContainerForm" },
    rolling_cargo: { url: "rollingCargo", form: "#rpmRollingCargoForm" },
    loose_cargo: { url: "looseCargo", form: "#rpmLooseCargoForm" },
    trucking: { url: "truckings", form: "#rpmTruckingForm" },
    charter: { url: "charters", form: "#rpmCharterForm" },
  };

  let unitOptionsHtml = "";
  let ancillaryTypeOptionsHtml = "";
  let cargoYardOptionsHtml = "";
  let productLookupsPromise = null;

  function optionsHtmlFromList(values) {
    return values.map((v) => `<option value="${esc(v)}">${esc(v)}</option>`).join("");
  }

  async function loadProductLookups() {
    await window.prospectShared.ensureContainerLookupsLoaded();

    // Delivery Types LOV is no longer fetched here - Service Type is now
    // the fixed SERVICE_TYPES list (see top of file), not an LOV-backed id.
    const [unitRes, ancillaryRes, yardRes] = await Promise.all([
      apiCall({ mode: "GET", url: "/api/listofval/unit" }),
      apiCall({ mode: "GET", url: "/api/listofval/ancillarytype" }),
      apiCall({ mode: "GET", url: "/api/cargoYards?per_page=200" }),
    ]);

    if (Array.isArray(unitRes)) {
      unitOptionsHtml = unitRes.map((lov) => `<option value="${esc(lov.lov_name)}">${esc(lov.lov_name)}</option>`).join("");
    }
    if (Array.isArray(ancillaryRes)) {
      ancillaryTypeOptionsHtml = ancillaryRes.map((lov) => `<option value="${esc(lov.lov_name)}">${esc(lov.lov_name)}</option>`).join("");
    }
    if (yardRes.success) {
      cargoYardOptionsHtml = yardRes.data.data.map((y) => `<option value="${y.cargo_yard_id}">${esc(y.name)}</option>`).join("");
    }
  }

  function ensureProductLookupsLoaded() {
    if (!productLookupsPromise) productLookupsPromise = loadProductLookups();
    return productLookupsPromise;
  }

  // ------------------------------------------------------------------
  // Shared dropdown population (static lists + Origin/Destination pairs
  // sourced from the Origin & Destination tab's own saved list)
  // ------------------------------------------------------------------
  function populateProductStaticDropdowns(modal) {
    modal.querySelectorAll(".rpmPortDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = `<option value="">Select Port</option>${window.prospectShared.getPortsOptionsHtml()}`;
      el.value = current;
      makeSearchableSelect(el);
      refreshSearchable(el);
    });
    modal.querySelectorAll(".rpmCargoTypeDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = `<option value="">Select Cargo Type</option>${window.prospectShared.getCargoTypeOptionsHtml()}`;
      el.value = current;
    });
    modal.querySelectorAll(".rpmTruckingCargoTypeDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = `<option value="">Select Cargo Type</option>${window.prospectShared.getTruckingCargoTypeOptionsHtml()}`;
      el.value = current;
    });
    modal.querySelectorAll(".rpmDeliveryTypeDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = serviceTypeOptionsHtml;
      el.value = current;
    });
    modal.querySelectorAll(".rpmUnitDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = `<option value="">Select Unit</option>${unitOptionsHtml}`;
      el.value = current;
    });
    modal.querySelectorAll(".rpmRtUnitDropdown").forEach((el) => {
      el.innerHTML = RT_UNIT_OPTIONS_HTML;
    });
    modal.querySelectorAll(".rpmTermsOfPaymentDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = optionsHtmlFromList(TERMS_OF_PAYMENT_STANDARD);
      if (current) el.value = current;
    });
    modal.querySelectorAll(".rpmCharterTermsOfPaymentDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = optionsHtmlFromList(TERMS_OF_PAYMENT_CHARTER);
      if (current) el.value = current;
    });
  }

  // Products' Origin/Destination selects still key off prospect_location_id
  // (a separate, not-yet-finalized contract - see this tab's own header
  // comment) - entries with no prospect_location_id (fully manual O&D
  // entries, now possible since that form is editable) can't be referenced
  // here yet and are excluded rather than shown broken. Reads the label off
  // the row's own snapshot fields (l.location_mnemonic/formatLocationLabel(l))
  // rather than the joined prospect_location, since that reflects what was
  // actually saved even if the entry was edited away from its seed address.
  function odOptionsHtml(locations, excludeId) {
    return (locations ?? [])
      .filter((l) => l.prospect_location_id && String(l.prospect_location_id) !== String(excludeId ?? ""))
      .map((l) => `<option value="${l.prospect_location_id}">${esc(l.location_mnemonic || formatLocationLabel(l))}</option>`)
      .join("");
  }

  // originEl/destEl keep their live `locations` list on the element itself
  // (not a closure variable) so the one-time-bound change listener always
  // filters against the latest Origin & Destination tab data, not whatever
  // was current the first time this pair was bound.
  function bindOdPair(originEl, destEl, locations) {
    originEl._odLocations = locations;

    const currentOrigin = originEl.value;
    const currentDest = destEl.value;
    originEl.innerHTML = `<option value="">Select Origin</option>${odOptionsHtml(locations)}`;
    originEl.value = currentOrigin;
    destEl.innerHTML = `<option value="">Select Destination</option>${odOptionsHtml(locations, originEl.value)}`;
    destEl.value = currentDest;
    refreshSearchable(originEl);
    refreshSearchable(destEl);

    if (!originEl.dataset.odBound) {
      originEl.dataset.odBound = "1";
      makeSearchableSelect(originEl);
      makeSearchableSelect(destEl);
      originEl.addEventListener("change", () => {
        const keepDest = destEl.value;
        destEl.innerHTML = `<option value="">Select Destination</option>${odOptionsHtml(originEl._odLocations, originEl.value)}`;
        destEl.value = keepDest === originEl.value ? "" : keepDest;
        refreshSearchable(destEl);
      });
    }
  }

  function populateOdPairs(modal, lead) {
    const locations = getMyProposalRequest(lead)?.locations ?? [];
    [
      ["#rpmPcOrigin", "#rpmPcDestination"],
      ["#rpmRcOrigin", "#rpmRcDestination"],
      ["#rpmLcOrigin", "#rpmLcDestination"],
      ["#rpmTrOrigin", "#rpmTrDestination"],
    ].forEach(([originSel, destSel]) => {
      const originEl = modal.querySelector(originSel);
      const destEl = modal.querySelector(destSel);
      if (originEl && destEl) bindOdPair(originEl, destEl, locations);
    });
  }

  // ------------------------------------------------------------------
  // CONTAINER form - CV/FR/RF unified. Minimum Temperature only for RF
  // (same TYPE_FIELD_VISIBILITY-style convention as the Requirements tab).
  // Dispatch Mode (Origin/Destination) are disabled per side when the
  // selected Service Type doesn't include trucking on that side (Pier).
  // ------------------------------------------------------------------
  function applyPcContainerTypeVisibility(modal) {
    const type = modal.querySelector("#rpmPcContainerType").value;
    modal.querySelector(".rpm-field-min-temp")?.classList.toggle("hidden", type !== "RF");
  }

  function populatePcContainerSizeOptions(modal) {
    const type = modal.querySelector("#rpmPcContainerType").value;
    const catalog = window.prospectShared.getContainerCatalogByCode()[type] ?? { sizes: [] };
    const sizeSelect = modal.querySelector("#rpmPcContainerSize");
    const current = sizeSelect.value;
    sizeSelect.innerHTML = '<option value="">Select Size</option>' + catalog.sizes.map((s) => `<option value="${s.id}">${s.size}</option>`).join("");
    sizeSelect.value = current;
  }

  // Dispatch Mode (a trucking concept) only makes sense on a side that
  // actually has door/trucking service - split "Door - Pier" style Service
  // Type strings on " - " into [originSide, destSide] and disable/clear
  // whichever side says "Pier" (no trucking there).
  function applyDispatchModeGating(modal, deliverySelectId, originDispatchId, destDispatchId) {
    const serviceType = modal.querySelector(`#${deliverySelectId}`)?.value ?? "";
    const [originSide, destSide] = serviceType.split(" - ");
    const originEl = modal.querySelector(`#${originDispatchId}`);
    const destEl = modal.querySelector(`#${destDispatchId}`);
    if (originEl) {
      originEl.disabled = originSide === "Pier";
      if (originEl.disabled) originEl.value = "";
    }
    if (destEl) {
      destEl.disabled = destSide === "Pier";
      if (destEl.disabled) destEl.value = "";
    }
  }

  // ------------------------------------------------------------------
  // Product picker - swaps which form pane is visible, same mechanic as
  // the Requirements tab's bindProductSelector().
  // ------------------------------------------------------------------
  function bindProductSelector(modal) {
    modal.querySelector("#rpmProductSelect").addEventListener("change", function () {
      Object.values(PRODUCT_PANES).forEach((id) => modal.querySelector(`#${id}`)?.classList.add("hidden"));
      const paneId = PRODUCT_PANES[this.value];
      if (paneId) modal.querySelector(`#${paneId}`)?.classList.remove("hidden");
    });
  }

  function collectFormPayload(formSelector) {
    const form = document.querySelector(formSelector);
    const obj = {};
    form.querySelectorAll("[data-field]").forEach((el) => {
      obj[el.dataset.field] = el.type === "checkbox" ? el.checked : el.value === "" ? null : el.value;
    });
    return obj;
  }

  // Inverse of collectFormPayload() above - the "Copy from Prospect
  // Products" shortcut's prefill primitive (see
  // renderProductsPrefillShortcuts()/applyProductPrefill() below). Only
  // touches a [data-field] element when `data` actually has that key
  // (even if its value is null, which is written through as a blank) -
  // keys simply absent from `data` are left exactly as they were, which is
  // how fields with no prospect-requirement equivalent stay blank. Dumb by
  // design: the per-type field mapping lives in the caller, not here.
  function fillFormFromData(formSelector, data) {
    const form = document.querySelector(formSelector);
    form.querySelectorAll("[data-field]").forEach((el) => {
      const field = el.dataset.field;
      if (!(field in data) || data[field] === undefined) return;
      const value = data[field];
      if (el.type === "checkbox") {
        el.checked = !!value;
      } else {
        el.value = value ?? "";
      }
      if (el.tagName === "SELECT") refreshSearchable(el);
    });
  }

  function resetProductForm(modal, formSelector) {
    document.querySelector(formSelector)
      .querySelectorAll("[data-field]")
      .forEach((el) => {
        if (el.type === "checkbox") el.checked = false;
        else if (el.tagName === "SELECT") el.selectedIndex = 0;
        else el.value = "";
      });
    // Container form's type-driven bits need re-applying after a reset
    // snaps #rpmPcContainerType back to its default (CV) - harmless no-op
    // when a different product's form was just reset.
    applyPcContainerTypeVisibility(modal);
    populatePcContainerSizeOptions(modal);
  }

  async function submitProduct(modal, button) {
    const lead = rpmLead;
    if (!lead) return;

    const productType = modal.querySelector("#rpmProductSelect").value;
    if (!productType) {
      showMessage({ status: "error", title: "Select a product first." });
      return;
    }

    const { url, form } = PRODUCT_ENDPOINTS[productType];
    const payload = collectFormPayload(form);

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${rpmProposalRequestId}/products/${url}`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Adding", message: response.message ?? "" });
      return;
    }

    showMessage({ status: "success", title: "Product Added" });
    resetProductForm(modal, form);
    await refreshLead(modal);
  }

  async function removeProductRow(productType, id) {
    const lead = rpmLead;
    if (!lead) return;

    const confirmed = await customConfirm("Remove this product?");
    if (!confirmed) return;

    const modal = document.getElementById("RequestProposalModal");
    const response = await apiCall({
      mode: "DELETE",
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${rpmProposalRequestId}/products/${PRODUCT_ENDPOINTS[productType].url}/${id}`,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Removing", message: response.message ?? "" });
      return;
    }

    showMessage({ status: "success", title: "Product Removed" });
    await refreshLead(modal);
  }

  // ------------------------------------------------------------------
  // Saved-row tables - each shows a count+View button for the sub-item
  // lists that were never built inline (Ancillary/Top Load/Charter Cargo
  // Info/Charter Ports) - see the 4 sub-item modals below.
  // ------------------------------------------------------------------
  function toggleTableWrap(modal, wrapId, hasRows) {
    modal.querySelector(`#${wrapId}`)?.classList.toggle("hidden", !hasRows);
  }

  function productContainerRowHtml(row) {
    const ancCount = (row.ancillary_services ?? []).length;
    return `
    <tr data-id="${row.id}">
        <td class="px-3 py-2">${esc(row.container_type || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.container_size?.size || EMPTY)}</td>
        <td class="px-3 py-2">${row.minimum_temperature != null ? esc(row.minimum_temperature) + "°C" : EMPTY}</td>
        <td class="px-3 py-2">${esc(row.service_type || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.origin_location?.location_mnemonic || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.origin_port?.name || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.destination_location?.location_mnemonic || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.destination_port?.name || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.cargo_type || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.cargo_description || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.dispatch_mode_origin || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.dispatch_mode_destination || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.terms_of_payment || EMPTY)}</td>
        <td class="px-3 py-2"><button type="button" class="rpm-view-ancillary text-orange-600 hover:text-orange-700 text-xs font-medium" data-type="container" data-id="${row.id}">${ancCount} · View</button></td>
        <td class="px-3 py-2"><button type="button" class="rpm-remove-product text-red-500 text-xs font-medium" data-product="container" data-id="${row.id}">✕ Remove</button></td>
    </tr>`;
  }

  function productCargoRowHtml(row, productType, withTopLoad) {
    const ancCount = (row.ancillary_services ?? []).length;
    const topCell = withTopLoad
      ? `<td class="px-3 py-2"><button type="button" class="rpm-view-topload text-orange-600 hover:text-orange-700 text-xs font-medium" data-id="${row.id}">${(row.top_load_cargo ?? []).length} · View</button></td>`
      : "";
    return `
    <tr data-id="${row.id}">
        <td class="px-3 py-2">${esc(row.cargo_type || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.cargo_details || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.cargo_quantity ?? EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.cargo_units || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.revenue_ton ?? EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.revenue_ton_unit || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.cargo_measurement || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.service_type || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.origin_location?.location_mnemonic || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.origin_port?.name || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.destination_location?.location_mnemonic || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.destination_port?.name || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.terms_of_payment || EMPTY)}</td>
        <td class="px-3 py-2"><button type="button" class="rpm-view-ancillary text-orange-600 hover:text-orange-700 text-xs font-medium" data-type="${productType}" data-id="${row.id}">${ancCount} · View</button></td>
        ${topCell}
        <td class="px-3 py-2"><button type="button" class="rpm-remove-product text-red-500 text-xs font-medium" data-product="${productType}" data-id="${row.id}">✕ Remove</button></td>
    </tr>`;
  }

  function productTruckingRowHtml(row) {
    const ancCount = (row.ancillary_services ?? []).length;
    return `
    <tr data-id="${row.id}">
        <td class="px-3 py-2">${esc(row.trucking_cargo_type || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.dispatch_mode || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.origin_location?.location_mnemonic || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.destination_location?.location_mnemonic || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.cargo_type || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.cargo_description || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.terms_of_payment || EMPTY)}</td>
        <td class="px-3 py-2"><button type="button" class="rpm-view-ancillary text-orange-600 hover:text-orange-700 text-xs font-medium" data-type="trucking" data-id="${row.id}">${ancCount} · View</button></td>
        <td class="px-3 py-2"><button type="button" class="rpm-remove-product text-red-500 text-xs font-medium" data-product="trucking" data-id="${row.id}">✕ Remove</button></td>
    </tr>`;
  }

  function productCharterRowHtml(row) {
    const cargoCount = (row.cargo_items ?? []).length;
    const portCount = (row.ports ?? []).length;
    return `
    <tr data-id="${row.id}">
        <td class="px-3 py-2">${esc(row.vessel_name || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.vessel_dead_weight ?? EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.charter_start_date || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.charter_end_date || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.loading_date || EMPTY)}</td>
        <td class="px-3 py-2">${esc(row.laytime_loading_days ?? "-")} / ${esc(row.laytime_unloading_days ?? "-")}</td>
        <td class="px-3 py-2">${row.demurrage_charges ? "Yes" : "No"}</td>
        <td class="px-3 py-2">${row.lashing_service ? "Yes" : "No"}</td>
        <td class="px-3 py-2">${row.insurance_services ? "Yes" : "No"}</td>
        <td class="px-3 py-2">${esc(row.terms_of_payment || EMPTY)}</td>
        <td class="px-3 py-2">${row.declared_value != null ? "₱" + Number(row.declared_value).toLocaleString() : EMPTY}</td>
        <td class="px-3 py-2">${row.weight != null ? Number(row.weight).toLocaleString() + " " + esc(row.weight_unit || "") : EMPTY}</td>
        <td class="px-3 py-2"><button type="button" class="rpm-view-charter-cargo text-orange-600 hover:text-orange-700 text-xs font-medium" data-id="${row.id}">${cargoCount} · View</button></td>
        <td class="px-3 py-2"><button type="button" class="rpm-view-charter-ports text-orange-600 hover:text-orange-700 text-xs font-medium" data-id="${row.id}">${portCount} · View</button></td>
        <td class="px-3 py-2"><button type="button" class="rpm-remove-product text-red-500 text-xs font-medium" data-product="charter" data-id="${row.id}">✕ Remove</button></td>
    </tr>`;
  }

  function bindProductRowActions(modal) {
    modal.querySelectorAll(".rpm-remove-product").forEach((btn) => {
      btn.addEventListener("click", () => removeProductRow(btn.dataset.product, Number(btn.dataset.id)));
    });
    modal.querySelectorAll(".rpm-view-ancillary").forEach((btn) => {
      btn.addEventListener("click", () => window.openAncillaryServicesModal(btn.dataset.type, Number(btn.dataset.id)));
    });
    modal.querySelectorAll(".rpm-view-topload").forEach((btn) => {
      btn.addEventListener("click", () => window.openTopLoadCargoModal(Number(btn.dataset.id)));
    });
    modal.querySelectorAll(".rpm-view-charter-cargo").forEach((btn) => {
      btn.addEventListener("click", () => window.openCharterCargoInfoModal(Number(btn.dataset.id)));
    });
    modal.querySelectorAll(".rpm-view-charter-ports").forEach((btn) => {
      btn.addEventListener("click", () => window.openCharterPortsModal(Number(btn.dataset.id)));
    });
  }

  // ------------------------------------------------------------------
  // "Copy from Prospect Products" shortcuts - one button per intake-time
  // requirement row (lead.requirement_containers/_truckings/_charters,
  // already on the payload rpmLead holds - never fetched here). Purely a
  // prefill shortcut: switches to the matching product pane below and
  // fills only the fields that have a real overlapping prospect field,
  // then leaves the form fully editable/unsubmitted. See the task's field
  // mapping table for exactly what prefills vs stays blank per product
  // type - deviations from it are NOT taken here, only what's written
  // below. Reuses LINE_ITEM_TYPE_META (defined above, Confirmation tab's
  // recap) for the readable per-type label instead of a third copy.
  // ------------------------------------------------------------------
  const CONTAINER_TYPE_TO_PRODUCT = { CV: "container", FR: "container", RF: "container", RC: "rolling_cargo", LC: "loose_cargo" };

  function containerPrefillData(row) {
    return {
      container_type: row.container_type ?? "",
      minimum_temperature: row.minimum_temperature,
      service_type: row.service_type ?? "",
      quantity: row.quantity,
      cargo_type: row.cargo_type ?? "",
      cargo_description: row.general_cargo_description ?? "",
      container_size_id: row.container_size?.id ?? "",
    };
  }

  // Shared by Rolling Cargo and Loose Cargo panes - identical field set.
  function rollingOrLoosePrefillData(row) {
    return {
      cargo_type: row.cargo_type ?? "",
      cargo_details: row.general_cargo_description ?? "",
      cargo_quantity: row.quantity,
      cargo_units: row.booking_unit_type ?? "",
      revenue_ton: row.revenue_ton,
      cargo_measurement: row.cargo_measurement ?? "",
      service_type: row.service_type ?? "",
    };
  }

  function truckingPrefillData(row) {
    return {
      trucking_cargo_type: row.trucking_cargo_type ?? "",
      dispatch_mode: row.dispatch_mode ?? "",
      quantity: row.quantity,
      cargo_type: row.cargo_type ?? "",
      cargo_description: row.general_cargo_description ?? "",
    };
  }

  function charterPrefillData(row) {
    return {
      charter_start_date: row.charter_start_date ?? "",
      charter_end_date: row.charter_end_date ?? "",
      declared_value: row.declared_value,
      weight: row.weight,
      weight_unit: row.weight_unit ?? "",
    };
  }

  function prefillChipLabel(kind, row) {
    if (kind === "container") {
      const typeLabel = LINE_ITEM_TYPE_META[row.container_type]?.label ?? row.container_type ?? EMPTY;
      const origin = row.origin_location?.name || EMPTY;
      const dest = row.destination_location?.name || EMPTY;
      return `${typeLabel} · ${origin} → ${dest} · Qty ${row.quantity ?? EMPTY}`;
    }
    if (kind === "trucking") {
      const origin = row.origin_location?.name || EMPTY;
      const dest = row.destination_location?.name || EMPTY;
      return `Trucking · ${origin} → ${dest} · Qty ${row.quantity ?? EMPTY}`;
    }
    // charter - no simple route/qty, dates instead (see brief).
    return `Charter · ${row.charter_start_date || EMPTY} → ${row.charter_end_date || EMPTY}`;
  }

  function findRequirementRow(kind, id) {
    const key = { container: "requirement_containers", trucking: "requirement_truckings", charter: "requirement_charters" }[kind];
    return (rpmLead?.[key] ?? []).find((r) => r.id === id) ?? null;
  }

  function switchProductPane(modal, productType) {
    const select = modal.querySelector("#rpmProductSelect");
    select.value = productType;
    select.dispatchEvent(new Event("change"));
  }

  function applyProductPrefill(modal, kind, id) {
    const row = findRequirementRow(kind, id);
    if (!row) return;

    if (kind === "container") {
      const productType = CONTAINER_TYPE_TO_PRODUCT[row.container_type] ?? "container";
      const { form } = PRODUCT_ENDPOINTS[productType];
      switchProductPane(modal, productType);
      resetProductForm(modal, form);

      if (productType === "container") {
        // Ordering matters: the size <select> has no options until
        // generated for the chosen type, so set the type + regenerate
        // visibility/options BEFORE writing container_size_id.
        modal.querySelector("#rpmPcContainerType").value = row.container_type;
        applyPcContainerTypeVisibility(modal);
        populatePcContainerSizeOptions(modal);
        fillFormFromData(form, containerPrefillData(row));
        // service_type was just prefilled - re-run the dispatch-mode
        // enabled/disabled gating so it matches the copied value.
        applyDispatchModeGating(modal, "rpmPcDeliveryType", "rpmPcDispatchOrigin", "rpmPcDispatchDestination");
      } else {
        fillFormFromData(form, rollingOrLoosePrefillData(row));
      }
      modal.querySelector("#" + PRODUCT_PANES[productType])?.scrollIntoView({ behavior: "smooth", block: "nearest" });
      return;
    }

    if (kind === "trucking") {
      switchProductPane(modal, "trucking");
      resetProductForm(modal, PRODUCT_ENDPOINTS.trucking.form);
      fillFormFromData(PRODUCT_ENDPOINTS.trucking.form, truckingPrefillData(row));
      modal.querySelector("#" + PRODUCT_PANES.trucking)?.scrollIntoView({ behavior: "smooth", block: "nearest" });
      return;
    }

    if (kind === "charter") {
      switchProductPane(modal, "charter");
      resetProductForm(modal, PRODUCT_ENDPOINTS.charter.form);
      fillFormFromData(PRODUCT_ENDPOINTS.charter.form, charterPrefillData(row));
      modal.querySelector("#" + PRODUCT_PANES.charter)?.scrollIntoView({ behavior: "smooth", block: "nearest" });
    }
  }

  function prefillChipHtml(kind, row) {
    return `<button type="button" class="rpm-prefill-chip px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-sm font-medium text-zinc-700 dark:text-zinc-200 hover:border-orange-400 dark:hover:border-orange-500" data-kind="${kind}" data-id="${row.id}">${esc(prefillChipLabel(kind, row))}</button>`;
  }

  function renderProductsPrefillShortcuts(modal, lead) {
    const containers = lead.requirement_containers ?? [];
    const truckings = lead.requirement_truckings ?? [];
    const charters = lead.requirement_charters ?? [];
    const total = containers.length + truckings.length + charters.length;

    modal.querySelector("#rpmProductsPrefillWrap").classList.toggle("hidden", total === 0);
    if (total === 0) return;

    modal.querySelector("#rpmProductsPrefillChips").innerHTML = [
      ...containers.map((row) => prefillChipHtml("container", row)),
      ...truckings.map((row) => prefillChipHtml("trucking", row)),
      ...charters.map((row) => prefillChipHtml("charter", row)),
    ].join("");

    modal.querySelectorAll(".rpm-prefill-chip").forEach((btn) => {
      btn.addEventListener("click", () => applyProductPrefill(modal, btn.dataset.kind, Number(btn.dataset.id)));
    });
  }

  function renderProducts(modal, lead) {
    const pr = getMyProposalRequest(lead);
    const containers = pr?.product_containers ?? [];
    const rolling = pr?.product_rolling_cargo ?? [];
    const loose = pr?.product_loose_cargo ?? [];
    const truckings = pr?.product_truckings ?? [];
    const charters = pr?.product_charters ?? [];

    toggleTableWrap(modal, "rpmProductContainersTableWrap", containers.length > 0);
    toggleTableWrap(modal, "rpmProductRollingCargoTableWrap", rolling.length > 0);
    toggleTableWrap(modal, "rpmProductLooseCargoTableWrap", loose.length > 0);
    toggleTableWrap(modal, "rpmProductTruckingsTableWrap", truckings.length > 0);
    toggleTableWrap(modal, "rpmProductChartersTableWrap", charters.length > 0);

    modal.querySelector("#rpmProductsEmpty").classList.toggle(
      "hidden",
      containers.length + rolling.length + loose.length + truckings.length + charters.length > 0
    );

    modal.querySelector("#rpmProductContainersTableBody").innerHTML = containers.map(productContainerRowHtml).join("");
    modal.querySelector("#rpmProductRollingCargoTableBody").innerHTML = rolling
      .map((r) => productCargoRowHtml(r, "rolling_cargo", true))
      .join("");
    modal.querySelector("#rpmProductLooseCargoTableBody").innerHTML = loose
      .map((r) => productCargoRowHtml(r, "loose_cargo", false))
      .join("");
    modal.querySelector("#rpmProductTruckingsTableBody").innerHTML = truckings.map(productTruckingRowHtml).join("");
    modal.querySelector("#rpmProductChartersTableBody").innerHTML = charters.map(productCharterRowHtml).join("");

    bindProductRowActions(modal);
    populateProductStaticDropdowns(modal);
    populateOdPairs(modal, lead);
    populatePcContainerSizeOptions(modal);
    applyPcContainerTypeVisibility(modal);
    renderProductsPrefillShortcuts(modal, lead);
  }

  // ==================================================================
  // SUB-ITEM MODALS - Ancillary Services (shared across Container/Rolling
  // Cargo/Loose Cargo/Trucking), Top Load Cargo (Rolling Cargo only),
  // Charter Cargo Info, Charter Ports. All follow the same shape: open
  // with a parent context, render the parent's current list, add/remove
  // against the dedicated endpoint, "Edit" = remove + refill the form
  // (no separate update endpoint) so re-clicking Add re-saves it.
  // ==================================================================

  // Re-fetches the shared prospect payload (updates rpmLead) then
  // re-renders whichever sub-item modal is currently open.
  async function refreshProductsAndReRenderSubModal(rerenderFn) {
    const modal = document.getElementById("RequestProposalModal");
    await refreshLead(modal);
    rerenderFn();
  }

  function findProductRow(parentType, parentId) {
    const pr = getMyProposalRequest(rpmLead);
    const listMap = {
      container: pr?.product_containers,
      rolling_cargo: pr?.product_rolling_cargo,
      loose_cargo: pr?.product_loose_cargo,
      trucking: pr?.product_truckings,
    };
    return (listMap[parentType] ?? []).find((r) => r.id === parentId);
  }

  function findCharterRow(id) {
    return (getMyProposalRequest(rpmLead)?.product_charters ?? []).find((c) => c.id === id);
  }

  // ------------------------------------------------------------------
  // ANCILLARY SERVICES MODAL
  // ------------------------------------------------------------------
  let ancillaryContext = null;

  function renderAncillaryList() {
    const modal = document.getElementById("AncillaryServicesModal");
    const row = findProductRow(ancillaryContext.parentType, ancillaryContext.parentId);
    const services = row?.ancillary_services ?? [];
    modal.querySelector("#asmEmpty").classList.toggle("hidden", services.length > 0);
    modal.querySelector("#asmList").innerHTML = services
      .map(
        (s) => `
      <div class="flex justify-between items-center border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2">
          <div class="text-sm text-zinc-800 dark:text-zinc-100">
              <span class="font-medium">${esc(s.ancillary_type || EMPTY)}</span>
              <span class="text-zinc-400"> · ${esc(s.cargo_yard?.name || EMPTY)} · ${esc(s.ancillary_unit || EMPTY)}</span>
              ${s.ancillary_remarks ? `<div class="text-xs text-zinc-400">${esc(s.ancillary_remarks)}</div>` : ""}
          </div>
          <div class="flex gap-2 shrink-0">
              <button type="button" class="asm-edit text-orange-600 text-xs font-medium" data-id="${s.id}">Edit</button>
              <button type="button" class="asm-remove text-red-500 text-xs font-medium" data-id="${s.id}">✕ Remove</button>
          </div>
      </div>`
      )
      .join("");

    modal.querySelectorAll(".asm-remove").forEach((btn) => btn.addEventListener("click", () => removeAncillary(Number(btn.dataset.id))));
    modal.querySelectorAll(".asm-edit").forEach((btn) => btn.addEventListener("click", () => editAncillary(Number(btn.dataset.id), services)));
  }

  function openAncillaryServicesModal(parentType, parentId) {
    ancillaryContext = { parentType, parentId };
    const modal = document.getElementById("AncillaryServicesModal");
    modal.querySelector("#asmAncillaryType").innerHTML = `<option value="">Select Ancillary Type</option>${ancillaryTypeOptionsHtml}`;
    modal.querySelector("#asmCargoYard").innerHTML = `<option value="">Select Cargo Yard</option>${cargoYardOptionsHtml}`;
    modal.querySelector("#asmUnit").innerHTML = `<option value="">Select Unit</option>${unitOptionsHtml}`;
    resetAncillaryForm();
    renderAncillaryList();
    window.initModal({ modalId: "AncillaryServicesModal" });
  }
  window.openAncillaryServicesModal = openAncillaryServicesModal;

  function resetAncillaryForm() {
    const modal = document.getElementById("AncillaryServicesModal");
    modal.querySelector("#asmAncillaryType").value = "";
    modal.querySelector("#asmCargoYard").value = "";
    modal.querySelector("#asmUnit").value = "";
    modal.querySelector("#asmRemarks").value = "";
  }

  async function addAncillary(button) {
    const modal = document.getElementById("AncillaryServicesModal");
    const payload = {
      ancillaryable_type: ancillaryContext.parentType,
      ancillaryable_id: ancillaryContext.parentId,
      ancillary_type: modal.querySelector("#asmAncillaryType").value || null,
      cargo_yard_id: modal.querySelector("#asmCargoYard").value || null,
      ancillary_unit: modal.querySelector("#asmUnit").value || null,
      ancillary_remarks: modal.querySelector("#asmRemarks").value.trim() || null,
    };
    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${rpmLead.uuid}/proposalRequests/${rpmProposalRequestId}/products/ancillaryServices`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Adding", message: response.message ?? "" });
      return;
    }
    showMessage({ status: "success", title: "Ancillary Service Added" });
    resetAncillaryForm();
    await refreshProductsAndReRenderSubModal(renderAncillaryList);
  }

  async function deleteAncillaryRow(id) {
    return apiCall({ mode: "DELETE", url: `/api/crm/prospects/${rpmLead.uuid}/proposalRequests/${rpmProposalRequestId}/products/ancillaryServices/${id}` });
  }

  async function removeAncillary(id) {
    const confirmed = await customConfirm("Remove this ancillary service?");
    if (!confirmed) return;
    const response = await deleteAncillaryRow(id);
    if (!response.success) {
      showMessage({ status: "error", title: "Error Removing", message: response.message ?? "" });
      return;
    }
    showMessage({ status: "success", title: "Ancillary Service Removed" });
    await refreshProductsAndReRenderSubModal(renderAncillaryList);
  }

  async function editAncillary(id, services) {
    const row = services.find((s) => s.id === id);
    if (!row) return;
    const modal = document.getElementById("AncillaryServicesModal");
    modal.querySelector("#asmAncillaryType").value = row.ancillary_type ?? "";
    modal.querySelector("#asmCargoYard").value = String(row.cargo_yard_id ?? "");
    modal.querySelector("#asmUnit").value = row.ancillary_unit ?? "";
    modal.querySelector("#asmRemarks").value = row.ancillary_remarks ?? "";
    await deleteAncillaryRow(id);
    await refreshProductsAndReRenderSubModal(renderAncillaryList);
  }

  // ------------------------------------------------------------------
  // TOP LOAD CARGO MODAL (Rolling Cargo only)
  // ------------------------------------------------------------------
  let topLoadRollingCargoId = null;

  function findRollingCargoRow(id) {
    return (getMyProposalRequest(rpmLead)?.product_rolling_cargo ?? []).find((r) => r.id === id);
  }

  function renderTopLoadList() {
    const modal = document.getElementById("TopLoadCargoModal");
    const row = findRollingCargoRow(topLoadRollingCargoId);
    const items = row?.top_load_cargo ?? [];
    modal.querySelector("#tlcmEmpty").classList.toggle("hidden", items.length > 0);
    modal.querySelector("#tlcmList").innerHTML = items
      .map(
        (t) => `
      <div class="flex justify-between items-center border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2">
          <div class="text-sm text-zinc-800 dark:text-zinc-100">
              <span class="font-medium">${esc(t.top_load_type || EMPTY)}</span>
              <span class="text-zinc-400"> · Qty ${esc(t.quantity ?? EMPTY)} ${esc(t.units || "")} · RT ${esc(t.revenue_ton ?? EMPTY)} ${esc(t.revenue_ton_unit || "")}</span>
              ${t.details ? `<div class="text-xs text-zinc-400">${esc(t.details)}</div>` : ""}
          </div>
          <div class="flex gap-2 shrink-0">
              <button type="button" class="tlcm-edit text-orange-600 text-xs font-medium" data-id="${t.id}">Edit</button>
              <button type="button" class="tlcm-remove text-red-500 text-xs font-medium" data-id="${t.id}">✕ Remove</button>
          </div>
      </div>`
      )
      .join("");

    modal.querySelectorAll(".tlcm-remove").forEach((btn) => btn.addEventListener("click", () => removeTopLoad(Number(btn.dataset.id))));
    modal.querySelectorAll(".tlcm-edit").forEach((btn) => btn.addEventListener("click", () => editTopLoad(Number(btn.dataset.id), items)));
  }

  function openTopLoadCargoModal(rollingCargoId) {
    topLoadRollingCargoId = rollingCargoId;
    const modal = document.getElementById("TopLoadCargoModal");
    modal.querySelector("#tlcmType").innerHTML = `<option value="">Select Type</option>${window.prospectShared.getCargoTypeOptionsHtml()}`;
    modal.querySelector("#tlcmUnits").innerHTML = `<option value="">Select Unit</option>${unitOptionsHtml}`;
    modal.querySelector("#tlcmRevenueTonUnit").innerHTML = RT_UNIT_OPTIONS_HTML;
    resetTopLoadForm();
    renderTopLoadList();
    window.initModal({ modalId: "TopLoadCargoModal" });
  }
  window.openTopLoadCargoModal = openTopLoadCargoModal;

  function collectTopLoadForm() {
    const modal = document.getElementById("TopLoadCargoModal");
    return {
      top_load_type: modal.querySelector("#tlcmType").value || null,
      details: modal.querySelector("#tlcmDetails").value.trim() || null,
      quantity: modal.querySelector("#tlcmQuantity").value || null,
      units: modal.querySelector("#tlcmUnits").value || null,
      revenue_ton: modal.querySelector("#tlcmRevenueTon").value || null,
      revenue_ton_unit: modal.querySelector("#tlcmRevenueTonUnit").value || null,
      measurement: modal.querySelector("#tlcmMeasurement").value.trim() || null,
    };
  }

  function resetTopLoadForm() {
    const modal = document.getElementById("TopLoadCargoModal");
    modal.querySelector("#tlcmType").value = "";
    modal.querySelector("#tlcmDetails").value = "";
    modal.querySelector("#tlcmQuantity").value = "";
    modal.querySelector("#tlcmUnits").value = "";
    modal.querySelector("#tlcmRevenueTon").value = "";
    modal.querySelector("#tlcmRevenueTonUnit").value = "";
    modal.querySelector("#tlcmMeasurement").value = "";
  }

  async function addTopLoad(button) {
    const payload = collectTopLoadForm();
    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${rpmLead.uuid}/proposalRequests/${rpmProposalRequestId}/products/rollingCargo/${topLoadRollingCargoId}/topLoad`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Adding", message: response.message ?? "" });
      return;
    }
    showMessage({ status: "success", title: "Top Load Cargo Added" });
    resetTopLoadForm();
    await refreshProductsAndReRenderSubModal(renderTopLoadList);
  }

  async function deleteTopLoadRow(id) {
    return apiCall({
      mode: "DELETE",
      url: `/api/crm/prospects/${rpmLead.uuid}/proposalRequests/${rpmProposalRequestId}/products/rollingCargo/${topLoadRollingCargoId}/topLoad/${id}`,
    });
  }

  async function removeTopLoad(id) {
    const confirmed = await customConfirm("Remove this top load cargo?");
    if (!confirmed) return;
    const response = await deleteTopLoadRow(id);
    if (!response.success) {
      showMessage({ status: "error", title: "Error Removing", message: response.message ?? "" });
      return;
    }
    showMessage({ status: "success", title: "Top Load Cargo Removed" });
    await refreshProductsAndReRenderSubModal(renderTopLoadList);
  }

  async function editTopLoad(id, items) {
    const row = items.find((t) => t.id === id);
    if (!row) return;
    const modal = document.getElementById("TopLoadCargoModal");
    modal.querySelector("#tlcmType").value = row.top_load_type ?? "";
    modal.querySelector("#tlcmDetails").value = row.details ?? "";
    modal.querySelector("#tlcmQuantity").value = row.quantity ?? "";
    modal.querySelector("#tlcmUnits").value = row.units ?? "";
    modal.querySelector("#tlcmRevenueTon").value = row.revenue_ton ?? "";
    modal.querySelector("#tlcmRevenueTonUnit").value = row.revenue_ton_unit ?? "";
    modal.querySelector("#tlcmMeasurement").value = row.measurement ?? "";
    await deleteTopLoadRow(id);
    await refreshProductsAndReRenderSubModal(renderTopLoadList);
  }

  // ------------------------------------------------------------------
  // CHARTER CARGO INFO MODAL
  // ------------------------------------------------------------------
  let charterCargoInfoCharterId = null;

  function renderCharterCargoInfoList() {
    const modal = document.getElementById("CharterCargoInfoModal");
    const charter = findCharterRow(charterCargoInfoCharterId);
    const items = charter?.cargo_items ?? [];
    modal.querySelector("#ccimEmpty").classList.toggle("hidden", items.length > 0);
    modal.querySelector("#ccimList").innerHTML = items
      .map(
        (c) => `
      <div class="flex justify-between items-center border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2">
          <div class="text-sm text-zinc-800 dark:text-zinc-100">
              <span class="font-medium">${esc(c.cargo_type || EMPTY)}</span>
              <span class="text-zinc-400"> · ${esc(c.cargo_description || EMPTY)}</span>
              ${c.special_requirements ? `<div class="text-xs text-zinc-400">${esc(c.special_requirements)}</div>` : ""}
          </div>
          <div class="flex gap-2 shrink-0">
              <button type="button" class="ccim-edit text-orange-600 text-xs font-medium" data-id="${c.id}">Edit</button>
              <button type="button" class="ccim-remove text-red-500 text-xs font-medium" data-id="${c.id}">✕ Remove</button>
          </div>
      </div>`
      )
      .join("");

    modal.querySelectorAll(".ccim-remove").forEach((btn) => btn.addEventListener("click", () => removeCharterCargoInfo(Number(btn.dataset.id))));
    modal.querySelectorAll(".ccim-edit").forEach((btn) => btn.addEventListener("click", () => editCharterCargoInfo(Number(btn.dataset.id), items)));
  }

  function openCharterCargoInfoModal(charterId) {
    charterCargoInfoCharterId = charterId;
    const modal = document.getElementById("CharterCargoInfoModal");
    modal.querySelector("#ccimCargoType").innerHTML = `<option value="">Select Cargo Type</option>${window.prospectShared.getCargoTypeOptionsHtml()}`;
    resetCharterCargoInfoForm();
    renderCharterCargoInfoList();
    window.initModal({ modalId: "CharterCargoInfoModal" });
  }
  window.openCharterCargoInfoModal = openCharterCargoInfoModal;

  function collectCharterCargoInfoForm() {
    const modal = document.getElementById("CharterCargoInfoModal");
    return {
      cargo_type: modal.querySelector("#ccimCargoType").value || null,
      cargo_description: modal.querySelector("#ccimCargoDescription").value.trim() || null,
      special_requirements: modal.querySelector("#ccimSpecialRequirements").value.trim() || null,
    };
  }

  function resetCharterCargoInfoForm() {
    const modal = document.getElementById("CharterCargoInfoModal");
    modal.querySelector("#ccimCargoType").value = "";
    modal.querySelector("#ccimCargoDescription").value = "";
    modal.querySelector("#ccimSpecialRequirements").value = "";
  }

  async function addCharterCargoInfo(button) {
    const payload = collectCharterCargoInfoForm();
    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${rpmLead.uuid}/proposalRequests/${rpmProposalRequestId}/products/charters/${charterCargoInfoCharterId}/cargo`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Adding", message: response.message ?? "" });
      return;
    }
    showMessage({ status: "success", title: "Cargo Info Added" });
    resetCharterCargoInfoForm();
    await refreshProductsAndReRenderSubModal(renderCharterCargoInfoList);
  }

  async function deleteCharterCargoInfoRow(id) {
    return apiCall({
      mode: "DELETE",
      url: `/api/crm/prospects/${rpmLead.uuid}/proposalRequests/${rpmProposalRequestId}/products/charters/${charterCargoInfoCharterId}/cargo/${id}`,
    });
  }

  async function removeCharterCargoInfo(id) {
    const confirmed = await customConfirm("Remove this cargo info?");
    if (!confirmed) return;
    const response = await deleteCharterCargoInfoRow(id);
    if (!response.success) {
      showMessage({ status: "error", title: "Error Removing", message: response.message ?? "" });
      return;
    }
    showMessage({ status: "success", title: "Cargo Info Removed" });
    await refreshProductsAndReRenderSubModal(renderCharterCargoInfoList);
  }

  async function editCharterCargoInfo(id, items) {
    const row = items.find((c) => c.id === id);
    if (!row) return;
    const modal = document.getElementById("CharterCargoInfoModal");
    modal.querySelector("#ccimCargoType").value = row.cargo_type ?? "";
    modal.querySelector("#ccimCargoDescription").value = row.cargo_description ?? "";
    modal.querySelector("#ccimSpecialRequirements").value = row.special_requirements ?? "";
    await deleteCharterCargoInfoRow(id);
    await refreshProductsAndReRenderSubModal(renderCharterCargoInfoList);
  }

  // ------------------------------------------------------------------
  // CHARTER PORTS MODAL - sequential "PORT N" labels, last one relabeled
  // "FINAL PORT" (display-only, no stored flag - see the migration's
  // comment). Amount is grayed out when Port Charges Account is Direct
  // Payment (also enforced server-side).
  // ------------------------------------------------------------------
  let charterPortsCharterId = null;

  // Amount to be Charged AND the loading/unloading cargo-charge fields all
  // gray out together when Port Charges Account is Direct Payment (also
  // enforced server-side) - Direct Payment means the charterer/shipper pays
  // the port directly, so nothing about how much cargo moves through this
  // port needs recording on our side either.
  const CPM_LOADING_UNLOADING_FIELD_IDS = [
    "cpmCargoesLoading",
    "cpmMeasurementLoading",
    "cpmRtUnitLoading",
    "cpmCargoesUnloading",
    "cpmMeasurementUnloading",
    "cpmRtUnitUnloading",
  ];

  function applyPortChargeAmountGating(modal) {
    const account = modal.querySelector("#cpmPortChargeAccount").value;
    const isDirect = account === "direct";

    const amountEl = modal.querySelector("#cpmPortChargeAmount");
    amountEl.disabled = isDirect;
    if (amountEl.disabled) amountEl.value = "";

    CPM_LOADING_UNLOADING_FIELD_IDS.forEach((id) => {
      const el = modal.querySelector(`#${id}`);
      if (!el) return;
      el.disabled = isDirect;
      if (isDirect) el.value = "";
    });
  }

  function portRowLabel(index, total) {
    return index === total - 1 ? "FINAL PORT" : `PORT ${index + 1}`;
  }

  function renderCharterPortsList() {
    const modal = document.getElementById("CharterPortsModal");
    const charter = findCharterRow(charterPortsCharterId);
    const ports = charter?.ports ?? [];
    modal.querySelector("#cpmEmpty").classList.toggle("hidden", ports.length > 0);
    modal.querySelector("#cpmList").innerHTML = ports
      .map(
        (p, index) => `
      <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2">
          <div class="flex justify-between items-start gap-3">
              <div class="text-sm text-zinc-800 dark:text-zinc-100">
                  <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">${esc(portRowLabel(index, ports.length))}</span>
                  <span class="font-medium ml-1">${esc(p.port?.name || EMPTY)}</span>
                  <div class="text-xs text-zinc-400 mt-0.5">
                      ${p.port_charge_account === "direct" ? "Direct Payment" : "Invoice"}${p.port_charge_amount != null ? ` · ₱${Number(p.port_charge_amount).toLocaleString()}` : ""}
                      · Loading: ${esc(p.cargoes_for_loading ?? EMPTY)} ${esc(p.cargo_measurement_for_loading || "")} (${esc(p.revenue_ton_unit_loading || EMPTY)})
                      · Unloading: ${esc(p.cargoes_for_unloading ?? EMPTY)} ${esc(p.cargo_measurement_for_unloading || "")} (${esc(p.revenue_ton_unit_unloading || EMPTY)})
                  </div>
              </div>
              <div class="flex gap-2 shrink-0">
                  <button type="button" class="cpm-edit text-orange-600 text-xs font-medium" data-id="${p.id}">Edit</button>
                  <button type="button" class="cpm-remove text-red-500 text-xs font-medium" data-id="${p.id}">✕ Remove</button>
              </div>
          </div>
      </div>`
      )
      .join("");

    modal.querySelectorAll(".cpm-remove").forEach((btn) => btn.addEventListener("click", () => removeCharterPort(Number(btn.dataset.id))));
    modal.querySelectorAll(".cpm-edit").forEach((btn) => btn.addEventListener("click", () => editCharterPort(Number(btn.dataset.id), ports)));
  }

  function openCharterPortsModal(charterId) {
    charterPortsCharterId = charterId;
    const modal = document.getElementById("CharterPortsModal");
    const portSelect = modal.querySelector("#cpmPort");
    portSelect.innerHTML = `<option value="">Select Port</option>${window.prospectShared.getPortsOptionsHtml()}`;
    modal.querySelectorAll(".cpmRtUnitDropdown").forEach((el) => (el.innerHTML = RT_UNIT_OPTIONS_HTML));
    resetCharterPortsForm();
    makeSearchableSelect(portSelect);
    refreshSearchable(portSelect);
    renderCharterPortsList();
    window.initModal({ modalId: "CharterPortsModal" });
  }
  window.openCharterPortsModal = openCharterPortsModal;

  function collectCharterPortForm() {
    const modal = document.getElementById("CharterPortsModal");
    return {
      port_id: modal.querySelector("#cpmPort").value || null,
      port_charge_account: modal.querySelector("#cpmPortChargeAccount").value || null,
      port_charge_amount: modal.querySelector("#cpmPortChargeAmount").value || null,
      cargoes_for_loading: modal.querySelector("#cpmCargoesLoading").value || null,
      cargo_measurement_for_loading: modal.querySelector("#cpmMeasurementLoading").value.trim() || null,
      revenue_ton_unit_loading: modal.querySelector("#cpmRtUnitLoading").value || null,
      cargoes_for_unloading: modal.querySelector("#cpmCargoesUnloading").value || null,
      cargo_measurement_for_unloading: modal.querySelector("#cpmMeasurementUnloading").value.trim() || null,
      revenue_ton_unit_unloading: modal.querySelector("#cpmRtUnitUnloading").value || null,
    };
  }

  function resetCharterPortsForm() {
    const modal = document.getElementById("CharterPortsModal");
    modal.querySelector("#cpmPort").value = "";
    refreshSearchable(modal.querySelector("#cpmPort"));
    modal.querySelector("#cpmPortChargeAccount").value = "direct";
    modal.querySelector("#cpmPortChargeAmount").value = "";
    modal.querySelector("#cpmCargoesLoading").value = "";
    modal.querySelector("#cpmMeasurementLoading").value = "";
    modal.querySelector("#cpmRtUnitLoading").value = "";
    modal.querySelector("#cpmCargoesUnloading").value = "";
    modal.querySelector("#cpmMeasurementUnloading").value = "";
    modal.querySelector("#cpmRtUnitUnloading").value = "";
    applyPortChargeAmountGating(modal);
  }

  async function addCharterPort(button) {
    const payload = collectCharterPortForm();
    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${rpmLead.uuid}/proposalRequests/${rpmProposalRequestId}/products/charters/${charterPortsCharterId}/ports`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Adding", message: response.message ?? "" });
      return;
    }
    showMessage({ status: "success", title: "Port Added" });
    resetCharterPortsForm();
    await refreshProductsAndReRenderSubModal(renderCharterPortsList);
  }

  async function deleteCharterPortRow(id) {
    return apiCall({
      mode: "DELETE",
      url: `/api/crm/prospects/${rpmLead.uuid}/proposalRequests/${rpmProposalRequestId}/products/charters/${charterPortsCharterId}/ports/${id}`,
    });
  }

  async function removeCharterPort(id) {
    const confirmed = await customConfirm("Remove this port?");
    if (!confirmed) return;
    const response = await deleteCharterPortRow(id);
    if (!response.success) {
      showMessage({ status: "error", title: "Error Removing", message: response.message ?? "" });
      return;
    }
    showMessage({ status: "success", title: "Port Removed" });
    await refreshProductsAndReRenderSubModal(renderCharterPortsList);
  }

  async function editCharterPort(id, ports) {
    const row = ports.find((p) => p.id === id);
    if (!row) return;
    const modal = document.getElementById("CharterPortsModal");
    modal.querySelector("#cpmPort").value = String(row.port_id ?? "");
    refreshSearchable(modal.querySelector("#cpmPort"));
    modal.querySelector("#cpmPortChargeAccount").value = row.port_charge_account ?? "direct";
    modal.querySelector("#cpmPortChargeAmount").value = row.port_charge_amount ?? "";
    modal.querySelector("#cpmCargoesLoading").value = row.cargoes_for_loading ?? "";
    modal.querySelector("#cpmMeasurementLoading").value = row.cargo_measurement_for_loading ?? "";
    modal.querySelector("#cpmRtUnitLoading").value = row.revenue_ton_unit_loading ?? "";
    modal.querySelector("#cpmCargoesUnloading").value = row.cargoes_for_unloading ?? "";
    modal.querySelector("#cpmMeasurementUnloading").value = row.cargo_measurement_for_unloading ?? "";
    modal.querySelector("#cpmRtUnitUnloading").value = row.revenue_ton_unit_unloading ?? "";
    applyPortChargeAmountGating(modal);
    await deleteCharterPortRow(id);
    await refreshProductsAndReRenderSubModal(renderCharterPortsList);
  }

  function initSubItemModals() {
    const asm = document.getElementById("AncillaryServicesModal");
    if (asm) {
      asm.querySelector("#asmAddBtn").addEventListener("click", function () {
        addAncillary(this);
      });
      asm.querySelector("#asmCloseBtn").addEventListener("click", () => window.closeModal("AncillaryServicesModal"));
    }

    const tlcm = document.getElementById("TopLoadCargoModal");
    if (tlcm) {
      tlcm.querySelector("#tlcmAddBtn").addEventListener("click", function () {
        addTopLoad(this);
      });
      tlcm.querySelector("#tlcmCloseBtn").addEventListener("click", () => window.closeModal("TopLoadCargoModal"));
    }

    const ccim = document.getElementById("CharterCargoInfoModal");
    if (ccim) {
      ccim.querySelector("#ccimAddBtn").addEventListener("click", function () {
        addCharterCargoInfo(this);
      });
      ccim.querySelector("#ccimCloseBtn").addEventListener("click", () => window.closeModal("CharterCargoInfoModal"));
    }

    const cpm = document.getElementById("CharterPortsModal");
    if (cpm) {
      cpm.querySelector("#cpmAddBtn").addEventListener("click", function () {
        addCharterPort(this);
      });
      cpm.querySelector("#cpmCloseBtn").addEventListener("click", () => window.closeModal("CharterPortsModal"));
      cpm.querySelector("#cpmPortChargeAccount").addEventListener("change", () => applyPortChargeAmountGating(cpm));
    }
  }

  // ------------------------------------------------------------------
  // PUBLIC API
  // ------------------------------------------------------------------
  // proposalRequestId is always a known, already-minted request id - the
  // row is created eagerly by the Prospect Info modal's Contact Summary
  // button (logic_prospect_info_modal.js) before this wizard ever opens;
  // this is just opened against it later, from the assigned CSR/RM's "My
  // Requests" page (logic_proposal_requests_mine.js), which prefills every
  // tab from its saved data.
  async function openRequestProposalModal(lead, proposalRequestId) {
    const modal = document.getElementById("RequestProposalModal");
    if (!modal || !lead) return;

    rpmProposalRequestId = proposalRequestId ?? null;
    rpmSelectedOdLocationId = null;
    rpmOdCard = null;

    await ensureProductLookupsLoaded();
    renderAll(modal, lead);
    switchRpmTab(modal, "CompanyDetails");
    window.initModal({ modalId: "RequestProposalModal" });
  }
  window.openRequestProposalModal = openRequestProposalModal;

  function initRequestProposalModal() {
    const modal = document.getElementById("RequestProposalModal");
    if (!modal) return; // guarded: only runs on pages that actually include the modal

    bindRpmTabs(modal);

    modal.querySelector("#rpmSaveCompanyDetailsBtn").addEventListener("click", function () {
      saveCompanyDetails(modal, this);
    });
    modal.querySelector("#rpmAddSignatoryBtn").addEventListener("click", function () {
      addSignatory(this);
    });
    modal.querySelector("#rpmAddOdBtn").addEventListener("click", function () {
      addOdLocation(this);
    });
    modal.querySelector("#rpmSubmitBtn").addEventListener("click", function () {
      submitProposalRequest(this);
    });

    bindProductSelector(modal);
    modal.querySelector("#rpmAddProductBtn").addEventListener("click", function () {
      submitProduct(modal, this);
    });
    modal.querySelector("#rpmPcContainerType").addEventListener("change", () => {
      applyPcContainerTypeVisibility(modal);
      populatePcContainerSizeOptions(modal);
    });
    modal.querySelector("#rpmPcDeliveryType").addEventListener("change", () => {
      applyDispatchModeGating(modal, "rpmPcDeliveryType", "rpmPcDispatchOrigin", "rpmPcDispatchDestination");
    });

    initSubItemModals();
  }
  window.initRequestProposalModal = initRequestProposalModal;
})();
