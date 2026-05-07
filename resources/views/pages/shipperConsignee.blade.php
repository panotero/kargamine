<div class="container mx-auto space-y-5">

    <button id="newCustomer" class="px-5 py-2 rounded-lg bg-orange-400 hover:bg-orange-600 text-white font-bold">New
        Customer</button>
    <div class=" overflow-auto bg-white rounded-lg text-black border-2 border-slate-100 drop-shadow-md p-5">
        <div class="w-full rounded-lg overflow-hidden">
            <table class="w-full text-sm text-left text-black border-collapse responsive-table rounded-lg">
                <thead>
                    <tr>

                        <th class="px-4 py-3 text-left">Customer Code</th>

                        <th class="px-4 py-3 text-left">Company Name</th>

                        <th class="px-4 py-3 text-left">Type</th>

                        <th class="px-4 py-3 text-left">Industry</th>

                        <th class="px-4 py-3 text-left">Primary Contact</th>

                        <th class="px-4 py-3 text-left">Contact Number</th>

                        <th class="px-4 py-3 text-left">Account Owner</th>

                        <th class="px-4 py-3 text-left">Stage</th>

                        <th class="px-4 py-3 text-left">Potential Volume</th>

                        <th class="px-4 py-3 text-left">Status</th>

                        <th class="px-4 py-3 text-right">Action</th>

                    </tr>
                </thead>
                <tbody class="bg-gray-100">

                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- Modal --}}
<div id="NewCustomerModal" class="fixed inset-0 hidden z-40 flex items-center justify-center bg-black/50 px-4 modal">
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl w-full lg:max-w-[50vw] max-h-[90vh] overflow-y-auto">
        {{-- Header --}}
        <div class="w-full p-5 flex justify-between text-black dark:text-white border-b">
            <p class="text-xl font-semibold">CRM - Company Setup</p>
        </div>

        {{-- Contents --}}
        <div class="max-h-[70vh] overflow-y-auto p-5 space-y-6">
            {{-- ================= STAGE 1 ================= --}}
            <div class="stage" data-stage="1">
                <p class="font-semibold text-lg mb-3">Stage 1 - Company Information</p>

                <div class="space-y-2">

                    <!-- Customer Type -->
                    <div class="space-y-2">
                        <p class="text-sm font-medium dark:text-white">Customer Type</p>

                        <div class="flex flex-col md:flex-row gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input required type="radio" name="customer_type" value="shipper"
                                    class="w-4 h-4 text-blue-600 border-gray-300">
                                <span>Shipper</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input required type="radio" name="customer_type" value="consignee"
                                    class="w-4 h-4 text-blue-600 border-gray-300">
                                <span>Consignee</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input required type="radio" name="customer_type" value="both"
                                    class="w-4 h-4 text-blue-600 border-gray-300">
                                <span>Shipper / Consignee</span>
                            </label>
                        </div>
                    </div>

                    <!-- Form Grid -->
                    <div class="grid grid-cols-2 gap-3">

                        <div class="flex flex-col">
                            <label class="text-sm">Customer Code</label>
                            <input required type="text" id="customer_code"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm">Company Name</label>
                            <input required type="text" id="company_name"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                        </div>

                        <div class="flex flex-col col-span-2">
                            <label class="text-sm">Registered Address</label>
                            <textarea required id="registered_address" class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white"></textarea>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm">Contact Number 1</label>
                            <input required type="tel" id="contact_number_1"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm">Contact Number 2</label>
                            <input type="tel" id="contact_number_2"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                        </div>

                        <!-- Industry Dropdown -->
                        <div class="flex flex-col">
                            <label class="text-sm">Industry</label>

                            <select required id="industry"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                                <option value="">Select Industry</option>
                                <option value="logistics">Logistics</option>
                                <option value="manufacturing">Manufacturing</option>
                                <option value="retail">Retail</option>
                                <option value="ecommerce">E-Commerce</option>
                                <option value="construction">Construction</option>
                                <option value="food_beverage">Food & Beverage</option>
                                <option value="pharmaceutical">Pharmaceutical</option>
                                <option value="automotive">Automotive</option>
                                <option value="technology">Technology</option>
                                <option value="others">Others</option>
                            </select>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm">Organization Type</label>
                            <input required type="text" id="organization_type"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm">Tax Identification Number</label>
                            <input required type="text" id="tin"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm">Business Start Date</label>
                            <input required type="date" id="business_start_date"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm">Number of Employees</label>
                            <input required type="number" min="0" id="employees"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm">Synkar</label>

                            <select required id="synkar"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                                <option value="">Select Option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm">Estimated Annual Revenue</label>
                            <input required type="number" min="0" step="0.01" id="annual_revenue"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm">Estimated Net Income</label>
                            <input required type="number" min="0" step="0.01" id="net_income"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                        </div>

                        <div class="flex flex-col col-span-2">
                            <label class="text-sm">Company URL</label>
                            <input required type="url" id="company_url"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                        </div>

                    </div>

                </div>
            </div>

            {{-- ================= STAGE 2 ================= --}}
            <div class="stage hidden" data-stage="2">

                <p class="font-semibold text-lg mb-3">Stage 2 - Contact & References</p>

                <div class="space-y-2">

                    <p>Contact Information</p>

                    <div class="contact-card border p-4 rounded space-y-4">

                        <div class="flex justify-between">
                            <div class="dark:text-white contact-title">Contact 1</div>

                            <button type="button"
                                class="remove-contact dark:bg-zinc-800 dark:text-white px-2 py-1 border rounded-md border-zinc-500">
                                Remove
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-3">

                            <div class="flex flex-col">
                                <label class="text-sm dark:text-white">Contact Name</label>

                                <input required type="text"
                                    class="contact_name border p-2 rounded-lg dark:bg-zinc-800 dark:text-white"
                                    placeholder="Contact Name">
                            </div>

                            <div class="flex flex-col">
                                <label class="text-sm dark:text-white">Contact Number</label>

                                <input required type="tel"
                                    class="contact_number border p-2 rounded-lg dark:bg-zinc-800 dark:text-white"
                                    placeholder="Contact Number">
                            </div>

                            <div class="flex flex-col col-span-2">
                                <label class="text-sm dark:text-white">Email Address</label>

                                <input required type="email"
                                    class="contact_email border p-2 rounded-lg dark:bg-zinc-800 dark:text-white"
                                    placeholder="Email Address">
                            </div>

                            <div class="flex flex-col">
                                <label class="text-sm dark:text-white">Role</label>

                                <select required
                                    class="contact_role border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                                    <option value="">Select Role</option>
                                    <option value="manager">Manager</option>
                                </select>
                            </div>

                            <div class="flex flex-col">
                                <label class="text-sm dark:text-white">Position</label>

                                <select required
                                    class="contact_position border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
                                    <option value="">Select Position</option>
                                    <option value="head">Head</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div id="additionalContactContainer"></div>

                    <div>
                        <button id="addContact"
                            class="text-md dark:text-white dark:bg-zinc-800 border border-zinc-500 rounded-md px-2 py-1 hover:dark:bg-zinc-500">
                            + Add Contact
                        </button>
                    </div>
                </div>

                <div class="border p-4 rounded space-y-4 mt-4">

                    <p class="font-medium dark:text-white">Trade / Bank Reference</p>

                    <div class="flex flex-col">
                        <label class="text-sm dark:text-white">Business Name</label>

                        <input required type="text" id="business_name"
                            class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                            placeholder="Business Name" />
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm dark:text-white">Nature of Relationship</label>

                        <input required type="text" id="relationship"
                            class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                            placeholder="Nature of Relationship" />
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm dark:text-white">Business Address</label>

                        <textarea required id="business_address" class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                            placeholder="Business Address"></textarea>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm dark:text-white">Contact Person</label>

                        <input required type="text" id="ref_contact_person"
                            class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                            placeholder="Contact Person" />
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm dark:text-white">Phone Number</label>

                        <input required type="tel" id="ref_phone"
                            class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                            placeholder="Phone Number" />
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm dark:text-white">Email Address</label>

                        <input required type="email" id="ref_email"
                            class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                            placeholder="Email Address" />
                    </div>

                </div>
            </div>

            {{-- ================= STAGE 3 ================= --}}
            <div class="stage hidden" data-stage="3">

                <p class="font-semibold text-lg mb-3">Stage 3 - Finance & Sales</p>

                <div class="border p-4 rounded space-y-4">

                    <p class="font-medium dark:text-white">Finance</p>

                    <div class="flex flex-col">
                        <label class="text-sm dark:text-white">Credit Terms</label>

                        <select required id="credit_terms"
                            class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full">
                            <option value="">Select Option</option>
                            <option value="15">15 Days</option>
                            <option value="30">30 Days</option>
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm dark:text-white">Payment Mode</label>
                        <select required id="payment_mode"
                            class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full">
                            <option value="">Select Option</option>
                            <option value="COLLECT DESTINATION">COLLECT DESTINATION</option>
                            <option value="PREPAID">PREPAID</option>
                            <option value="ELSEWHERE">ELSEWHERE</option>
                            <option value="ON-ACCOUNT">ON-ACCOUNT</option>
                        </select>
                    </div>


                    <div class="space-y-2">
                        <p class="text-sm font-medium dark:text-white">Invoice Submission</p>

                        <div class="flex flex-col md:flex-row gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input required type="radio" name="invoice_submission" value="Email"
                                    class="w-4 h-4 text-blue-600 border-gray-300">
                                <span>Via Email</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input required type="radio" name="invoice_submission" value="Courier"
                                    class="w-4 h-4 text-blue-600 border-gray-300">
                                <span>Via Courier</span>
                            </label>
                        </div>
                    </div>
                    <div id="invoice_email_container" class="space-y-2 hidden">
                        <div class="flex flex-col">
                            <label class="text-sm dark:text-white">Invoice Email Address</label>

                            <input required type="email" id="invoice_email_address"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                                placeholder="Invoice Email Address" />
                        </div>
                    </div>

                    <div id="invoice_courier_container" class="space-y-2 hidden">

                        <div class="flex flex-col">
                            <label class="text-sm dark:text-white">Recepient Name</label>
                            <input required type="text" id="courier_recepient_name"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                                placeholder="Email Address" />
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm dark:text-white">Recepient Contact</label>

                            <input required type="text" id="courier_recepient_contact"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                                placeholder="Email Address" />
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm dark:text-white">Recepient Addreess</label>

                            <input required type="text" id="courier_recepient_address"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                                placeholder="Email Address" />
                        </div>
                    </div>

                    <p class="font-medium dark:text-white">Additional Billing Service Request</p>

                    <div class="space-y-2">

                        <div class="flex flex-col">
                            <label class="text-sm dark:text-white">Document Handling</label>
                            <input required type="text" id="document_handling"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                                placeholder="Email Address" />
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm dark:text-white">Billing Summary Report</label>

                            <input required type="text" id="billing_summary_report"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                                placeholder="Email Address" />
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm dark:text-white">Other</label>

                            <input required type="text" id="other_billing_request"
                                class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                                placeholder="Email Address" />
                        </div>
                    </div>

                </div>


                <div class="border p-4 rounded space-y-4 mt-4">

                    <p class="font-medium dark:text-white">Sales</p>

                    <div class="flex flex-col">
                        <label class="text-sm dark:text-white">Account Owner / Sales Rep</label>

                        <input required type="text" id="account_owner"
                            class="border p-2 rounded-lg dark:bg-zinc-800 dark:text-white w-full"
                            placeholder="Account Owner / Sales Rep" />
                    </div>

                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="border-t border-gray-200 px-6 py-4 flex justify-between">


            <div class="flex gap-3">

                <button id="prevBtn"
                    class="bg-zinc-600 hover:bg-zinc-400 text-white px-5 py-2 rounded-lg text-sm font-medium disabled:opacity-50">
                    Previous
                </button>
                <button id="nextBtn"
                    class="hover:bg-orange-600 bg-orange-400 text-white px-5 py-2 rounded-lg text-sm font-medium">
                    Next
                </button>
                <button
                    class="modal-close border hover:bg-zinc-400 hover:text-white border-zinc-500 dark:bg-zinc-600 dark:text-white px-5 py-2 rounded-lg text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const newCustomerButton = document.getElementById("newCustomer");
        newCustomerButton.addEventListener("click", function() {
            initModal({
                modalId: "NewCustomerModal",
            });
        });



        //modal control
        const modal = document.getElementById('NewCustomerModal');
        const stages = modal.querySelectorAll('.stage');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');


        let currentStage = 0;

        const payload = {
            contacts: [],
            trade_references: []
        };

        function showStage(index) {
            stages.forEach((s, i) => {
                s.classList.toggle('hidden', i !== index);
            });

            prevBtn.disabled = index === 0;
            nextBtn.textContent = index === stages.length - 1 ? 'Finish' : 'Next';
        }

        function collectStage1() {
            payload.customer_code = document.getElementById('customer_code').value;
            payload.company_name = document.getElementById('company_name').value;
            payload.registered_address = document.getElementById('registered_address').value;
            payload.contact_number_1 = document.getElementById('contact_number_1').value;
            payload.contact_number_2 = document.getElementById('contact_number_2').value;
            payload.industry = document.getElementById('industry').value;
            payload.organization_type = document.getElementById('organization_type').value;
            payload.tax_identification_number = document.getElementById('tin').value;
            payload.business_start_date = document.getElementById('business_start_date').value;
            payload.number_of_employees = document.getElementById('employees').value;
            payload.synkar = document.getElementById('synkar').value;
            payload.estimated_annual_revenue = document.getElementById('annual_revenue').value;
            payload.estimated_annual_net_income = document.getElementById('net_income').value;
            payload.company_url = document.getElementById('company_url').value;
            payload.customer_type = document.querySelector('input[name="customer_type"]:checked')?.value;
        }

        function collectStage2() {


            const contacts = [];

            document.querySelectorAll('.contact-card').forEach(card => {

                contacts.push({
                    contact_name: card.querySelector('.contact_name').value,
                    contact_number: card.querySelector('.contact_number').value,
                    email: card.querySelector('.contact_email').value,
                    role: card.querySelector('.contact_role').value,
                    position: card.querySelector('.contact_position').value
                });

            });
            payload.contacts = contacts;

            payload.trade_references = [{
                business_name: document.getElementById('business_name').value,
                relationship: document.getElementById('relationship').value,
                business_address: document.getElementById('business_address').value,
                contact_person_name: document.getElementById('ref_contact_person').value,
                contact_phone: document.getElementById('ref_phone').value,
                contact_email: document.getElementById('ref_email').value
            }];
        }

        function collectStage3() {

            payload.finance = {
                credit_terms: document.getElementById('credit_terms').value,
                payment_mode: document.getElementById('payment_mode').value,
                standard_billing_service: document.getElementById('billing_service').value,
                invoice_email: document.getElementById('invoice_email').value,
                invoice_email_address: document.getElementById('invoice_email_address').value
            };

            payload.sales = {
                account_owner: document.getElementById('account_owner').value
            };

        }

        function collect() {
            if (currentStage === 0) collectStage1();
            if (currentStage === 1) collectStage2();
            if (currentStage === 2) collectStage3();
        }

        nextBtn.addEventListener('click', async function() {
            // if (!validateCurrentStage()) {
            //     return;
            // }
            collect();

            console.log('STAGE ' + (currentStage + 1), JSON.parse(JSON.stringify(payload)));

            if (currentStage < stages.length - 1) {
                currentStage++;
                showStage(currentStage);
            } else {

                console.log('FINAL PAYLOAD', payload);

                const response = await apiCall({
                    mode: "POST",
                    isJson: true,
                    payload: payload,
                    url: "/api/companies",
                    button: nextBtn
                });
                // const response = await apiJSONPOST(payload, 'api/options', SaveOption);
                console.log("response: " + response);
                if (!response.success) {

                    showMessage({
                        status: "error",
                        title: "Error Saving Company Information",
                        message: "There is some error saving your company information. Please contact system administrator",
                    });
                    return;
                };

                currentStage = 0;
                showStage(currentStage);
                clearInputs();
            }
        });

        function validateCurrentStage() {

            const currentStageEl = document.querySelector(`.stage[data-stage="${currentStage + 1}"]`);

            const inputs = currentStageEl.querySelectorAll('input, select, textarea');

            for (let input of inputs) {

                if (!input.checkValidity()) {
                    input.reportValidity(); // shows browser tooltip
                    return false;
                }

            }

            return true;
        }

        prevBtn.addEventListener('click', function() {
            if (currentStage > 0) {
                currentStage--;
                showStage(currentStage);
            }
        });

        modal.querySelectorAll('.modal-close').forEach(btn => {
            btn.addEventListener('click', async function() {
                clearInputs();
            });

        });

        function clearInputs() {


            document.querySelectorAll("input").forEach(input => {
                input.value = "";
            });

            document.querySelectorAll("select").forEach(input => {
                input.value = "";
            });
        }

        showStage(currentStage);


        const addBtn = document.getElementById('addContact');
        const container = document.getElementById('additionalContactContainer');

        let contactCount = 1;

        function createContactCard() {
            contactCount++;

            const div = document.createElement('div');
            div.className = 'contact-card border p-4 rounded space-y-4 mt-3';

            div.innerHTML = `
            <div class="flex justify-between">
                <div class="dark:text-white contact-title">Contact ${contactCount}</div>
                <button type="button" class="remove-contact dark:bg-zinc-800 dark:text-white px-2 py-1 border rounded-md border-zinc-500">
                    Remove
                </button>
            </div>

            <div class="grid grid-cols-2 gap-3">

    <div class="flex flex-col">
        <label class="text-sm dark:text-white">Contact Name</label>
        <input required
            class="contact_name border p-2 rounded-lg dark:bg-zinc-800 dark:text-white"
            placeholder="Contact Name">
    </div>

    <div class="flex flex-col">
        <label class="text-sm dark:text-white">Contact Number</label>
        <input required
            class="contact_number border p-2 rounded-lg dark:bg-zinc-800 dark:text-white"
            placeholder="Contact Number">
    </div>

    <div class="flex flex-col col-span-2">
        <label class="text-sm dark:text-white">Email Address</label>
        <input required
            class="contact_email border p-2 rounded-lg dark:bg-zinc-800 dark:text-white"
            placeholder="Email Address">
    </div>

    <div class="flex flex-col">
        <label class="text-sm dark:text-white">Role</label>
        <select
            class="contact_role border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
            <option value="">Select Role</option>
            <option value="manager">Manager</option>
        </select>
    </div>

    <div class="flex flex-col">
        <label class="text-sm dark:text-white">Position</label>
        <select
            class="contact_position border p-2 rounded-lg dark:bg-zinc-800 dark:text-white">
            <option value="">Select Position</option>
            <option value="head">Head</option>
        </select>
    </div>

</div>
        `;

            container.appendChild(div);
        }
        //updating card number index on removal
        function reindexContacts() {
            const cards = document.querySelectorAll('.contact-card');
            cards.forEach((card, index) => {
                card.querySelector('.contact-title').textContent = `Contact ${index + 1}`;
            });
            contactCount = cards.length;
        }

        // Add
        addBtn.addEventListener('click', function() {
            createContactCard();
        });

        // Remove (event delegation)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-contact')) {
                e.target.closest('.contact-card').remove();
                reindexContacts();
            }
        });

        initDataTables(10);


        //function for invoice submission mode
        const invoiceSubmissionRadios = document.querySelectorAll(
            'input[name="invoice_submission"]'
        );

        const invoiceEmailContainer = document.getElementById('invoice_email_container');
        const invoiceCourierContainer = document.getElementById('invoice_courier_container');

        function toggleInvoiceSubmission() {

            const selected = document.querySelector(
                'input[name="invoice_submission"]:checked'
            );

            // Hide all first
            invoiceEmailContainer.classList.add('hidden');
            invoiceCourierContainer.classList.add('hidden');

            // Remove required first
            invoiceEmailContainer.querySelectorAll('input').forEach(input => {
                input.required = false;
            });

            invoiceCourierContainer.querySelectorAll('input').forEach(input => {
                input.required = false;
            });

            if (!selected) return;

            // VIA EMAIL
            if (selected.value === 'Email') {

                invoiceEmailContainer.classList.remove('hidden');

                invoiceEmailContainer.querySelectorAll('input').forEach(input => {
                    input.required = true;
                });
            }

            // VIA COURIER
            if (selected.value === 'Courier') {

                invoiceCourierContainer.classList.remove('hidden');

                invoiceCourierContainer.querySelectorAll('input').forEach(input => {
                    input.required = true;
                });
            }
        }

        // Radio change listener
        invoiceSubmissionRadios.forEach(radio => {
            radio.addEventListener('change', toggleInvoiceSubmission);
        });

        // Initial load
        toggleInvoiceSubmission();
    })();
</script>
