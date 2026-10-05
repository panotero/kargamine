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

  const CONTAINER_TYPE_DOT_COLOR = {
    CV: "bg-orange-500",
    FR: "bg-amber-500",
    RF: "bg-cyan-500",
    LC: "bg-purple-500",
    RC: "bg-blue-500",
  };
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
  const WEIGHT_UNIT_LABEL = { kg: "kg", mt: "MT" };
  const DISPATCH_MODE_LABEL = { single: "Single", tandem: "Tandem" };
  const EMPTY = "—";

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
    const channels = Array.from(card.querySelectorAll(".pm-channel-row"))
      .map((row) => ({
        channel_type: row.querySelector('[data-field="channel_type"]').value,
        value: row.querySelector('[data-field="value"]').value.trim(),
        contact_type: row.querySelector('[data-field="contact_type"]').value,
      }))
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
  // ADD REQUIREMENT MODAL - mirrors ProspectModal's Requirements tab
  // (product picker + Freight/Trucking/Charter forms + tables with remove
  // actions), targeting its own `arm*` ids so both can exist in the DOM at
  // once without id collisions.
  // ------------------------------------------------------------------
  let armLocationsOptionsHtml = "";

  function applyArmLookups(modal) {
    modal.querySelector("#armCargoType").innerHTML = window.prospectShared.getCargoTypeOptionsHtml();
    modal.querySelector("#armDeliveryType").innerHTML = serviceTypeOptionsHtml;
    modal.querySelector("#armTruckingCargoType").innerHTML = window.prospectShared.getTruckingCargoTypeOptionsHtml();
    modal.querySelector("#armTruckingCargoTypeGeneral").innerHTML = window.prospectShared.getCargoTypeOptionsHtml();

    armLocationsOptionsHtml = window.prospectShared.getLocationsOptionsHtml();
    ["armOriginLocation", "armDestinationLocation", "armTruckingOrigin", "armTruckingDestination"].forEach((id) => {
      const el = modal.querySelector(`#${id}`);
      el.innerHTML = `<option value="">Select Location</option>${armLocationsOptionsHtml}`;
      makeSearchableSelect(el);
    });
  }

  function applyArmContainerTypeVisibility(modal) {
    const type = modal.querySelector("#armContainerType").value;
    const flags = TYPE_FIELD_VISIBILITY[type] || {};
    modal.querySelector(".field-convan-size")?.classList.toggle("hidden", !flags.convanSize);
    modal.querySelector(".field-temperature")?.classList.toggle("hidden", !flags.temperature);
    modal.querySelectorAll(".field-revenue-ton").forEach((el) => el.classList.toggle("hidden", !flags.revenueTon));
  }

  function populateArmContainerClassSizeOptions(modal) {
    const type = modal.querySelector("#armContainerType").value;
    const catalog = window.prospectShared.getContainerCatalogByCode()[type] ?? { sizes: [], classes: [] };
    const sizeSelect = modal.querySelector("#armContainerSize");
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

  function containerRowHtml(c) {
    const NA = "-";
    const flags = TYPE_FIELD_VISIBILITY[c.container_type] || {};
    const typeLabel = CONTAINER_TYPES.find((t) => t.value === c.container_type)?.label ?? c.container_type ?? EMPTY;
    const dot = CONTAINER_TYPE_DOT_COLOR[c.container_type] ?? "bg-zinc-400";
    const weight =
      c.weight != null
        ? `${Number(c.weight).toLocaleString()} ${WEIGHT_UNIT_LABEL[c.weight_unit] ?? c.weight_unit ?? ""}`.trim()
        : EMPTY;

    const cells = [
      `<span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full ${dot} shrink-0"></span>${typeLabel}</span>`,
      flags.convanSize ? (c.container_size?.size ?? EMPTY) : NA,
      flags.temperature ? (c.minimum_temperature ?? EMPTY) : NA,
      flags.revenueTon ? (c.revenue_ton ?? EMPTY) : NA,
      flags.revenueTon ? (c.cargo_measurement ?? EMPTY) : NA,
      c.quantity ?? EMPTY,
      c.frequency ?? EMPTY,
      c.service_type ?? EMPTY,
      c.origin_location?.name ?? EMPTY,
      c.destination_location?.name ?? EMPTY,
      c.declared_value_per_unit != null ? `₱${Number(c.declared_value_per_unit).toLocaleString()}` : EMPTY,
      weight,
      c.cargo_type ?? EMPTY,
      c.general_cargo_description ?? EMPTY,
      c.special_requirements ?? EMPTY,
      c.booking_unit_type ?? EMPTY,
    ];
    const tds = cells.map((v) => `<td class="px-3 py-2 align-top text-zinc-700 dark:text-zinc-200">${v}</td>`).join("");
    return `<tr class="arm-container-row" data-id="${c.id}">${tds}<td class="px-3 py-2 align-top"><button type="button" class="arm-remove-container-row text-red-500 text-xs font-medium whitespace-nowrap">✕ Remove</button></td></tr>`;
  }

  function truckingRowHtml(t) {
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
    return `<tr class="arm-trucking-row" data-id="${t.id}">${tds}<td class="px-3 py-2 align-top"><button type="button" class="arm-remove-trucking-row text-red-500 text-xs font-medium whitespace-nowrap">✕ Remove</button></td></tr>`;
  }

  function charterRowHtml(c) {
    const cargoSummary =
      (c.cargo_items ?? [])
        .map((item) => item.cargo_type)
        .filter(Boolean)
        .join(", ") || EMPTY;
    const portSummary = (c.ports ?? []).map((p, i) => `PORT ${i + 1}: ${p.port?.name ?? "—"}`).join(", ") || EMPTY;
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
    return `<tr class="arm-charter-row" data-id="${c.id}">${tds}<td class="px-3 py-2 align-top"><button type="button" class="arm-remove-charter-row text-red-500 text-xs font-medium whitespace-nowrap">✕ Remove</button></td></tr>`;
  }

  function toggleArmTableWrap(modal, wrapId, hasRows) {
    modal.querySelector(`#${wrapId}`)?.classList.toggle("hidden", !hasRows);
  }

  // "Prospect products" - owned directly by the prospect, independent of
  // any Proposal Request. `lead` is the full prospect payload (top-level
  // requirement_containers/_truckings/_charters fields), not a
  // ProposalRequest.
  function renderArmTables(modal, lead) {
    const containers = lead?.requirement_containers ?? [];
    const truckings = lead?.requirement_truckings ?? [];
    const charters = lead?.requirement_charters ?? [];

    const cBody = modal.querySelector("#armContainersTableBody");
    cBody.innerHTML = containers.map(containerRowHtml).join("");
    cBody.querySelectorAll(".arm-remove-container-row").forEach((btn) =>
      btn.addEventListener("click", () => removeArmRow(modal, "containers", btn.closest("tr").dataset.id))
    );
    toggleArmTableWrap(modal, "armContainersTableWrap", containers.length > 0);

    const tBody = modal.querySelector("#armTruckingsTableBody");
    tBody.innerHTML = truckings.map(truckingRowHtml).join("");
    tBody.querySelectorAll(".arm-remove-trucking-row").forEach((btn) =>
      btn.addEventListener("click", () => removeArmRow(modal, "truckings", btn.closest("tr").dataset.id))
    );
    toggleArmTableWrap(modal, "armTruckingsTableWrap", truckings.length > 0);

    const chBody = modal.querySelector("#armChartersTableBody");
    chBody.innerHTML = charters.map(charterRowHtml).join("");
    chBody.querySelectorAll(".arm-remove-charter-row").forEach((btn) =>
      btn.addEventListener("click", () => removeArmRow(modal, "charters", btn.closest("tr").dataset.id))
    );
    toggleArmTableWrap(modal, "armChartersTableWrap", charters.length > 0);

    modal.querySelector("#armRequirementsEmpty")?.classList.toggle(
      "hidden",
      Boolean(containers.length || truckings.length || charters.length)
    );
  }

  async function removeArmRow(modal, kind, id) {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;
    const response = await apiCall({
      mode: "DELETE",
      url: `/api/crm/prospects/${lead.uuid}/proposalRequest/${kind}/${id}`,
    });
    if (!response.success) {
      showMessage({ status: "error", title: "Error Removing", message: response.message ?? "" });
      return;
    }
    await window.prospectInfo.reload(lead.uuid);
    renderArmTables(modal, window.prospectInfo.getCurrentLead());
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
      weight: document.getElementById("armCharterWeight").value,
      weight_unit: document.getElementById("armCharterWeightUnit").value,
      cargo,
      ports,
    };
  }

  // Product picker
  const ARM_PRODUCT_PANES = { freight: "armProductPaneFreight", trucking: "armProductPaneTrucking", charter: "armProductPaneCharter" };

  function bindArmProductSelector(modal) {
    modal.querySelector("#armRequirementProduct").addEventListener("change", function () {
      Object.entries(ARM_PRODUCT_PANES).forEach(([key, paneId]) => {
        modal.querySelector(`#${paneId}`)?.classList.toggle("hidden", key !== this.value);
      });
      modal.querySelector("#armAddRequirementBtnWrap")?.classList.toggle("hidden", !this.value);
    });
  }

  function resetArmProductSelector(modal) {
    modal.querySelector("#armRequirementProduct").value = "";
    Object.values(ARM_PRODUCT_PANES).forEach((paneId) => modal.querySelector(`#${paneId}`)?.classList.add("hidden"));
    modal.querySelector("#armAddRequirementBtnWrap")?.classList.add("hidden");
  }

  async function submitArmRequirement(modal, button) {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;
    const product = modal.querySelector("#armRequirementProduct").value;
    if (!product) return;

    let url;
    let payload;
    if (product === "freight") {
      url = `/api/crm/prospects/${lead.uuid}/proposalRequest/containers`;
      payload = collectArmFormPayload("#armContainerForm");
      const typeSelect = modal.querySelector("#armContainerType");
      payload.booking_unit_type = typeSelect.options[typeSelect.selectedIndex]?.textContent ?? "";
    } else if (product === "trucking") {
      url = `/api/crm/prospects/${lead.uuid}/proposalRequest/truckings`;
      payload = collectArmFormPayload("#armTruckingForm");
    } else {
      url = `/api/crm/prospects/${lead.uuid}/proposalRequest/charters`;
      payload = collectArmCharterPayload();
    }

    const response = await apiCall({ mode: "POST", isJson: true, payload, url, button });
    if (!response.success) {
      const errors = response.data?.errors ?? [];
      showMessage({
        status: "error",
        title: "Error Adding Requirement",
        message: errors.length ? errors.join(" ") : response.message ?? "Please check the required fields.",
      });
      return;
    }

    showMessage({ status: "success", title: "Requirement Added" });

    if (product === "freight") {
      resetArmForm("#armContainerForm");
      applyArmContainerTypeVisibility(modal);
      populateArmContainerClassSizeOptions(modal);
    } else if (product === "trucking") {
      resetArmForm("#armTruckingForm");
    } else {
      resetArmCharterForm();
    }

    await window.prospectInfo.reload(lead.uuid);
    renderArmTables(modal, window.prospectInfo.getCurrentLead());
  }

  async function openAddRequirementModal() {
    const lead = window.prospectInfo?.getCurrentLead();
    if (!lead) return;
    const modal = document.getElementById("AddRequirementModal");

    await window.prospectShared.ensureContainerLookupsLoaded();
    applyArmLookups(modal);

    const containerTypeSelect = modal.querySelector("#armContainerType");
    if (!containerTypeSelect.dataset.filled) {
      containerTypeSelect.insertAdjacentHTML(
        "beforeend",
        CONTAINER_TYPES.map((t) => `<option value="${t.value}">${t.label}</option>`).join("")
      );
      containerTypeSelect.dataset.filled = "1";
    }

    resetArmForm("#armContainerForm");
    resetArmForm("#armTruckingForm");
    resetArmCharterForm();
    resetArmProductSelector(modal);
    applyArmContainerTypeVisibility(modal);
    populateArmContainerClassSizeOptions(modal);

    renderArmTables(modal, lead);

    window.initModal({ modalId: "AddRequirementModal" });
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
  // entry). Field markup kept identical to request-proposal-modal.blade.php's
  // equivalent card (see that file's ensureOdCard()).
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

    const armModal = document.getElementById("AddRequirementModal");
    document.getElementById("pimAddRequirementBtn")?.addEventListener("click", openAddRequirementModal);
    bindArmProductSelector(armModal);
    armModal.querySelector("#armContainerType").addEventListener("change", () => {
      applyArmContainerTypeVisibility(armModal);
      populateArmContainerClassSizeOptions(armModal);
    });
    armModal.querySelector("#armAddCharterCargoBtn").addEventListener("click", addArmCharterCargoRow);
    armModal.querySelector("#armAddCharterPortBtn").addEventListener("click", addArmCharterPortRow);
    armModal.querySelector("#armAddRequirementBtn").addEventListener("click", function () {
      submitArmRequirement(armModal, this);
    });
  }
  window.initAddModals = initAddModals;
})();
