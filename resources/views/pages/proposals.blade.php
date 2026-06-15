<div class="container mx-auto space-y-2">


    <div class="w-full flex">
        <button id="newProposalBtn"
            class="py-2 px-3 rounded-lg bg-orange-600 text-center font-bold flex items-center gap-1"><svg
                xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="5" stroke-linecap="round">
                <path d="M12 5V19" />
                <path d="M5 12H19" />
            </svg>
            Proposal</button>
    </div>
    <x-table-container>
        <table id="crmTable" class="w-full">
            <thead>
                <tr>
                    <th>Contact</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created</th>
                </tr>
            </thead>

            <tbody id="crmTableBody">

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
    })();
</script>
