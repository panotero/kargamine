<div class="w-full min-h-screen p-5 bg-gray-50">
    <div class="container mx-auto space-y-6">

        <!-- SUMMARY CARDS -->
        <div class="w-full border rounded-lg bg-white shadow flex flex-col lg:flex-row gap-4 p-4">
            <div class="flex flex-col w-full lg:w-1/3 gap-4">
                <div class="flex gap-4">
                    <div class="flex-1 border rounded-lg p-4 bg-blue-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Total Users</div>
                        <div id="totalUsers" class="text-2xl font-bold mt-2">0</div>
                    </div>
                    <div class="flex-1 border rounded-lg p-4 bg-green-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Total Current Documents</div>
                        <div id="totalCurrentDocs" class="text-2xl font-bold mt-2">0</div>
                    </div>
                </div>
            </div>

            <!-- FILTERS + EXPORT -->
            <div class="flex flex-1 w-full gap-4 flex-wrap items-end">
                <div class="flex flex-col flex-1 gap-2">
                    <label class="text-gray-700 font-medium">Office</label>
                    <select id="officeFilter" class="border rounded-lg p-2 w-full"></select>
                </div>

                <div class="flex flex-col flex-1 gap-2 justify-end">
                    <button id="exportPdf"
                        class="w-full border rounded-lg p-2 bg-red-500 text-white font-medium hover:bg-red-600">
                        Export PDF
                    </button>
                    <button id="exportExcel"
                        class="w-full border rounded-lg p-2 bg-green-500 text-white font-medium hover:bg-green-600">
                        Export Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- USER REPORT TABLE -->
        <div class="w-full bg-white border rounded-lg shadow overflow-auto">
            <table id="userReportTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">User</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Email</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Office</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-500">Current Documents</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-500">Processed Documents</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    (function() {

        const authUser = window.authUser;
        if (!authUser) return;

        const officeFilter = document.getElementById('officeFilter');
        const tableBody = document.querySelector('#userReportTable tbody');

        const totalUsersEl = document.getElementById('totalUsers');
        const totalCurrentDocsEl = document.getElementById('totalCurrentDocs');

        let tableInstance = null;

        async function init() {
            await setupOfficeFilter();
            await loadReport(officeFilter.value);

            officeFilter.addEventListener('change', () => {
                loadReport(officeFilter.value);
            });

            document.getElementById('exportPdf').onclick = exportPDF;
            document.getElementById('exportExcel').onclick = exportExcel;
        }

        /**
         * Office filter logic
         */
        async function setupOfficeFilter() {
            const userOffice = authUser.office?.office_name;

            if (userOffice !== 'ODDG-PP') {
                officeFilter.innerHTML = `<option value="${userOffice}">${userOffice}</option>`;
                officeFilter.disabled = true;
                return;
            }

            const offices = @json(\App\Models\Office::orderBy('office_name')->pluck('office_name'));
            officeFilter.innerHTML = offices.map(o =>
                `<option value="${o}">${o}</option>`
            ).join('');
        }

        /**
         * Load API data
         */
        async function loadReport(officeName) {
            tableBody.innerHTML = '';
            totalUsersEl.textContent = '0';
            totalCurrentDocsEl.textContent = '0';

            if (tableInstance) {
                tableInstance.destroy();
                tableInstance = null;
            }

            const res = await fetch(`/api/reports/users/${officeName}`);
            const payload = await res.json();

            totalUsersEl.textContent = payload.total_users.toLocaleString();

            let totalCurrentDocs = 0;

            payload.data.forEach(user => {
                totalCurrentDocs += user.current_document_count;

                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50';

                tr.innerHTML = `
                <td class="px-4 py-2 text-sm font-medium">${user.name}</td>
                <td class="px-4 py-2 text-sm">${user.email}</td>
                <td class="px-4 py-2 text-sm">${user.office_name}</td>
                <td class="px-4 py-2 text-center font-semibold text-yellow-700">
                    ${user.current_document_count}
                </td>
                <td class="px-4 py-2 text-center font-semibold text-green-700">
                    ${user.processed_document_count}
                </td>
            `;
                tableBody.appendChild(tr);
            });

            totalCurrentDocsEl.textContent = totalCurrentDocs.toLocaleString();
            initDataTables();
        }

        /**
         * DataTables
         */
        function initDataTables() {
            tableInstance = $('#userReportTable').DataTable({
                paging: true,
                searching: true,
                info: true,
                lengthChange: false
            });
        }

        /**
         * Export
         */
        function exportExcel() {
            const wb = XLSX.utils.table_to_book(
                document.getElementById('userReportTable'), {
                    sheet: 'User Report'
                }
            );
            XLSX.writeFile(wb, 'user-report.xlsx');
        }

        function exportPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();
            doc.autoTable({
                html: '#userReportTable'
            });
            doc.save('user-report.pdf');
        }

        init();
    })();
</script>
