<div class="container mx-auto space-y-2">

    <div class="flex justify-between items-center mb-5 p-2">

        <div>
            <h1 class="text-2xl font-bold">Proposals</h1>
            <p class="text-zinc-500">Manage Proposals</p>
        </div>

        <button id="newProposalBtn" class="bg-orange-400 hover:bg-orange-500 text-white px-4 py-2 rounded-lg">
            + New Proposal
        </button>

    </div>
    <x-table-container>
        <table id="proposalTable" class="w-full">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Company</th>
                    <th>Contact Name</th>
                    <th>Proposed </th>
                    <th>Status</th>
                    <th>Proposed By</th>
                    <th>Created</th>
                </tr>
            </thead>

            <tbody id="proposalTableBody">

            </tbody>

        </table>
    </x-table-container>
</div>

<x-new-proposal-modal />
<x-proposal-modal />
<x-contract-modal />


<script>
    (function() { // ============================================================
        // CONSTANTS & STATE
        // ============================================================

        const modal = document.getElementById("proposalModal");
        const approveBtn = modal.querySelector("#approveBtn");
        const onHoldBtn = modal.querySelector("#onHoldBtn");
        const rejectBtn = modal.querySelector("#rejectBtn");

        let proposalCode = "";

        const STATUS = {
            PENDING: 1,
            APPROVED: 2,
            REJECTED: 3,
            ON_HOLD: 6,
        };

        const LEAD_INFO_MAPPING = {
            company_name: "leadInfoCompanyName",
            company_address: "leadCompanyAddress",
            contact_name: "leadContactName",
            email: "leadEmail",
            mobile: "leadMobile",
            signatory: "leadSignatory",
            signatory_position: "leadSignatoryPosition",
            source: "leadSource",
            estimated_value: "leadEstimatedValue",
            created_at: "leadCreatedAt",
            expected_close_date: "leadExpectedCloseDate",
            proposalCode: "proposalCode",
            proposedBy: "proposalProposedBy",
            proposalDate: "proposalCreatedAt",
            proposalEditDate: "proposalUpdatedAt",
            proposalModalCode: "proposalModalCode",
            proposalModalStatus: "proposalModalStatus",
        };

        // ============================================================
        // INIT
        // ============================================================

        document.getElementById("newProposalBtn").addEventListener("click", function() {
            if (!document.querySelector("#generateProposal")) return;
            initSideModal({
                modalId: "generateProposal"
            });
        });

        RenderProposalTable();

        // ============================================================
        // PROPOSAL TABLE
        // ============================================================

        async function RenderProposalTable() {

            const table = document.getElementById("proposalTable");
            const tbody = table.querySelector("tbody");

            tbody.innerHTML = initLoading();

            const proposals = await apiCall({
                mode: "GET",
                url: "/api/proposal",
            });

            if (!proposals.success) {
                showMessage({
                    status: "error",
                    title: "Error Fetching Proposals",
                    message: "There is an error fetching your information. Please contact the system administrator.",
                });
                return;
            }

            tbody.innerHTML = proposals.data.map(row => `
        <tr class="rowclick cursor-pointer hover:bg-zinc-100"
            data-proposal-code="${row.code}">
            <td>${row.code}</td>
            <td>${row.lead.company.company_name}</td>
            <td>${row.lead.contact_name}</td>
            <td>${row.rates.length}</td>
            <td>
                <span class="px-3 py-1 text-xs font-semibold rounded-full ${getStatusBadgeClass(row.status)}">
                    ${row.status?.status ?? "UNKNOWN"}
                </span>
            </td>
            <td>${row.creator.name}</td>
            <td>${formatDateTime(row.created_at)}</td>
        </tr>
    `).join("");

            initDataTables(10);

            table.querySelectorAll("tbody tr").forEach(row => {
                row.addEventListener("click", function() {
                    proposalCode = row.dataset.proposalCode;
                    loadProposalInfo(proposalCode);
                    initModal({
                        modalId: "proposalModal"
                    });
                });
            });
        }

        // ============================================================
        // LOAD PROPOSAL INFO
        // ============================================================

        async function loadProposalInfo(code) {

            const response = await apiCall({
                mode: "GET",
                url: `/api/proposal/${code}`,
            });

            if (!response.success) {
                showMessage({
                    status: "error",
                    title: "Error Fetching Proposal",
                    message: "There is an error fetching your information. Please contact the system administrator.",
                });
                return;
            }

            const proposal = response.data;

            renderLeadInfo(proposal);
            renderRates(proposal);
            document.getElementById("createContractBtn").dataset.proposalCode = proposal.code;
        }

        // ============================================================
        // RENDER LEAD INFO
        // ============================================================

        function renderLeadInfo(proposal) {

            const {
                lead,
                creator,
                status,
                code
            } = proposal;

            const data = {
                company_name: lead.company.company_name,
                company_address: lead.company.company_address,
                contact_name: lead.contact_name,
                email: lead.email,
                mobile: lead.mobile,
                signatory: lead.company.authorized_signatory_name,
                signatory_position: lead.company.authorized_signatory_position,
                source: lead.source,
                estimated_value: lead.estimated_value,
                created_at: lead.created_at,
                expected_close_date: lead.expected_close_date,
                proposalCode: code,
                proposedBy: creator.name,
                proposalDate: formatDateTime(proposal.created_at),
                proposalEditDate: formatDateTime(proposal.updated_at),
                proposalModalCode: code,
                proposalModalStatus: status.status,
            };

            Object.entries(LEAD_INFO_MAPPING).forEach(([key, elementId]) => {
                const el = modal.querySelector(`#${elementId}`);
                if (el) el.textContent = data[key] ?? "-";
            });

            // Toggle action button visibility based on status
            const approvalButtons = modal.querySelector(".approval-buttons");
            const contractButtons = modal.querySelector(".generate-contract-button");

            approvalButtons.classList.toggle("hidden", status.id !== STATUS.PENDING);
            contractButtons.classList.toggle("hidden", status.id !== STATUS.APPROVED);
        }

        // ============================================================
        // RENDER RATES
        // ============================================================

        function renderRates(proposal) {

            const container = modal.querySelector("#proposedRateContainer");
            const isEditable = proposal.status.id === STATUS.PENDING;

            container.innerHTML = proposal.rates.map(rate => `
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
            <div class="grid grid-cols-[1fr_1fr_auto] gap-4 items-start">

                <!-- Route -->
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest mb-1.5">Route</p>
                    <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-3 grid grid-cols-2 gap-x-4 gap-y-3">
                        <div>
                            <p class="text-[11px] text-zinc-400 mb-0.5">Origin</p>
                            <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100">${rate?.route_from?.code ?? "-"}</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-zinc-400 mb-0.5">Destination</p>
                            <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100">${rate?.route_to?.code ?? "-"}</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-zinc-400 mb-0.5">Origin Service</p>
                            <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100">${rate?.service_origin?.mode ?? "-"}</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-zinc-400 mb-0.5">Dest. Service</p>
                            <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100">${rate?.service_destination?.mode ?? "-"}</p>
                        </div>
                    </div>
                </div>

                <!-- Container -->
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest mb-1.5">Container</p>
                    <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-3 grid grid-cols-2 gap-x-4">
                        <div>
                            <p class="text-[11px] text-zinc-400 mb-0.5">Class</p>
                            <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100">${rate?.van_class?.class ?? "-"}</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-zinc-400 mb-0.5">Type</p>
                            <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100">${rate?.van_type?.type ?? "-"}</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-zinc-400 mb-0.5">Size</p>
                            <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100">${rate?.van_size?.size ?? "-"}</p>
                        </div>
                    </div>
                </div>

                <!-- Rate + Actions -->
                <div class="flex flex-col items-end gap-3 pt-6">
                    <div class="text-right">
                        <p class="text-[11px] text-zinc-400 mb-0.5">Proposed Rate</p>
                        <p class="text-base font-medium text-zinc-800 dark:text-zinc-100">${rate?.proposed_rate ?? "-"}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] text-zinc-400 mb-0.5">Min. Quantity</p>
                        <p class="text-base font-medium text-zinc-800 dark:text-zinc-100">${rate?.min_van_qty ?? "-"}</p>
                    </div>
                    <div class="flex gap-1.5 ${isEditable ? "" : "hidden"}">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white w-8 h-8 flex items-center justify-center rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a1.875 1.875 0 1 1 2.651 2.651L7.5 19.151 3 21l1.849-4.5L16.862 4.487Z" />
                            </svg>
                        </button>
                        <button class="bg-red-600 hover:bg-red-700 text-white w-8 h-8 flex items-center justify-center rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21H8.084a2.25 2.25 0 0 1-2.244-1.327L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.875c0-1.036-.84-1.875-1.875-1.875h-3.75c-1.035 0-1.875.839-1.875 1.875V5.25m7.5 0h-7.5" />
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    `).join("");
        }

        // ============================================================
        // UPDATE PROPOSAL STATUS
        // ============================================================

        async function updateProposalStatus(code, status, button) {

            const response = await apiCall({
                mode: "POST",
                isJson: true,
                payload: {
                    proposalCode: code,
                    status
                },
                url: "/api/proposal/approvals",
                button,
            });

            if (!response.success) {
                showMessage({
                    status: "error",
                    title: "Error Processing Information",
                    message: "There is some error processing your information. Please contact the system administrator.",
                });
                return false;
            }

            return true;
        }

        // ============================================================
        // APPROVAL BUTTON LISTENERS
        // ============================================================

        async function handleStatusUpdate(status) {
            await updateProposalStatus(proposalCode, status, null);
            await loadProposalInfo(proposalCode);
            await RenderProposalTable();
        }

        approveBtn.addEventListener("click", () => handleStatusUpdate(STATUS.APPROVED));
        onHoldBtn.addEventListener("click", () => handleStatusUpdate(STATUS.ON_HOLD));
        rejectBtn.addEventListener("click", () => handleStatusUpdate(STATUS.REJECTED));

        document.getElementById("createContractBtn").addEventListener("click", function() {
            if (typeof window.openCreateContractModal !== "function")
                return; // partial/script not on this page

            const proposalCode = this.dataset.proposalCode;
            window.openCreateContractModal(proposalCode);
        });
    })();
</script>
