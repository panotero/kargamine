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

<script>
    (function() {
        document.getElementById("newProposalBtn").addEventListener("click", function() {

            const proposalModal = document.querySelector("#generateProposal");
            if (!proposalModal) return;
            initSideModal({
                modalId: "generateProposal",
            });
        });
        RenderProposalTable();

        //render proposals
        async function RenderProposalTable() {

            //select table
            const table = document.getElementById("proposalTable");


            //initialize html variable
            let html = "";

            table.querySelector("tbody").innerHTML = initLoading();
            //get all proposals api


            const proposals = await apiCall({
                mode: "GET",
                url: `/api/proposal`,
            });

            console.log(proposals.data);

            proposals.data.forEach((row) => {
                const status = row.status?.status ?? "UNKNOWN";

                const statusClass = getStatusBadgeClass(row.status);

                html += `
        <tr class="cursor-pointer hover:bg-zinc-100"
            data-uuid="${row.uuid}">

            <td>${row.code}</td>
            <td>${row.lead.company.company_name} "No Company"</td>
            <td>${row.lead.contact_name }</td>
            <td>${row.rates.length}</td>

            <td>
                <span class="px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">
                    ${status}
                </span>
            </td>

            <td>${row.creator.name}</td>
            <td>${formatDateTime(row.created_at)}</td>

        </tr>
    `;
            });

            table.querySelector("tbody").innerHTML = html;

            initDataTables(10);

            //appennd html to the table
        }


    })();
</script>
