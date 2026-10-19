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
  const RPM_TABS = ["CompanyDetails", "OriginDestination", "Products", "BuildMatrix", "TermsConditions", "Confirmation"];

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
  // Destination popup's fields - see renderRpmOdChips()/hydrateOdCard().
  // Purely a convenience autofill source and an optional "seeded from"
  // reference sent alongside the Save payload; the form itself is always
  // editable and clicking a chip never removes it from the suggestion list
  // (each Save creates its own independent snapshot entry, see
  // saveRpmAddOd()). Cleared after a successful save and on every popup
  // (re)open.
  let rpmSelectedOdLocationId = null;
  // The single live address-form card built via
  // window.prospectShared.locationCardHtml()/initLocationPsgcCascade() for
  // the Origin & Destination popup (<x-rpm-add-od-modal />) - rebuilt fresh
  // every time the popup opens (see openRpmAddOdModal()), cached here only
  // for the lifetime of that one open so a chip click's hydrateOdCard()
  // call reuses the same card instance.
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
  // Origin & Destination has its own chip+preview picker instead, in its
  // own popup (see renderRpmOdChips() below).
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
    el.innerHTML = `<option value="">Select Contact (optional - or type below manually)</option>${options}`;
    // Keeps its live `lead.contacts` list on the element itself (same
    // pattern as bindOdPair's _odLocations) so the change handler below
    // always prefills from the latest data, not whatever was current the
    // first time this dropdown was populated.
    el._contacts = lead.contacts ?? [];
    refreshSearchable(el);
  }

  // Picking a Contact prefills Name/Position/Email/Contact Number - every
  // field stays fully editable afterward, and this never clears fields the
  // admin may have already typed manually (picking the blank option is a
  // no-op here, not a reset).
  function signatoryContactChange(modal) {
    const select = modal.querySelector("#rpmSignatoryContact");
    const contact = (select._contacts ?? []).find((c) => String(c.id) === String(select.value));
    if (!contact) return;

    modal.querySelector("#rpmSignatoryName").value = formatContactName(contact);
    modal.querySelector("#rpmSignatoryPosition").value = contact.position ?? "";
    modal.querySelector("#rpmSignatoryEmail").value = (contact.channels ?? []).find((c) => c.channel_type === "email")?.value ?? "";
    const phoneChannel =
      (contact.channels ?? []).find((c) => c.channel_type === "mobile") ?? (contact.channels ?? []).find((c) => c.channel_type === "landline");
    modal.querySelector("#rpmSignatoryContactNumber").value = phoneChannel?.value ?? "";
  }

  // ------------------------------------------------------------------
  // TAB: COMPANY DETAILS
  // ------------------------------------------------------------------
  // Displays each signatory's own name/position/email/contact_number
  // snapshot (captured at Add time - see addSignatory()) directly, not
  // re-derived from the linked prospect_contact - that snapshot is what
  // was actually entered on this request, which may differ from whatever
  // the live contact currently says.
  function renderSignatoriesList(modal, lead) {
    const signatories = getMyProposalRequest(lead)?.signatories ?? [];
    modal.querySelector("#rpmSignatoriesEmpty").classList.toggle("hidden", signatories.length > 0);
    modal.querySelector("#rpmSignatoriesList").innerHTML = signatories
      .map((s) => {
        const subline = [s.position, s.email, s.contact_number].filter(Boolean).join(" · ");
        return `
      <div class="flex justify-between items-center border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2">
          <div>
              <p class="text-sm text-zinc-800 dark:text-zinc-100">${esc(s.name || EMPTY)}</p>
              ${subline ? `<p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">${esc(subline)}</p>` : ""}
          </div>
          <button type="button" class="rpm-remove-signatory text-red-500 text-xs font-medium shrink-0" data-id="${s.id}">✕ Remove</button>
      </div>`;
      })
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
    const name = modal.querySelector("#rpmSignatoryName").value.trim();
    if (!name) {
      showMessage({ status: "error", title: "Name is required." });
      return;
    }

    const payload = {
      prospect_contact_id: contactId || null,
      name,
      position: modal.querySelector("#rpmSignatoryPosition").value.trim() || null,
      email: modal.querySelector("#rpmSignatoryEmail").value.trim() || null,
      contact_number: modal.querySelector("#rpmSignatoryContactNumber").value.trim() || null,
    };

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${rpmProposalRequestId}/signatories`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Adding", message: response.message ?? "" });
      return;
    }

    showMessage({ status: "success", title: "Authorized Signatory Added" });
    modal.querySelector("#rpmSignatoryContact").value = "";
    modal.querySelector("#rpmSignatoryName").value = "";
    modal.querySelector("#rpmSignatoryPosition").value = "";
    modal.querySelector("#rpmSignatoryEmail").value = "";
    modal.querySelector("#rpmSignatoryContactNumber").value = "";
    refreshSearchable(modal.querySelector("#rpmSignatoryContact"));
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

  // Builds the single live address-form card (rebuilt fresh every time the
  // popup opens, cached in rpmOdCard for the lifetime of one open - see its
  // declaration above) using the same shared helpers AddLocationModal
  // already uses for exactly this "one-off editable address, not a
  // repeatable list" shape. Trimmed of the Remove button (nothing to
  // remove - there's only ever one card) and the Primary radio (doesn't
  // apply to an O&D entry). Lives in <x-rpm-add-od-modal />, not inside
  // #RequestProposalModal, hence document.getElementById rather than a
  // modal-scoped query.
  function ensureRpmOdCard() {
    const wrap = document.getElementById("rpmAodmCardWrap");
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
  // filtered by what's already been added (each Save below creates its own
  // independent snapshot entry, so the same seed address can legitimately
  // start more than one). Clicking a chip is a pure convenience autofill;
  // it does not append a row by itself - saveRpmAddOd() does that, and
  // works even with no chip ever clicked.
  function renderRpmOdChips(addresses) {
    const chipsWrap = document.getElementById("rpmAodmChips");
    document.getElementById("rpmAodmChipsEmpty").classList.toggle("hidden", addresses.length > 0);
    chipsWrap.innerHTML = addresses
      .map((loc) => {
        const active = loc.id === rpmSelectedOdLocationId;
        const activeClasses = active
          ? "border-orange-500 text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-950/20"
          : "border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 hover:border-orange-400 dark:hover:border-orange-500";
        return `<button type="button" class="rpm-aodm-chip px-3 py-1.5 rounded-lg border text-sm font-medium ${activeClasses}" data-id="${loc.id}" aria-pressed="${active}">${esc(formatLocationLabel(loc))}</button>`;
      })
      .join("");

    chipsWrap.querySelectorAll(".rpm-aodm-chip").forEach((btn) => {
      btn.addEventListener("click", async () => {
        rpmSelectedOdLocationId = Number(btn.dataset.id);
        renderRpmOdChips(addresses);
        const loc = addresses.find((a) => a.id === rpmSelectedOdLocationId);
        if (loc) await hydrateOdCard(ensureRpmOdCard(), loc);
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
  }

  // Opens <x-rpm-add-od-modal /> with a fresh blank card + this prospect's
  // own address chips - mirrors openPmAddLocationModal()'s shape
  // (logic_prospect_modal.js).
  function openRpmAddOdModal() {
    const lead = rpmLead;
    if (!lead) return;

    rpmSelectedOdLocationId = null;
    rpmOdCard = null;

    ensureRpmOdCard();
    renderRpmOdChips(lead.addresses ?? []);

    window.initModal({ modalId: "RpmAddOdModal" });
  }

  async function saveRpmAddOd(button) {
    const lead = rpmLead;
    if (!lead) return;

    const modal = document.getElementById("RequestProposalModal");
    const payload = { ...collectOdCard(ensureRpmOdCard()), prospect_location_id: rpmSelectedOdLocationId };

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
    rpmOdCard = null;
    await refreshLead(modal);
    window.closeModal("RpmAddOdModal");
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

  // RATE MATRIX RECAP - a flat table (not grouped by type, unlike the
  // Products block above) mirroring the Build Rate Matrix tab exactly, same
  // unmerged rows + Item No. sequence (see allProductRowsFlat()), same Rate
  // cell (amount + ▸/▾ fee breakdown toggle, via matrixRateCellHtml()/
  // matrixBreakdownRowHtml() - see those for the ⚠-icon-when-unpriced
  // fallback). Shared with the read-only "My Requests" Proposal Request
  // Summary modal via window.buildProposalRequestRecapHtml AND
  // window.wireMatrixBreakdownToggles (that modal lives in
  // logic_proposal_requests_mine.js, a different file on the same global
  // bundle, and must call the latter after setting this HTML or its toggles
  // stay dead).
  //
  // onlyWithRate: false (default) - every product row, same as the Build
  // Matrix tab itself. Both actual callers (the wizard's own Confirmation
  // tab via buildConfirmationTabHtml(), and the read-only My Requests
  // Proposal Request Summary modal via buildProposalRequestRecapHtml())
  // pass onlyWithRate: true - an unpriced row is still just the prospect's
  // own requirement, not something to show in a rate summary yet. The
  // default stays false here so this function itself remains reusable for
  // an unfiltered listing if one's ever needed again.
  function buildRateMatrixRecapBlock(pr, { onlyWithRate = false } = {}) {
    let rows = allProductRowsFlat(pr);
    if (onlyWithRate) {
      rows = rows.filter((row) => rpmMatrixConfirmedKeys.has(matrixRowKey(row)) || hasPersistedRate(pr, row));
    }

    if (!rows.length) {
      return `
      <div class="space-y-2">
          <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Build Rate Matrix</p>
          <p class="text-sm text-zinc-400 dark:text-zinc-500">${onlyWithRate ? "No products have a rate yet." : "No products added."}</p>
      </div>`;
    }

    const rowsHtml = rows
      .map((row) => {
        const rateRow = matrixRateRowFor(pr, row);
        return matrixRecapRowHtml(row, rateRow) + matrixBreakdownRowHtml(row, rateRow);
      })
      .join("");

    return `
    <div class="space-y-2">
        <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Build Rate Matrix</p>
        <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
            <table class="min-w-full text-xs">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                    <tr>
                        <th class="px-3 py-2 text-left">Item No.</th>
                        <th class="px-3 py-2 text-left">Rate</th>
                        <th class="px-3 py-2 text-left">Product</th>
                        <th class="px-3 py-2 text-left">Detail</th>
                        <th class="px-3 py-2 text-left">Route</th>
                        <th class="px-3 py-2 text-right">Qty</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">${rowsHtml}</tbody>
            </table>
        </div>
    </div>`;
  }

  // TERMS OF PAYMENT RECAP - one row per section that actually has a saved
  // term (see saveTermsAndConditions()/renderTermsConditions() - each of
  // the 6 per-product TnC sections has its own Terms of Payment select,
  // persisted as a proposal_request_terms_of_payment row keyed by
  // section). Ancillary fees are no longer captured on that tab, so
  // there's nothing to recap here anymore. Shared with the My Requests
  // read-only summary too (via buildProposalRequestRecapHtml()) - this
  // rewrite is required there regardless, since the old flat
  // terms_of_payment_standard/_charter fields it used to read no longer
  // exist.
  function buildTermsRecapBlock(pr) {
    const terms = pr?.terms_of_payment ?? [];

    if (!terms.length) {
      return `
      <div class="space-y-2">
          <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Terms of Payment</p>
          <p class="text-sm text-zinc-400 dark:text-zinc-500">No terms of payment set.</p>
      </div>`;
    }

    return `
    <div class="space-y-2">
        <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Terms of Payment</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            ${terms
              .map(
                (t) => `
            <div>
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">${esc(t.section)}</p>
                <p class="text-sm text-zinc-800 dark:text-zinc-100 mt-0.5">${esc(t.terms_of_payment)}</p>
            </div>`
              )
              .join("")}
        </div>
    </div>`;
  }

  // Leaner Confirmation-tab-only recap: just the Rate Matrix (filtered to
  // priced products) + Terms of Payment - unlike
  // buildProposalRequestRecapHtml() (shared with the My Requests read-only
  // summary, which still shows Company Details/Origin & Destination/
  // Products in full), this is wizard-local and not window-exposed.
  function buildConfirmationTabHtml(pr) {
    return buildRateMatrixRecapBlock(pr, { onlyWithRate: true }) + buildTermsRecapBlock(pr);
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
      ["Authorized Signatories", signatories.length ? signatories.map((s) => s.name || EMPTY).join(", ") : EMPTY],
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
    // onlyWithRate: true - an unpriced product is still just the
    // prospect's own requirement, not yet part of what's being proposed;
    // it only belongs in a rate summary once Build Rate Matrix has priced
    // it (same rule the Confirmation tab already enforces).
    const rateMatrixBlock = buildRateMatrixRecapBlock(pr, { onlyWithRate: true });
    const termsBlock = buildTermsRecapBlock(pr);

    return [companyBlock, odBlock, productsBlock, rateMatrixBlock, termsBlock].filter(Boolean).join("");
  }
  window.buildProposalRequestRecapHtml = buildProposalRequestRecapHtml;

  function renderConfirmation(modal, lead) {
    const pr = getMyProposalRequest(lead);
    const recap = modal.querySelector("#rpmConfirmationRecap");
    recap.innerHTML = buildConfirmationTabHtml(pr);
    wireMatrixBreakdownToggles(recap);
    modal.querySelector("#rpmSubmittedStatus").textContent = pr?.submitted_at ? `Submitted on ${formatDateTime(pr.submitted_at)}` : "";
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
    "rpmAddContainerBtn",
    "rpmAddRollingCargoBtn",
    "rpmAddLooseCargoBtn",
    "rpmAddTruckingBtn",
    "rpmAddCharterBtn",
    "rpmSaveTermsConditionsBtn",
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
    renderBuildMatrix(modal, lead);
    renderTermsConditions(modal, lead);
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
  // Maps each product type to its own standalone "+ Add" popup (see
  // rpm-add-*-modal.blade.php) - used by applyProductPrefill() to open the
  // right popup for the "Copy from Prospect Products" shortcut.
  const PRODUCT_MODAL_IDS = {
    container: "RpmAddContainerModal",
    rolling_cargo: "RpmAddRollingCargoModal",
    loose_cargo: "RpmAddLooseCargoModal",
    trucking: "RpmAddTruckingModal",
    charter: "RpmAddCharterModal",
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
  // id -> {name, base_value} - the Special Charges catalog (App Settings ->
  // /page_maintenance), now the data source for the Ancillary Type select
  // (was a free LOV) so a picked type can show its real base value.
  let specialChargesById = {};
  let productLookupsPromise = null;

  function optionsHtmlFromList(values) {
    return values.map((v) => `<option value="${esc(v)}">${esc(v)}</option>`).join("");
  }

  async function loadProductLookups() {
    await window.prospectShared.ensureContainerLookupsLoaded();

    // Delivery Types LOV is no longer fetched here - Service Type is now
    // the fixed SERVICE_TYPES list (see top of file), not an LOV-backed id.
    const [unitRes, chargesRes, yardRes] = await Promise.all([
      apiCall({ mode: "GET", url: "/api/listofval/unit" }),
      apiCall({ mode: "GET", url: "/api/specialCharges?per_page=200" }),
      apiCall({ mode: "GET", url: "/api/cargoYards?per_page=200" }),
    ]);

    if (Array.isArray(unitRes)) {
      unitOptionsHtml = unitRes.map((lov) => `<option value="${esc(lov.lov_name)}">${esc(lov.lov_name)}</option>`).join("");
    }
    if (chargesRes.success) {
      const charges = (chargesRes.data.data ?? []).filter((c) => c.is_active);
      specialChargesById = Object.fromEntries(charges.map((c) => [c.special_charge_id, c]));
      ancillaryTypeOptionsHtml = charges.map((c) => `<option value="${c.special_charge_id}">${esc(c.name)}</option>`).join("");
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
  // Document-scoped (not modal-scoped) - these fields now live across 5
  // separate standalone popups (see rpm-add-*-modal.blade.php), not nested
  // inside #RequestProposalModal.
  function populateProductStaticDropdowns() {
    document.querySelectorAll(".rpmPortDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = `<option value="">Select Port</option>${window.prospectShared.getPortsOptionsHtml()}`;
      el.value = current;
      makeSearchableSelect(el);
      refreshSearchable(el);
    });
    document.querySelectorAll(".rpmCargoTypeDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = `<option value="">Select Cargo Type</option>${window.prospectShared.getCargoTypeOptionsHtml()}`;
      el.value = current;
    });
    document.querySelectorAll(".rpmTruckingCargoTypeDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = `<option value="">Select Cargo Type</option>${window.prospectShared.getTruckingCargoTypeOptionsHtml()}`;
      el.value = current;
    });
    document.querySelectorAll(".rpmDeliveryTypeDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = serviceTypeOptionsHtml;
      el.value = current;
    });
    document.querySelectorAll(".rpmUnitDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = `<option value="">Select Unit</option>${unitOptionsHtml}`;
      el.value = current;
    });
    document.querySelectorAll(".rpmRtUnitDropdown").forEach((el) => {
      el.innerHTML = RT_UNIT_OPTIONS_HTML;
    });
    document.querySelectorAll(".rpmTermsOfPaymentDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = `<option value="">Select Terms of Payment</option>${optionsHtmlFromList(TERMS_OF_PAYMENT_STANDARD)}`;
      el.value = current;
    });
    document.querySelectorAll(".rpmCharterTermsOfPaymentDropdown").forEach((el) => {
      const current = el.value;
      el.innerHTML = `<option value="">Select Terms of Payment</option>${optionsHtmlFromList(TERMS_OF_PAYMENT_CHARTER)}`;
      el.value = current;
    });
    const charterServiceSelect = document.getElementById("rpmChCharterServiceId");
    if (charterServiceSelect) {
      const current = charterServiceSelect.value;
      charterServiceSelect.innerHTML = `<option value="">Select Charter Type (optional)</option>${window.prospectShared.getCharterServiceOptionsHtml()}`;
      charterServiceSelect.value = current;
    }
  }

  // Products' Origin/Destination selects still key off prospect_location_id
  // (a separate, not-yet-finalized contract - see this tab's own header
  // comment) - entries with no prospect_location_id (fully manual O&D
  // entries, now possible since that form is editable) can't be referenced
  // here yet and are excluded rather than shown broken. Reads the label off
  // the row's own snapshot fields (l.location_mnemonic/formatLocationLabel(l))
  // rather than the joined prospect_location, since that reflects what was
  // actually saved even if the entry was edited away from its seed address.
  function odOptionsHtml(addresses, excludeId) {
    return (addresses ?? [])
      .filter((a) => String(a.id) !== String(excludeId ?? ""))
      .map((a) => `<option value="${a.id}">${esc(a.location_mnemonic || formatLocationLabel(a))}</option>`)
      .join("");
  }

  // Re-renders one side's Port <select> to only the ports whose Location
  // has a Serviceable Area matching the selected Origin/Destination
  // address's province/town-city (see getPortsOptionsHtmlForArea() in
  // logic_prospect_modal.js) - falls back to every port when nothing's
  // selected yet or nothing matches. Clears the previously-picked port if
  // it's no longer among the filtered options, same "drop a now-invalid
  // value" precedent as applyDispatchModeGating() below.
  function applyPortFilterForSide(locationEl, portEl) {
    if (!portEl) return;

    const address = (locationEl._odLocations ?? []).find((a) => String(a.id) === String(locationEl.value));
    const current = portEl.value;
    portEl.innerHTML =
      '<option value="">Select Port</option>' +
      window.prospectShared.getPortsOptionsHtmlForArea(address?.address_province, address?.address_town_city);
    portEl.value = Array.from(portEl.options).some((o) => o.value === current) ? current : "";
    refreshSearchable(portEl);
  }

  // originEl/destEl keep their live `locations` list on the element itself
  // (not a closure variable) so the one-time-bound change listener always
  // filters against the latest Origin & Destination tab data, not whatever
  // was current the first time this pair was bound.
  function bindOdPair(originEl, destEl, locations, originPortEl, destPortEl) {
    originEl._odLocations = locations;

    const currentOrigin = originEl.value;
    const currentDest = destEl.value;
    originEl.innerHTML = `<option value="">Select Origin</option>${odOptionsHtml(locations)}`;
    originEl.value = currentOrigin;
    destEl.innerHTML = `<option value="">Select Destination</option>${odOptionsHtml(locations, originEl.value)}`;
    destEl.value = currentDest;
    refreshSearchable(originEl);
    refreshSearchable(destEl);
    applyPortFilterForSide(originEl, originPortEl);
    applyPortFilterForSide(destEl, destPortEl);

    if (!originEl.dataset.odBound) {
      originEl.dataset.odBound = "1";
      makeSearchableSelect(originEl);
      makeSearchableSelect(destEl);
      originEl.addEventListener("change", () => {
        const keepDest = destEl.value;
        destEl.innerHTML = `<option value="">Select Destination</option>${odOptionsHtml(originEl._odLocations, originEl.value)}`;
        destEl.value = keepDest === originEl.value ? "" : keepDest;
        refreshSearchable(destEl);
        applyPortFilterForSide(originEl, originPortEl);
        applyPortFilterForSide(destEl, destPortEl);
      });
      destEl.addEventListener("change", () => {
        applyPortFilterForSide(destEl, destPortEl);
      });
    }
  }

  // Sourced from the prospect's own address book (lead.addresses), the same
  // list the Origin & Destination tab's own suggestion chips use - NOT from
  // getMyProposalRequest(lead)?.locations (the O&D tab's saved rows), since
  // origin_prospect_location_id/destination_prospect_location_id FK straight
  // to prospect_locations. Previously sourced from the O&D tab's own saved
  // rows filtered to only chip-selected ones (via prospect_location_id),
  // which silently hid any manually-typed O&D entry from these dropdowns.
  function populateOdPairs(lead) {
    const addresses = lead.addresses ?? [];
    [
      ["#rpmPcOrigin", "#rpmPcDestination", "#rpmPcOriginPort", "#rpmPcDestinationPort"],
      ["#rpmRcOrigin", "#rpmRcDestination", "#rpmRcOriginPort", "#rpmRcDestinationPort"],
      ["#rpmLcOrigin", "#rpmLcDestination", "#rpmLcOriginPort", "#rpmLcDestinationPort"],
      ["#rpmTrOrigin", "#rpmTrDestination"],
    ].forEach(([originSel, destSel, originPortSel, destPortSel]) => {
      const originEl = document.querySelector(originSel);
      const destEl = document.querySelector(destSel);
      const originPortEl = originPortSel ? document.querySelector(originPortSel) : null;
      const destPortEl = destPortSel ? document.querySelector(destPortSel) : null;
      if (originEl && destEl) bindOdPair(originEl, destEl, addresses, originPortEl, destPortEl);
    });
  }

  // ------------------------------------------------------------------
  // CONTAINER form - CV/FR/RF unified. Minimum Temperature only for RF
  // (same TYPE_FIELD_VISIBILITY-style convention as the Requirements tab).
  // Dispatch Mode (Origin/Destination) are disabled per side when the
  // selected Service Type doesn't include trucking on that side (Pier).
  // ------------------------------------------------------------------
  // Document-scoped - these now live in the standalone <x-rpm-add-container-modal />
  // popup, not nested inside #RequestProposalModal.
  function applyPcContainerTypeVisibility() {
    const type = document.getElementById("rpmPcContainerType").value;

    // Per brief: the rest of the Add Container form stays hidden until a
    // Container Type is picked - mirrors the Requirements tab's own Add
    // Freight gating (applyContainerTypeVisibility() in logic_prospect_modal.js).
    document.getElementById("rpmPcRestOfForm")?.classList.toggle("hidden", !type);
    document.getElementById("rpmPcLockedHint")?.classList.toggle("hidden", Boolean(type));

    document.querySelector(".rpm-field-min-temp")?.classList.toggle("hidden", type !== "RF");
  }

  function populatePcContainerSizeOptions() {
    const type = document.getElementById("rpmPcContainerType").value;
    const catalog = window.prospectShared.getContainerCatalogByCode()[type] ?? { sizes: [] };
    const sizeSelect = document.getElementById("rpmPcContainerSize");
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

  function collectFormPayload(formSelector) {
    const form = document.querySelector(formSelector);
    const obj = {};
    form.querySelectorAll("[data-field]").forEach((el) => {
      if (el.type === "checkbox") {
        obj[el.dataset.field] = el.checked;
        return;
      }
      const rawValue = el.classList.contains("currency-input") ? parseCurrencyValue(el.value) : el.value;
      obj[el.dataset.field] = rawValue === "" ? null : rawValue;
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

  function resetProductForm(formSelector) {
    document.querySelector(formSelector)
      .querySelectorAll("[data-field]")
      .forEach((el) => {
        if (el.type === "checkbox") el.checked = false;
        else if (el.tagName === "SELECT") el.selectedIndex = 0;
        else el.value = "";
      });
    // Container form's type-driven bits need re-applying after a reset
    // snaps #rpmPcContainerType back to its default (blank) - harmless
    // no-op when a different product's form was just reset.
    applyPcContainerTypeVisibility();
    populatePcContainerSizeOptions();
  }

  // Opens the standalone "+ Add X" popup for one product type (see
  // rpm-add-*-modal.blade.php), with a freshly-reset form.
  function openRpmAddProductModal(productType) {
    if (!rpmLead) return;
    resetProductForm(PRODUCT_ENDPOINTS[productType].form);
    window.initModal({ modalId: PRODUCT_MODAL_IDS[productType] });
  }
  function openRpmAddContainerModal() {
    openRpmAddProductModal("container");
  }
  function openRpmAddRollingCargoModal() {
    openRpmAddProductModal("rolling_cargo");
  }
  function openRpmAddLooseCargoModal() {
    openRpmAddProductModal("loose_cargo");
  }
  function openRpmAddTruckingModal() {
    openRpmAddProductModal("trucking");
  }
  function openRpmAddCharterModal() {
    openRpmAddProductModal("charter");
  }

  // Shared save handler for all 5 product popups - POSTs the matching
  // form, appends the row to the Products tab behind it, then closes the
  // popup. See the 5 thin wrappers below (one per "+ Add" button).
  async function saveRpmProduct(productType, button) {
    const lead = rpmLead;
    if (!lead) return;

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
    resetProductForm(form);
    await refreshLead(document.getElementById("RequestProposalModal"));
    window.closeModal(PRODUCT_MODAL_IDS[productType]);
  }
  function saveRpmContainer(button) {
    return saveRpmProduct("container", button);
  }
  function saveRpmRollingCargo(button) {
    return saveRpmProduct("rolling_cargo", button);
  }
  function saveRpmLooseCargo(button) {
    return saveRpmProduct("loose_cargo", button);
  }
  function saveRpmTrucking(button) {
    return saveRpmProduct("trucking", button);
  }
  function saveRpmCharter(button) {
    return saveRpmProduct("charter", button);
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
        <td class="px-3 py-2"><span class="inline-flex items-center gap-1.5">${matrixRateIcon(ancCount > 0)}<button type="button" class="rpm-view-ancillary text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 text-xs font-medium" data-type="container" data-id="${row.id}">${ancCount} · View</button></span></td>
        <td class="px-3 py-2"><button type="button" class="rpm-remove-product text-red-500 text-xs font-medium" data-product="container" data-id="${row.id}">✕ Remove</button></td>
    </tr>`;
  }

  function productCargoRowHtml(row, productType, withTopLoad) {
    const ancCount = (row.ancillary_services ?? []).length;
    const topCell = withTopLoad
      ? `<td class="px-3 py-2"><button type="button" class="rpm-view-topload text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 text-xs font-medium" data-id="${row.id}">${(row.top_load_cargo ?? []).length} · View</button></td>`
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
        <td class="px-3 py-2"><span class="inline-flex items-center gap-1.5">${matrixRateIcon(ancCount > 0)}<button type="button" class="rpm-view-ancillary text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 text-xs font-medium" data-type="${productType}" data-id="${row.id}">${ancCount} · View</button></span></td>
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
        <td class="px-3 py-2"><span class="inline-flex items-center gap-1.5">${matrixRateIcon(ancCount > 0)}<button type="button" class="rpm-view-ancillary text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 text-xs font-medium" data-type="trucking" data-id="${row.id}">${ancCount} · View</button></span></td>
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
        <td class="px-3 py-2"><button type="button" class="rpm-view-charter-cargo text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 text-xs font-medium" data-id="${row.id}">${cargoCount} · View</button></td>
        <td class="px-3 py-2"><button type="button" class="rpm-view-charter-ports text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 text-xs font-medium" data-id="${row.id}">${portCount} · View</button></td>
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

  // Opens the matching product's own standalone "+ Add" popup (see
  // PRODUCT_MODAL_IDS above), pre-filled with this requirement row's
  // overlapping fields - the "Copy from Prospect Products" shortcut's
  // primitive. Same open-then-fill shape as openPmAddFreightModal() in
  // logic_prospect_modal.js, just reset+fill before showing instead of
  // after.
  function applyProductPrefill(kind, id) {
    const row = findRequirementRow(kind, id);
    if (!row) return;

    if (kind === "container") {
      const productType = CONTAINER_TYPE_TO_PRODUCT[row.container_type] ?? "container";
      const { form } = PRODUCT_ENDPOINTS[productType];
      resetProductForm(form);

      if (productType === "container") {
        // Ordering matters: the size <select> has no options until
        // generated for the chosen type, so set the type + regenerate
        // visibility/options BEFORE writing container_size_id.
        document.getElementById("rpmPcContainerType").value = row.container_type;
        applyPcContainerTypeVisibility();
        populatePcContainerSizeOptions();
        fillFormFromData(form, containerPrefillData(row));
        // service_type was just prefilled - re-run the dispatch-mode
        // enabled/disabled gating so it matches the copied value.
        applyDispatchModeGating(document.getElementById("RpmAddContainerModal"), "rpmPcDeliveryType", "rpmPcDispatchOrigin", "rpmPcDispatchDestination");
      } else {
        fillFormFromData(form, rollingOrLoosePrefillData(row));
      }
      window.initModal({ modalId: PRODUCT_MODAL_IDS[productType] });
      return;
    }

    if (kind === "trucking") {
      resetProductForm(PRODUCT_ENDPOINTS.trucking.form);
      fillFormFromData(PRODUCT_ENDPOINTS.trucking.form, truckingPrefillData(row));
      window.initModal({ modalId: PRODUCT_MODAL_IDS.trucking });
      return;
    }

    if (kind === "charter") {
      resetProductForm(PRODUCT_ENDPOINTS.charter.form);
      fillFormFromData(PRODUCT_ENDPOINTS.charter.form, charterPrefillData(row));
      window.initModal({ modalId: PRODUCT_MODAL_IDS.charter });
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
      btn.addEventListener("click", () => applyProductPrefill(btn.dataset.kind, Number(btn.dataset.id)));
    });
  }

  function renderProducts(modal, lead) {
    const pr = getMyProposalRequest(lead);
    const containers = pr?.product_containers ?? [];
    const rolling = pr?.product_rolling_cargo ?? [];
    const loose = pr?.product_loose_cargo ?? [];
    const truckings = pr?.product_truckings ?? [];
    const charters = pr?.product_charters ?? [];

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
    populateProductStaticDropdowns();
    populateOdPairs(lead);
    populatePcContainerSizeOptions();
    applyPcContainerTypeVisibility();
    renderProductsPrefillShortcuts(modal, lead);
  }

  // ==================================================================
  // TAB: BUILD RATE MATRIX - every product added on the Products tab,
  // unmerged (one row per actual saved product, unlike collectProductLineItems()
  // above which merges by route+type for the Confirmation recap - this tab
  // needs the full per-row field set for ProductSpecificationModal, which
  // a merged summary row doesn't carry). Rate column is a session-only
  // "reviewed" flag (rpmMatrixConfirmedKeys, reset per wizard open) - not
  // persisted; real rate-matrix pricing is backend work planned separately.
  // ==================================================================
  let rpmMatrixConfirmedKeys = new Set();
  let rpmMatrixCurrentKey = null;
  // Full flat row (from allProductRowsFlat()) for whatever product the
  // Product Specification modal is currently open against - set in
  // openProductSpecificationModal(), cleared in cancelProductSpecification()/
  // saveProductSpecification(). saveProductSpecification() needs __type/id/
  // __itemNo off of it to build the productRates POST payload.
  let rpmMatrixCurrentRow = null;

  // Item No. is this function's own sequence (NOT the Products recap's
  // collectProductLineItems() numbering, which merges duplicate rows) -
  // every consumer (Build Matrix tab, Product Specification modal, the
  // Confirmation Rate Matrix summary) calls this same function, so they
  // always agree without sharing any state. Deliberately unmerged - one
  // row per actual saved record, since the modal needs each row's full,
  // exact field set (dispatch mode, ports, etc.) which a merged summary
  // row can't safely carry.
  function allProductRowsFlat(pr) {
    const rows = [];
    (pr?.product_containers ?? []).forEach((r) => rows.push({ ...r, __type: "container", __typeCode: r.container_type }));
    (pr?.product_rolling_cargo ?? []).forEach((r) => rows.push({ ...r, __type: "rolling_cargo", __typeCode: "RC" }));
    (pr?.product_loose_cargo ?? []).forEach((r) => rows.push({ ...r, __type: "loose_cargo", __typeCode: "LC" }));
    (pr?.product_truckings ?? []).forEach((r) => rows.push({ ...r, __type: "trucking", __typeCode: "TR" }));
    (pr?.product_charters ?? []).forEach((r) => rows.push({ ...r, __type: "charter", __typeCode: "CH" }));
    const code = pr?.code || "RQ-YY-XXXXXX";
    rows.forEach((row, idx) => {
      row.__itemNo = `${code}-${String(idx + 1).padStart(2, "0")}`;
    });
    return rows;
  }

  function matrixRowKey(row) {
    return `${row.__type}:${row.id}`;
  }

  function matrixRateIcon(confirmed) {
    return confirmed
      ? `<span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400" title="Rate reviewed">✓</span>`
      : `<span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400" title="Rate not yet set">⚠</span>`;
  }

  function matrixRouteLabel(row) {
    if (row.__type === "charter") {
      const ports = row.ports ?? [];
      const origin = ports[0]?.port?.name || EMPTY;
      const dest = ports.length ? ports[ports.length - 1]?.port?.name || EMPTY : EMPTY;
      return `${esc(origin)} → ${esc(dest)}`;
    }
    const origin = row.origin_location?.location_mnemonic || EMPTY;
    const dest = row.destination_location?.location_mnemonic || EMPTY;
    return `${esc(origin)} → ${esc(dest)}`;
  }

  function matrixDetailLabel(row) {
    switch (row.__type) {
      case "container":
        return [LINE_ITEM_TYPE_META[row.container_type]?.label ?? row.container_type, row.container_size?.size].filter(Boolean).map(esc).join(" · ") || EMPTY;
      case "rolling_cargo":
      case "loose_cargo":
        return esc(row.cargo_type || EMPTY);
      case "trucking":
        return esc(row.trucking_cargo_type || EMPTY);
      case "charter":
        return esc(row.vessel_name || EMPTY);
      default:
        return EMPTY;
    }
  }

  function matrixQtyLabel(row) {
    switch (row.__type) {
      case "container":
      case "trucking":
        return esc(row.quantity ?? EMPTY);
      case "rolling_cargo":
      case "loose_cargo":
        return esc(row.cargo_quantity ?? EMPTY);
      case "charter":
        return `${(row.cargo_items ?? []).length} cargo line${(row.cargo_items ?? []).length === 1 ? "" : "s"}`;
      default:
        return EMPTY;
    }
  }

  // hasPersistedRate() lets the Rate ✓ icon survive a reload/reopen - the
  // session-only rpmMatrixConfirmedKeys Set is empty on every fresh wizard
  // open (see openRequestProposalModal()), so "reviewed" must also be true
  // whenever a product_rates row already exists for this product on the
  // current pr. Both signals are OR'd by the two callers below.
  function hasPersistedRate(pr, row) {
    return (pr?.product_rates ?? []).some((r) => r.product_type === row.__type && Number(r.product_id) === Number(row.id));
  }

  // Same (product_type, product_id) match hasPersistedRate() uses, but
  // returns the actual row (not just a boolean) so callers can show the
  // real amount + fee breakdown instead of just a reviewed/not-reviewed
  // icon - used by every Rate-column renderer (Build Matrix tab and both
  // recap tables).
  function matrixRateRowFor(pr, row) {
    return (pr?.product_rates ?? []).find((r) => r.product_type === row.__type && Number(r.product_id) === Number(row.id));
  }

  // Rate column cell for the Build Matrix tab (not the recap tables, which
  // keep the plain icon via matrixRateIcon()/matrixRecapRowHtml()). No
  // persisted rate yet -> same ⚠ icon as before, but still gets a toggle if
  // the row has ancillary services (those are independent of whether the
  // rate itself has been set yet). A persisted rate -> the real total
  // (peso() - same formatter the rate entry modal uses) plus a ▸/▾ toggle
  // when there's a fee breakdown and/or ancillary services to show, wired
  // up in renderBuildMatrix() below.
  function matrixRateCellHtml(row, rateRow) {
    const hasAncillary = (row.ancillary_services ?? []).length > 0;
    if (!rateRow || rateRow.total_rate == null) {
      const toggle = hasAncillary
        ? `<button type="button" class="rpm-matrix-breakdown-toggle ml-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300" aria-expanded="false" title="Show ancillary services">▸</button>`
        : "";
      return `${matrixRateIcon(false)}${toggle}`;
    }
    const hasBreakdown = (rateRow.fee_breakdown ?? []).length > 0 || hasAncillary;
    const toggle = hasBreakdown
      ? `<button type="button" class="rpm-matrix-breakdown-toggle ml-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300" aria-expanded="false" title="Show fee breakdown">▸</button>`
      : "";
    return `<span class="font-semibold text-zinc-800 dark:text-zinc-100 whitespace-nowrap">${peso(Number(rateRow.total_rate))}</span>${toggle}`;
  }

  function breakdownSubtableHtml(title, headers, rowsHtml) {
    return `
    <div class="space-y-1">
        <p class="text-[10px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">${esc(title)}</p>
        <table class="min-w-full text-[11px]">
            <thead class="text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                <tr>${headers.map((h, i) => `<th class="px-3 py-1 ${i === 0 ? "text-left" : "text-right"}">${esc(h)}</th>`).join("")}</tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">${rowsHtml}</tbody>
        </table>
    </div>`;
  }

  // Collapsed-by-default sub-row inserted directly under its parent matrix
  // row (colspan across all 6 columns), read-only, toggled by the caret
  // matrixRateCellHtml() renders. Independently toggleable per row (not an
  // exclusive accordion) - this is a passive review table, not a form, so
  // comparing two rows' breakdowns open at once is a legitimate use case.
  // Stacks two independent sub-tables when both exist - Rate Breakdown
  // (the product's own fee_breakdown) and Ancillary Services (quantity ×
  // base price + adjustment per line, same shape ProductSpecificationModal
  // edits) - either can be present without the other.
  function matrixBreakdownRowHtml(row, rateRow) {
    const feeLines = rateRow?.fee_breakdown ?? [];
    const ancillaryLines = row?.ancillary_services ?? [];
    if (!feeLines.length && !ancillaryLines.length) return "";

    const feeTable = feeLines.length
      ? breakdownSubtableHtml(
          "Rate Breakdown",
          ["Detail", "Base Rate", "Adjustment", "Total"],
          feeLines
            .map((line) => {
              const base = Number(line.base) || 0;
              const adjustment = Number(line.adjustment) || 0;
              return `
            <tr>
                <td class="px-3 py-1.5">${esc(line.detail)}</td>
                <td class="px-3 py-1.5 text-right">${peso(base)}</td>
                <td class="px-3 py-1.5 text-right">${peso(adjustment)}</td>
                <td class="px-3 py-1.5 text-right font-medium text-zinc-700 dark:text-zinc-200">${peso(base + adjustment)}</td>
            </tr>`;
            })
            .join("")
        )
      : "";

    const ancillaryTable = ancillaryLines.length
      ? breakdownSubtableHtml(
          "Ancillary Services",
          ["Detail", "Qty", "Base Price", "Adjustment", "Sub Total"],
          ancillaryLines
            .map((s) => {
              const base = Number(s.base_value) || 0;
              const qty = Number(s.quantity) || 0;
              const adjustment = Number(s.adjustment) || 0;
              return `
            <tr>
                <td class="px-3 py-1.5">${esc(s.special_charge?.name || s.ancillary_type || EMPTY)}</td>
                <td class="px-3 py-1.5 text-right">${esc(s.quantity ?? EMPTY)}</td>
                <td class="px-3 py-1.5 text-right">${peso(base)}</td>
                <td class="px-3 py-1.5 text-right">${peso(adjustment)}</td>
                <td class="px-3 py-1.5 text-right font-medium text-zinc-700 dark:text-zinc-200">${peso(qty * base + adjustment)}</td>
            </tr>`;
            })
            .join("")
        )
      : "";

    return `
    <tr class="rpm-matrix-breakdown hidden bg-zinc-50 dark:bg-zinc-800/30">
        <td colspan="6" class="px-3 py-2 space-y-3">
            ${feeTable}
            ${ancillaryTable}
        </td>
    </tr>`;
  }

  // Shared by every table that renders matrixRateCellHtml()'s ▸/▾ toggle -
  // the Build Matrix tab, the Confirmation tab recap, and the read-only "My
  // Requests" Proposal Request Summary modal (wired from
  // logic_proposal_requests_mine.js via window.wireMatrixBreakdownToggles,
  // since that modal lives in a different file but shares this global
  // bundle). Finds the breakdown row via DOM adjacency (the toggle's own
  // <tr> immediate next sibling) rather than a data-key lookup, since two of
  // those three tables can exist in the same modal's DOM at once (different
  // <details>/tab panes) and a key-based querySelector could resolve to the
  // wrong table's row.
  function wireMatrixBreakdownToggles(container) {
    container.querySelectorAll(".rpm-matrix-breakdown-toggle").forEach((btn) => {
      btn.addEventListener("click", (event) => {
        event.stopPropagation();
        const breakdownRow = btn.closest("tr")?.nextElementSibling;
        if (!breakdownRow || !breakdownRow.classList.contains("rpm-matrix-breakdown")) return;
        const expanded = !breakdownRow.classList.toggle("hidden");
        btn.textContent = expanded ? "▾" : "▸";
        btn.setAttribute("aria-expanded", String(expanded));
      });
    });
  }
  window.wireMatrixBreakdownToggles = wireMatrixBreakdownToggles;

  function matrixRowHtml(row, rateRow) {
    const meta = LINE_ITEM_TYPE_META[row.__typeCode] ?? { label: row.__typeCode, dot: "bg-zinc-400" };
    return `
    <tr class="rpm-matrix-row cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800" data-type="${row.__type}" data-id="${row.id}">
        <td class="px-3 py-2 font-mono text-zinc-700 dark:text-zinc-200 whitespace-nowrap">${esc(row.__itemNo)}</td>
        <td class="px-3 py-2">${matrixRateCellHtml(row, rateRow)}</td>
        <td class="px-3 py-2"><span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full ${meta.dot} shrink-0"></span>${esc(meta.label)}</span></td>
        <td class="px-3 py-2">${matrixDetailLabel(row)}</td>
        <td class="px-3 py-2">${matrixRouteLabel(row)}</td>
        <td class="px-3 py-2 text-right">${matrixQtyLabel(row)}</td>
    </tr>`;
  }

  // Same cells and Rate display as matrixRowHtml(), minus the
  // clickable/hover-to-edit affordance - used by buildRateMatrixRecapBlock()
  // (Confirmation tab + the read-only "My Requests" summary modal, neither
  // of which opens ProductSpecificationModal on click for this table, just
  // the same ▸/▾ breakdown toggle Build Matrix has).
  function matrixRecapRowHtml(row, rateRow) {
    const meta = LINE_ITEM_TYPE_META[row.__typeCode] ?? { label: row.__typeCode, dot: "bg-zinc-400" };
    return `
    <tr>
        <td class="px-3 py-2 font-mono text-zinc-700 dark:text-zinc-200 whitespace-nowrap">${esc(row.__itemNo)}</td>
        <td class="px-3 py-2">${matrixRateCellHtml(row, rateRow)}</td>
        <td class="px-3 py-2"><span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full ${meta.dot} shrink-0"></span>${esc(meta.label)}</span></td>
        <td class="px-3 py-2">${matrixDetailLabel(row)}</td>
        <td class="px-3 py-2">${matrixRouteLabel(row)}</td>
        <td class="px-3 py-2 text-right">${matrixQtyLabel(row)}</td>
    </tr>`;
  }

  function renderBuildMatrix(modal, lead) {
    const pr = getMyProposalRequest(lead);
    const rows = allProductRowsFlat(pr);

    modal.querySelector("#rpmMatrixEmpty").classList.toggle("hidden", rows.length > 0);
    modal.querySelector("#rpmMatrixTableWrap").classList.toggle("hidden", rows.length === 0);
    modal.querySelector("#rpmMatrixTableBody").innerHTML = rows
      .map((row) => {
        const rateRow = matrixRateRowFor(pr, row);
        return matrixRowHtml(row, rateRow) + matrixBreakdownRowHtml(row, rateRow);
      })
      .join("");

    modal.querySelectorAll(".rpm-matrix-row").forEach((tr) => {
      tr.addEventListener("click", () => openProductSpecificationModal(tr.dataset.type, Number(tr.dataset.id)));
    });

    wireMatrixBreakdownToggles(modal);
  }

  // ==================================================================
  // TAB: TERMS & CONDITIONS - each of the 6 per-product reference sections
  // has its own Terms of Payment select (.rpm-tc-payment-term,
  // data-section identifies which - see request-proposal-modal.blade.php),
  // persisted as one proposal_request_terms_of_payment row per section
  // (keyed by section). A section is only shown when its matching product
  // table actually has at least one row - see
  // RPM_TC_SECTION_PRODUCT_CHECKS below. Every other input in this pane
  // (declared value caps, surcharge %, storage days, load limits,
  // temperature, laytime) stays display-only per brief, not persisted.
  // ==================================================================
  // Maps each TnC section id to a check against the current pr - run once
  // per renderTermsConditions() call to show/hide that section. ConVans &
  // Flatracks / Reefer Vans both come from product_containers, split by
  // container_type; the rest map 1:1 to their own product array.
  const RPM_TC_SECTION_PRODUCT_CHECKS = {
    rpmTcSectionConvan: (pr) => (pr?.product_containers ?? []).some((c) => ["CV", "FR"].includes(c.container_type)),
    rpmTcSectionReefer: (pr) => (pr?.product_containers ?? []).some((c) => c.container_type === "RF"),
    rpmTcSectionRolling: (pr) => (pr?.product_rolling_cargo ?? []).length > 0,
    rpmTcSectionBreakbulk: (pr) => (pr?.product_loose_cargo ?? []).length > 0,
    rpmTcSectionTrucking: (pr) => (pr?.product_truckings ?? []).length > 0,
    rpmTcSectionCharter: (pr) => (pr?.product_charters ?? []).length > 0,
  };

  function renderTermsConditions(modal, lead) {
    const pr = getMyProposalRequest(lead);
    const terms = pr?.terms_of_payment ?? [];

    modal.querySelectorAll(".rpm-tc-payment-term").forEach((el) => {
      const saved = terms.find((t) => t.section === el.dataset.section);
      el.value = saved?.terms_of_payment ?? "";
    });

    Object.entries(RPM_TC_SECTION_PRODUCT_CHECKS).forEach(([sectionId, hasProduct]) => {
      modal.querySelector(`#${sectionId}`)?.classList.toggle("hidden", !hasProduct(pr));
    });
  }

  async function saveTermsAndConditions(modal, button) {
    const lead = rpmLead;
    if (!lead) return;

    const terms_of_payment = [];
    modal.querySelectorAll(".rpm-tc-payment-term").forEach((el) => {
      if (!el.value) return;
      terms_of_payment.push({ section: el.dataset.section, terms_of_payment: el.value });
    });

    const payload = { terms_of_payment };

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${rpmProposalRequestId}/termsAndConditions`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Saving", message: response.message ?? "" });
      return;
    }

    showMessage({ status: "success", title: "Terms of Payment Saved" });
    await refreshLead(modal);
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
    modal.querySelector("#asmListWrap").classList.toggle("hidden", services.length === 0);
    modal.querySelector("#asmList").innerHTML = services
      .map(
        (s) => `
      <tr>
          <td class="px-3 py-2">
              <span class="font-medium text-zinc-800 dark:text-zinc-100">${esc(s.special_charge?.name || s.ancillary_type || EMPTY)}</span>
              ${s.ancillary_remarks ? `<div class="text-xs text-zinc-400">${esc(s.ancillary_remarks)}</div>` : ""}
          </td>
          <td class="px-3 py-2">${esc(s.cargo_yard?.name || EMPTY)}</td>
          <td class="px-3 py-2">${esc(s.ancillary_unit || EMPTY)}</td>
          <td class="px-3 py-2 text-right">${peso(Number(s.base_value) || 0)}</td>
          <td class="px-3 py-2 text-right">${esc(s.quantity ?? EMPTY)}</td>
          <td class="px-3 py-2 text-right font-medium">${peso((Number(s.base_value) || 0) * (Number(s.quantity) || 0))}</td>
          <td class="px-3 py-2 text-right whitespace-nowrap">
              <button type="button" class="asm-edit text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 text-xs font-medium" data-id="${s.id}">Edit</button>
              <button type="button" class="asm-remove text-red-500 text-xs font-medium ml-2" data-id="${s.id}">✕</button>
          </td>
      </tr>`
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
    modal.querySelector("#asmQuantity").value = "1";
    modal.querySelector("#asmRemarks").value = "";
    updateAncillaryPreview();
  }

  // Live Base Value + Subtotal preview, driven by the picked Special
  // Charge (specialChargesById) and the typed Quantity - recomputed on
  // every Type change and every Quantity keystroke, nothing persisted here.
  function updateAncillaryPreview() {
    const modal = document.getElementById("AncillaryServicesModal");
    const charge = specialChargesById[modal.querySelector("#asmAncillaryType").value];
    const unit = modal.querySelector("#asmUnit").value;
    const rate = (charge?.rates ?? []).find((r) => r.unit === unit);
    const baseValue = Number(rate?.base_value) || 0;
    const quantity = Number(window.parseCurrencyValue(modal.querySelector("#asmQuantity").value)) || 0;
    const priced = Boolean(charge && rate);
    modal.querySelector("#asmBaseValuePreview").textContent = priced ? peso(baseValue) : "—";
    modal.querySelector("#asmSubtotalPreview").textContent = priced ? peso(baseValue * quantity) : "—";
  }

  async function addAncillary(button) {
    const modal = document.getElementById("AncillaryServicesModal");
    const payload = {
      ancillaryable_type: ancillaryContext.parentType,
      ancillaryable_id: ancillaryContext.parentId,
      special_charge_id: modal.querySelector("#asmAncillaryType").value || null,
      cargo_yard_id: modal.querySelector("#asmCargoYard").value || null,
      ancillary_unit: modal.querySelector("#asmUnit").value || null,
      ancillary_remarks: modal.querySelector("#asmRemarks").value.trim() || null,
      quantity: window.parseCurrencyValue(modal.querySelector("#asmQuantity").value) || 0,
    };
    if (!payload.special_charge_id) {
      showMessage({ status: "error", title: "Select an Ancillary Type first." });
      return;
    }
    if (!payload.ancillary_unit) {
      showMessage({ status: "error", title: "Pick a unit", message: "Choose an ancillary unit so the charge can be priced." });
      return;
    }
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
    modal.querySelector("#asmAncillaryType").value = String(row.special_charge_id ?? "");
    modal.querySelector("#asmCargoYard").value = String(row.cargo_yard_id ?? "");
    modal.querySelector("#asmUnit").value = row.ancillary_unit ?? "";
    modal.querySelector("#asmQuantity").value = row.quantity ?? "1";
    modal.querySelector("#asmRemarks").value = row.ancillary_remarks ?? "";
    updateAncillaryPreview();
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
              <button type="button" class="tlcm-edit text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 text-xs font-medium" data-id="${t.id}">Edit</button>
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
      quantity: parseCurrencyValue(modal.querySelector("#tlcmQuantity").value) || null,
      units: modal.querySelector("#tlcmUnits").value || null,
      revenue_ton: parseCurrencyValue(modal.querySelector("#tlcmRevenueTon").value) || null,
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
              <button type="button" class="ccim-edit text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 text-xs font-medium" data-id="${c.id}">Edit</button>
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
                  <button type="button" class="cpm-edit text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 text-xs font-medium" data-id="${p.id}">Edit</button>
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
      port_charge_amount: parseCurrencyValue(modal.querySelector("#cpmPortChargeAmount").value) || null,
      cargoes_for_loading: parseCurrencyValue(modal.querySelector("#cpmCargoesLoading").value) || null,
      cargo_measurement_for_loading: modal.querySelector("#cpmMeasurementLoading").value.trim() || null,
      revenue_ton_unit_loading: modal.querySelector("#cpmRtUnitLoading").value || null,
      cargoes_for_unloading: parseCurrencyValue(modal.querySelector("#cpmCargoesUnloading").value) || null,
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

  // ------------------------------------------------------------------
  // PRODUCT SPECIFICATION MODAL - opened from a Build Rate Matrix row.
  // Summary grid is real data (the actual saved product row); the fee
  // breakdown table below is sample/placeholder only (see file header
  // comment on rpmMatrixConfirmedKeys) - Save just marks the row reviewed
  // for this wizard session, it doesn't persist any pricing.
  // ------------------------------------------------------------------
  function specRow(label, value) {
    return `<div><span class="text-zinc-400 dark:text-zinc-500">${esc(label)}:</span> <span class="font-medium text-zinc-800 dark:text-zinc-100">${value || value === 0 ? esc(value) : EMPTY}</span></div>`;
  }

  function buildProductSpecSummaryRows(row) {
    const itemNoRow = specRow("Item No.", row.__itemNo);
    switch (row.__type) {
      case "container":
        return [
          itemNoRow,
          specRow("Container Type", LINE_ITEM_TYPE_META[row.container_type]?.label ?? row.container_type),
          specRow("Size", row.container_size?.size),
          specRow("Service Type", row.service_type),
          specRow("Origin", row.origin_location?.location_mnemonic),
          specRow("Port of Origin", row.origin_port?.name),
          specRow("Destination", row.destination_location?.location_mnemonic),
          specRow("Port of Destination", row.destination_port?.name),
          specRow("Dispatch Mode (Origin)", row.dispatch_mode_origin),
          specRow("Dispatch Mode (Destination)", row.dispatch_mode_destination),
          specRow("Cargo Type", row.cargo_type),
          specRow("Cargo Description", row.cargo_description),
        ].join("");
      case "rolling_cargo":
      case "loose_cargo":
        return [
          itemNoRow,
          specRow("Cargo Type", row.cargo_type),
          specRow("Cargo Details", row.cargo_details),
          specRow("Quantity", row.cargo_quantity),
          specRow("Units", row.cargo_units),
          specRow("Revenue Ton (RT)", row.revenue_ton),
          specRow("RT Unit", row.revenue_ton_unit),
          specRow("Cargo Measurement", row.cargo_measurement),
          specRow("Service Type", row.service_type),
          specRow("Origin", row.origin_location?.location_mnemonic),
          specRow("Port of Origin", row.origin_port?.name),
          specRow("Destination", row.destination_location?.location_mnemonic),
          specRow("Port of Destination", row.destination_port?.name),
        ].join("");
      case "trucking":
        return [
          itemNoRow,
          specRow("Trucking Cargo Type", row.trucking_cargo_type),
          specRow("Dispatch Mode", row.dispatch_mode),
          specRow("Origin", row.origin_location?.location_mnemonic),
          specRow("Destination", row.destination_location?.location_mnemonic),
          specRow("Cargo Type", row.cargo_type),
          specRow("Cargo Description", row.cargo_description),
        ].join("");
      case "charter":
        return [
          itemNoRow,
          specRow("Vessel Name", row.vessel_name),
          specRow("Vessel Dead Weight", row.vessel_dead_weight),
          specRow("Loading Date", row.loading_date),
          specRow("Charter Start Date", row.charter_start_date),
          specRow("Charter End Date", row.charter_end_date),
          specRow("Declared Value", row.declared_value),
          specRow("Weight", row.weight != null ? `${row.weight} ${row.weight_unit ?? ""}`.trim() : null),
        ].join("");
      default:
        return "";
    }
  }

  // Sample/placeholder only - not derived from any real rate data. Adjustment
  // can be negative (discount) or positive (increase); Total Rate = Base + Adjustment.
  function sampleFeeBreakdown() {
    return [
      { detail: "Base Freight Charge", base: 15000, adjustment: 0 },
      { detail: "Fuel Surcharge", base: 1200, adjustment: 150 },
      { detail: "Documentation Fee", base: 500, adjustment: 0 },
      { detail: "Handling Fee", base: 800, adjustment: -100 },
    ];
  }

  function peso(n) {
    const sign = n < 0 ? "-" : "";
    return `${sign}₱${Math.abs(n).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
  }

  // Rate Adjustment is a live-editable input (discount = negative, increase =
  // positive) - Base Rate stays display-only (the "standard rate"); editing
  // Adjustment recomputes that row's Total Rate and the grand Total row
  // instantly via recomputePsmRow()/recomputePsmGrandTotal() below. Still
  // sample data end to end - nothing here is persisted (see Save handler).
  function feeBreakdownRowHtml(line) {
    const total = line.base + line.adjustment;
    return `
    <tr class="psm-fee-row" data-base="${line.base}">
        <td class="px-3 py-2">${esc(line.detail)}</td>
        <td class="px-3 py-2 text-right">${peso(line.base)}</td>
        <td class="px-3 py-2 text-right">
            <input type="text" inputmode="decimal" class="currency-input psm-adjustment-input w-28 text-right border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1 text-xs" value="${line.adjustment}">
        </td>
        <td class="px-3 py-2 text-right font-semibold text-zinc-800 dark:text-zinc-100 psm-row-total">${peso(total)}</td>
    </tr>`;
  }

  function feeBreakdownTotalRowHtml(lines) {
    const base = lines.reduce((sum, l) => sum + l.base, 0);
    const adjustment = lines.reduce((sum, l) => sum + l.adjustment, 0);
    return `
    <tr class="bg-zinc-50 dark:bg-zinc-800/50 font-semibold">
        <td class="px-3 py-2">Total</td>
        <td class="px-3 py-2 text-right" id="psmTotalBase">${peso(base)}</td>
        <td class="px-3 py-2 text-right" id="psmTotalAdjustment">${peso(adjustment)}</td>
        <td class="px-3 py-2 text-right" id="psmTotalTotal">${peso(base + adjustment)}</td>
    </tr>`;
  }

  function recomputePsmRow(input) {
    const tr = input.closest("tr");
    const base = Number(tr.dataset.base) || 0;
    const adjustment = Number(window.parseCurrencyValue(input.value)) || 0;
    tr.querySelector(".psm-row-total").textContent = peso(base + adjustment);
    recomputePsmGrandTotal();
  }

  function recomputePsmGrandTotal() {
    const modal = document.getElementById("ProductSpecificationModal");
    let base = 0;
    let adjustment = 0;
    modal.querySelectorAll("#psmRateBreakdownBody tr.psm-fee-row").forEach((tr) => {
      base += Number(tr.dataset.base) || 0;
      adjustment += Number(window.parseCurrencyValue(tr.querySelector(".psm-adjustment-input").value)) || 0;
    });
    modal.querySelector("#psmTotalBase").textContent = peso(base);
    modal.querySelector("#psmTotalAdjustment").textContent = peso(adjustment);
    modal.querySelector("#psmTotalTotal").textContent = peso(base + adjustment);
  }

  // Rehydrates the fee breakdown from this product's already-saved
  // product_rates row (if one exists) instead of sampleFeeBreakdown(), so
  // re-opening/re-saving doesn't clobber a reviewer's prior adjustments.
  function savedFeeBreakdown(pr, row) {
    const saved = (pr?.product_rates ?? []).find((r) => r.product_type === row.__type && Number(r.product_id) === Number(row.id));
    return saved?.fee_breakdown ?? null;
  }

  // Ancillary Services section (right column, below Rate Breakdown) - each
  // line is a REAL ancillary service row already added from the Products
  // tab (unlike the Rate Breakdown's sample lines, these have a real id and
  // no "add new" affordance here). Quantity/Base Price/Adjustment are all
  // editable; Sub Total = Qty*Base + Adjustment, same shape as Rate
  // Breakdown's Base/Adjustment/Total, recomputed live and persisted
  // together with the rate on Save (see saveProductSpecification()).
  function ancillaryBreakdownRowHtml(service) {
    const baseValue = Number(service.base_value) || 0;
    const quantity = Number(service.quantity) || 0;
    const adjustment = Number(service.adjustment) || 0;
    const subtotal = quantity * baseValue + adjustment;
    return `
    <tr class="psm-ancillary-row" data-id="${service.id}">
        <td class="px-3 py-2">${esc(service.special_charge?.name || service.ancillary_type || EMPTY)}</td>
        <td class="px-3 py-2 text-right">
            <input type="text" inputmode="decimal" class="currency-input psm-ancillary-qty w-20 text-right border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1 text-xs" value="${quantity}">
        </td>
        <td class="px-3 py-2 text-right">
            <input type="text" inputmode="decimal" class="currency-input psm-ancillary-base w-28 text-right border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1 text-xs" value="${baseValue}">
        </td>
        <td class="px-3 py-2 text-right">
            <input type="text" inputmode="decimal" class="currency-input psm-ancillary-adjustment w-28 text-right border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1 text-xs" value="${adjustment}">
        </td>
        <td class="px-3 py-2 text-right font-semibold text-zinc-800 dark:text-zinc-100 psm-ancillary-subtotal">${peso(subtotal)}</td>
    </tr>`;
  }

  function recomputeAncillaryRow(tr) {
    const qty = Number(window.parseCurrencyValue(tr.querySelector(".psm-ancillary-qty").value)) || 0;
    const base = Number(window.parseCurrencyValue(tr.querySelector(".psm-ancillary-base").value)) || 0;
    const adjustment = Number(window.parseCurrencyValue(tr.querySelector(".psm-ancillary-adjustment").value)) || 0;
    tr.querySelector(".psm-ancillary-subtotal").textContent = peso(qty * base + adjustment);
  }

  function openProductSpecificationModal(type, id) {
    const pr = getMyProposalRequest(rpmLead);
    const row = allProductRowsFlat(pr).find((r) => r.__type === type && r.id === id);
    if (!row) return;

    rpmMatrixCurrentKey = matrixRowKey(row);
    rpmMatrixCurrentRow = row;

    const modal = document.getElementById("ProductSpecificationModal");
    const meta = LINE_ITEM_TYPE_META[row.__typeCode] ?? { label: row.__typeCode };
    modal.querySelector("#psmSubtitle").textContent = `${meta.label} · ${matrixRouteLabel(row).replace(/<[^>]+>/g, "")}`;
    modal.querySelector("#psmSummaryGrid").innerHTML = buildProductSpecSummaryRows(row);

    const lines = savedFeeBreakdown(pr, row) ?? sampleFeeBreakdown();
    modal.querySelector("#psmRateBreakdownBody").innerHTML = lines.map(feeBreakdownRowHtml).join("") + feeBreakdownTotalRowHtml(lines);
    modal.querySelectorAll(".psm-adjustment-input").forEach((input) => {
      input.addEventListener("input", () => recomputePsmRow(input));
    });

    const ancServices = row.ancillary_services ?? [];
    modal.querySelector("#psmAncillaryEmpty").classList.toggle("hidden", ancServices.length > 0);
    modal.querySelector("#psmAncillaryWrap").classList.toggle("hidden", ancServices.length === 0);
    modal.querySelector("#psmAncillaryBody").innerHTML = ancServices.map(ancillaryBreakdownRowHtml).join("");
    modal.querySelectorAll(".psm-ancillary-row input").forEach((input) => {
      input.addEventListener("input", () => recomputeAncillaryRow(input.closest("tr")));
    });

    window.initModal({ modalId: "ProductSpecificationModal" });
  }
  window.openProductSpecificationModal = openProductSpecificationModal;

  async function cancelProductSpecification() {
    const confirmed = await customConfirm("Discard changes to this product's rate review? Nothing entered here has been saved yet.");
    if (!confirmed) return;
    rpmMatrixCurrentKey = null;
    rpmMatrixCurrentRow = null;
    window.closeModal("ProductSpecificationModal");
  }

  async function saveProductSpecification(button) {
    const row = rpmMatrixCurrentRow;
    const lead = rpmLead;
    if (!row || !lead) return;

    const modal = document.getElementById("ProductSpecificationModal");
    const feeBreakdown = [];
    modal.querySelectorAll("#psmRateBreakdownBody tr.psm-fee-row").forEach((tr) => {
      const detail = tr.querySelector("td")?.textContent?.trim() ?? "";
      const base = Number(tr.dataset.base) || 0;
      const adjustment = Number(window.parseCurrencyValue(tr.querySelector(".psm-adjustment-input").value)) || 0;
      feeBreakdown.push({ detail, base, adjustment });
    });
    const base_rate = feeBreakdown.reduce((sum, l) => sum + l.base, 0);
    const adjustment = feeBreakdown.reduce((sum, l) => sum + l.adjustment, 0);
    const total_rate = base_rate + adjustment;

    const ancillaryAdjustments = [];
    modal.querySelectorAll("#psmAncillaryBody tr.psm-ancillary-row").forEach((tr) => {
      ancillaryAdjustments.push({
        id: Number(tr.dataset.id),
        quantity: Number(window.parseCurrencyValue(tr.querySelector(".psm-ancillary-qty").value)) || 0,
        base_value: Number(window.parseCurrencyValue(tr.querySelector(".psm-ancillary-base").value)) || 0,
        adjustment: Number(window.parseCurrencyValue(tr.querySelector(".psm-ancillary-adjustment").value)) || 0,
      });
    });

    const payload = {
      product_type: row.__type,
      product_id: row.id,
      item_no: row.__itemNo,
      base_rate,
      adjustment,
      total_rate,
      fee_breakdown: feeBreakdown,
      ancillary_adjustments: ancillaryAdjustments,
    };

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${rpmProposalRequestId}/productRates`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Saving Rate", message: response.message ?? "" });
      return;
    }

    if (rpmMatrixCurrentKey) rpmMatrixConfirmedKeys.add(rpmMatrixCurrentKey);
    rpmMatrixCurrentKey = null;
    rpmMatrixCurrentRow = null;
    window.closeModal("ProductSpecificationModal");
    showMessage({ status: "success", title: "Rate Matrix Saved" });
    const rpModal = document.getElementById("RequestProposalModal");
    await refreshLead(rpModal);
  }

  function initSubItemModals() {
    const asm = document.getElementById("AncillaryServicesModal");
    if (asm) {
      asm.querySelector("#asmAddBtn").addEventListener("click", function () {
        addAncillary(this);
      });
      asm.querySelector("#asmCloseBtn").addEventListener("click", () => window.closeModal("AncillaryServicesModal"));
      asm.querySelector("#asmAncillaryType").addEventListener("change", updateAncillaryPreview);
      asm.querySelector("#asmUnit").addEventListener("change", updateAncillaryPreview);
      asm.querySelector("#asmQuantity").addEventListener("input", updateAncillaryPreview);
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

    const psm = document.getElementById("ProductSpecificationModal");
    if (psm) {
      psm.querySelector("#psmCancelBtn").addEventListener("click", cancelProductSpecification);
      psm.querySelector("#psmSaveBtn").addEventListener("click", function () {
        saveProductSpecification(this);
      });
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
    rpmMatrixConfirmedKeys = new Set();

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
    modal.querySelector("#rpmSignatoryContact").addEventListener("change", () => {
      signatoryContactChange(modal);
    });
    modal.querySelector("#rpmAddOdBtn").addEventListener("click", function () {
      openRpmAddOdModal();
    });
    modal.querySelector("#rpmSubmitBtn").addEventListener("click", function () {
      submitProposalRequest(this);
    });

    if (document.getElementById("RpmAddOdModal")) {
      document.getElementById("rpmAodmCancelBtn").addEventListener("click", () => window.closeModal("RpmAddOdModal"));
      document.getElementById("rpmAodmSaveBtn").addEventListener("click", function () {
        saveRpmAddOd(this);
      });
    }

    modal.querySelector("#rpmAddContainerBtn").addEventListener("click", openRpmAddContainerModal);
    modal.querySelector("#rpmAddRollingCargoBtn").addEventListener("click", openRpmAddRollingCargoModal);
    modal.querySelector("#rpmAddLooseCargoBtn").addEventListener("click", openRpmAddLooseCargoModal);
    modal.querySelector("#rpmAddTruckingBtn").addEventListener("click", openRpmAddTruckingModal);
    modal.querySelector("#rpmAddCharterBtn").addEventListener("click", openRpmAddCharterModal);

    if (document.getElementById("RpmAddContainerModal")) {
      document.getElementById("rpmAcmCancelBtn").addEventListener("click", () => window.closeModal("RpmAddContainerModal"));
      document.getElementById("rpmAcmSaveBtn").addEventListener("click", function () {
        saveRpmContainer(this);
      });
      document.getElementById("rpmPcContainerType").addEventListener("change", () => {
        applyPcContainerTypeVisibility();
        populatePcContainerSizeOptions();
      });
      document.getElementById("rpmPcDeliveryType").addEventListener("change", () => {
        applyDispatchModeGating(document.getElementById("RpmAddContainerModal"), "rpmPcDeliveryType", "rpmPcDispatchOrigin", "rpmPcDispatchDestination");
      });
    }
    if (document.getElementById("RpmAddRollingCargoModal")) {
      document.getElementById("rpmArcmCancelBtn").addEventListener("click", () => window.closeModal("RpmAddRollingCargoModal"));
      document.getElementById("rpmArcmSaveBtn").addEventListener("click", function () {
        saveRpmRollingCargo(this);
      });
    }
    if (document.getElementById("RpmAddLooseCargoModal")) {
      document.getElementById("rpmAlcmCancelBtn").addEventListener("click", () => window.closeModal("RpmAddLooseCargoModal"));
      document.getElementById("rpmAlcmSaveBtn").addEventListener("click", function () {
        saveRpmLooseCargo(this);
      });
    }
    if (document.getElementById("RpmAddTruckingModal")) {
      document.getElementById("rpmAtmCancelBtn").addEventListener("click", () => window.closeModal("RpmAddTruckingModal"));
      document.getElementById("rpmAtmSaveBtn").addEventListener("click", function () {
        saveRpmTrucking(this);
      });
    }
    if (document.getElementById("RpmAddCharterModal")) {
      document.getElementById("rpmAchmCancelBtn").addEventListener("click", () => window.closeModal("RpmAddCharterModal"));
      document.getElementById("rpmAchmSaveBtn").addEventListener("click", function () {
        saveRpmCharter(this);
      });
    }

    modal.querySelector("#rpmSaveTermsConditionsBtn")?.addEventListener("click", function () {
      saveTermsAndConditions(modal, this);
    });

    initSubItemModals();
  }
  window.initRequestProposalModal = initRequestProposalModal;
})();
