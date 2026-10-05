// Read-only "Prospect Info" modal - shown instead of the ProspectModal form
// once Identity, Contact Information, and Requirements are all complete
// (see openProspectRecord() in logic_prospect_modal.js). Deliberately a
// separate file/IIFE (doesn't share JS scope with logic_prospect_modal.js,
// same reasoning as CONTAINER_TYPE_DOT_COLOR's duplication there) - a
// handful of small display-only constants are duplicated below rather than
// reaching into that file's closure.
(function () {
  const CONTAINER_TYPE_LABEL = {
    CV: "Container Van (CV)",
    FR: "Flatrack (FR)",
    RF: "Reefer Van (RF)",
    LC: "Loose Cargo (LC)",
    RC: "Rolling Cargo (RC)",
  };
  const CONTAINER_TYPE_DOT_COLOR = {
    CV: "bg-orange-500",
    FR: "bg-amber-500",
    RF: "bg-cyan-500",
    LC: "bg-purple-500",
    RC: "bg-blue-500",
  };
  // CV/FR/RF are sized types (RF also needs temperature); RC/LC have no
  // fixed size and are priced by Revenue Ton instead - mirrors
  // TYPE_FIELD_VISIBILITY in logic_prospect_modal.js, read-only subset.
  const TYPE_FIELD_VISIBILITY = {
    CV: { convanSize: true, temperature: false, revenueTon: false },
    FR: { convanSize: true, temperature: false, revenueTon: false },
    RF: { convanSize: true, temperature: true, revenueTon: false },
    LC: { convanSize: false, temperature: false, revenueTon: true },
    RC: { convanSize: false, temperature: false, revenueTon: true },
  };
  const WEIGHT_UNIT_LABEL = { kg: "kg", mt: "MT" };
  const DISPATCH_MODE_LABEL = { single: "Single", tandem: "Tandem" };
  const CHANNEL_TYPE_LABEL = {
    mobile: "Mobile",
    landline: "Landline",
    email: "Email",
  };
  const CONTACT_TYPE_LABEL = { personal: "Personal", business: "Business" };
  const EMPTY = "—";
  const PIM_TABS = [
    "Locations",
    "Contacts",
    "Requirements",
    "OriginDestination",
  ];

  // RFP status pill - adapted from logic_proposal_requests_mine.js's
  // STATUS_PILL, but that one omits "pending" (its queue only ever shows
  // already-assigned requests); this tab shows just-minted, not-yet-assigned
  // ones too, so "pending" must be included here.
  const RFP_STATUS_PILL = {
    pending: {
      label: "Pending Assignment",
      classes: "bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400",
    },
    assigned: {
      label: "Assigned",
      classes: "bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300",
    },
    for_approval: {
      label: "For Approval",
      classes:
        "bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300",
    },
    approved: {
      label: "Approved",
      classes:
        "bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300",
    },
    signed: {
      label: "Signed",
      classes:
        "bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300",
    },
    cancelled: {
      label: "Cancelled",
      classes: "bg-zinc-100 dark:bg-zinc-800 text-zinc-400 dark:text-zinc-500",
    },
  };
  const CP_STATUS_LABEL = {
    1: "Pending RM Approval",
    2: "Approved",
    3: "Disapproved",
    4: "Accepted",
    5: "Rejected",
    6: "Cancelled",
    7: "Pending Manager Approval",
  };
  // Timeline dot color by activity type (VISUALS: green=success, red=danger,
  // blue=default neutral event).
  const ACTIVITY_DOT_COLOR = {
    proposal_approved: "bg-green-500",
    proposal_signed: "bg-green-500",
    proposal_disapproved: "bg-red-500",
    proposal_rejected: "bg-red-500",
    proposal_cancelled: "bg-red-500",
    rfp_cancelled: "bg-red-500",
  };
  const ACTIVITY_DOT_DEFAULT = "bg-blue-500";

  // This modal's currently-displayed prospect (the GET .../prospects/{uuid}
  // payload) - kept around so the inline Contact Summary edit can rebuild
  // its save payload (addresses etc.) without a second fetch, and exposed
  // read-only via window.prospectInfo.getCurrentLead() for the standalone
  // Add Location/Contact/Requirement modals (logic_prospect_add_modals.js).
  let currentLead = null;
  // Snapshot of the 4 edit-mode field values taken when Edit was clicked -
  // compared against on every keystroke/change to decide whether Save
  // should be enabled (see checkSummaryDirty()).
  let summaryOriginalValues = null;

  function esc(v) {
    return String(v ?? "").replace(
      /[&<>"']/g,
      (c) =>
        ({
          "&": "&amp;",
          "<": "&lt;",
          ">": "&gt;",
          '"': "&quot;",
          "'": "&#39;",
        })[c],
    );
  }

  function formatLocationLabel(loc) {
    const line =
      [
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

  // Reduces a saved "Category - detail" source string down to just its
  // base category, for the quick-edit dropdown (which only offers the 5
  // categories, not the Referral/Social Media detail sub-fields - see
  // saveSummaryEdit()'s "only re-encode if the category actually changed"
  // guard, which is what keeps this lossy reduction from silently
  // destroying a saved Referral name/platform on unrelated edits).
  function decodeSourceCategory(source) {
    if (!source) return "";
    if (/^Referral - /.test(source)) return "Referral";
    if (/^Social Media - /.test(source)) return "Social Media";
    if (source === "Cold Call" || source === "Walk-In") return source;
    return "Others";
  }

  function switchPimTab(modal, tab) {
    PIM_TABS.forEach((t) => {
      modal
        .querySelector(`[data-pim-pane="${t}"]`)
        ?.classList.toggle("hidden", t !== tab);
      const btn = modal.querySelector(`[data-pim-tab="${t}"]`);
      if (!btn) return;
      const active = t === tab;
      btn.classList.toggle("border-orange-500", active);
      btn.classList.toggle("text-orange-600", active);
      btn.classList.toggle("border-transparent", !active);
      btn.classList.toggle("text-zinc-500", !active);
      btn.classList.toggle("dark:text-zinc-400", !active);
    });
  }

  function bindPimTabs(modal) {
    PIM_TABS.forEach((t) => {
      modal
        .querySelector(`[data-pim-tab="${t}"]`)
        ?.addEventListener("click", () => switchPimTab(modal, t));
    });
  }

  // ------------------------------------------------------------------
  // RIGHT COLUMN: CONTACT SUMMARY (persistent, not a tab) - read view +
  // inline edit mode
  // ------------------------------------------------------------------
  function renderContactSummary(modal, lead) {
    const company = lead.company ?? {};
    const rows = [
      ["Prospect Source", lead.source || EMPTY],
      ["Prospect Name", company.company_name || EMPTY],
      ["Business Type", company.type_of_business || EMPTY],
      ["Attending CSR", lead.user?.name || EMPTY],
      ["Relationship Manager", lead.relationship_manager?.name || EMPTY],
    ];

    modal.querySelector("#pimIdentitySummary").innerHTML = rows
      .map(
        ([label, value]) => `
      <div>
          <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">${esc(label)}</p>
          <p class="text-sm text-zinc-800 dark:text-zinc-100 mt-0.5">${esc(value)}</p>
      </div>`,
      )
      .join("");
  }

  async function loadSummaryEditLookups(modal) {
    const businessTypesRes = await apiCall({
      mode: "GET",
      url: "/api/listofval/typeofbusiness",
    });

    const businessSelect = modal.querySelector("#pimEditTypeOfBusiness");
    if (Array.isArray(businessTypesRes)) {
      businessSelect.innerHTML =
        '<option value="">Select Business Type</option>' +
        businessTypesRes
          .map(
            (lov) =>
              `<option value="${esc(lov.lov_name)}">${esc(lov.lov_name)}</option>`,
          )
          .join("");
    }
  }

  function checkSummaryDirty(modal) {
    if (!summaryOriginalValues) return;
    const current = {
      source: modal.querySelector("#pimEditSource").value,
      company_name: modal.querySelector("#pimEditCompanyName").value,
      type_of_business: modal.querySelector("#pimEditTypeOfBusiness").value,
    };
    const dirty = Object.keys(current).some(
      (k) => current[k] !== summaryOriginalValues[k],
    );
    modal.querySelector("#pimSummarySaveBtn").disabled = !dirty;
  }

  async function enterSummaryEditMode(modal) {
    const lead = currentLead;
    if (!lead) return;

    await loadSummaryEditLookups(modal);

    const sourceCategory = decodeSourceCategory(lead.source);
    modal.querySelector("#pimEditSource").value = sourceCategory;
    modal.querySelector("#pimEditCompanyName").value =
      lead.company?.company_name ?? "";
    modal.querySelector("#pimEditTypeOfBusiness").value =
      lead.company?.type_of_business ?? "";

    summaryOriginalValues = {
      source: sourceCategory,
      company_name: lead.company?.company_name ?? "",
      type_of_business: lead.company?.type_of_business ?? "",
    };

    modal.querySelector("#pimIdentitySummary").classList.add("hidden");
    modal.querySelector("#pimIdentityEditForm").classList.remove("hidden");
    modal.querySelector("#pimSummarySaveBtn").disabled = true;
  }

  function exitSummaryEditMode(modal) {
    modal.querySelector("#pimIdentityEditForm").classList.add("hidden");
    modal.querySelector("#pimIdentitySummary").classList.remove("hidden");
    summaryOriginalValues = null;
  }

  async function saveSummaryEdit(modal, button) {
    const lead = currentLead;
    if (!lead) return;

    // Only re-encode Source from the quick-edit dropdown if the category
    // actually changed - otherwise resend the original raw string as-is,
    // so an unrelated edit (e.g. just the name) can't silently truncate a
    // saved "Referral - <name>"/"Social Media - <platform>" detail.
    const newSourceCategory = modal.querySelector("#pimEditSource").value;
    const source =
      newSourceCategory === summaryOriginalValues.source
        ? lead.source
        : newSourceCategory;

    const addresses = (lead.addresses ?? []).map((a) => ({
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

    const payload = {
      uuid: lead.uuid,
      client_type: lead.client_type || "corporate",
      source,
      company_name: modal.querySelector("#pimEditCompanyName").value,
      type_of_business:
        modal.querySelector("#pimEditTypeOfBusiness").value || null,
      addresses,
    };

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

    showMessage({ status: "success", title: "Prospect Identity Saved" });

    const refreshed = await apiCall({
      mode: "GET",
      url: `/api/crm/prospects/${lead.uuid}`,
    });
    if (refreshed.success) {
      currentLead = refreshed.data;
      renderContactSummary(modal, currentLead);
    }
    exitSummaryEditMode(modal);
    window.reloadCrmData?.();
  }

  // ------------------------------------------------------------------
  // TAB: LOCATIONS
  // ------------------------------------------------------------------
  // saveStage1 requires at least one address - resending the full list
  // minus this one, same replace-all contract Add Location already uses.
  async function removeLocation(locationId) {
    const lead = currentLead;
    if (!lead) return;

    const remaining = (lead.addresses ?? []).filter((a) => a.id !== locationId);
    if (!remaining.length) {
      showMessage({
        status: "error",
        title: "At least one location is required.",
      });
      return;
    }

    const confirmed = await customConfirm("Remove this location?");
    if (!confirmed) return;

    const payload = {
      uuid: lead.uuid,
      client_type: lead.client_type || "corporate",
      source: lead.source,
      company_name: lead.company?.company_name ?? "",
      type_of_business: lead.company?.type_of_business ?? null,
      relationship_manager_id: lead.relationship_manager_id ?? null,
      addresses: remaining.map((a) => ({
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
      })),
    };

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload,
      url: "/api/crm/prospects/stage1",
    });
    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Removing",
        message: response.message ?? "",
      });
      return;
    }

    showMessage({ status: "success", title: "Location Removed" });
    await reloadProspectInfo(lead.uuid);
    window.reloadCrmData?.();
  }

  function renderLocationsSummary(modal, lead) {
    const locations = lead.addresses ?? [];
    const wrap = modal.querySelector("#pimLocationsSummary");
    wrap.innerHTML = locations.length
      ? locations
          .map(
            (loc) => `
      <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-3 flex justify-between items-start gap-3">
          <div>
              <p class="text-sm text-zinc-800 dark:text-zinc-100">${esc(formatLocationLabel(loc))}</p>
              <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">Postal Code: ${esc(loc.address_postal_code || EMPTY)}${loc.is_primary ? " · Primary" : ""}</p>
          </div>
          <button type="button" class="pim-remove-location text-red-500 text-xs font-medium shrink-0" data-id="${loc.id}">✕ Remove</button>
      </div>`,
          )
          .join("")
      : `<p class="text-sm text-zinc-400 dark:text-zinc-500">No locations on file.</p>`;

    wrap.querySelectorAll(".pim-remove-location").forEach((btn) => {
      btn.addEventListener("click", () =>
        removeLocation(Number(btn.dataset.id)),
      );
    });
  }

  // ------------------------------------------------------------------
  // TAB: CONTACTS
  // ------------------------------------------------------------------
  // The contacts endpoint is replace-all too, and has no "at least one"
  // constraint (contacts is nullable server-side) - resending the full
  // list minus this one, same shape Add Contact already sends.
  async function removeContact(contactId) {
    const lead = currentLead;
    if (!lead) return;

    const confirmed = await customConfirm("Remove this contact?");
    if (!confirmed) return;

    const remaining = (lead.contacts ?? [])
      .filter((c) => c.id !== contactId)
      .map((c) => ({
        title: c.title,
        first_name: c.first_name,
        middle_name: c.middle_name,
        last_name: c.last_name,
        position: c.position,
        channels: (c.channels ?? []).map((ch) => ({
          channel_type: ch.channel_type,
          value: ch.value,
          contact_type: ch.contact_type,
        })),
        location_ids: c.location_ids ?? [],
      }));

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: { contacts: remaining },
      url: `/api/crm/prospects/${lead.uuid}/contacts`,
    });
    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Removing",
        message: response.message ?? "",
      });
      return;
    }

    showMessage({ status: "success", title: "Contact Removed" });
    await reloadProspectInfo(lead.uuid);
  }

  function renderContactsSummary(modal, lead) {
    const locationsById = {};
    (lead.addresses ?? []).forEach(
      (loc) => (locationsById[loc.id] = formatLocationLabel(loc)),
    );

    const contacts = lead.contacts ?? [];
    const wrap = modal.querySelector("#pimContactsSummary");
    wrap.innerHTML = contacts.length
      ? contacts
          .map((contact) => {
            const name = [
              contact.title,
              contact.first_name,
              contact.middle_name,
              contact.last_name,
            ]
              .filter(Boolean)
              .join(" ");
            const channelsHtml = (contact.channels ?? []).length
              ? (contact.channels ?? [])
                  .map(
                    (ch) =>
                      `<li>${esc(CHANNEL_TYPE_LABEL[ch.channel_type] ?? ch.channel_type)}: ${esc(ch.value)} (${esc(CONTACT_TYPE_LABEL[ch.contact_type] ?? ch.contact_type)})</li>`,
                  )
                  .join("")
              : `<li>${EMPTY}</li>`;
            const locationId = (contact.location_ids ?? [])[0];
            const addressLine =
              locationId != null ? (locationsById[locationId] ?? EMPTY) : EMPTY;

            return `
          <div class="border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-2">
              <div class="flex justify-between items-start gap-3">
                  <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">${esc(name || EMPTY)}</p>
                  <button type="button" class="pim-remove-contact text-red-500 text-xs font-medium shrink-0" data-id="${contact.id}">✕ Remove</button>
              </div>
              <p class="text-xs text-zinc-400 dark:text-zinc-500">${esc(contact.position || EMPTY)}</p>
              <ul class="text-sm text-zinc-700 dark:text-zinc-200 list-disc list-inside">${channelsHtml}</ul>
              <p class="text-xs text-zinc-400 dark:text-zinc-500">Address: ${esc(addressLine)}</p>
          </div>`;
          })
          .join("")
      : `<p class="text-sm text-zinc-400 dark:text-zinc-500">No contact persons on file.</p>`;

    wrap.querySelectorAll(".pim-remove-contact").forEach((btn) => {
      btn.addEventListener("click", () =>
        removeContact(Number(btn.dataset.id)),
      );
    });
  }

  // ------------------------------------------------------------------
  // TAB: REQUIREMENTS (read-only tables - same cells as
  // logic_prospect_modal.js's *TableRowHtml(), minus the Actions column)
  // ------------------------------------------------------------------
  function containerRowHtml(c) {
    const NA = "-";
    const flags = TYPE_FIELD_VISIBILITY[c.container_type] || {};
    const typeLabel =
      CONTAINER_TYPE_LABEL[c.container_type] ?? c.container_type ?? EMPTY;
    const dot = CONTAINER_TYPE_DOT_COLOR[c.container_type] ?? "bg-zinc-400";
    const weight =
      c.weight != null
        ? `${Number(c.weight).toLocaleString()} ${WEIGHT_UNIT_LABEL[c.weight_unit] ?? c.weight_unit ?? ""}`.trim()
        : EMPTY;

    const cells = [
      `<span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full ${dot} shrink-0"></span>${esc(typeLabel)}</span>`,
      flags.convanSize ? esc(c.container_size?.size ?? EMPTY) : NA,
      flags.temperature ? esc(c.minimum_temperature ?? EMPTY) : NA,
      flags.revenueTon ? esc(c.revenue_ton ?? EMPTY) : NA,
      flags.revenueTon ? esc(c.cargo_measurement ?? EMPTY) : NA,
      esc(c.quantity ?? EMPTY),
      esc(c.frequency ?? EMPTY),
      esc(c.delivery_type?.name ?? EMPTY),
      esc(c.origin_location?.name ?? EMPTY),
      esc(c.destination_location?.name ?? EMPTY),
      c.declared_value_per_unit != null
        ? `₱${Number(c.declared_value_per_unit).toLocaleString()}`
        : EMPTY,
      weight,
      esc(c.cargo_type ?? EMPTY),
      esc(c.general_cargo_description ?? EMPTY),
      esc(c.special_requirements ?? EMPTY),
      esc(c.booking_unit_type ?? EMPTY),
    ];
    return `<tr>${cells.map((v) => `<td class="px-3 py-2 align-top text-zinc-700 dark:text-zinc-200">${v}</td>`).join("")}</tr>`;
  }

  function truckingRowHtml(t) {
    const weight =
      t.weight != null
        ? `${Number(t.weight).toLocaleString()} ${WEIGHT_UNIT_LABEL[t.weight_unit] ?? t.weight_unit ?? ""}`.trim()
        : EMPTY;
    const cells = [
      esc(t.trucking_cargo_type ?? EMPTY),
      esc(t.quantity ?? EMPTY),
      esc(t.frequency ?? EMPTY),
      esc(DISPATCH_MODE_LABEL[t.dispatch_mode] ?? t.dispatch_mode ?? EMPTY),
      esc(t.origin_location?.name ?? EMPTY),
      esc(t.destination_location?.name ?? EMPTY),
      t.declared_value_per_unit != null
        ? `₱${Number(t.declared_value_per_unit).toLocaleString()}`
        : EMPTY,
      weight,
      esc(t.cargo_type ?? EMPTY),
      esc(t.general_cargo_description ?? EMPTY),
      esc(t.special_requirements ?? EMPTY),
    ];
    return `<tr>${cells.map((v) => `<td class="px-3 py-2 align-top text-zinc-700 dark:text-zinc-200">${v}</td>`).join("")}</tr>`;
  }

  function charterRowHtml(c) {
    const cargoSummary =
      (c.cargo_items ?? [])
        .map((item) => item.cargo_type)
        .filter(Boolean)
        .join(", ") || EMPTY;
    const portSummary =
      (c.ports ?? [])
        .map((p, i) => `PORT ${i + 1}: ${p.port?.name ?? "—"}`)
        .join(", ") || EMPTY;
    const weight =
      c.weight != null
        ? `${Number(c.weight).toLocaleString()} ${WEIGHT_UNIT_LABEL[c.weight_unit] ?? c.weight_unit ?? ""}`.trim()
        : EMPTY;
    const cells = [
      esc(cargoSummary),
      esc(portSummary),
      esc(c.charter_start_date ?? EMPTY),
      esc(c.charter_end_date ?? EMPTY),
      c.declared_value != null
        ? `₱${Number(c.declared_value).toLocaleString()}`
        : EMPTY,
      weight,
    ];
    return `<tr>${cells.map((v) => `<td class="px-3 py-2 align-top text-zinc-700 dark:text-zinc-200">${v}</td>`).join("")}</tr>`;
  }

  // "Prospect products" - owned directly by the prospect, independent of
  // any Proposal Request.
  function renderRequirementsSummary(modal, lead) {
    const containers = lead.requirement_containers ?? [];
    const truckings = lead.requirement_truckings ?? [];
    const charters = lead.requirement_charters ?? [];

    modal
      .querySelector("#pimContainersTableWrap")
      .classList.toggle("hidden", !containers.length);
    modal.querySelector("#pimContainersTableBody").innerHTML = containers
      .map(containerRowHtml)
      .join("");

    modal
      .querySelector("#pimTruckingsTableWrap")
      .classList.toggle("hidden", !truckings.length);
    modal.querySelector("#pimTruckingsTableBody").innerHTML = truckings
      .map(truckingRowHtml)
      .join("");

    modal
      .querySelector("#pimChartersTableWrap")
      .classList.toggle("hidden", !charters.length);
    modal.querySelector("#pimChartersTableBody").innerHTML = charters
      .map(charterRowHtml)
      .join("");

    modal
      .querySelector("#pimRequirementsEmpty")
      ?.classList.toggle(
        "hidden",
        Boolean(containers.length || truckings.length || charters.length),
      );
  }

  // ------------------------------------------------------------------
  // TAB: ORIGIN & DESTINATION (conditionally visible - the tab button
  // itself is hidden, not just its pane, until the Request for Proposal
  // wizard's Origin & Destination tab has saved at least one row here.
  // Read/writes the same proposal_request_locations rows as that wizard -
  // see logic_prospect_request_proposal.js.)
  // ------------------------------------------------------------------
  async function removeOdLocation(id) {
    const lead = currentLead;
    if (!lead) return;

    if (!lead.proposal_request?.id) {
      showMessage({
        status: "error",
        title: "Error Removing",
        message: "No draft proposal request to remove this location from.",
      });
      return;
    }

    const confirmed = await customConfirm(
      "Remove this origin/destination location?",
    );
    if (!confirmed) return;

    const response = await apiCall({
      mode: "DELETE",
      url: `/api/crm/prospects/${lead.uuid}/proposalRequests/${lead.proposal_request.id}/locations/${id}`,
    });
    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Removing",
        message: response.message ?? "",
      });
      return;
    }

    showMessage({ status: "success", title: "Location Removed" });
    await reloadProspectInfo(lead.uuid);
  }

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
        <td class="px-3 py-2"><button type="button" class="pim-remove-od text-red-500 text-xs font-medium" data-id="${row.id}">✕ Remove</button></td>
    </tr>`;
  }

  function renderOriginDestinationTab(modal, lead) {
    const rows = lead.proposal_request?.locations ?? [];
    const tabBtn = modal.querySelector('[data-pim-tab="OriginDestination"]');
    const wasActive =
      tabBtn &&
      !tabBtn.classList.contains("hidden") &&
      tabBtn.classList.contains("border-orange-500");

    tabBtn?.classList.toggle("hidden", rows.length === 0);

    // The tab was showing but its last row just got removed - fall back
    // to Locations rather than leaving an inaccessible pane active.
    if (wasActive && rows.length === 0) {
      switchPimTab(modal, "Locations");
    }

    modal.querySelector("#pimOdTableBody").innerHTML = rows
      .map(odRowHtml)
      .join("");

    modal.querySelectorAll(".pim-remove-od").forEach((btn) => {
      btn.addEventListener("click", () =>
        removeOdLocation(Number(btn.dataset.id)),
      );
    });
  }

  // ------------------------------------------------------------------
  // REQUESTED PROPOSALS & ACTIVITY/HISTORY (read-only - rendered into the
  // right-column Contact Summary sidebar, not a tab; all data already
  // present on the lead payload, no extra network round-trip besides the
  // native PDF/signed document download links below)
  // ------------------------------------------------------------------
  function clientProposalRowHtml(cp) {
    const statusLabel = CP_STATUS_LABEL[cp.status] ?? "—";
    const links = [];
    if (cp.status === 2 || cp.status === 4) {
      links.push(
        `<a href="/api/clientProposals/${cp.id}/pdf" class="text-[11px] font-medium text-orange-600 hover:text-orange-700">Download PDF</a>`,
      );
    }
    if (
      cp.status === 4 &&
      typeof cp.signed_document_path === "string" &&
      /^(\/|https?:\/\/)/.test(cp.signed_document_path)
    ) {
      links.push(
        `<a href="${esc(cp.signed_document_path)}" target="_blank" rel="noopener" class="text-[11px] font-medium text-orange-600 hover:text-orange-700">Download Signed Copy</a>`,
      );
    }

    return `
    <div class="flex flex-wrap items-center justify-between gap-2 pl-3">
        <p class="text-xs text-zinc-700 dark:text-zinc-200">${esc(cp.code)} <span class="text-zinc-400 dark:text-zinc-500">(${esc(statusLabel)})</span></p>
        <div class="flex items-center gap-3">${links.join("")}</div>
    </div>`;
  }

  function requestedProposalCardHtml(r) {
    const pill = RFP_STATUS_PILL[r.status] ?? RFP_STATUS_PILL.pending;
    const proposals = r.client_proposals ?? [];
    const proposalsHtml = proposals.length
      ? proposals.map(clientProposalRowHtml).join("")
      : `<p class="text-[11px] text-zinc-400 dark:text-zinc-500 pl-3">No proposal generated yet.</p>`;

    return `
    <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-2.5 space-y-1.5">
        <div class="flex justify-between items-start gap-3">
            <p class="text-xs font-semibold text-zinc-800 dark:text-zinc-100">${esc(r.code)}</p>
            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium ${pill.classes}">${esc(pill.label)}</span>
        </div>
        <p class="text-[11px] text-zinc-400 dark:text-zinc-500">Requested ${esc(window.formatDateTime(r.created_at))}</p>
        <div class="space-y-1">${proposalsHtml}</div>
    </div>`;
  }

  function renderRequestedProposals(modal, lead) {
    const requests = [...(lead.proposal_requests ?? [])].sort(
      (a, b) => new Date(b.created_at) - new Date(a.created_at),
    );
    const wrap = modal.querySelector("#pimRequestedProposals");
    wrap.innerHTML = requests.length
      ? requests.map(requestedProposalCardHtml).join("")
      : `<p class="text-xs text-zinc-400 dark:text-zinc-500">No proposal requests yet.</p>`;
  }

  function activityRowHtml(item) {
    const dot = ACTIVITY_DOT_COLOR[item.type] ?? ACTIVITY_DOT_DEFAULT;
    return `
    <div class="flex gap-3 py-1.5">
        <span class="w-2 h-2 rounded-full ${dot} shrink-0 mt-1.5"></span>
        <div>
            <p class="text-xs text-zinc-800 dark:text-zinc-100">${esc(item.description)}</p>
            <p class="text-[11px] text-zinc-400 dark:text-zinc-500">${esc(item.user?.name ?? "System")} · ${esc(window.formatDateTime(item.created_at))}</p>
        </div>
    </div>`;
  }

  function renderActivityTimeline(modal, lead) {
    const items = lead.activities ?? [];
    const wrap = modal.querySelector("#pimActivityTimeline");
    wrap.innerHTML = items.length
      ? items.map(activityRowHtml).join("")
      : `<p class="text-xs text-zinc-400 dark:text-zinc-500">No activity recorded yet.</p>`;
  }

  // ------------------------------------------------------------------
  // PUBLIC API
  // ------------------------------------------------------------------
  function renderAll(modal, lead) {
    currentLead = lead;
    modal.dataset.prospectUuid = lead.uuid;
    modal.querySelector("#pimTitle").textContent =
      lead.company?.company_name || lead.contact_name || "Prospect";
    modal.querySelector("#pimSubtitle").textContent = lead.source || "";

    renderContactSummary(modal, lead);
    renderRequestedProposals(modal, lead);
    renderActivityTimeline(modal, lead);
    renderLocationsSummary(modal, lead);
    renderContactsSummary(modal, lead);
    renderRequirementsSummary(modal, lead);
    renderOriginDestinationTab(modal, lead);
  }

  function openProspectInfoModal(lead) {
    const modal = document.getElementById("ProspectInfoModal");
    if (!modal) return;

    exitSummaryEditMode(modal);
    renderAll(modal, lead);
    switchPimTab(modal, "Locations");
    window.initModal({ modalId: "ProspectInfoModal" });
  }
  window.openProspectInfoModal = openProspectInfoModal;

  // Silent refresh while this modal is (or was just) open - called by the
  // standalone Add Location/Add Contact/Add Requirement modals
  // (logic_prospect_add_modals.js) after a successful save, so this
  // modal's data is current without resetting the active tab or
  // re-triggering the open animation.
  async function reloadProspectInfo(uuid) {
    const modal = document.getElementById("ProspectInfoModal");
    if (!modal || !uuid) return;
    const response = await apiCall({
      mode: "GET",
      url: `/api/crm/prospects/${uuid}`,
    });
    if (!response.success) return;
    renderAll(modal, response.data);
  }

  function initProspectInfoModal() {
    const modal = document.getElementById("ProspectInfoModal");
    if (!modal) return; // guarded: only runs on pages that actually include the modal

    // Page-scoped, not module-top-level: every logic_*.js file loads
    // globally (see resources/js/app.js), so window.prospectInfo must only
    // be claimed by whichever page actually has #ProspectInfoModal in the
    // DOM - logic_proposal_requests_mine.js defines its own equivalent the
    // same way for its own page, guarded on its own root element.
    window.prospectInfo = {
      getCurrentLead: () => currentLead,
      reload: reloadProspectInfo,
    };

    bindPimTabs(modal);

    modal
      .querySelector("#pimSummaryEditBtn")
      .addEventListener("click", () => enterSummaryEditMode(modal));
    modal
      .querySelector("#pimSummaryCancelBtn")
      .addEventListener("click", () => exitSummaryEditMode(modal));
    modal
      .querySelector("#pimSummarySaveBtn")
      .addEventListener("click", function () {
        saveSummaryEdit(modal, this);
      });
    [
      "pimEditSource",
      "pimEditCompanyName",
      "pimEditTypeOfBusiness",
    ].forEach((id) => {
      const el = modal.querySelector(`#${id}`);
      el?.addEventListener("input", () => checkSummaryDirty(modal));
      el?.addEventListener("change", () => checkSummaryDirty(modal));
    });

    // Add Location/Add Contact/Add Requirement buttons are wired by
    // logic_prospect_add_modals.js's initAddModals() - separate dedicated
    // modals, not a reopened ProspectModal form (see that file).

    // Always starts a brand-new Proposal Request - independent of any
    // other request already in progress for this prospect. This is the
    // TEMP CSR's whole job: mint it and confirm. Filling it out happens on
    // the assigned CSR/Relationship Manager's own "My Requests" page under
    // Proposals instead (logic_proposal_requests_mine.js) - this modal no
    // longer opens the wizard itself. apiCall does NOT auto-toast a
    // {success:false} response (only genuine network/exception failures) -
    // show the error explicitly.
    modal
      .querySelector("#pimRequestProposalBtn")
      .addEventListener("click", async function () {
        if (!currentLead) return;
        const response = await apiCall({
          mode: "POST",
          isJson: true,
          payload: {},
          url: `/api/crm/prospects/${currentLead.uuid}/proposalRequests`,
          button: this,
        });
        if (!response.success) {
          showMessage({
            status: "error",
            title: "Error Starting Request",
            message: response.message ?? "",
          });
          return;
        }

        showMessage({ status: "success", title: "Proposal Request Started" });
        await reloadProspectInfo(currentLead.uuid);
      });

    // Origin & Destination tab's Add button opens a genuinely separate
    // dedicated modal (AddOriginDestinationModal) - wired by
    // logic_prospect_add_modals.js's initAddModals(), same as Add
    // Location/Add Contact/Add Requirement.
  }
  window.initProspectInfoModal = initProspectInfoModal;
})();
