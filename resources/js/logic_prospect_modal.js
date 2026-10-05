// 4-tab "Prospect" modal logic (resources/views/components/prospect-modal.blade.php).
// Pass 1: only the Prospect Identity tab (tab 1) is fully wired - tabs 2-4
// are static placeholders (see the component). Replaces the old full-page
// CRM lead form (crmLeadForm.blade.php / logic embedded in that view).
//
// Single-bundle architecture (see CLAUDE.md): this whole file only ever
// executes once, at initial script load, so it can't rely on the modal's
// markup existing yet. Instead it exposes window.initProspectModal() -
// crm.blade.php's own inline <script> (which DOES re-execute on every SPA
// fragment load, see navmenu.js's loadPage()) calls that once per visit,
// the same pattern logic_crm.js's window.initCrmLogic() uses.
(function () {
  // Guards the one truly global (document-level) listener this file adds
  // (closing an open source popover on outside click) - everything else is
  // bound to elements inside #ProspectModal, which is recreated fresh on
  // every SPA fragment load, so per-element listeners never stack.
  let docClickBound = false;

  // Fixed Service Type list (replaces the old Delivery Type LOV-backed
  // select on the Requirements container form - the value IS the label
  // string, POSTed as `service_type`, not an id).
  const SERVICE_TYPES = ["Door - Door", "Door - Pier", "Pier - Door", "Pier - Pier"];
  const serviceTypeOptionsHtml =
    '<option value="">Select Service Type</option>' +
    SERVICE_TYPES.map((t) => `<option value="${t}">${t}</option>`).join("");

  const PSGC_API = "https://psgc.cloud/api"; // already used (unauthenticated) by crmLeadForm.blade.php's address cascade

  const COUNTRIES = [
    "Philippines", "United States", "Singapore", "Hong Kong", "China", "Japan",
    "South Korea", "Malaysia", "Indonesia", "Thailand", "Vietnam", "Taiwan",
    "Australia", "United Kingdom", "Canada", "United Arab Emirates", "Other",
  ];
  const countryOptionsHtml = COUNTRIES
    .map((c) => `<option value="${c}" ${c === "Philippines" ? "selected" : ""}>${c}</option>`)
    .join("");

  // Popover element id, keyed by the Prospect Source value that reveals it.
  const SOURCE_POPOVER_MAP = {
    Referral: "pmReferralPopover",
    "Social Media": "pmSocialMediaPopover",
    Others: "pmOthersPopover",
  };

  const ADDRESS_TYPE_PLACEHOLDER_HTML = '<option value="">Select Address Type</option>';
  let addressTypeOptionsHtml = ADDRESS_TYPE_PLACEHOLDER_HTML;

  // Populated by fillTypeOfBusiness() - lets deriveClientTypeFromBusinessType()
  // look up the lov_is_individual flag for whichever Business Type is picked.
  let typeOfBusinessOptionsCache = [];

  // Tab 2 (Contact Information) small hardcoded lists.
  const CHANNEL_TYPES = [
    { value: "mobile", label: "Mobile" },
    { value: "landline", label: "Landline" },
    { value: "email", label: "Email" },
  ];
  const CONTACT_TYPES = [
    { value: "personal", label: "Personal" },
    { value: "business", label: "Business" },
  ];

  // Tab 3 (Requirements) - three "products": Container (CONTAINER_TYPES,
  // TYPE_FIELD_VISIBILITY), Trucking, and Charter, each with its own form
  // + table (hidden until its first row is added). TYPE_FIELD_VISIBILITY
  // must stay in sync with
  // ProspectController::proposalRequestContainerRowErrors()'s $typeFlags -
  // CV/FR/RF are sized types (RF also needs temperature), RC/LC have no
  // fixed size and are priced by Revenue Ton instead.
  const CONTAINER_TYPES = [
    { value: "CV", label: "Container Van (CV)" },
    { value: "FR", label: "Flatrack (FR)" },
    { value: "RF", label: "Reefer Van (RF)" },
    { value: "LC", label: "Break Bulk Cargo (BB)" },
    { value: "RC", label: "Rolling Cargo (RC)" },
  ];
  const WEIGHT_UNIT_LABEL = { kg: "kg", mt: "MT" };
  const DISPATCH_MODE_LABEL = { single: "Single", tandem: "Tandem" };
  // #pmRequirementProduct option value -> its pane id (see bindProductSelector()).
  const PRODUCT_PANES = {
    freight: "pmProductPaneFreight",
    trucking: "pmProductPaneTrucking",
    charter: "pmProductPaneCharter",
  };
  const TYPE_FIELD_VISIBILITY = {
    CV: { convanSize: true, temperature: false, revenueTon: false },
    FR: { convanSize: true, temperature: false, revenueTon: false },
    RF: { convanSize: true, temperature: true, revenueTon: false },
    LC: { convanSize: false, temperature: false, revenueTon: true },
    RC: { convanSize: false, temperature: false, revenueTon: true },
  };
  // Same dot-color mapping used to live as logic_crm.js's CONTAINER_TYPE_ACCENT
  // (removed with the dead LeadInfoModal - see VISUALS.md's container/cargo
  // type mapping); kept here since this file doesn't share JS scope with that one.
  const CONTAINER_TYPE_DOT_COLOR = {
    CV: "bg-orange-500",
    FR: "bg-amber-500",
    RF: "bg-cyan-500",
    LC: "bg-purple-500",
    RC: "bg-blue-500",
  };

  // ------------------------------------------------------------------
  // STATE (reset on every openProspectModal() call)
  // ------------------------------------------------------------------
  let currentProspectUuid = null;
  // The open prospect's own locations (prospect_locations rows, from the
  // Identity tab) - source list for Tab 2's per-contact address checklist.
  // Reset to [] on every openProspectModal() call, populated from the GET
  // .../prospects/{uuid} response and refreshed after every Identity save
  // (see saveIdentity()) so newly added/removed locations stay in sync.
  let currentProspectLocations = [];
  // This prospect's single Proposal Request (Tab 3 header code + the
  // container table's data source) - {id, code, containers} or null until
  // the first booking requirement is actually saved. The backend mints it
  // lazily inside storeProposalRequestContainer(), not on tab view, so
  // opening this tab and closing without adding anything creates nothing.

  // Cached LOV/master-data option-html strings + lookup tables for Tabs 2/3
  // - fetched once per SPA page load (this file's module scope persists for
  // the whole session, see header comment) and reassigned (not appended)
  // each time, same pattern as addressTypeOptionsHtml above.
  let titleOptionsHtml = "";
  let cargoTypeOptionsHtml = '<option value="">Select Cargo Type</option>';
  let deliveryTypesOptionsHtml = '<option value="">Select Service Type</option>';
  let truckingCargoTypeOptionsHtml = '<option value="">Select Type</option>';
  // Locations (Container/Trucking origin+destination) and Ports (Charter
  // port calls) - plain flat lists, no location->port cascade (removed per
  // report; origin/destination are Location-level everywhere except
  // Charter, which is genuinely Port-level).
  let locationsOptionsHtml = "";
  let portsOptionsHtml = "";
  // Class/size lists are owned per-Container (see Container::syncCatalog())
  // and keyed here by Container.code, matching CONTAINER_TYPES' values.
  let containerCatalogByCode = {};
  // Guards loadContainerLookups() so it only ever runs once per SPA page
  // load, not once per Requirements-tab visit.
  let containerLookupsPromise = null;

  function fetchTimeout(ms) {
    const controller = new AbortController();
    const timer = setTimeout(() => controller.abort(), ms);
    return { signal: controller.signal, clear: () => clearTimeout(timer) };
  }

  // Best-effort PSGC call - short timeout, never throws past the caller's
  // try/catch. PSGC APIs don't expose postal codes and the endpoint can be
  // slow/unavailable, so every call site must degrade to manual entry
  // rather than block saving.
  // The PSGC API's own data has "ñ"/"Ñ" double-encoded as the two-character
  // mojibake "Ã±"/"Ã‘" (UTF-8 bytes for ñ misread as Latin-1, then
  // re-encoded) - confirmed systematic across Las Piñas, Parañaque, Muñoz,
  // etc, not a one-off, so it's safe to restore the real character rather
  // than just stripping the tilde down to a plain "n".
  function fixMojibake(str) {
    return typeof str === "string" ? str.replace(/Ã±/g, "ñ").replace(/Ã‘/g, "Ñ") : str;
  }

  async function fetchPsgc(url) {
    const t = fetchTimeout(6000);
    try {
      const res = await fetch(url, { signal: t.signal });
      if (!res.ok) throw new Error(`PSGC request failed: ${res.status}`);
      const data = await res.json();
      return Array.isArray(data)
        ? data.map((item) => (item && typeof item.name === "string" ? { ...item, name: fixMojibake(item.name) } : item))
        : data;
    } finally {
      t.clear();
    }
  }

  function resetSelect(select, placeholder) {
    select.innerHTML = "";
    const option = document.createElement("option");
    option.value = "";
    option.textContent = placeholder;
    select.appendChild(option);
    select.disabled = true;
  }

  function populateSelect(select, items, placeholder) {
    resetSelect(select, placeholder);
    items.forEach((item) => {
      const option = document.createElement("option");
      const name = (item.name || "").trim();
      option.value = name;
      option.textContent = name;
      option.dataset.code = item.code;
      // Only cities/municipalities carry a zip_code from the PSGC API - PH
      // postal codes are assigned per city/municipality, not per barangay.
      if (item.zip_code) option.dataset.zip = item.zip_code;
      select.appendChild(option);
    });
    select.disabled = false;
  }

  function refreshSearchable(el) {
    el?._searchableSelect?.refresh();
  }

  // Swaps a PSGC-driven <select> for a plain manually-editable text input,
  // preserving its data-field/id so the rest of the form (collection,
  // hydration) doesn't need to know which mode a given card ended up in.
  // Used when a PSGC call fails - the cascade degrades to free text rather
  // than leaving a dead, permanently-disabled select on the form.
  function convertToManualInput(select, placeholderLabel) {
    select._searchableSelect?.destroy();
    const input = document.createElement("input");
    input.type = "text";
    input.id = select.id;
    input.dataset.field = select.dataset.field;
    input.placeholder = placeholderLabel;
    input.className =
      "w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm";
    select.replaceWith(input);
    return input;
  }

  // ------------------------------------------------------------------
  // LOOKUPS (LOV-backed selects + relationship manager users)
  // ------------------------------------------------------------------
  async function fillTypeOfBusiness(modal) {
    const response = await apiCall({ mode: "GET", url: "/api/listofval/typeofbusiness" });
    const select = modal.querySelector(".pmTypeOfBusinessDropdown");
    if (!select || !Array.isArray(response)) return;
    typeOfBusinessOptionsCache = response;
    response.forEach((lov) => {
      select.insertAdjacentHTML("beforeend", `<option value="${lov.lov_name}">${lov.lov_name}</option>`);
    });
    refreshSearchable(select);
  }

  async function fillLeadSource(modal) {
    const response = await apiCall({ mode: "GET", url: "/api/listofval/leadsource" });
    const select = modal.querySelector(".pmSourceDropdown");
    if (!select || !Array.isArray(response)) return;
    response.forEach((lov) => {
      select.insertAdjacentHTML("beforeend", `<option value="${lov.lov_name}">${lov.lov_name}</option>`);
    });
  }

  async function fillAddressTypeOptions() {
    const response = await apiCall({ mode: "GET", url: "/api/listofval/addresstype" });
    if (!Array.isArray(response)) return;
    // Reassign (not +=) - this file's module scope survives across every
    // SPA visit to the CRM page, so appending here would duplicate every
    // option on each re-visit (initProspectModal() re-runs, this cache does not).
    addressTypeOptionsHtml =
      ADDRESS_TYPE_PLACEHOLDER_HTML +
      response.map((lov) => `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join("");
  }

  // Tab 2's Title select is rendered fresh per contact card (see
  // contactCardHtml()), so this only needs to cache the options-html string
  // - nothing to paint into the DOM here.
  async function fillContactTitleOptions() {
    const response = await apiCall({ mode: "GET", url: "/api/listofval/title" });
    if (!Array.isArray(response)) return;
    titleOptionsHtml = response.map((lov) => `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join("");
  }

  async function fillCargoTypeOptions() {
    const response = await apiCall({ mode: "GET", url: "/api/listofval/cargotype" });
    if (!Array.isArray(response)) return;
    cargoTypeOptionsHtml =
      '<option value="">Select Cargo Type</option>' +
      response.map((lov) => `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join("");
  }

  async function fillTruckingCargoTypeOptions() {
    const response = await apiCall({ mode: "GET", url: "/api/listofval/truckingcargotype" });
    if (!Array.isArray(response)) return;
    truckingCargoTypeOptionsHtml =
      '<option value="">Select Type</option>' +
      response.map((lov) => `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join("");
  }

  // Container catalog, Delivery Types (Door-Door/Door-Pier/etc - the same
  // app-wide settings the Booking module uses, labeled "Service Type"
  // here), Locations (Container/Trucking origin+destination) and Ports
  // (Charter port calls). Fetched once ever (see containerLookupsPromise)
  // since none of this master data changes mid-session.
  async function loadContainerLookups() {
    const [containersRes, deliveryTypesRes, locationsRes, portsRes] = await Promise.all([
      apiCall({ mode: "GET", url: "/api/containers?per_page=200" }),
      apiCall({ mode: "GET", url: "/api/deliveryTypes?per_page=200" }),
      apiCall({ mode: "GET", url: "/api/locations?per_page=200" }),
      apiCall({ mode: "GET", url: "/api/ports?per_page=200" }),
      fillCargoTypeOptions(),
      fillTruckingCargoTypeOptions(),
    ]);

    if (containersRes.success) {
      containerCatalogByCode = {};
      containersRes.data.data.forEach((container) => {
        containerCatalogByCode[container.code] = {
          sizes: container.sizes ?? [],
          classes: container.classes ?? [],
        };
      });
    }
    if (deliveryTypesRes.success) {
      deliveryTypesOptionsHtml =
        '<option value="">Select Service Type</option>' +
        deliveryTypesRes.data.data.map((d) => `<option value="${d.delivery_type_id}">${d.name}</option>`).join("");
    }
    if (locationsRes.success) {
      locationsOptionsHtml = locationsRes.data.data
        .map((l) => `<option value="${l.location_id}">${l.name}</option>`)
        .join("");
    }
    if (portsRes.success) {
      portsOptionsHtml = portsRes.data.data
        .map((p) => `<option value="${p.port_id}">${p.location?.name ?? "-"} - ${p.name}</option>`)
        .join("");
    }
  }

  function ensureContainerLookupsLoaded() {
    if (!containerLookupsPromise) containerLookupsPromise = loadContainerLookups();
    return containerLookupsPromise;
  }

  // ------------------------------------------------------------------
  // SOURCE POPOVERS (encode/decode ported from crmLeadForm.blade.php's
  // SOURCE_FOLLOWUP_INPUTS - only the reveal mechanism changed, from
  // inline hidden-class toggling to an anchored popover, and Social Media
  // moved from free text to a 4-choice picker).
  // ------------------------------------------------------------------
  function closeAllSourcePopovers(modal) {
    Object.values(SOURCE_POPOVER_MAP).forEach((id) => {
      modal.querySelector(`#${id}`)?.classList.add("hidden");
    });
  }

  function updateSourceDetailSummary(modal) {
    const select = modal.querySelector("#pmSourceSelect");
    const summary = modal.querySelector("#pmSourceDetailSummary");
    const value = select.value;
    let text = "";

    if (value === "Referral") {
      const name = modal.querySelector("#pmSourceReferralName").value.trim();
      text = name ? `Referred by ${name}` : "";
    } else if (value === "Social Media") {
      const platform = modal.querySelector("#pmSourceSocialPlatform").value;
      text = platform ? `Platform: ${platform}` : "";
    } else if (value === "Others") {
      const other = modal.querySelector("#pmSourceOthersText").value.trim();
      text = other || "";
    }

    summary.textContent = text;
    summary.classList.toggle("hidden", !text);
  }

  function encodeSource(modal) {
    const value = modal.querySelector("#pmSourceSelect").value;
    if (value === "Others") {
      return modal.querySelector("#pmSourceOthersText").value || "";
    }
    if (value === "Referral") {
      const name = modal.querySelector("#pmSourceReferralName").value;
      return name ? `Referral - ${name}` : "Referral";
    }
    if (value === "Social Media") {
      const platform = modal.querySelector("#pmSourceSocialPlatform").value;
      return platform ? `Social Media - ${platform}` : "Social Media";
    }
    return value || "";
  }

  // Reconstructs the source select + popover detail field(s) from the
  // saved "Category - detail" string, exactly mirroring crmLeadForm.blade.php's
  // hydrateExisting() decode logic.
  function decodeSourceIntoForm(modal, source) {
    const select = modal.querySelector("#pmSourceSelect");
    const knownSource = Array.from(select.options).some((o) => o.value === source);
    const referralMatch = source?.match(/^Referral - (.*)$/);
    const socialMatch = source?.match(/^Social Media - (.*)$/);

    if (source && knownSource) {
      select.value = source;
    } else if (referralMatch) {
      select.value = "Referral";
    } else if (socialMatch) {
      select.value = "Social Media";
    } else if (source) {
      select.value = "Others";
    }

    if (referralMatch) {
      modal.querySelector("#pmSourceReferralName").value = referralMatch[1];
    } else if (socialMatch) {
      modal.querySelector("#pmSourceSocialPlatform").value = socialMatch[1];
    } else if (source && !knownSource) {
      modal.querySelector("#pmSourceOthersText").value = source;
    }

    updateSourceDetailSummary(modal);
  }

  // ------------------------------------------------------------------
  // CLIENT TYPE - derived from the selected Business Type's lov_is_individual
  // flag (see BusinessTypeSeeder), no longer a manual toggle.
  // ------------------------------------------------------------------
  function deriveClientTypeFromBusinessType(modal) {
    const value = modal.querySelector("#pmTypeOfBusiness")?.value || "";
    const match = typeOfBusinessOptionsCache.find((lov) => lov.lov_name === value);
    const clientType = match?.lov_is_individual ? "individual" : "corporate";
    const hidden = modal.querySelector("#pmClientType");
    if (hidden) hidden.value = clientType;
    return clientType;
  }

  function bindClientTypeToggle(modal) {
    modal.querySelector("#pmTypeOfBusiness")?.addEventListener("change", () => deriveClientTypeFromBusinessType(modal));
  }

  function bindSourcePopovers(modal) {
    const select = modal.querySelector("#pmSourceSelect");

    select.addEventListener("change", function () {
      closeAllSourcePopovers(modal);
      const popoverId = SOURCE_POPOVER_MAP[this.value];
      if (popoverId) modal.querySelector(`#${popoverId}`)?.classList.remove("hidden");
      if (!SOURCE_POPOVER_MAP[this.value]) updateSourceDetailSummary(modal);
    });

    modal.querySelectorAll(".pm-popover-done").forEach((btn) => {
      btn.addEventListener("click", function () {
        modal.querySelector(`#${this.dataset.pmPopover}`)?.classList.add("hidden");
        updateSourceDetailSummary(modal);
      });
    });

    modal.querySelectorAll(".pm-social-platform-btn").forEach((btn) => {
      btn.addEventListener("click", function () {
        modal.querySelector("#pmSourceSocialPlatform").value = this.dataset.platform;
        modal.querySelectorAll(".pm-social-platform-btn").forEach((b) =>
          b.classList.toggle("border-orange-500", b === this));
        modal.querySelector("#pmSocialMediaPopover")?.classList.add("hidden");
        updateSourceDetailSummary(modal);
      });
    });

    if (!docClickBound) {
      docClickBound = true;
      document.addEventListener("click", (e) => {
        const openModal = document.getElementById("ProspectModal");
        if (!openModal || openModal.classList.contains("hidden")) return;
        if (e.target.closest("#pmSourceSelect") || e.target.closest(".modaldropdown")) return;
        closeAllSourcePopovers(openModal);
      });
    }
  }

  // ------------------------------------------------------------------
  // LOCATIONS (repeatable cards, PH PSGC cascade with graceful fallback)
  // ------------------------------------------------------------------
  function locationCardHtml(index) {
    return `
    <div class="pm-location-card border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-3" data-index="${index}">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <select data-field="address_type" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm font-semibold">
                    ${addressTypeOptionsHtml}
                </select>
                <label class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400 whitespace-nowrap">
                    <input type="radio" name="pm_address_primary_radio" class="pm-primary-radio">
                    Primary
                </label>
            </div>
            <button type="button" class="pm-remove-location text-red-500 text-xs font-medium">✕ Remove</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">No.</label>
                <input type="text" data-field="address_no" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Building</label>
                <input type="text" data-field="address_building" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Street</label>
                <input type="text" data-field="address_street" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Country</label>
                <select data-field="address_country" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    ${countryOptionsHtml}
                </select>
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label class="text-[11px] text-zinc-400 uppercase">Province <span class="req-asterisk">*</span></label>
                <select data-field="address_province" id="pmLocationProvince${index}" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Province</option>
                </select>
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label class="text-[11px] text-zinc-400 uppercase">Town/City <span class="req-asterisk">*</span></label>
                <select data-field="address_town_city" id="pmLocationCity${index}" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm" disabled>
                    <option value="">Select Town/City</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Barangay</label>
                <select data-field="address_barangay" id="pmLocationBarangay${index}" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm" disabled>
                    <option value="">Select Barangay</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Postal Code</label>
                <input type="text" data-field="address_postal_code" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
        </div>
    </div>`;
  }

  // Metro Manila is the National Capital Region (NCR) in the Philippine
  // Standard Geographic Code, not a province - it has no entry under
  // /provinces at all, and its cities sit directly under this region code
  // instead. Injected into the province list below as a synthetic entry so
  // it's selectable the same way; loadCities() special-cases this exact
  // code to query /regions instead of /provinces for its city list. NCR is
  // the only PSGC region without a province level, so no other region
  // needs this treatment.
  const NCR_REGION_CODE = "1300000000";

  // Wires the province -> city -> barangay cascade for one card, degrading
  // to plain manually-editable text inputs (in place of the affected
  // select(s) and everything below it in the hierarchy) the moment any
  // PSGC call fails - never blocks the rest of the form.
  async function initLocationPsgcCascade(card) {
    let provinceSelect = card.querySelector('[data-field="address_province"]');
    let citySelect = card.querySelector('[data-field="address_town_city"]');
    let barangaySelect = card.querySelector('[data-field="address_barangay"]');
    const postalInput = card.querySelector('[data-field="address_postal_code"]');

    // Populated by loadCities() whenever a "city" list contains PSGC
    // type:"SubMun" entries (currently only the City of Manila's ~15
    // internal districts - Tondo, Binondo, Quiapo, etc - are structured
    // this way) - excluded from the City dropdown itself (they aren't
    // real top-level cities/municipalities), but loadBarangays() below
    // needs them since that's genuinely where their barangays live,
    // instead of directly under the parent city's own code.
    let subMunicipalities = [];

    async function loadCities(provinceCode) {
      if (citySelect.tagName !== "SELECT") return;
      resetSelect(citySelect, "Loading...");
      resetSelect(barangaySelect, "Select Barangay");
      refreshSearchable(citySelect);
      refreshSearchable(barangaySelect);
      postalInput.readOnly = false;
      delete card.dataset.pendingZip;
      subMunicipalities = [];

      if (!provinceCode) {
        resetSelect(citySelect, "Select Town/City");
        refreshSearchable(citySelect);
        return;
      }

      try {
        const endpoint =
          provinceCode === NCR_REGION_CODE
            ? `${PSGC_API}/regions/${provinceCode}/cities-municipalities`
            : `${PSGC_API}/provinces/${provinceCode}/cities-municipalities`;
        const entries = await fetchPsgc(endpoint);
        subMunicipalities = entries.filter((e) => e.type === "SubMun");
        const cities = entries.filter((e) => e.type !== "SubMun");
        cities.sort((a, b) => a.name.localeCompare(b.name));
        populateSelect(citySelect, cities, "Select Town/City");
        refreshSearchable(citySelect);
      } catch (err) {
        console.warn("PSGC city lookup unavailable, falling back to manual entry", err);
        citySelect = convertToManualInput(citySelect, "Town/City");
        barangaySelect = convertToManualInput(barangaySelect, "Barangay");
      }
    }

    async function loadBarangays(cityCode) {
      if (barangaySelect.tagName !== "SELECT") return;
      resetSelect(barangaySelect, "Loading...");
      refreshSearchable(barangaySelect);

      if (!cityCode) {
        resetSelect(barangaySelect, "Select Barangay");
        refreshSearchable(barangaySelect);
        return;
      }

      try {
        let barangays = await fetchPsgc(`${PSGC_API}/cities-municipalities/${cityCode}/barangays`);

        // A city with zero barangays directly under its own code (so far
        // only observed for the City of Manila) has them nested one level
        // deeper instead, under its SubMun districts - detected generically
        // by a shared 6-digit PSGC code prefix rather than hardcoding
        // "Manila" by name, in case another city is ever structured the
        // same way.
        if (!barangays.length && subMunicipalities.length) {
          const prefix = cityCode.slice(0, 6);
          const districts = subMunicipalities.filter((s) => s.code.slice(0, 6) === prefix);
          if (districts.length) {
            const perDistrict = await Promise.all(
              districts.map((d) => fetchPsgc(`${PSGC_API}/cities-municipalities/${d.code}/barangays`))
            );
            barangays = perDistrict.flat();
          }
        }

        barangays.sort((a, b) => a.name.localeCompare(b.name));
        populateSelect(barangaySelect, barangays, "Select Barangay");
        refreshSearchable(barangaySelect);
      } catch (err) {
        console.warn("PSGC barangay lookup unavailable, falling back to manual entry", err);
        barangaySelect = convertToManualInput(barangaySelect, "Barangay");
      }
    }

    try {
      const provinces = await fetchPsgc(`${PSGC_API}/provinces`);
      provinces.push({ name: "Metro Manila (NCR)", code: NCR_REGION_CODE });
      provinces.sort((a, b) => a.name.localeCompare(b.name));
      populateSelect(provinceSelect, provinces, "Select Province");
      makeSearchableSelect(provinceSelect);

      provinceSelect.addEventListener("change", function () {
        const provinceCode = this.selectedOptions[0]?.dataset.code;
        loadCities(provinceCode);
      });

      citySelect.addEventListener("change", function () {
        const cityCode = this.selectedOptions[0]?.dataset.code;
        loadBarangays(cityCode);
        // Postal codes are per city/municipality, not per barangay - the
        // zip only gets *applied* (and the field locked) once a barangay
        // is also chosen, per brief; stash it here in the meantime.
        const zip = this.selectedOptions[0]?.dataset.zip;
        postalInput.readOnly = false;
        if (zip) card.dataset.pendingZip = zip;
        else delete card.dataset.pendingZip;
      });

      barangaySelect.addEventListener("change", function () {
        if (this.value && card.dataset.pendingZip) {
          postalInput.value = card.dataset.pendingZip;
          postalInput.readOnly = true;
        } else {
          postalInput.readOnly = false;
        }
      });

      makeSearchableSelect(citySelect);
      makeSearchableSelect(barangaySelect);

      card._locationLookups = { loadCities, loadBarangays };
    } catch (err) {
      console.warn("PSGC province lookup unavailable, falling back to manual entry for this location", err);
      provinceSelect = convertToManualInput(provinceSelect, "Province");
      citySelect = convertToManualInput(citySelect, "Town/City");
      citySelect.disabled = false;
      barangaySelect = convertToManualInput(barangaySelect, "Barangay");
      barangaySelect.disabled = false;
    }
  }

  // Async: callers that only need the card inserted (button click, form
  // reset) can call this without awaiting - the DOM insert above happens
  // synchronously before the first await either way. Callers that need to
  // hydrate saved province/city/barangay values afterward (see
  // hydrateIdentityForm()) MUST await it - initLocationPsgcCascade() only
  // finishes populating the province <select> (and setting
  // card._locationLookups, which the city/barangay cascade needs) once its
  // first PSGC fetch resolves, so hydrating before that resolves silently
  // fails to fill any of the three fields.
  async function addLocationCard(modal) {
    const wrap = modal.querySelector("#pmLocationsContainer");
    const index = wrap.children.length;
    wrap.insertAdjacentHTML("beforeend", locationCardHtml(index));
    const card = wrap.lastElementChild;

    // Default address_type to "Main Office" on add, per brief.
    const typeSelect = card.querySelector('[data-field="address_type"]');
    typeSelect.value = "Main Office";

    if (index === 0) card.querySelector(".pm-primary-radio").checked = true;

    card.querySelector(".pm-remove-location").addEventListener("click", () => card.remove());

    await initLocationPsgcCascade(card);

    return card;
  }

  async function hydrateLocationCard(card, address) {
    ["address_no", "address_building", "address_street", "address_postal_code"].forEach((field) => {
      const el = card.querySelector(`[data-field="${field}"]`);
      if (el) el.value = address[field] ?? "";
    });

    const typeSelect = card.querySelector('[data-field="address_type"]');
    if (typeSelect) typeSelect.value = address.address_type ?? "";

    const countrySelect = card.querySelector('[data-field="address_country"]');
    if (countrySelect) countrySelect.value = address.address_country || "Philippines";

    card.querySelector(".pm-primary-radio").checked = Boolean(address.is_primary);

    const { loadCities, loadBarangays } = card._locationLookups ?? {};
    const provinceSelect = card.querySelector('[data-field="address_province"]');
    const citySelect = card.querySelector('[data-field="address_town_city"]');
    const barangaySelect = card.querySelector('[data-field="address_barangay"]');

    if (address.address_province && provinceSelect) {
      provinceSelect.value = address.address_province;
      refreshSearchable(provinceSelect);

      if (provinceSelect.tagName === "SELECT" && loadCities) {
        const provinceCode = provinceSelect.selectedOptions[0]?.dataset.code;
        await loadCities(provinceCode);

        const freshCitySelect = card.querySelector('[data-field="address_town_city"]');
        if (address.address_town_city && freshCitySelect) {
          freshCitySelect.value = address.address_town_city;
          refreshSearchable(freshCitySelect);

          if (freshCitySelect.tagName === "SELECT" && loadBarangays) {
            const cityCode = freshCitySelect.selectedOptions[0]?.dataset.code;
            await loadBarangays(cityCode);

            const freshBarangaySelect = card.querySelector('[data-field="address_barangay"]');
            if (address.address_barangay && freshBarangaySelect) {
              freshBarangaySelect.value = address.address_barangay;
              refreshSearchable(freshBarangaySelect);
            }
          }
        }
      } else {
        // Already degraded to manual text inputs - just fill the values.
        if (address.address_town_city && citySelect) citySelect.value = address.address_town_city;
        if (address.address_barangay && barangaySelect) barangaySelect.value = address.address_barangay;
      }
    }
  }

  function collectLocations(modal) {
    return Array.from(modal.querySelectorAll(".pm-location-card")).map((card) => {
      const obj = {};
      card.querySelectorAll("[data-field]").forEach((el) => {
        obj[el.dataset.field] = el.value;
      });
      obj.is_primary = card.querySelector(".pm-primary-radio")?.checked ?? false;
      return obj;
    });
  }

  // ------------------------------------------------------------------
  // TAB 2: PROSPECT CONTACT INFORMATION (repeatable contact-person cards)
  // ------------------------------------------------------------------
  function formatLocationLabel(loc) {
    const line = [
      loc.address_no,
      loc.address_building,
      loc.address_street,
      loc.address_barangay,
      loc.address_town_city,
      loc.address_province,
    ]
      .filter(Boolean)
      .join(", ") || "Unnamed location";
    return loc.address_type ? `${loc.address_type} — ${line}` : line;
  }

  // "Prompting" empty state (VISUALS.md) when the prospect has no locations
  // yet - a real actionable gap (contacts can't be linked to an address
  // until the Identity tab has at least one), not a "nothing here" case.
  function locationsPromptingEmptyState() {
    return `
    <div class="border-2 border-dashed border-orange-300 dark:border-orange-800 bg-orange-50 dark:bg-orange-950/20 rounded-xl p-4 flex flex-col items-center text-center gap-1">
        <span class="text-[11px] font-semibold uppercase tracking-widest text-orange-600 dark:text-orange-400">⚠ Action Needed</span>
        <p class="text-xs text-zinc-600 dark:text-zinc-300">No locations yet. Add one in the Prospect Identity tab first.</p>
    </div>`;
  }

  // Single-select dropdown of this prospect's own locations only - per
  // brief, one address per contact. The wire format (location_ids, on both
  // collectContacts() and the backend) stays an array for forward
  // compatibility, it just only ever holds 0 or 1 entries now.
  function addressDropdownHtml(selectedId) {
    if (!currentProspectLocations.length) return locationsPromptingEmptyState();
    return `
    <select class="pm-contact-location-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
        <option value="">No address</option>
        ${currentProspectLocations
          .map(
            (loc) =>
              `<option value="${loc.id}" ${Number(selectedId) === loc.id ? "selected" : ""}>${formatLocationLabel(loc)}</option>`
          )
          .join("")}
    </select>`;
  }

  // Regenerates every already-rendered contact card's address dropdown from
  // the current currentProspectLocations, preserving whatever was already
  // selected - called after every Identity save so Tab 2 stays in sync with
  // locations added/removed there (per brief).
  function refreshContactLocationOptions(modal) {
    modal.querySelectorAll(".pm-contact-card").forEach((card) => {
      const selected = card.querySelector(".pm-contact-location-select")?.value || null;
      const wrap = card.querySelector(".pm-contact-addresses");
      if (wrap) wrap.innerHTML = addressDropdownHtml(selected);
    });
  }

  function channelRowHtml() {
    return `
    <div class="pm-channel-row flex items-center gap-2">
        <select data-field="channel_type" class="w-32 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            ${CHANNEL_TYPES.map((t) => `<option value="${t.value}">${t.label}</option>`).join("")}
        </select>
        <input type="text" data-field="value" placeholder="Number or email" class="flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
        <select data-field="contact_type" class="w-28 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            ${CONTACT_TYPES.map((t) => `<option value="${t.value}">${t.label}</option>`).join("")}
        </select>
        <button type="button" class="pm-remove-channel text-red-500 text-xs font-medium shrink-0">✕</button>
    </div>`;
  }

  function addChannelRow(card, prefill) {
    const wrap = card.querySelector(".pm-channels-container");
    wrap.insertAdjacentHTML("beforeend", channelRowHtml());
    const row = wrap.lastElementChild;
    row.querySelector(".pm-remove-channel").addEventListener("click", () => row.remove());
    if (prefill) {
      row.querySelector('[data-field="channel_type"]').value = prefill.channel_type ?? "mobile";
      row.querySelector('[data-field="value"]').value = prefill.value ?? "";
      row.querySelector('[data-field="contact_type"]').value = prefill.contact_type ?? "personal";
    }
    return row;
  }

  function contactCardHtml() {
    return `
    <div class="pm-contact-card border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-4">
        <div class="flex justify-between items-center">
            <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-200">Contact Person</p>
            <button type="button" class="pm-remove-contact text-red-500 text-xs font-medium">✕ Remove</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Title</label>
                <select data-field="title" class="pmContactTitleDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Title</option>${titleOptionsHtml}
                </select>
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label class="text-[11px] text-zinc-400 uppercase">First Name <span class="req-asterisk">*</span></label>
                <input type="text" data-field="first_name" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Middle Name</label>
                <input type="text" data-field="middle_name" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label class="text-[11px] text-zinc-400 uppercase">Last Name <span class="req-asterisk">*</span></label>
                <input type="text" data-field="last_name" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div class="md:col-span-4">
                <label class="text-[11px] text-zinc-400 uppercase">Position</label>
                <input type="text" data-field="position" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
        </div>

        <div class="border-t dark:border-zinc-700 pt-3">
            <div class="flex justify-between items-center mb-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Numbers / Emails</p>
                <button type="button" class="pm-add-channel text-xs px-2 py-1 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">+ Add Number/Email</button>
            </div>
            <div class="pm-channels-container space-y-2"></div>
        </div>

        <div class="border-t dark:border-zinc-700 pt-3">
            <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Address</p>
            <div class="pm-contact-addresses"></div>
        </div>
    </div>`;
  }

  function addContactCard(modal) {
    const wrap = modal.querySelector("#pmContactsContainer");
    wrap.insertAdjacentHTML("beforeend", contactCardHtml());
    const card = wrap.lastElementChild;

    card.querySelector(".pm-remove-contact").addEventListener("click", () => card.remove());
    card.querySelector(".pm-add-channel").addEventListener("click", () => addChannelRow(card));
    card.querySelector(".pm-contact-addresses").innerHTML = addressDropdownHtml(null);
    makeSearchableSelect(card.querySelector(".pmContactTitleDropdown"));

    addChannelRow(card);

    return card;
  }

  function hydrateContactCard(card, contact) {
    ["title", "first_name", "middle_name", "last_name", "position"].forEach((field) => {
      const el = card.querySelector(`[data-field="${field}"]`);
      if (el) el.value = contact[field] ?? "";
    });
    refreshSearchable(card.querySelector(".pmContactTitleDropdown"));

    card.querySelector(".pm-channels-container").innerHTML = "";
    const channels = contact.channels ?? [];
    channels.forEach((ch) => addChannelRow(card, ch));
    if (!channels.length) addChannelRow(card);

    const locationId = (contact.location_ids ?? [])[0] ?? null;
    card.querySelector(".pm-contact-addresses").innerHTML = addressDropdownHtml(locationId);
  }

  function resetContactsForm(modal) {
    modal.querySelector("#pmContactsContainer").innerHTML = "";
    addContactCard(modal);
  }

  function hydrateContactsTab(modal, lead) {
    const wrap = modal.querySelector("#pmContactsContainer");
    wrap.innerHTML = "";
    const contacts = lead.contacts ?? [];
    contacts.forEach((c) => hydrateContactCard(addContactCard(modal), c));
    if (!contacts.length) addContactCard(modal);
  }

  function collectContacts(modal) {
    return Array.from(modal.querySelectorAll(".pm-contact-card")).map((card) => {
      const get = (field) => card.querySelector(`[data-field="${field}"]`)?.value.trim() || "";

      const channels = Array.from(card.querySelectorAll(".pm-channel-row"))
        .map((row) => ({
          channel_type: row.querySelector('[data-field="channel_type"]').value,
          value: row.querySelector('[data-field="value"]').value.trim(),
          contact_type: row.querySelector('[data-field="contact_type"]').value,
        }))
        // Drop untouched/empty rows rather than erroring on them - the
        // backend requires a value on any channel actually submitted.
        .filter((ch) => ch.value);

      const selectedLocation = card.querySelector(".pm-contact-location-select")?.value;
      const location_ids = selectedLocation ? [Number(selectedLocation)] : [];

      return {
        title: get("title") || null,
        first_name: get("first_name"),
        middle_name: get("middle_name") || null,
        last_name: get("last_name"),
        position: get("position") || null,
        channels,
        location_ids,
      };
    });
  }

  async function saveContacts(modal, button) {
    if (!currentProspectUuid) {
      showMessage({ status: "error", title: "Save Prospect Identity first." });
      return;
    }

    const contacts = collectContacts(modal);
    if (contacts.some((c) => !c.first_name || !c.last_name)) {
      showMessage({ status: "error", title: "Each contact needs a First Name and Last Name." });
      return;
    }

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: { contacts },
      url: `/api/crm/prospects/${currentProspectUuid}/contacts`,
      button,
    });

    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Saving",
        message: response.message ?? "Please check the required fields.",
      });
      return;
    }

    showMessage({ status: "success", title: "Contact Information Saved" });

    const wrap = modal.querySelector("#pmContactsContainer");
    wrap.innerHTML = "";
    const savedContacts = response.data.contacts ?? [];
    savedContacts.forEach((c) => hydrateContactCard(addContactCard(modal), c));
    if (!savedContacts.length) addContactCard(modal);

    await refreshStageCompletion(modal);
  }

  // ------------------------------------------------------------------
  // TAB 3: PROSPECT REQUIREMENTS (Proposal Request + container table)
  // ------------------------------------------------------------------
  function fillContainerTypeSelect(modal) {
    const select = modal.querySelector("#pmContainerType");
    if (!select) return;
    select.insertAdjacentHTML(
      "beforeend",
      CONTAINER_TYPES.map((t) => `<option value="${t.value}">${t.label}</option>`).join("")
    );
  }

  function applyContainerLookupsToDom(modal) {
    const cargoSelect = modal.querySelector("#pmCargoType");
    if (cargoSelect) cargoSelect.innerHTML = cargoTypeOptionsHtml;

    // Service Type is now the fixed 4-value list, not the Delivery Types
    // LOV (deliveryTypesOptionsHtml is still fetched/cached above but no
    // longer applied to this select - kept in case another consumer needs it).
    const deliveryTypeSelect = modal.querySelector("#pmDeliveryType");
    if (deliveryTypeSelect) deliveryTypeSelect.innerHTML = serviceTypeOptionsHtml;

    const originSelect = modal.querySelector("#pmOriginLocation");
    if (originSelect) originSelect.innerHTML = `<option value="">Select Location</option>${locationsOptionsHtml}`;
    const destinationSelect = modal.querySelector("#pmDestinationLocation");
    if (destinationSelect) destinationSelect.innerHTML = `<option value="">Select Location</option>${locationsOptionsHtml}`;
    [originSelect, destinationSelect].forEach((el) => makeSearchableSelect(el));

    const truckingCargoTypeSelect = modal.querySelector("#pmTruckingCargoType");
    if (truckingCargoTypeSelect) truckingCargoTypeSelect.innerHTML = truckingCargoTypeOptionsHtml;
    const truckingGeneralCargoSelect = modal.querySelector("#pmTruckingCargoTypeGeneral");
    if (truckingGeneralCargoSelect) truckingGeneralCargoSelect.innerHTML = cargoTypeOptionsHtml;

    const truckingOrigin = modal.querySelector("#pmTruckingOrigin");
    if (truckingOrigin) truckingOrigin.innerHTML = `<option value="">Select Location</option>${locationsOptionsHtml}`;
    const truckingDestination = modal.querySelector("#pmTruckingDestination");
    if (truckingDestination) truckingDestination.innerHTML = `<option value="">Select Location</option>${locationsOptionsHtml}`;
    [truckingOrigin, truckingDestination].forEach((el) => makeSearchableSelect(el));
  }

  // Fires once per SPA page load (called from initProspectModal(), not per
  // tab-click) - the type select is a static list, containers/delivery
  // types/cargo-type/locations/ports are cached network fetches (see
  // ensureContainerLookupsLoaded()).
  async function initRequirementsLookups(modal) {
    fillContainerTypeSelect(modal);
    await ensureContainerLookupsLoaded();
    applyContainerLookupsToDom(modal);
  }

  function applyContainerTypeVisibility(modal) {
    const type = modal.querySelector("#pmContainerType").value;
    const flags = TYPE_FIELD_VISIBILITY[type] || {};
    modal.querySelector(".field-convan-size")?.classList.toggle("hidden", !flags.convanSize);
    modal.querySelector(".field-temperature")?.classList.toggle("hidden", !flags.temperature);
    modal.querySelectorAll(".field-revenue-ton").forEach((el) => el.classList.toggle("hidden", !flags.revenueTon));
  }

  function populateContainerClassSizeOptions(modal) {
    const type = modal.querySelector("#pmContainerType").value;
    const catalog = containerCatalogByCode[type] ?? { sizes: [], classes: [] };

    const sizeSelect = modal.querySelector("#pmContainerSize");
    if (sizeSelect) {
      sizeSelect.innerHTML =
        '<option value="">Select Size</option>' + catalog.sizes.map((s) => `<option value="${s.id}">${s.size}</option>`).join("");
    }
  }

  // One-time bindings for the single (non-repeatable) Add Booking
  // Requirement form - called once from initProspectModal(), same as the
  // Identity tab's static field listeners.
  function bindContainerFormCascades(modal) {
    modal.querySelector("#pmContainerType")?.addEventListener("change", () => {
      applyContainerTypeVisibility(modal);
      populateContainerClassSizeOptions(modal);
    });
  }

  function collectContainerFormPayload(modal) {
    const form = modal.querySelector("#pmContainerForm");
    const obj = {};
    form.querySelectorAll("[data-field]").forEach((el) => {
      if (el.type === "checkbox") {
        obj[el.dataset.field] = el.checked;
      } else if (el.classList.contains("currency-input")) {
        obj[el.dataset.field] = parseCurrencyValue(el.value);
      } else {
        obj[el.dataset.field] = el.value;
      }
    });
    const typeSelect = modal.querySelector("#pmContainerType");
    obj.booking_unit_type = typeSelect.options[typeSelect.selectedIndex]?.textContent ?? "";
    return obj;
  }

  function resetContainerForm(modal) {
    const form = modal.querySelector("#pmContainerForm");
    form.querySelectorAll("input[type=text], input[type=number], textarea").forEach((el) => (el.value = ""));
    form.querySelectorAll("select").forEach((el) => (el.selectedIndex = 0));

    applyContainerTypeVisibility(modal);
    populateContainerClassSizeOptions(modal);
  }

  // Shows/hides a product's table wrap based on whether it has any rows -
  // per brief, "initially there is no table on the tab" for each of
  // Container/Trucking/Charter; the table only appears once its first row
  // is added.
  function toggleTableWrap(modal, wrapId, hasRows) {
    modal.querySelector(`#${wrapId}`)?.classList.toggle("hidden", !hasRows);
  }

  // Renders one saved container as a table row - `-` (hyphen) for a field
  // that TYPE_FIELD_VISIBILITY marks not-applicable for this row's type,
  // "—" (em dash) for a field that IS applicable but wasn't filled in.
  // Column order here MUST stay in sync with the static <thead> in
  // prospect-modal.blade.php.
  function containerTableRowHtml(c) {
    const NA = "-";
    const EMPTY = "—";
    const flags = TYPE_FIELD_VISIBILITY[c.container_type] || {};
    const typeLabel = CONTAINER_TYPES.find((t) => t.value === c.container_type)?.label ?? c.container_type ?? EMPTY;
    const dot = CONTAINER_TYPE_DOT_COLOR[c.container_type] ?? "bg-zinc-400";
    const sizeLabel = flags.convanSize ? c.container_size?.size : null;

    const weight =
      c.weight != null
        ? `${Number(c.weight).toLocaleString()} ${WEIGHT_UNIT_LABEL[c.weight_unit] ?? c.weight_unit ?? ""}`.trim()
        : EMPTY;

    // Identity/qty/route first (what a reviewer scans for first), pricing
    // and handling detail after - table-positioning fix, see VISUALS.md.
    const cells = [
      c.quantity ?? EMPTY,
      c.origin_location?.name ?? EMPTY,
      c.destination_location?.name ?? EMPTY,
      flags.temperature ? (c.minimum_temperature ?? EMPTY) : NA,
      flags.revenueTon ? (c.revenue_ton ?? EMPTY) : NA,
      flags.revenueTon ? (c.cargo_measurement ?? EMPTY) : NA,
      c.frequency ?? EMPTY,
      c.service_type ?? EMPTY,
      c.declared_value_per_unit != null ? `₱${Number(c.declared_value_per_unit).toLocaleString()}` : EMPTY,
      weight,
      c.cargo_type ?? EMPTY,
      c.general_cargo_description ?? EMPTY,
      c.special_requirements ?? EMPTY,
      c.booking_unit_type ?? EMPTY,
    ];

    const firstTd = `<td class="px-3 py-2 align-top text-zinc-700 dark:text-zinc-200 sticky left-0 z-[1] bg-white dark:bg-zinc-900 border-r border-zinc-100 dark:border-zinc-700"><span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full ${dot} shrink-0"></span>${typeLabel}${sizeLabel ? ` · ${sizeLabel}` : ""}</span></td>`;
    const tds = cells.map((v) => `<td class="px-3 py-2 align-top text-zinc-700 dark:text-zinc-200">${v}</td>`).join("");

    return `<tr class="pm-container-row" data-id="${c.id}">${firstTd}${tds}<td class="px-3 py-2 align-top"><button type="button" class="pm-remove-container-row text-red-500 text-xs font-medium whitespace-nowrap">✕ Remove</button></td></tr>`;
  }

  function bindContainerRowRemove(modal, row) {
    row.querySelector(".pm-remove-container-row")?.addEventListener("click", async () => {
      const id = row.dataset.id;
      const response = await apiCall({
        mode: "DELETE",
        url: `/api/crm/prospects/${currentProspectUuid}/proposalRequest/containers/${id}`,
      });

      if (!response.success) {
        showMessage({ status: "error", title: "Error Removing", message: response.message ?? "" });
        return;
      }

      row.remove();
      const tbody = modal.querySelector("#pmContainersTableBody");
      toggleTableWrap(modal, "pmContainersTableWrap", Boolean(tbody.querySelector(".pm-container-row")));

      await refreshStageCompletion(modal);
    });
  }

  function renderContainersTable(modal, containers) {
    const tbody = modal.querySelector("#pmContainersTableBody");
    tbody.innerHTML = "";
    containers.forEach((c) => tbody.insertAdjacentHTML("beforeend", containerTableRowHtml(c)));
    tbody.querySelectorAll(".pm-container-row").forEach((row) => bindContainerRowRemove(modal, row));
    toggleTableWrap(modal, "pmContainersTableWrap", containers.length > 0);
  }

  function appendContainerRow(modal, container) {
    const tbody = modal.querySelector("#pmContainersTableBody");
    tbody.insertAdjacentHTML("beforeend", containerTableRowHtml(container));
    bindContainerRowRemove(modal, tbody.lastElementChild);
    toggleTableWrap(modal, "pmContainersTableWrap", true);
  }

  // One "Add Requirement" area shared by Freight/Trucking/Charter - picking
  // a product swaps which pane is visible (and reveals the shared submit
  // button); blank shows none, per brief.
  function bindProductSelector(modal) {
    modal.querySelector("#pmRequirementProduct").addEventListener("change", function () {
      Object.entries(PRODUCT_PANES).forEach(([key, paneId]) => {
        modal.querySelector(`#${paneId}`)?.classList.toggle("hidden", key !== this.value);
      });
      modal.querySelector("#pmAddRequirementBtnWrap")?.classList.toggle("hidden", !this.value);
    });
  }

  function resetProductSelector(modal) {
    const select = modal.querySelector("#pmRequirementProduct");
    select.value = "";
    Object.values(PRODUCT_PANES).forEach((paneId) => modal.querySelector(`#${paneId}`)?.classList.add("hidden"));
    modal.querySelector("#pmAddRequirementBtnWrap")?.classList.add("hidden");
  }

  function resetRequirementsForm(modal) {
    resetContainerForm(modal);
    renderContainersTable(modal, []);
    resetTruckingForm(modal);
    renderTruckingsTable(modal, []);
    resetCharterForm(modal);
    renderChartersTable(modal, []);
    resetProductSelector(modal);
  }

  // "Prospect products" - owned directly by the prospect, independent of
  // any Proposal Request. Top-level fields on the lead payload, not
  // nested under proposal_request.
  function hydrateRequirementsTab(modal, lead) {
    renderContainersTable(modal, lead.requirement_containers ?? []);
    renderTruckingsTable(modal, lead.requirement_truckings ?? []);
    renderChartersTable(modal, lead.requirement_charters ?? []);
  }

  async function addContainerRow(modal, button) {
    if (!currentProspectUuid) {
      showMessage({ status: "error", title: "Save Prospect Identity first." });
      return;
    }

    const payload = collectContainerFormPayload(modal);

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${currentProspectUuid}/proposalRequest/containers`,
      button,
    });

    if (!response.success) {
      const errors = response.data?.errors ?? [];
      showMessage({
        status: "error",
        title: "Error Adding Requirement",
        message: errors.length ? errors.join(" ") : response.message ?? "Please check the required fields.",
      });
      return;
    }

    appendContainerRow(modal, response.data.container);
    resetContainerForm(modal);
    showMessage({ status: "success", title: "Booking Requirement Added" });
    await refreshStageCompletion(modal);
  }

  // ------------------------------------------------------------------
  // TAB 3 (cont'd): TRUCKING - second "product", flat single-row form.
  // ------------------------------------------------------------------
  function collectTruckingFormPayload(modal) {
    const form = modal.querySelector("#pmTruckingForm");
    const obj = {};
    form.querySelectorAll("[data-field]").forEach((el) => {
      obj[el.dataset.field] = el.classList.contains("currency-input") ? parseCurrencyValue(el.value) : el.value;
    });
    return obj;
  }

  function resetTruckingForm(modal) {
    const form = modal.querySelector("#pmTruckingForm");
    form.querySelectorAll("input[type=text], input[type=number], textarea").forEach((el) => (el.value = ""));
    form.querySelectorAll("select").forEach((el) => (el.selectedIndex = 0));
  }

  function truckingTableRowHtml(t) {
    const EMPTY = "—";
    const weight =
      t.weight != null
        ? `${Number(t.weight).toLocaleString()} ${WEIGHT_UNIT_LABEL[t.weight_unit] ?? t.weight_unit ?? ""}`.trim()
        : EMPTY;

    const cells = [
      t.trucking_cargo_type ?? EMPTY,
      t.quantity ?? EMPTY,
      t.frequency ?? EMPTY,
      DISPATCH_MODE_LABEL[t.dispatch_mode] ?? t.dispatch_mode ?? EMPTY,
      t.origin_location?.name ?? EMPTY,
      t.destination_location?.name ?? EMPTY,
      t.declared_value_per_unit != null ? `₱${Number(t.declared_value_per_unit).toLocaleString()}` : EMPTY,
      weight,
      t.cargo_type ?? EMPTY,
      t.general_cargo_description ?? EMPTY,
      t.special_requirements ?? EMPTY,
    ];

    const tds = cells.map((v) => `<td class="px-3 py-2 align-top text-zinc-700 dark:text-zinc-200">${v}</td>`).join("");
    return `<tr class="pm-trucking-row" data-id="${t.id}">${tds}<td class="px-3 py-2 align-top"><button type="button" class="pm-remove-trucking-row text-red-500 text-xs font-medium whitespace-nowrap">✕ Remove</button></td></tr>`;
  }

  function bindTruckingRowRemove(modal, row) {
    row.querySelector(".pm-remove-trucking-row")?.addEventListener("click", async () => {
      const id = row.dataset.id;
      const response = await apiCall({
        mode: "DELETE",
        url: `/api/crm/prospects/${currentProspectUuid}/proposalRequest/truckings/${id}`,
      });

      if (!response.success) {
        showMessage({ status: "error", title: "Error Removing", message: response.message ?? "" });
        return;
      }

      row.remove();
      const tbody = modal.querySelector("#pmTruckingsTableBody");
      toggleTableWrap(modal, "pmTruckingsTableWrap", Boolean(tbody.querySelector(".pm-trucking-row")));

      await refreshStageCompletion(modal);
    });
  }

  function renderTruckingsTable(modal, truckings) {
    const tbody = modal.querySelector("#pmTruckingsTableBody");
    tbody.innerHTML = "";
    truckings.forEach((t) => tbody.insertAdjacentHTML("beforeend", truckingTableRowHtml(t)));
    tbody.querySelectorAll(".pm-trucking-row").forEach((row) => bindTruckingRowRemove(modal, row));
    toggleTableWrap(modal, "pmTruckingsTableWrap", truckings.length > 0);
  }

  function appendTruckingRow(modal, trucking) {
    const tbody = modal.querySelector("#pmTruckingsTableBody");
    tbody.insertAdjacentHTML("beforeend", truckingTableRowHtml(trucking));
    bindTruckingRowRemove(modal, tbody.lastElementChild);
    toggleTableWrap(modal, "pmTruckingsTableWrap", true);
  }

  async function addTruckingRow(modal, button) {
    if (!currentProspectUuid) {
      showMessage({ status: "error", title: "Save Prospect Identity first." });
      return;
    }

    const payload = collectTruckingFormPayload(modal);

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${currentProspectUuid}/proposalRequest/truckings`,
      button,
    });

    if (!response.success) {
      const errors = response.data?.errors ?? [];
      showMessage({
        status: "error",
        title: "Error Adding Requirement",
        message: errors.length ? errors.join(" ") : response.message ?? "Please check the required fields.",
      });
      return;
    }

    appendTruckingRow(modal, response.data.trucking);
    resetTruckingForm(modal);
    showMessage({ status: "success", title: "Trucking Requirement Added" });
    await refreshStageCompletion(modal);
  }

  // ------------------------------------------------------------------
  // TAB 3 (cont'd): CHARTER - third "product". Each booking owns its own
  // repeatable Cargo list and Port list, entered inline in the form and
  // summarized as one table row per booking.
  // ------------------------------------------------------------------
  function charterCargoRowHtml() {
    return `
    <div class="pm-charter-cargo-row flex items-start gap-2">
        <select data-field="cargo_type" class="w-40 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            ${cargoTypeOptionsHtml}
        </select>
        <input type="text" data-field="general_cargo_description" placeholder="Cargo description" class="flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
        <input type="text" data-field="special_requirements" placeholder="Special requirements" class="flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
        <button type="button" class="pm-remove-charter-cargo text-red-500 text-xs font-medium shrink-0">✕</button>
    </div>`;
  }

  function addCharterCargoRow(modal) {
    const wrap = modal.querySelector("#pmCharterCargoContainer");
    wrap.insertAdjacentHTML("beforeend", charterCargoRowHtml());
    const row = wrap.lastElementChild;
    row.querySelector(".pm-remove-charter-cargo").addEventListener("click", () => row.remove());
    return row;
  }

  function charterPortRowHtml() {
    return `
    <div class="pm-charter-port-row flex items-center gap-2">
        <span class="pm-charter-port-label text-xs font-semibold text-zinc-500 dark:text-zinc-400 w-16 shrink-0">PORT</span>
        <select data-field="port_id" class="pmCharterPortSelect flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            <option value="">Select Port</option>${portsOptionsHtml}
        </select>
        <button type="button" class="pm-remove-charter-port text-red-500 text-xs font-medium shrink-0">✕</button>
    </div>`;
  }

  // Re-labels every port row "PORT 1", "PORT 2"... in DOM order - called
  // after every add/remove so the numbering never has a gap.
  function renumberCharterPortRows(modal) {
    modal.querySelectorAll("#pmCharterPortsContainer .pm-charter-port-row").forEach((row, index) => {
      row.querySelector(".pm-charter-port-label").textContent = `PORT ${index + 1}`;
    });
  }

  function addCharterPortRow(modal) {
    const wrap = modal.querySelector("#pmCharterPortsContainer");
    wrap.insertAdjacentHTML("beforeend", charterPortRowHtml());
    const row = wrap.lastElementChild;
    makeSearchableSelect(row.querySelector(".pmCharterPortSelect"));
    row.querySelector(".pm-remove-charter-port").addEventListener("click", () => {
      row.remove();
      renumberCharterPortRows(modal);
    });
    renumberCharterPortRows(modal);
    return row;
  }

  function collectCharterCargoRows(modal) {
    return Array.from(modal.querySelectorAll(".pm-charter-cargo-row")).map((row) => ({
      cargo_type: row.querySelector('[data-field="cargo_type"]').value,
      general_cargo_description: row.querySelector('[data-field="general_cargo_description"]').value,
      special_requirements: row.querySelector('[data-field="special_requirements"]').value,
    }));
  }

  function collectCharterPortRows(modal) {
    return Array.from(modal.querySelectorAll(".pm-charter-port-row")).map((row) => ({
      port_id: row.querySelector('[data-field="port_id"]').value,
    }));
  }

  function collectCharterFormPayload(modal) {
    const get = (id) => modal.querySelector(id)?.value ?? "";
    return {
      charter_start_date: get("#pmCharterStartDate"),
      charter_end_date: get("#pmCharterEndDate"),
      declared_value: parseCurrencyValue(get("#pmCharterDeclaredValue")),
      weight: get("#pmCharterWeight"),
      weight_unit: get("#pmCharterWeightUnit"),
      cargo: collectCharterCargoRows(modal),
      ports: collectCharterPortRows(modal),
    };
  }

  // Unlike resetContainerForm/resetTruckingForm, this also reseeds ONE
  // blank cargo row and ONE "PORT 1" row (mirrors addContactCard()/
  // addLocationCard() always leaving one card ready on Tabs 1/2).
  function resetCharterForm(modal) {
    modal.querySelector("#pmCharterStartDate").value = "";
    modal.querySelector("#pmCharterEndDate").value = "";
    modal.querySelector("#pmCharterDeclaredValue").value = "";
    modal.querySelector("#pmCharterWeight").value = "";
    modal.querySelector("#pmCharterWeightUnit").selectedIndex = 0;

    modal.querySelector("#pmCharterCargoContainer").innerHTML = "";
    addCharterCargoRow(modal);

    modal.querySelector("#pmCharterPortsContainer").innerHTML = "";
    addCharterPortRow(modal);
  }

  function charterTableRowHtml(c) {
    const EMPTY = "—";
    const cargoSummary =
      (c.cargo_items ?? [])
        .map((item) => item.cargo_type)
        .filter(Boolean)
        .join(", ") || EMPTY;
    const portSummary =
      (c.ports ?? []).map((p, i) => `PORT ${i + 1}: ${p.port?.name ?? "—"}`).join(", ") || EMPTY;
    const weight =
      c.weight != null
        ? `${Number(c.weight).toLocaleString()} ${WEIGHT_UNIT_LABEL[c.weight_unit] ?? c.weight_unit ?? ""}`.trim()
        : EMPTY;

    const cells = [
      cargoSummary,
      portSummary,
      c.charter_start_date ?? EMPTY,
      c.charter_end_date ?? EMPTY,
      c.declared_value != null ? `₱${Number(c.declared_value).toLocaleString()}` : EMPTY,
      weight,
    ];

    const tds = cells.map((v) => `<td class="px-3 py-2 align-top text-zinc-700 dark:text-zinc-200">${v}</td>`).join("");
    return `<tr class="pm-charter-row" data-id="${c.id}">${tds}<td class="px-3 py-2 align-top"><button type="button" class="pm-remove-charter-row text-red-500 text-xs font-medium whitespace-nowrap">✕ Remove</button></td></tr>`;
  }

  function bindCharterRowRemove(modal, row) {
    row.querySelector(".pm-remove-charter-row")?.addEventListener("click", async () => {
      const id = row.dataset.id;
      const response = await apiCall({
        mode: "DELETE",
        url: `/api/crm/prospects/${currentProspectUuid}/proposalRequest/charters/${id}`,
      });

      if (!response.success) {
        showMessage({ status: "error", title: "Error Removing", message: response.message ?? "" });
        return;
      }

      row.remove();
      const tbody = modal.querySelector("#pmChartersTableBody");
      toggleTableWrap(modal, "pmChartersTableWrap", Boolean(tbody.querySelector(".pm-charter-row")));

      await refreshStageCompletion(modal);
    });
  }

  function renderChartersTable(modal, charters) {
    const tbody = modal.querySelector("#pmChartersTableBody");
    tbody.innerHTML = "";
    charters.forEach((c) => tbody.insertAdjacentHTML("beforeend", charterTableRowHtml(c)));
    tbody.querySelectorAll(".pm-charter-row").forEach((row) => bindCharterRowRemove(modal, row));
    toggleTableWrap(modal, "pmChartersTableWrap", charters.length > 0);
  }

  function appendCharterRow(modal, charter) {
    const tbody = modal.querySelector("#pmChartersTableBody");
    tbody.insertAdjacentHTML("beforeend", charterTableRowHtml(charter));
    bindCharterRowRemove(modal, tbody.lastElementChild);
    toggleTableWrap(modal, "pmChartersTableWrap", true);
  }

  async function addCharter(modal, button) {
    if (!currentProspectUuid) {
      showMessage({ status: "error", title: "Save Prospect Identity first." });
      return;
    }

    const payload = collectCharterFormPayload(modal);

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${currentProspectUuid}/proposalRequest/charters`,
      button,
    });

    if (!response.success) {
      const errors = response.data?.errors ?? [];
      showMessage({
        status: "error",
        title: "Error Adding Requirement",
        message: errors.length ? errors.join(" ") : response.message ?? "Please check the required fields.",
      });
      return;
    }

    appendCharterRow(modal, response.data.charter);
    resetCharterForm(modal);
    showMessage({ status: "success", title: "Charter Requirement Added" });
    await refreshStageCompletion(modal);
  }

  // ------------------------------------------------------------------
  // TABS + GATING
  // ------------------------------------------------------------------
  const PM_TABS = ["Identity", "Contact", "Requirements"];

  function switchTab(modal, tab) {
    PM_TABS.forEach((t) => {
      modal.querySelector(`[data-pm-pane="${t}"]`)?.classList.toggle("hidden", t !== tab);
      const btn = modal.querySelector(`[data-pm-tab="${t}"]`);
      if (!btn) return;
      const active = t === tab;
      btn.classList.toggle("border-orange-500", active);
      btn.classList.toggle("text-orange-600", active);
      btn.classList.toggle("bg-orange-50", active);
      btn.classList.toggle("dark:bg-orange-950/20", active);
      btn.classList.toggle("border-zinc-200", !active);
      btn.classList.toggle("dark:border-zinc-700", !active);
      btn.classList.toggle("text-zinc-400", !active && btn.disabled);
      btn.classList.toggle("text-zinc-600", !active && !btn.disabled);
      btn.classList.toggle("dark:text-zinc-500", !active && btn.disabled);
      btn.classList.toggle("dark:text-zinc-300", !active && !btn.disabled);
    });
  }

  // Not a staged/gated form: Identity, Contact, and Requirements are all
  // freely navigable regardless of save state (saving Contact/Requirements
  // before Identity exists is handled by each save handler's own "Save
  // Prospect Identity first" guard, not by disabling the tab). Each tab
  // shows a green check when ITS OWN required fields are complete -
  // independent of the others - and is otherwise blank (no stage numbers).
  // Once all three are complete, row-clicks route to the read-only
  // ProspectInfoModal instead of this form - see openProspectRecord().
  function setTabCompletionIndicator(modal, tabId, complete) {
    const indicator = modal.querySelector(`#${tabId} .pm-tab-indicator`);
    if (!indicator) return;
    indicator.textContent = complete ? "✓" : "";
    indicator.classList.toggle("border", Boolean(complete));
    indicator.classList.toggle("border-green-500", Boolean(complete));
    indicator.classList.toggle("dark:border-green-500", Boolean(complete));
    indicator.classList.toggle("text-green-600", Boolean(complete));
    indicator.classList.toggle("dark:text-green-400", Boolean(complete));
  }

  function updateTabGating(modal, stageCompletion) {
    setTabCompletionIndicator(modal, "pmTabBtnIdentity", stageCompletion?.[1]);
    setTabCompletionIndicator(modal, "pmTabBtnContact", stageCompletion?.[2]);
    setTabCompletionIndicator(modal, "pmTabBtnRequirements", stageCompletion?.[3]);
  }

  function bindTabs(modal) {
    PM_TABS.forEach((t) => {
      modal.querySelector(`[data-pm-tab="${t}"]`)?.addEventListener("click", function () {
        if (this.disabled) return;
        switchTab(modal, t);
      });
    });
  }

  // ------------------------------------------------------------------
  // IDENTITY FORM: reset / hydrate / save
  // ------------------------------------------------------------------
  function resetIdentityForm(modal) {
    modal.querySelector("#pmIdentityForm").reset();
    // form.reset() reverts the real underlying <select>s but never touches
    // the searchable-select text-input overlay - refresh both so they don't
    // keep showing a stale previously-typed label.
    refreshSearchable(modal.querySelector("#pmTypeOfBusiness"));
    deriveClientTypeFromBusinessType(modal);
    modal.querySelector("#pmSourceDetailSummary").classList.add("hidden");
    modal.querySelector("#pmSourceSocialPlatform").value = "";
    modal.querySelectorAll(".pm-social-platform-btn").forEach((b) => b.classList.remove("border-orange-500"));
    modal.querySelector("#pmAttendingCsr").textContent = modal.dataset.currentUserName || "-";
    modal.querySelector("#pmLocationsContainer").innerHTML = "";
    closeAllSourcePopovers(modal);
    addLocationCard(modal);
  }

  async function hydrateIdentityForm(modal, lead) {
    const company = lead.company ?? {};
    modal.querySelector("#pmCompanyName").value = company.company_name ?? "";
    modal.querySelector("#pmTypeOfBusiness").value = company.type_of_business ?? "";
    refreshSearchable(modal.querySelector("#pmTypeOfBusiness"));
    deriveClientTypeFromBusinessType(modal);

    modal.querySelector("#pmAttendingCsr").textContent = lead.user?.name ?? modal.dataset.currentUserName ?? "-";

    decodeSourceIntoForm(modal, lead.source);

    const wrap = modal.querySelector("#pmLocationsContainer");
    wrap.innerHTML = "";
    const addresses = lead.addresses?.length ? lead.addresses : [{ is_primary: true }];
    for (const address of addresses) {
      const card = await addLocationCard(modal);
      await hydrateLocationCard(card, address);
    }
  }

  async function saveIdentity(modal, button) {
    const companyName = modal.querySelector("#pmCompanyName").value.trim();
    if (!companyName) {
      showMessage({ status: "error", title: "Prospect Name is required." });
      return;
    }

    const source = encodeSource(modal);
    if (!source) {
      showMessage({ status: "error", title: "Select a Prospect Source." });
      return;
    }

    const locations = collectLocations(modal);
    if (!locations.length) {
      showMessage({ status: "error", title: "Add at least one Location." });
      return;
    }

    const typeOfBusiness = modal.querySelector("#pmTypeOfBusiness").value;
    if (!typeOfBusiness) {
      showMessage({ status: "error", title: "Select a Business Type." });
      return;
    }

    const payload = {
      source,
      company_name: companyName,
      type_of_business: typeOfBusiness,
      addresses: locations,
    };
    if (currentProspectUuid) payload.uuid = currentProspectUuid;

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: "/api/crm/prospects/stage1",
      button,
    });

    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Saving",
        message: response.message ?? "Please check the required fields.",
      });
      return;
    }

    currentProspectUuid = response.data.uuid;
    window.currentProspectUuid = currentProspectUuid;

    // Tab 2's per-contact address checklist is sourced from this prospect's
    // own locations - keep it in sync with whatever the Identity tab just
    // saved (locations may have been added/removed this save).
    currentProspectLocations = response.data.addresses ?? [];
    refreshContactLocationOptions(modal);

    showMessage({ status: "success", title: "Prospect Identity Saved" });

    modal.querySelector("#pmTitle").textContent = "Edit Prospect";
    modal.querySelector("#pmSubtitle").textContent = companyName;

    // Best-effort refresh of the CRM list behind the modal. Used to also
    // fire a no-op call into the LeadInfoModal loader as a side effect of
    // window.reloadCrmData() - that modal (and the dead-code call) was
    // removed, so this now only re-renders the table/counts.
    window.reloadCrmData?.();

    await refreshStageCompletion(modal);
  }

  async function refreshStageCompletion(modal) {
    if (!currentProspectUuid) return;
    const response = await apiCall({ mode: "GET", url: `/api/crm/prospects/${currentProspectUuid}` });
    if (!response.success) return;
    updateTabGating(modal, response.data.stage_completion);
  }

  // ------------------------------------------------------------------
  // DRAFT CACHE + MINIMIZE (multiple simultaneous New Prospect forms)
  // ------------------------------------------------------------------
  // Lets a CSR start several new prospects and switch between them without
  // losing anything, by caching not-yet-saved Identity/Contact tab input in
  // localStorage rather than only in the single live DOM instance this app
  // renders (see initProspectModal()). Once a prospect has a real uuid
  // (Identity has been saved at least once), reopening it always re-fetches
  // from the server via the existing openProspectModal(uuid) path - the
  // local snapshot is only ever the source of truth *before* that first
  // save. Requirements-tab rows are deliberately never part of this cache:
  // each row is already written to the database the moment "+ Add
  // Requirement" is clicked, so there is no unsaved requirements state to
  // lose in the first place.
  const DRAFT_STORE_KEY = "kargamine_prospect_drafts";
  let currentDraftId = null;
  let openingFromDraftDock = false;

  function loadDraftStore() {
    try {
      return JSON.parse(localStorage.getItem(DRAFT_STORE_KEY) || "{}");
    } catch (e) {
      return {};
    }
  }
  function saveDraftStore(store) {
    localStorage.setItem(DRAFT_STORE_KEY, JSON.stringify(store));
  }

  function draftLabel(entry) {
    return (entry.snapshot?.companyName || "").trim() || entry.autoLabel || "New Prospect";
  }

  function captureDraftSnapshot(modal) {
    return {
      companyName: modal.querySelector("#pmCompanyName").value,
      typeOfBusiness: modal.querySelector("#pmTypeOfBusiness").value,
      clientType: modal.querySelector("#pmClientType").value,
      source: encodeSource(modal),
      addresses: collectLocations(modal),
      contacts: collectContacts(modal),
    };
  }

  async function restoreDraftSnapshot(modal, snapshot) {
    const lead = {
      company: { company_name: snapshot.companyName, type_of_business: snapshot.typeOfBusiness },
      client_type: snapshot.clientType,
      source: snapshot.source,
      addresses: snapshot.addresses?.length ? snapshot.addresses : [],
      contacts: snapshot.contacts ?? [],
    };
    await hydrateIdentityForm(modal, lead);
    currentProspectLocations = lead.addresses;
    hydrateContactsTab(modal, lead);
  }

  // Only company name / source / a real address field / a real contact
  // name counts as "something to lose" - a freshly-reset form already has
  // default values (address_type "Main Office", country "Philippines") that
  // would otherwise falsely look like unsaved input.
  const MEANINGFUL_ADDRESS_FIELDS = [
    "address_no", "address_building", "address_street",
    "address_province", "address_town_city", "address_barangay", "address_postal_code",
  ];
  function snapshotHasMeaningfulData(snap) {
    if (!snap) return false;
    const hasAddress = (snap.addresses ?? []).some((a) =>
      MEANINGFUL_ADDRESS_FIELDS.some((f) => (a[f] || "").toString().trim())
    );
    const hasContact = (snap.contacts ?? []).some((c) => c.first_name || c.last_name || c.channels?.length);
    return Boolean((snap.companyName || "").trim() || snap.source || hasAddress || hasContact);
  }

  function hasUnsavedDraftData(modal) {
    if (currentProspectUuid) return false; // already in the database
    return snapshotHasMeaningfulData(captureDraftSnapshot(modal));
  }

  function activeTabId(modal) {
    return modal.querySelector(".pm-tab-pane:not(.hidden)")?.dataset.pmPane || "Identity";
  }

  // ProspectModal is a centered <x-modal> (not <x-side-modal>) so drafts can
  // be visually interchanged like separate windows rather than a single
  // drawer. Shown/hidden by hand here (not window.initModal()) because that
  // helper re-binds close-button listeners with addEventListener on every
  // call without ever removing the previous ones - harmless for a plain
  // once-per-visit modal, but would stack a second, confirm-less close
  // handler on top of bindProspectModalCloseOverride() below every time this
  // modal reopens. window.closeModal() (the plain-modal closer, scoped to
  // just this modal's own inputs) is still reused as-is for a real close.
  function showProspectModal(modal) {
    modal.classList.remove("hidden");
    document.body.style.position = "fixed";
    document.body.style.top = `-${window.scrollY}px`;
    document.body.style.left = "0";
    document.body.style.right = "0";
    document.body.style.overflow = "hidden";
  }

  // Counts other-than-this-one open .modal elements, mirroring
  // customFunctions.js's own checkopenmodal() - that function isn't
  // actually reachable from here (it's a bare top-level declaration inside
  // a Vite-bundled ES module, so unlike window.initModal()/window.closeModal()
  // it never became a real global - calling it threw a ReferenceError that
  // silently aborted hideProspectModalForMinimize() before it ever reached
  // the dock re-render, which was the root cause of minimized drafts not
  // showing up until some unrelated action re-rendered the dock).
  function countOpenModals() {
    return Array.from(document.querySelectorAll(".modal")).filter((m) => !m.classList.contains("hidden")).length;
  }

  // Hides without resetting any fields - minimize must never lose data,
  // unlike a real close (window.closeModal()).
  function hideProspectModalForMinimize(modal) {
    modal.classList.add("hidden");
    if (countOpenModals() > 0) return;
    document.body.style.position = "";
    document.body.style.top = "";
    document.body.style.left = "";
    document.body.style.right = "";
    document.body.style.overflow = "";
  }

  function setProspectModalLoading(modal, isLoading) {
    modal.querySelector("#pmLoadingOverlay")?.classList.toggle("hidden", !isLoading);
  }

  // Bumped every time a fresh open/reopen starts hydrating data in the
  // background (server GET or local draft-snapshot restore). The async work
  // checks this before touching the DOM when it resolves - if the user
  // clicked a different tab in the meantime, an older/slower response is
  // simply discarded instead of clobbering whatever loaded faster after it.
  let prospectLoadToken = 0;

  // Snapshots whichever draft is currently live in the single DOM instance
  // (if any) - called right before switching to a different draft and
  // before minimizing, so nothing typed is lost.
  function persistCurrentDraftIfAny(modal) {
    if (!currentDraftId) return;
    const store = loadDraftStore();
    const entry = store[currentDraftId];
    if (!entry) return;
    entry.uuid = currentProspectUuid;
    entry.snapshot = captureDraftSnapshot(modal);
    entry.activeTab = activeTabId(modal);
    entry.lastLocalSave = Date.now();
    saveDraftStore(store);
  }

  function escapeHtmlText(s) {
    const d = document.createElement("div");
    d.textContent = s ?? "";
    return d.innerHTML;
  }

  function renderDraftDock() {
    const dock = document.getElementById("pmDraftDock");
    if (!dock) return;
    const store = loadDraftStore();
    const ids = Object.keys(store).filter((id) => store[id].minimized);
    dock.innerHTML = "";
    ids.forEach((id) => {
      const entry = store[id];
      const dirty = !entry.uuid && snapshotHasMeaningfulData(entry.snapshot);
      const pill = document.createElement("div");
      pill.className =
        "flex items-center gap-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 " +
        "rounded-t-lg shadow-lg shadow-black/10 dark:shadow-black/30 px-3 py-2 text-xs font-medium " +
        "text-zinc-700 dark:text-zinc-200 max-w-[12rem] shrink-0";
      pill.innerHTML =
        (dirty ? '<span class="w-1.5 h-1.5 rounded-full bg-orange-500 shrink-0" title="Unsaved changes cached locally"></span>' : "") +
        `<span class="truncate cursor-pointer">${escapeHtmlText(draftLabel(entry))}</span>` +
        '<button type="button" class="text-zinc-400 hover:text-red-500 text-[10px] shrink-0" title="Discard draft">✕</button>';
      pill.querySelector("span.truncate").addEventListener("click", () => reopenDraft(id));
      pill.querySelector("button").addEventListener("click", (e) => {
        e.stopPropagation();
        discardDraft(id);
      });
      dock.appendChild(pill);
    });
    dock.classList.toggle("hidden", ids.length === 0);
  }

  function updateDraftIndicator(modal) {
    const el = modal.querySelector("#pmDraftIndicator");
    if (!el) return;
    if (!currentDraftId) {
      el.classList.add("hidden");
      return;
    }
    const entry = loadDraftStore()[currentDraftId];
    if (!entry) {
      el.classList.add("hidden");
      return;
    }
    el.classList.remove("hidden");
    if (entry.uuid) {
      el.textContent = "This prospect is already saved to the database.";
    } else if (entry.lastLocalSave) {
      const when = new Date(entry.lastLocalSave).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
      el.textContent = `Draft cached in this browser · ${when} · not yet in the database`;
    } else {
      el.textContent = "Not saved yet.";
    }
  }

  async function reopenDraft(id) {
    const modal = document.getElementById("ProspectModal");
    if (!modal) return;
    persistCurrentDraftIfAny(modal);

    const store = loadDraftStore();
    const entry = store[id];
    if (!entry) return;
    entry.minimized = false;
    saveDraftStore(store);
    currentDraftId = id;

    if (entry.uuid) {
      openingFromDraftDock = true;
      await openProspectModal(entry.uuid);
      return; // openProspectModal() itself finishes wiring + renders the dock
    }

    currentProspectUuid = null;
    window.currentProspectUuid = null;
    currentProspectLocations = [];
    resetIdentityForm(modal);
    resetContactsForm(modal);
    resetRequirementsForm(modal);
    switchTab(modal, entry.activeTab || "Identity");
    updateTabGating(modal, {});
    modal.querySelector("#pmTitle").textContent = "New Prospect";
    modal.querySelector("#pmSubtitle").textContent = "Fill in the Prospect Identity tab to get started.";

    // Show instantly, then fill in the restored fields (including the
    // province/city/barangay cascade, which itself does a network lookup)
    // in the background - same "never wait to see the modal" rule as
    // openProspectModal().
    showProspectModal(modal);
    bindProspectModalCloseOverride(modal);
    updateDraftIndicator(modal);
    renderDraftDock();

    const myToken = ++prospectLoadToken;
    setProspectModalLoading(modal, true);
    try {
      await restoreDraftSnapshot(modal, entry.snapshot || {});
    } catch (e) {
      if (myToken === prospectLoadToken) {
        showMessage({
          status: "error",
          title: "Error Loading Draft",
          message: "Something went wrong restoring this draft. Please try again.",
        });
      }
    }
    if (myToken !== prospectLoadToken) return;
    setProspectModalLoading(modal, false);
  }

  function discardDraft(id) {
    const store = loadDraftStore();
    delete store[id];
    saveDraftStore(store);
    if (currentDraftId === id) {
      currentDraftId = null;
      window.closeModal("ProspectModal");
    }
    renderDraftDock();
  }

  // One atomic read-modify-write against a single loaded copy of the store,
  // so the entry is always created (if it didn't already exist - e.g. the
  // first minimize of a prospect opened via row click, not the dock) *and*
  // always ends up flagged minimized - previously these were two separate
  // load/save round-trips (one to ensure the entry existed, one via
  // persistCurrentDraftIfAny(), one more to flip the flag), and a thrown
  // error capturing the snapshot in the middle could abort the function
  // before the flag/re-render ever ran, leaving the tab silently missing
  // from the dock until some other action (e.g. reopening a different
  // draft) happened to call renderDraftDock() again.
  function minimizeCurrentDraft() {
    const modal = document.getElementById("ProspectModal");
    if (!modal) return;

    const store = loadDraftStore();
    if (!currentDraftId) {
      currentDraftId = currentProspectUuid || `local_${Date.now()}`;
    }
    if (!store[currentDraftId]) {
      store[currentDraftId] = {
        id: currentDraftId,
        uuid: currentProspectUuid || null,
        autoLabel: `New Prospect ${Object.keys(store).length + 1}`,
        activeTab: "Identity",
        minimized: false,
      };
    }

    try {
      store[currentDraftId].uuid = currentProspectUuid;
      store[currentDraftId].snapshot = captureDraftSnapshot(modal);
      store[currentDraftId].activeTab = activeTabId(modal);
      store[currentDraftId].lastLocalSave = Date.now();
    } catch (e) {
      // A snapshot failure should never stop this draft from actually
      // showing up in the dock - worst case it reopens without whatever
      // wasn't captured, instead of vanishing entirely.
      console.warn("Could not snapshot this draft before minimizing", e);
    }

    store[currentDraftId].minimized = true;
    saveDraftStore(store);

    // Hide without the generic confirm-then-reset close path - the whole
    // point of minimizing is that nothing gets discarded or wiped.
    hideProspectModalForMinimize(modal);
    renderDraftDock();
  }

  // Binds the close (✕) button's handler directly (not via window.initModal()
  // - see showProspectModal()'s comment for why). Unlike the generic plain
  // modal close, this only warns when there's real unsaved-and-not-yet-in-
  // the-database data, says plainly what's at stake, and discards this
  // draft's local cache entry on confirm - a true close (unlike minimize)
  // really does throw the draft away.
  function bindProspectModalCloseOverride(modal) {
    const closeBtn = modal.querySelector(".modal-close");
    if (!closeBtn) return;
    closeBtn.onclick = async function () {
      // Only a not-yet-saved prospect actually has a "draft" to discard -
      // closing an already-saved one should never touch its dock entry
      // (there previously was no such guard here, so plainly closing a
      // saved prospect that had been minimized before would silently wipe
      // its entry for no reason, breaking future minimize/reopen for it).
      const isUnsavedDraft = !currentProspectUuid;
      if (isUnsavedDraft && hasUnsavedDraftData(modal)) {
        const confirmed = await customConfirm(
          "Discard this draft? Anything not saved to the database will be lost."
        );
        if (!confirmed) return;
      }
      if (isUnsavedDraft && currentDraftId) {
        const store = loadDraftStore();
        delete store[currentDraftId];
        saveDraftStore(store);
        currentDraftId = null;
        renderDraftDock();
      }
      window.closeModal("ProspectModal");
    };
  }

  // ------------------------------------------------------------------
  // PUBLIC API
  // ------------------------------------------------------------------
  async function openProspectModal(uuid) {
    const modal = document.getElementById("ProspectModal");
    if (!modal) return;

    currentProspectUuid = uuid || null;
    window.currentProspectUuid = currentProspectUuid;
    currentProspectLocations = [];

    // Keep the draft-dock key in sync: reopening from the dock already set
    // currentDraftId itself (see reopenDraft()); any other entry point
    // (row click, "+ New Prospect") starts a fresh one, keyed by the real
    // uuid once one exists so it lines up with however this same record
    // might later be reopened from the dock.
    if (!openingFromDraftDock) {
      currentDraftId = currentProspectUuid || null;
    }
    openingFromDraftDock = false;

    resetIdentityForm(modal);
    resetContactsForm(modal);
    resetRequirementsForm(modal);
    switchTab(modal, "Identity");
    updateTabGating(modal, {});

    if (currentProspectUuid) {
      modal.querySelector("#pmTitle").textContent = "Edit Prospect";
      modal.querySelector("#pmSubtitle").textContent = "Loading…";
    } else {
      modal.querySelector("#pmTitle").textContent = "New Prospect";
      modal.querySelector("#pmSubtitle").textContent = "Fill in the Prospect Identity tab to get started.";
    }

    // Show instantly - a click should never wait on a network round-trip
    // just to see the modal open, especially with several draft tabs to
    // click between in quick succession (see the dock's reopenDraft()).
    showProspectModal(modal);
    bindProspectModalCloseOverride(modal);
    updateDraftIndicator(modal);
    renderDraftDock();

    if (currentProspectUuid) {
      const myToken = ++prospectLoadToken;
      setProspectModalLoading(modal, true);

      const response = await apiCall({ mode: "GET", url: `/api/crm/prospects/${currentProspectUuid}` });

      // A different draft was opened/reopened while this was in flight -
      // discard this (now stale) response instead of overwriting whatever
      // loaded after it.
      if (myToken !== prospectLoadToken) return;

      if (response.success) {
        await hydrateIdentityForm(modal, response.data);
        currentProspectLocations = response.data.addresses ?? [];
        hydrateContactsTab(modal, response.data);
        hydrateRequirementsTab(modal, response.data);
        modal.querySelector("#pmSubtitle").textContent = response.data.company?.company_name ?? response.data.contact_name ?? "";
        updateTabGating(modal, response.data.stage_completion);
      } else {
        modal.querySelector("#pmSubtitle").textContent = "Unable to load this prospect.";
        showMessage({
          status: "error",
          title: "Error Loading",
          message: response.message ?? "Unable to load this prospect. Please try again.",
        });
      }

      setProspectModalLoading(modal, false);
    }
  }
  window.openProspectModal = openProspectModal;

  // Row-click router: opens the read-only ProspectInfoModal (see
  // logic_prospect_info_modal.js) once Identity, Contact, and Requirements
  // are all complete; otherwise falls back to this form modal. A brand-new
  // prospect (no uuid, from "+ New Prospect") always goes straight to the
  // form - nothing to check completeness on yet.
  async function openProspectRecord(uuid) {
    if (!uuid) return openProspectModal();

    const response = await apiCall({ mode: "GET", url: `/api/crm/prospects/${uuid}` });
    if (!response.success) return openProspectModal(uuid);

    const complete = response.data.stage_completion;
    const allComplete = Boolean(complete?.[1] && complete?.[2] && complete?.[3]);

    if (allComplete && window.openProspectInfoModal) {
      window.openProspectInfoModal(response.data);
    } else {
      openProspectModal(uuid);
    }
  }
  window.openProspectRecord = openProspectRecord;

  // ------------------------------------------------------------------
  // INIT (called from crm.blade.php's inline <script> on every SPA load)
  // ------------------------------------------------------------------
  function initProspectModal() {
    const modal = document.getElementById("ProspectModal");
    if (!modal) return; // guarded: only runs on pages that actually include the modal

    modal.dataset.currentUserName = modal.querySelector("#pmAttendingCsr")?.textContent?.trim() || "";

    bindTabs(modal);
    bindClientTypeToggle(modal);
    bindSourcePopovers(modal);
    bindContainerFormCascades(modal);

    modal.querySelector("#pmAddLocationBtn").addEventListener("click", () => addLocationCard(modal));

    modal.querySelector("#pmMinimizeBtn").addEventListener("click", () => minimizeCurrentDraft());

    // A plain vertical mouse wheel does nothing on an overflow-x-auto row -
    // same fix as navmenu.js's topnavMenu wheel handler, applied here since
    // the draft dock is its own separate horizontally-scrolling strip.
    // Touch swipe already works natively via overflow-x-auto - nothing
    // extra needed for that.
    const dock = document.getElementById("pmDraftDock");
    if (dock) {
      dock.addEventListener(
        "wheel",
        (e) => {
          if (dock.scrollWidth <= dock.clientWidth) return;
          e.preventDefault();
          dock.scrollLeft += e.deltaY;
        },
        { passive: false }
      );
    }

    renderDraftDock();

    modal.querySelector("#pmSaveIdentityBtn").addEventListener("click", function () {
      saveIdentity(modal, this);
    });

    modal.querySelector("#pmAddContactBtn").addEventListener("click", () => addContactCard(modal));

    modal.querySelector("#pmSaveContactsBtn").addEventListener("click", function () {
      saveContacts(modal, this);
    });

    bindProductSelector(modal);
    modal.querySelector("#pmAddCharterCargoBtn").addEventListener("click", () => addCharterCargoRow(modal));
    modal.querySelector("#pmAddCharterPortBtn").addEventListener("click", () => addCharterPortRow(modal));
    modal.querySelector("#pmAddRequirementBtn").addEventListener("click", function () {
      const product = modal.querySelector("#pmRequirementProduct").value;
      if (product === "freight") addContainerRow(modal, this);
      else if (product === "trucking") addTruckingRow(modal, this);
      else if (product === "charter") addCharter(modal, this);
    });

    Promise.all([
      fillTypeOfBusiness(modal),
      fillLeadSource(modal),
      fillAddressTypeOptions(),
      fillContactTitleOptions(),
      initRequirementsLookups(modal),
    ]);
  }
  window.initProspectModal = initProspectModal;

  // Read-only access to this file's cached lookups/pure helpers, for the
  // standalone Add Location/Add Contact/Add Requirement modals
  // (logic_prospect_add_modals.js) - they're genuinely separate modals (not
  // a reopened ProspectModal), but re-fetching all this master data again
  // and re-implementing the PSGC cascade would be wasteful/risky
  // duplication when it's already loaded here every SPA visit.
  window.prospectShared = {
    ensureContainerLookupsLoaded,
    locationCardHtml,
    initLocationPsgcCascade,
    channelRowHtml,
    addChannelRow,
    contactCardHtml,
    getAddressTypeOptionsHtml: () => addressTypeOptionsHtml,
    getTitleOptionsHtml: () => titleOptionsHtml,
    getCargoTypeOptionsHtml: () => cargoTypeOptionsHtml,
    getDeliveryTypesOptionsHtml: () => deliveryTypesOptionsHtml,
    getTruckingCargoTypeOptionsHtml: () => truckingCargoTypeOptionsHtml,
    getLocationsOptionsHtml: () => locationsOptionsHtml,
    getPortsOptionsHtml: () => portsOptionsHtml,
    getContainerCatalogByCode: () => containerCatalogByCode,
  };
})();
