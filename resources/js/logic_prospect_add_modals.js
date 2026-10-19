// Three standalone "Add X" modals opened from ProspectInfoModal - Add
// Location, Add Contact, Add Requirement. Deliberately separate modals
// (not a reopened ProspectModal form, per brief), but reuse
// window.prospectShared (see logic_prospect_modal.js's exports) for the
// PSGC cascade, contact-card markup, and cached master-data lookups rather
// than re-fetching/re-implementing them. window.prospectInfo (see
// logic_prospect_info_modal.js) is how these report back - getCurrentLead()
// for the prospect being edited, reload(uuid) to refresh the info modal's
// tabs after a successful save.
(function () {
  // Fixed Service Type list (mirrors logic_prospect_modal.js's Requirements
  // tab - the value IS the label string, POSTed as `service_type`, not an
  // id via the old Delivery Type LOV-backed select).
  const SERVICE_TYPES = ["Door - Door", "Door - Pier", "Pier - Door", "Pier - Pier"];
  const serviceTypeOptionsHtml =
    '<option value="">Select Service Type</option>' +
    SERVICE_TYPES.map((t) => `<option value="${t}">${t}</option>`).join("");

  const CONTAINER_TYPES = [
    { value: "CV", label: "Container Van (CV)" },
    { value: "FR", label: "Flatrack (FR)" },
    { value: "RF", label: "Reefer Van (RF)" },
    { value: "LC", label: "Break Bulk Cargo (BB)" },
    { value: "RC", label: "Rolling Cargo (RC)" },
  ];
  const TYPE_FIELD_VISIBILITY = {
    CV: { convanSize: true, temperature: false, revenueTon: false },
    FR: { convanSize: true, temperature: false, revenueTon: false },
    RF: { convanSize: true, temperature: true, revenueTon: false },
    LC: { convanSize: false, temperature: false, revenueTon: true },
    RC: { convanSize: false, temperature: false, revenueTon: true },
  };

  // Staff-entered free-text address fields get interpolated raw into
  // innerHTML all over this file (chip labels, the O&D preview form) - esc()
  // matches the identical helper in logic_prospect_request_proposal.js /
  // logic_prospect_info_modal.js / logic_prospect_modal.js.
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
    return loc.address_type ? `${esc(loc.address_type)} — ${esc(line)}` : esc(line);
  }

  function refreshSearchable(el) {
    el?._searchableSelect?.refresh();
  }

  function addressesPayload(lead) {
    return (lead.addresses ?? []).map((a) => ({
      address_type: a.address_type,
      is_primary: a.is_primary,
      address_no: a.address_no,
      address_building: a.address_building,
      address_street: a.address_street,
      address_barangay: a.address_barangay,
      address_town_city: a.address_town_city,
      address_province: a.address_province,
      address_country: a.address_country,
      address_postal_code: a.address_postal_code,
    }));
  }

  // ------------------------------------------------------------------
  // ADD LOCATION MODAL
  // ------------------------------------------------------------------
  function openAddLocationModal() {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;

    const wrap = document.getElementById("almLocationCardWrap");
    wrap.innerHTML = window.prospectShared.locationCardHtml(0);
    const card = wrap.firstElementChild;
    card.querySelector(".pm-remove-location")?.remove();

    const typeSelect = card.querySelector('[data-field="address_type"]');
    if (typeSelect) typeSelect.value = "Main Office";

    window.prospectShared.initLocationPsgcCascade(card);
    window.initModal({ modalId: "AddLocationModal" });
  }

  function collectAlmLocation() {
    const card = document.querySelector("#almLocationCardWrap .pm-location-card");
    if (!card) return null;
    const obj = {};
    card.querySelectorAll("[data-field]").forEach((el) => (obj[el.dataset.field] = el.value));
    obj.is_primary = card.querySelector(".pm-primary-radio")?.checked ?? false;
    return obj;
  }

  async function saveAddLocation(button) {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;

    const newLocation = collectAlmLocation();
    if (!newLocation) return;

    const payload = {
      uuid: lead.uuid,
      client_type: lead.client_type || "corporate",
      source: lead.source,
      company_name: lead.company?.company_name ?? "",
      type_of_business: lead.company?.type_of_business ?? null,
      relationship_manager_id: lead.relationship_manager_id ?? null,
      addresses: [...addressesPayload(lead), newLocation],
    };

    const response = await apiCall({ mode: "POST", isJson: true, payload, url: "/api/crm/prospects/stage1", button });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Saving", message: response.message ?? "Please check the required fields." });
      return;
    }

    showMessage({ status: "success", title: "Location Added" });
    await window.prospectInfo.reload(lead.uuid);
    window.closeModal("AddLocationModal");
    window.reloadCrmData?.();
  }

  // ------------------------------------------------------------------
  // ADD CONTACT MODAL
  // ------------------------------------------------------------------
  function openAddContactModal() {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;

    const wrap = document.getElementById("acmContactCardWrap");
    wrap.innerHTML = window.prospectShared.contactCardHtml();
    const card = wrap.firstElementChild;
    card.querySelector(".pm-remove-contact")?.remove();
    card.querySelector(".pm-add-channel")?.addEventListener("click", () => window.prospectShared.addChannelRow(card));
    window.prospectShared.addChannelRow(card);
    makeSearchableSelect(card.querySelector(".pmContactTitleDropdown"));

    const addressWrap = card.querySelector(".pm-contact-addresses");
    const locations = lead.addresses ?? [];
    addressWrap.innerHTML = locations.length
      ? `<select class="acmContactLocationSelect w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
          <option value="">No address</option>
          ${locations.map((loc) => `<option value="${loc.id}">${formatLocationLabel(loc)}</option>`).join("")}
        </select>`
      : `<p class="text-xs text-zinc-500 dark:text-zinc-400">No locations yet - add one on the Locations tab first.</p>`;
    const addressSelect = addressWrap.querySelector("select");
    if (addressSelect) makeSearchableSelect(addressSelect);

    window.initModal({ modalId: "AddContactModal" });
  }

  function collectAcmContact() {
    const card = document.querySelector("#acmContactCardWrap .pm-contact-card");
    if (!card) return null;
    const get = (field) => card.querySelector(`[data-field="${field}"]`)?.value.trim() || "";
    const PHONE_CHANNEL_TYPES = ["mobile", "landline"];
    const channels = Array.from(card.querySelectorAll(".pm-channel-row"))
      .map((row) => {
        const channel_type = row.querySelector('[data-field="channel_type"]').value;
        const rawValue = row.querySelector('[data-field="value"]').value.trim();
        return {
          channel_type,
          // Phone numbers are stored digits-only - strip the live formatting
          // sugar before submitting; email values are left untouched.
          value: PHONE_CHANNEL_TYPES.includes(channel_type) ? parsePhoneValue(rawValue) : rawValue,
          contact_type: row.querySelector('[data-field="contact_type"]').value,
        };
      })
      .filter((ch) => ch.value);
    const locationSelect = card.querySelector(".acmContactLocationSelect");
    const location_ids = locationSelect?.value ? [Number(locationSelect.value)] : [];

    return {
      title: get("title") || null,
      first_name: get("first_name"),
      middle_name: get("middle_name") || null,
      last_name: get("last_name"),
      position: get("position") || null,
      channels,
      location_ids,
    };
  }

  async function saveAddContact(button) {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;

    const newContact = collectAcmContact();
    if (!newContact || !newContact.first_name || !newContact.last_name) {
      showMessage({ status: "error", title: "A First Name and Last Name are required." });
      return;
    }

    const existingContacts = (lead.contacts ?? []).map((c) => ({
      title: c.title,
      first_name: c.first_name,
      middle_name: c.middle_name,
      last_name: c.last_name,
      position: c.position,
      channels: (c.channels ?? []).map((ch) => ({ channel_type: ch.channel_type, value: ch.value, contact_type: ch.contact_type })),
      location_ids: c.location_ids ?? [],
    }));

    const payload = { contacts: [...existingContacts, newContact] };

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${lead.uuid}/contacts`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Saving", message: response.message ?? "Please check the required fields." });
      return;
    }

    showMessage({ status: "success", title: "Contact Added" });
    await window.prospectInfo.reload(lead.uuid);
    window.closeModal("AddContactModal");
  }

  // ------------------------------------------------------------------
  // ADD FREIGHT / ADD TRUCKING / ADD CHARTER MODALS - three separate
  // popups, one per Requirements-tab table (mirrors ProspectModal's own
  // Requirements tab redesign - no shared product picker, no duplicate
  // "live" tables with their own remove actions inside the popup; the
  // read-only tables on the Requirements tab itself now own Remove
  // directly, see removeRequirementRow()/renderRequirementsSummary() in
  // logic_prospect_info_modal.js). All three target the same `arm*` field
  // ids ProspectModal's own Requirements-tab popups don't use, so neither
  // set of ids collides with the other even though both modals can exist
  // in the DOM at once.
  // ------------------------------------------------------------------
  let armLocationsOptionsHtml = "";

  function applyArmLookups() {
    document.getElementById("armCargoType").innerHTML = window.prospectShared.getCargoTypeOptionsHtml();
    document.getElementById("armDeliveryType").innerHTML = serviceTypeOptionsHtml;
    document.getElementById("armTruckingCargoType").innerHTML = window.prospectShared.getTruckingCargoTypeOptionsHtml();
    document.getElementById("armTruckingCargoTypeGeneral").innerHTML = window.prospectShared.getCargoTypeOptionsHtml();

    armLocationsOptionsHtml = window.prospectShared.getLocationsOptionsHtml();
    ["armOriginLocation", "armDestinationLocation", "armTruckingOrigin", "armTruckingDestination"].forEach((id) => {
      const el = document.getElementById(id);
      el.innerHTML = `<option value="">Select Location</option>${armLocationsOptionsHtml}`;
      makeSearchableSelect(el);
    });
  }

  function applyArmContainerTypeVisibility() {
    const type = document.getElementById("armContainerType").value;
    const flags = TYPE_FIELD_VISIBILITY[type] || {};
    document.querySelector(".field-convan-size")?.classList.toggle("hidden", !flags.convanSize);
    document.querySelector(".field-temperature")?.classList.toggle("hidden", !flags.temperature);
    document.querySelectorAll(".field-revenue-ton").forEach((el) => el.classList.toggle("hidden", !flags.revenueTon));
  }

  function populateArmContainerClassSizeOptions() {
    const type = document.getElementById("armContainerType").value;
    const catalog = window.prospectShared.getContainerCatalogByCode()[type] ?? { sizes: [], classes: [] };
    const sizeSelect = document.getElementById("armContainerSize");
    sizeSelect.innerHTML =
      '<option value="">Select Size</option>' + catalog.sizes.map((s) => `<option value="${s.id}">${s.size}</option>`).join("");
  }

  function collectArmFormPayload(formSelector) {
    const form = document.querySelector(formSelector);
    const obj = {};
    form.querySelectorAll("[data-field]").forEach((el) => {
      obj[el.dataset.field] = el.classList.contains("currency-input") ? parseCurrencyValue(el.value) : el.value;
    });
    return obj;
  }

  function resetArmForm(formSelector) {
    const form = document.querySelector(formSelector);
    form.querySelectorAll("input[type=text], input[type=number], textarea").forEach((el) => (el.value = ""));
    form.querySelectorAll("select").forEach((el) => (el.selectedIndex = 0));
  }

  // Cargo/Port repeatable rows (Charter pane)
  function charterCargoRowHtml() {
    return `
    <div class="arm-charter-cargo-row flex items-start gap-2">
        <select data-field="cargo_type" class="w-40 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            ${window.prospectShared.getCargoTypeOptionsHtml()}
        </select>
        <input type="text" data-field="general_cargo_description" placeholder="Cargo description" class="flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
        <input type="text" data-field="special_requirements" placeholder="Special requirements" class="flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
        <button type="button" class="arm-remove-charter-cargo text-red-500 text-xs font-medium shrink-0">✕</button>
    </div>`;
  }

  function addArmCharterCargoRow() {
    const wrap = document.getElementById("armCharterCargoContainer");
    wrap.insertAdjacentHTML("beforeend", charterCargoRowHtml());
    wrap.lastElementChild.querySelector(".arm-remove-charter-cargo").addEventListener("click", (e) => e.target.closest(".arm-charter-cargo-row").remove());
  }

  function charterPortRowHtml() {
    return `
    <div class="arm-charter-port-row flex items-center gap-2">
        <span class="arm-charter-port-label text-xs font-semibold text-zinc-500 dark:text-zinc-400 w-16 shrink-0">PORT</span>
        <select data-field="port_id" class="armCharterPortSelect flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            <option value="">Select Port</option>${window.prospectShared.getPortsOptionsHtml()}
        </select>
        <button type="button" class="arm-remove-charter-port text-red-500 text-xs font-medium shrink-0">✕</button>
    </div>`;
  }

  function renumberArmCharterPortRows() {
    document.querySelectorAll("#armCharterPortsContainer .arm-charter-port-row").forEach((row, index) => {
      row.querySelector(".arm-charter-port-label").textContent = `PORT ${index + 1}`;
    });
  }

  function addArmCharterPortRow() {
    const wrap = document.getElementById("armCharterPortsContainer");
    wrap.insertAdjacentHTML("beforeend", charterPortRowHtml());
    const row = wrap.lastElementChild;
    makeSearchableSelect(row.querySelector(".armCharterPortSelect"));
    row.querySelector(".arm-remove-charter-port").addEventListener("click", () => {
      row.remove();
      renumberArmCharterPortRows();
    });
    renumberArmCharterPortRows();
  }

  function resetArmCharterForm() {
    document.getElementById("armCharterStartDate").value = "";
    document.getElementById("armCharterEndDate").value = "";
    document.getElementById("armCharterDeclaredValue").value = "";
    document.getElementById("armCharterWeight").value = "";
    document.getElementById("armCharterWeightUnit").selectedIndex = 0;
    document.getElementById("armCharterServiceId").innerHTML =
      `<option value="">Select Charter Type (optional)</option>${window.prospectShared.getCharterServiceOptionsHtml()}`;
    document.getElementById("armCharterCargoContainer").innerHTML = "";
    addArmCharterCargoRow();
    document.getElementById("armCharterPortsContainer").innerHTML = "";
    addArmCharterPortRow();
  }

  function collectArmCharterPayload() {
    const cargo = Array.from(document.querySelectorAll(".arm-charter-cargo-row")).map((row) => ({
      cargo_type: row.querySelector('[data-field="cargo_type"]').value,
      general_cargo_description: row.querySelector('[data-field="general_cargo_description"]').value,
      special_requirements: row.querySelector('[data-field="special_requirements"]').value,
    }));
    const ports = Array.from(document.querySelectorAll(".arm-charter-port-row")).map((row) => ({
      port_id: row.querySelector('[data-field="port_id"]').value,
    }));
    return {
      charter_start_date: document.getElementById("armCharterStartDate").value,
      charter_end_date: document.getElementById("armCharterEndDate").value,
      declared_value: parseCurrencyValue(document.getElementById("armCharterDeclaredValue").value),
      weight: parseCurrencyValue(document.getElementById("armCharterWeight").value),
      weight_unit: document.getElementById("armCharterWeightUnit").value,
      charter_service_id: document.getElementById("armCharterServiceId").value || null,
      cargo,
      ports,
    };
  }

  // Opens <x-pim-add-freight-modal /> with a freshly-reset form.
  async function openPimAddFreightModal() {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;

    await window.prospectShared.ensureContainerLookupsLoaded();
    applyArmLookups();

    const containerTypeSelect = document.getElementById("armContainerType");
    if (!containerTypeSelect.dataset.filled) {
      containerTypeSelect.insertAdjacentHTML(
        "beforeend",
        CONTAINER_TYPES.map((t) => `<option value="${t.value}">${t.label}</option>`).join("")
      );
      containerTypeSelect.dataset.filled = "1";
    }

    resetArmForm("#armContainerForm");
    applyArmContainerTypeVisibility();
    populateArmContainerClassSizeOptions();

    window.initModal({ modalId: "PimAddFreightModal" });
  }

  async function savePimFreight(button) {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;

    const payload = collectArmFormPayload("#armContainerForm");
    const typeSelect = document.getElementById("armContainerType");
    payload.booking_unit_type = typeSelect.options[typeSelect.selectedIndex]?.textContent ?? "";

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${lead.uuid}/proposalRequest/containers`,
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

    showMessage({ status: "success", title: "Freight Added" });
    resetArmForm("#armContainerForm");
    applyArmContainerTypeVisibility();
    populateArmContainerClassSizeOptions();
    await window.prospectInfo.reload(lead.uuid);
    window.closeModal("PimAddFreightModal");
  }

  // Opens <x-pim-add-trucking-modal /> with a freshly-reset form.
  async function openPimAddTruckingModal() {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;

    await window.prospectShared.ensureContainerLookupsLoaded();
    applyArmLookups();
    resetArmForm("#armTruckingForm");

    window.initModal({ modalId: "PimAddTruckingModal" });
  }

  async function savePimTrucking(button) {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;

    const payload = collectArmFormPayload("#armTruckingForm");

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${lead.uuid}/proposalRequest/truckings`,
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

    showMessage({ status: "success", title: "Trucking Added" });
    resetArmForm("#armTruckingForm");
    await window.prospectInfo.reload(lead.uuid);
    window.closeModal("PimAddTruckingModal");
  }

  // Opens <x-pim-add-charter-modal /> with a freshly-reset form.
  async function openPimAddCharterModal() {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;

    await window.prospectShared.ensureContainerLookupsLoaded();
    resetArmCharterForm();

    window.initModal({ modalId: "PimAddCharterModal" });
  }

  async function savePimCharter(button) {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;

    const payload = collectArmCharterPayload();

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${lead.uuid}/proposalRequest/charters`,
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

    showMessage({ status: "success", title: "Charter Added" });
    resetArmCharterForm();
    await window.prospectInfo.reload(lead.uuid);
    window.closeModal("PimAddCharterModal");
  }

  // ------------------------------------------------------------------
  // ADD ORIGIN/DESTINATION LOCATION MODAL
  // ------------------------------------------------------------------
  // Chip-selected prospect address (if any) seeding the form's fields - a
  // pure convenience autofill source and an optional "seeded from"
  // reference sent alongside Save. The form itself is always editable and
  // clicking a chip never removes it from the suggestion list (each Save
  // creates its own independent snapshot entry). Reset every time the
  // modal is opened.
  let aodmSelectedLocationId = null;
  // The single live address-form card for this modal - see ensureAodmCard().
  let aodmCard = null;

  // Builds the single live address-form card using the same shared helpers
  // AddLocationModal already uses for exactly this "one-off editable
  // address, not a repeatable list" shape. Trimmed of the Remove button
  // (nothing to remove) and the Primary radio (doesn't apply to an O&D
  // entry). Field markup kept identical to the Request for Proposal
  // wizard's own equivalent popup (see logic_prospect_request_proposal.js's
  // ensureRpmOdCard()).
  function ensureAodmCard() {
    const wrap = document.getElementById("aodmCardWrap");
    if (aodmCard && wrap.contains(aodmCard)) return aodmCard;

    wrap.innerHTML = window.prospectShared.locationCardHtml(0);
    const card = wrap.firstElementChild;
    card.querySelector(".pm-remove-location")?.remove();
    card.querySelector(".pm-primary-radio")?.closest("label")?.remove();
    window.prospectShared.initLocationPsgcCascade(card);
    aodmCard = card;
    return card;
  }

  // Autofills the card from a clicked suggestion chip's address - mirrors
  // logic_prospect_modal.js's hydrateLocationCard() (not exported via
  // window.prospectShared, so replicated here; kept identical to
  // logic_prospect_request_proposal.js's hydrateOdCard()).
  async function hydrateAodmCard(card, loc) {
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
        if (loc.address_town_city && citySelect) citySelect.value = loc.address_town_city;
        if (loc.address_barangay && barangaySelect) barangaySelect.value = loc.address_barangay;
      }
    }
  }

  function collectAodmCard(card) {
    const obj = {};
    card.querySelectorAll("[data-field]").forEach((el) => {
      obj[el.dataset.field] = el.value;
    });
    return obj;
  }

  // Suggestion chips for ALL of this prospect's own addresses - never
  // filtered by what's already been added (each Save creates its own
  // independent snapshot entry, so the same seed address can legitimately
  // start more than one). Clicking a chip is a pure convenience autofill;
  // Save works even with no chip ever clicked (manual entry).
  function renderAodmChips(addresses) {
    const chipsWrap = document.getElementById("aodmChips");
    document.getElementById("aodmChipsEmpty").classList.toggle("hidden", addresses.length > 0);
    chipsWrap.innerHTML = addresses
      .map((loc) => {
        const active = loc.id === aodmSelectedLocationId;
        const activeClasses = active
          ? "border-orange-500 text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-950/20"
          : "border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 hover:border-orange-400 dark:hover:border-orange-500";
        return `<button type="button" class="aodm-chip px-3 py-1.5 rounded-lg border text-sm font-medium ${activeClasses}" data-id="${loc.id}" aria-pressed="${active}">${formatLocationLabel(loc)}</button>`;
      })
      .join("");

    chipsWrap.querySelectorAll(".aodm-chip").forEach((btn) => {
      btn.addEventListener("click", async () => {
        aodmSelectedLocationId = Number(btn.dataset.id);
        renderAodmChips(addresses);
        const loc = addresses.find((a) => a.id === aodmSelectedLocationId);
        if (loc) await hydrateAodmCard(ensureAodmCard(), loc);
      });
    });
  }

  function openAddOriginDestinationModal() {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;

    aodmSelectedLocationId = null;
    aodmCard = null;

    ensureAodmCard();
    renderAodmChips(lead.addresses ?? []);

    window.initModal({ modalId: "AddOriginDestinationModal" });
  }

  async function saveAddOriginDestination(button) {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;

    if (!lead.proposal_request?.id) {
      showMessage({ status: "error", title: "No draft proposal request to add this location to." });
      return;
    }

    const payload = { ...collectAodmCard(ensureAodmCard()), prospect_location_id: aodmSelectedLocationId };

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${lead.proposal_request.id}/locations`,
      button,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Adding", message: response.message ?? "" });
      return;
    }

    showMessage({ status: "success", title: "Location Added" });
    await window.prospectInfo.reload(lead.uuid);
    window.closeModal("AddOriginDestinationModal");
  }

  function initAddModals() {
    if (!document.getElementById("AddLocationModal")) return; // guarded: only runs on pages that include these

    document.getElementById("pimAddLocationBtn")?.addEventListener("click", openAddLocationModal);
    document.getElementById("almCancelBtn").addEventListener("click", () => window.closeModal("AddLocationModal"));
    document.getElementById("almSaveBtn").addEventListener("click", function () {
      saveAddLocation(this);
    });

    document.getElementById("pimAddOdBtn")?.addEventListener("click", openAddOriginDestinationModal);
    document.getElementById("aodmCancelBtn")?.addEventListener("click", () => window.closeModal("AddOriginDestinationModal"));
    document.getElementById("aodmSaveBtn")?.addEventListener("click", function () {
      saveAddOriginDestination(this);
    });

    document.getElementById("pimAddContactBtn")?.addEventListener("click", openAddContactModal);
    document.getElementById("acmCancelBtn").addEventListener("click", () => window.closeModal("AddContactModal"));
    document.getElementById("acmSaveBtn").addEventListener("click", function () {
      saveAddContact(this);
    });

    document.getElementById("pimAddFreightBtn")?.addEventListener("click", openPimAddFreightModal);
    document.getElementById("armContainerType")?.addEventListener("change", () => {
      applyArmContainerTypeVisibility();
      populateArmContainerClassSizeOptions();
    });
    document.getElementById("pimAfmCancelBtn")?.addEventListener("click", () => window.closeModal("PimAddFreightModal"));
    document.getElementById("pimAfmSaveBtn")?.addEventListener("click", function () {
      savePimFreight(this);
    });

    document.getElementById("pimAddTruckingBtn")?.addEventListener("click", openPimAddTruckingModal);
    document.getElementById("pimAtmCancelBtn")?.addEventListener("click", () => window.closeModal("PimAddTruckingModal"));
    document.getElementById("pimAtmSaveBtn")?.addEventListener("click", function () {
      savePimTrucking(this);
    });

    document.getElementById("pimAddCharterBtn")?.addEventListener("click", openPimAddCharterModal);
    document.getElementById("armAddCharterCargoBtn")?.addEventListener("click", addArmCharterCargoRow);
    document.getElementById("armAddCharterPortBtn")?.addEventListener("click", addArmCharterPortRow);
    document.getElementById("pimChmCancelBtn")?.addEventListener("click", () => window.closeModal("PimAddCharterModal"));
    document.getElementById("pimChmSaveBtn")?.addEventListener("click", function () {
      savePimCharter(this);
    });
  }
  window.initAddModals = initAddModals;
})();
