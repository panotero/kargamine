// Logic for the "Team Management" admin page (pages/settings/team_management.blade.php).
// Bootstrapped by a one-line inline <script> in that view, since pages are
// injected into #content via AJAX (see navmenu.js loadPage()) and this
// module is bundled globally rather than fetched with the page fragment.
window.initTeamManagementPage = function initTeamManagementPage() {
  const tableBody = document.getElementById("teamsTbody");
  if (!tableBody) return;

  const modalTitle = document.getElementById("teamModalTitle");
  const form = document.getElementById("teamForm");
  const saveBtn = document.getElementById("saveTeamBtn");

  const fields = {
    id: document.getElementById("teamId"),
    name: document.getElementById("teamName"),
    description: document.getElementById("teamDescription"),
    parent: document.getElementById("teamParent"),
    active: document.getElementById("teamActive"),
  };

  const membersModalTitle = document.getElementById("membersModalTitle");
  const teamMembersList = document.getElementById("teamMembersList");
  const addMemberSelect = document.getElementById("addMemberSelect");
  const addMemberBtn = document.getElementById("addMemberBtn");

  let teamsData = [];
  let currentMembersTeam = null;

  // Turn a native <select> into the app's type-to-search combobox, or refresh
  // its option list if it was already converted (must run after any innerHTML
  // rebuild or programmatic .value assignment - see searchableSelect.js).
  function refreshSearchable(el) {
    if (!el) return;
    if (el._searchableSelect) el._searchableSelect.refresh();
    else if (typeof window.makeSearchableSelect === "function") {
      window.makeSearchableSelect(el);
    }
  }

  // All descendant ids of teamId (including itself), computed from the already-loaded flat list.
  function getDescendantIds(teamId) {
    const result = new Set([teamId]);
    let added = true;
    while (added) {
      added = false;
      teamsData.forEach((t) => {
        if (t.parent_id && result.has(t.parent_id) && !result.has(t.id)) {
          result.add(t.id);
          added = true;
        }
      });
    }
    return result;
  }

  function populateParentSelect(excludeTeamId = null) {
    const excluded = excludeTeamId ? getDescendantIds(excludeTeamId) : new Set();
    fields.parent.innerHTML = `<option value="">None (top-level team)</option>`;
    teamsData
      .filter((t) => !excluded.has(t.id))
      .forEach((t) => {
        const opt = document.createElement("option");
        opt.value = t.id;
        opt.textContent = t.name;
        fields.parent.appendChild(opt);
      });
    refreshSearchable(fields.parent);
  }

  function openModal(mode = "Add", team = null) {
    modalTitle.textContent = mode === "Add" ? "Add New Team" : "Modify Team";
    saveBtn.textContent = mode === "Add" ? "Save" : "Modify";

    populateParentSelect(team ? team.id : null);

    if (team) {
      fields.id.value = team.id;
      fields.name.value = team.name || "";
      fields.description.value = team.description || "";
      fields.parent.value = team.parent_id || "";
      fields.active.checked = !!team.is_active;
    } else {
      fields.id.value = "";
      fields.name.value = "";
      fields.description.value = "";
      fields.parent.value = "";
      fields.active.checked = true;
    }
    refreshSearchable(fields.parent);

    initModal({ modalId: "teamModal" });
  }

  async function loadTeams() {
    teamsData = await apiCall({ mode: "GET", url: "/api/teams" });
    if (!Array.isArray(teamsData)) teamsData = [];
    tableBody.innerHTML = "";

    if (!teamsData.length) {
      tableBody.innerHTML = `<tr><td colspan="6" class="px-4 py-10 text-center text-sm text-zinc-400 dark:text-zinc-500">No teams yet. Create your first team with the “Add New Team” button.</td></tr>`;
      return;
    }

    function renderTeamRows(parentId = null, level = 0) {
      const children = teamsData
        .filter((t) => (t.parent_id || null) === parentId)
        .sort((a, b) => a.name.localeCompare(b.name));

      children.forEach((team) => {
        const tr = document.createElement("tr");
        tr.classList.add(
          "hover:bg-zinc-50",
          "dark:hover:bg-zinc-800",
          "transition-colors",
          "duration-100",
        );

        const indent =
          "&nbsp;".repeat(level * 6) +
          (level > 0 ? '<span class="text-zinc-400">↳</span> ' : "");

        const leaderNames = (team.leaders || []).map((l) => l.name).join(", ");
        const statusBadge = team.is_active
          ? '<span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">Active</span>'
          : '<span class="px-2 py-0.5 rounded-full text-xs bg-zinc-200 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300">Inactive</span>';

        tr.innerHTML = `
                <td class="px-4 py-2.5 text-black dark:text-white">${indent}${team.name}</td>
                <td class="px-4 py-2.5 text-black dark:text-white">${team.description || ""}</td>
                <td class="px-4 py-2.5 text-black dark:text-white">${team.members_count ?? 0}</td>
                <td class="px-4 py-2.5 text-black dark:text-white">${leaderNames || "—"}</td>
                <td class="px-4 py-2.5 text-center">${statusBadge}</td>
                <td class="px-4 py-2.5 text-center whitespace-nowrap">
                    <button class="members-button p-1.5 text-zinc-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/40 rounded-lg transition" data-id="${team.id}" title="Members">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-4a4 4 0 11-8 0" />
                        </svg>
                    </button>
                    <button class="edit-button p-1.5 text-zinc-400 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-950/40 rounded-lg transition" data-id="${team.id}" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <button class="delete-button p-1.5 text-zinc-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-lg transition" data-id="${team.id}" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 pointer-events-none" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 6h18v2H3V6zm2 3h14l-1.5 12.5a1 1 0 0 1-1 .5H8a1 1 0 0 1-1-.5L5 9zm5 2v8h2v-8H10zm4 0v8h2v-8h-2zM9 4V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1h5v2H4V4h5z"/>
                        </svg>
                    </button>
                </td>
            `;

        tableBody.appendChild(tr);
        renderTeamRows(team.id, level + 1);
      });
    }

    renderTeamRows(null, 0);
  }

  tableBody.addEventListener("click", async (e) => {
    const btn = e.target.closest("button");
    if (!btn) return;

    const id = parseInt(btn.dataset.id);
    const team = teamsData.find((t) => t.id === id);
    if (!team) return;

    if (btn.classList.contains("edit-button")) openModal("Modify", team);
    else if (btn.classList.contains("delete-button")) await deleteTeam(team, btn);
    else if (btn.classList.contains("members-button")) await openMembersModal(team);
  });

  async function deleteTeam(team, button) {
    const confirmed = await customConfirm(`Delete team "${team.name}"?`);
    if (!confirmed) return;

    const res = await apiCall({
      mode: "DELETE",
      url: `/api/teams/${team.id}`,
      button,
    });

    if (res && res.success === false) {
      showMessage({
        status: "error",
        title: "Cannot delete team",
        message: res.message || "Failed to delete team.",
      });
      return;
    }

    await loadTeams();
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const payload = {
      name: fields.name.value,
      description: fields.description.value || null,
      parent_id: fields.parent.value || null,
      is_active: fields.active.checked,
    };

    const isUpdate = Boolean(fields.id.value);

    const res = await apiCall({
      mode: isUpdate ? "PUT" : "POST",
      isJson: true,
      payload,
      url: isUpdate ? `/api/teams/${fields.id.value}` : "/api/teams",
      button: saveBtn,
    });

    if (!res || res.success === false) {
      showMessage({
        status: "error",
        title: "Cannot save team",
        message: res?.invalid_fields
          ? Object.values(res.invalid_fields).flat().join(" ")
          : res?.message || "Failed to save team.",
      });
      return;
    }

    closeModal("teamModal");
    await loadTeams();
  });

  document.getElementById("addTeamBtn").addEventListener("click", () => openModal("Add"));

  // --- Members modal ---

  async function openMembersModal(team) {
    currentMembersTeam = team;
    membersModalTitle.textContent = `${team.name} — Members`;

    await refreshMembers();

    initModal({ modalId: "membersModal" });
  }

  async function refreshMembers() {
    if (!currentMembersTeam) return;

    const [members, allUsers] = await Promise.all([
      apiCall({ mode: "GET", url: `/api/teams/${currentMembersTeam.id}/members` }),
      apiCall({ mode: "GET", url: `/api/teams/users` }),
    ]);

    renderMembersList(Array.isArray(members) ? members : []);
    renderAddMemberSelect(
      Array.isArray(allUsers) ? allUsers : [],
      Array.isArray(members) ? members : [],
    );
  }

  function renderMembersList(members) {
    teamMembersList.innerHTML = "";

    if (members.length === 0) {
      teamMembersList.innerHTML = `<p class="text-sm text-zinc-500 dark:text-zinc-400">No members yet.</p>`;
      return;
    }

    members.forEach((member) => {
      const row = document.createElement("div");
      row.className =
        "flex items-center justify-between gap-3 px-3 py-2 rounded-lg bg-zinc-50 dark:bg-zinc-800 transition-colors";

      row.innerHTML = `
        <span class="text-sm text-zinc-800 dark:text-zinc-100 flex-1 min-w-0 truncate">${member.name}</span>
        <label class="flex items-center gap-1.5 text-xs text-zinc-600 dark:text-zinc-300 cursor-pointer">
          <input type="checkbox" class="leader-checkbox cursor-pointer" data-id="${member.id}" ${member.is_team_leader ? "checked" : ""}>
          Leader
        </label>
        <button type="button" class="remove-member-btn p-1.5 text-zinc-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-lg transition shrink-0" title="Remove from team">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      `;

      // Granting leadership is a real permission grant (visibility cascades
      // down the whole sub-team tree - TeamService::accessibleTeamIds), so
      // confirm before turning it ON. Turning it OFF (reducing access) is a
      // direct action.
      const checkbox = row.querySelector(".leader-checkbox");
      checkbox.addEventListener("change", async (e) => {
        const granting = e.target.checked;

        if (granting) {
          const confirmed = await customConfirm(
            `Make ${member.name} a leader of ${currentMembersTeam.name}? This gives them visibility into every record this team and its sub-teams own.`,
          );
          if (!confirmed) {
            e.target.checked = false;
            return;
          }
        }

        const res = await apiCall({
          mode: "PATCH",
          isJson: true,
          payload: { is_team_leader: granting },
          url: `/api/teams/${currentMembersTeam.id}/members/${member.id}`,
          button: e.target,
        });

        if (!res || res.success === false) {
          e.target.checked = !granting;
          showMessage({
            status: "error",
            title: "Error",
            message: res?.message || "Failed to update leader status.",
          });
          return;
        }

        await loadTeams();
      });

      row.querySelector(".remove-member-btn").addEventListener("click", async (e) => {
        const confirmed = await customConfirm(`Remove ${member.name} from this team?`);
        if (!confirmed) return;

        const res = await apiCall({
          mode: "DELETE",
          url: `/api/teams/${currentMembersTeam.id}/members/${member.id}`,
          button: e.currentTarget,
        });

        if (res && res.success === false) {
          showMessage({
            status: "error",
            title: "Error",
            message: res.message || "Failed to remove member.",
          });
          return;
        }

        await refreshMembers();
        await loadTeams();
      });

      teamMembersList.appendChild(row);
    });
  }

  function renderAddMemberSelect(allUsers, currentMembers) {
    const currentMemberIds = new Set(currentMembers.map((m) => m.id));

    addMemberSelect.innerHTML = "";
    allUsers
      .filter((u) => !currentMemberIds.has(u.id))
      .forEach((u) => {
        const opt = document.createElement("option");
        opt.value = u.id;
        opt.dataset.teamId = u.team_id || "";
        opt.dataset.teamName = u.team?.name || "";
        opt.textContent = u.team ? `${u.name} (currently in ${u.team.name})` : u.name;
        addMemberSelect.appendChild(opt);
      });

    refreshSearchable(addMemberSelect);
  }

  addMemberBtn.addEventListener("click", async () => {
    if (!currentMembersTeam) return;

    const selected = addMemberSelect.selectedOptions[0];
    if (!selected) return;

    if (selected.dataset.teamId) {
      const confirmed = await customConfirm(
        `${selected.textContent.split(" (")[0]} is already in "${selected.dataset.teamName}". Reassign to "${currentMembersTeam.name}"?`,
      );
      if (!confirmed) return;
    }

    const res = await apiCall({
      mode: "POST",
      isJson: true,
      payload: { user_id: selected.value },
      url: `/api/teams/${currentMembersTeam.id}/members`,
      button: addMemberBtn,
    });

    if (!res || res.success === false) {
      showMessage({
        status: "error",
        title: "Error",
        message: res?.message || "Failed to add member.",
      });
      return;
    }

    await refreshMembers();
    await loadTeams();
  });

  loadTeams();
};
