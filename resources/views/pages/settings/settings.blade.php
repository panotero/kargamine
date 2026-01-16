<div class="max-w-7xl h-screen mx-auto p-6">
    <h1 class="text-3xl font-semibold mb-8 text-center">Office & User Configuration</h1>

    <div class="flex flex-wrap justify-center gap-4 mb-8">

    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 mb-10">
        <div class="w-full flex justify-between p-5">

            <h2 class="text-xl font-semibold mb-4">Office List</h2>
            <button data-modal-name = "officeModal"
                class="px-5 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-2xl shadow-sm modal">
                Add Office
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse  p-5" id="officeTable">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-left">
                        <th class="p-3">ID</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Code</th>
                        <th class="p-3">Created At</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5">
        <div class="w-full flex justify-between p-5">

            <h2 class="text-xl font-semibold mb-4">User Config List</h2>
            <button data-modal-name = "userModal"
                class="px-5 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-2xl shadow-sm modal">
                Add User Config
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse  p-5" id="userTable">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-left">
                        <th class="p-3">ID</th>
                        <th class="p-3">Designation</th>
                        <th class="p-3">Approval Type</th>
                        <th class="p-3">Created At</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5">
        <div class="w-full flex justify-between p-5">

            <h2 class="text-xl font-semibold mb-4">Document Type List</h2>
            <button data-modal-name = "documentModal"
                class="px-5 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-2xl shadow-sm modal">
                Add Document Type
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse  p-5" id="documentTable">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-left">
                        <th class="p-3">ID</th>
                        <th class="p-3">Document Type</th>
                        <th class="p-3">Created At</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5">

        <div class="w-full flex justify-between p-5">

            <h2 class="text-xl font-semibold mb-4">Document Type List</h2>
            <button data-modal-name = "labelModal"
                class="px-5 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-2xl shadow-sm modal">
                Add Label Type
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse  p-5" id="labelTable">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-left">
                        <th class="p-3">ID</th>
                        <th class="p-3">Label</th>
                        <th class="p-3">Created At</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div id="officeModal"
    class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 modal-overlay modal">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-96 p-6">
        <h3 class="text-lg font-semibold mb-4">Add New Office</h3>
        <form id="officeForm">
            <input type="text" name="office_code" placeholder="Office Name" class="no-special-chars"
                class="w-full mb-3 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 p-2" required>
            <input type="text" name="office_code" placeholder="Office Code" class="no-special-chars"
                class="w-full mb-3 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 p-2" required>
            <div class="flex justify-end gap-3">
                <button type="button"
                    class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 closemodalbutton">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="userModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 modal-overlay modal">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-96 p-6">
        <h3 class="text-lg font-semibold mb-4">Add New User Config</h3>
        <form id="userForm">
            <input type="text" name="designation" placeholder="Designation"
                class="w-full mb-3 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 p-2" required>
            <select name="approval_type"
                class="w-full mb-3 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 p-2">
                <option value="routing">Routing</option>
                <option value="pre-approval">Pre-Approval</option>
                <option value="final-approval">Final-Approval</option>
            </select>
            <div class="flex justify-end gap-3">
                <button type="button"
                    class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 closemodalbutton">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white">Save</button>
            </div>
        </form>
    </div>
</div>
<div id="documentModal"
    class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 modal-overlay modal">

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-96 p-6">
        <h3 class="text-lg font-semibold mb-4">Add New Document Type</h3>

        <form id="documentTypeForm">
            <input type="text" name="document_type" placeholder="Document Type"
                class="w-full mb-3 rounded-lg border-gray-300 dark:border-gray-700
                          dark:bg-gray-900 p-2"
                required>
            <textarea name="description" placeholder="Description (optional)"
                class="no-special-chars w-full mb-3 rounded-lg border-gray-300 dark:border-gray-700
                             dark:bg-gray-900 p-2 h-24"></textarea>

            <div class="flex justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 closemodalbutton">
                    Cancel
                </button>

                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
<div id="labelModal"
    class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 modal-overlay modal">

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-96 p-6">
        <h3 class="text-lg font-semibold mb-4">Add New Document Type</h3>

        <form id="labelTypeForm">
            <input type="text" name="label_type" placeholder="Label"
                class="w-full mb-3 rounded-lg border-gray-300 dark:border-gray-700
                          dark:bg-gray-900 p-2"
                required>

            <div class="flex justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 closemodalbutton">
                    Cancel
                </button>

                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        const base = '/api';
        async function loadData() {
            const [offices, users, documentTypes, labelTypes] = await Promise.all([
                fetch(`${base}/offices`).then(res => res.json()),
                fetch(`${base}/userconfigs`).then(res => res.json()),
                fetch(`${base}/documenttypes`).then(res => res.json()),
                fetch(`${base}/labeltypes`).then(res => res.json()),
            ]);

            const officeTable = document.querySelector('#officeTable tbody');
            const userTable = document.querySelector('#userTable tbody');
            const documentTable = document.querySelector('#documentTable tbody');
            const labelTable = document.querySelector('#labelTable tbody');
            officeTable.innerHTML = '';
            userTable.innerHTML = '';
            documentTable.innerHTML = '';
            labelTable.innerHTML = '';

            offices.forEach(o => {
                officeTable.insertAdjacentHTML('beforeend', `
      <tr class="border-t hover:bg-gray-50 dark:hover:bg-gray-700">
        <td class="p-3">${o.office_id}</td>
        <td class="p-3">${o.office_code}</td>
        <td class="p-3">${o.office_code}</td>
        <td class="p-3">${o.created_at}</td>
        <td class="p-3"><a href="#" class="px-5 py-2 my-2  text-white bg-red-500 rounded-md deletebtn" data-mode="office" data-id="${o.office_id}">Delete</a></td>
      </tr>`);
            });
            documentTypes.forEach(d => {
                documentTable.insertAdjacentHTML('beforeend', `
        <tr class="border-t hover:bg-gray-50 dark:hover:bg-gray-700">
            <td class="p-3">${d.id}</td>
            <td class="p-3">${d.document_type}</td>
            <td class="p-3">${d.created_at}</td>
            <td class="p-3">
                <button
                    class="px-5 py-2 text-white bg-red-500 rounded-md deletebtn"
                    data-mode="documenttype"
                    data-id="${d.id}">
                    Delete
                </button>
            </td>
        </tr>
    `);
            });
            labelTypes.forEach(l => {
                labelTable.insertAdjacentHTML('beforeend', `
        <tr class="border-t hover:bg-gray-50 dark:hover:bg-gray-700">
            <td class="p-3">${l.id}</td>
            <td class="p-3">${l.label}</td>
            <td class="p-3">${l.created_at}</td>
            <td class="p-3">
                <button
                    class="px-5 py-2 text-white bg-red-500 rounded-md deletebtn"
                    data-mode="labeltype"
                    data-id="${l.id}">
                    Delete
                </button>
            </td>
        </tr>
    `);
            });

            users.forEach(u => {
                userTable.insertAdjacentHTML('beforeend', `
      <tr class="border-t hover:bg-gray-50 dark:hover:bg-gray-700">
        <td class="p-3">${u.id}</td>
        <td class="p-3">${u.designation}</td>
        <td class="p-3">${u.approval_type}</td>
        <td class="p-3">${u.created_at ?? ''}</td>
        <td class="p-3"><a href="#" class="px-5 py-2 my-2 text-white bg-red-500 rounded-md deletebtn" data-mode="user" data-id="${u.id}">Delete</a></td>
      </tr>`);
            });

            const deleteButton = document.querySelectorAll(".deletebtn");
            deleteButton.forEach(button => {
                button.addEventListener('click', () => {
                    const mode = button.dataset.mode;
                    const id = button.dataset.id;

                    if (mode === "office") {
                        deleteOffice(id);
                    } else if (mode === "user") {
                        deleteUser(id);
                    } else if (mode === "documenttype") {
                        deleteDocType(id);
                    } else if (mode === "labeltype") {
                        deleteLabelType(id);
                    } else {
                        console.warn("Unknown delete mode:", mode);
                    }
                })
            })
        }

        document.getElementById('officeForm').onsubmit = async e => {
            e.preventDefault();
            const formData = new FormData(e.target);
            await fetch(`${base}/offices`, {
                method: 'POST',
                body: formData
            });
            closeModal('officeModal');
            loadData();
        };

        document.getElementById('userForm').onsubmit = async e => {
            e.preventDefault();
            const formData = new FormData(e.target);
            await fetch(`${base}/userconfigs`, {
                method: 'POST',
                body: formData
            });
            closeModal('userModal');
            loadData();
        };
        document.getElementById('documentTypeForm').onsubmit = async e => {
            e.preventDefault();
            const formData = new FormData(e.target);
            await fetch(`${base}/documenttypes`, {
                method: 'POST',
                body: formData
            });
            closeModal('documentModal');
            loadData();
        };
        document.getElementById('labelTypeForm').onsubmit = async e => {
            e.preventDefault();
            const payload = {
                label_name: e.target.label_type.value
            };
            console.log(payload);

            // return;
            const response =
                await fetchWithRetry("/api/labeltypes", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                            .content,
                    },
                    body: payload,
                });
            // console.log(response);
            closeModal('labelModal');
            loadData();
        };
        async function deleteOffice(id) {
            await fetch(`${base}/offices/${id}`, {
                method: 'DELETE'
            });


            // const payload = {
            //     id: id
            // };
            // const result = await fetchWithRetry(`${base}/offices/${id}`, {
            //     method: "DELETE",
            //     headers: {
            //         "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            //             .content,
            //     },
            //     body: payload,
            // });
            loadData();
        }

        async function deleteUser(id) {
            await fetch(`${base}/userconfigs/${id}`, {
                method: 'DELETE'
            });
            loadData();
        }
        async function deleteDocType(id) {
            await fetch(`${base}/documenttypes/${id}`, {
                method: 'DELETE'
            });

            loadData();
        }
        async function deleteLabelType(id) {
            // await fetch(`${base}/labeltypes/${id}`, {
            //     method: 'DELETE'
            // });

            // const payload = {
            //     id: id
            // };
            const result = await fetchWithRetry(`${base}/labeltypes/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                        .content,
                },
            });

            loadData();
        }

        const modalButtons = document.querySelectorAll(".modal");
        const closemodalbutton = document.querySelectorAll(".closemodalbutton");
        modalButtons.forEach(button => {
            button.addEventListener('click', () => {
                const modalName = button.dataset.modalName;

                const modal = document.getElementById(modalName);
                if (modal) {
                    modal.classList.remove('hidden');
                }
            });
        });
        closemodalbutton.forEach(button => {
            button.addEventListener('click', () => {
                closeModal();
            });

        });

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                closeModal();
            }
        });

        function closeModal() {
            const openModals = document.querySelectorAll('[id$="Modal"]:not(.hidden)');
            openModals.forEach(m => m.classList.add('hidden'));
        }
        loadData();
    })();
</script>
