@php $navLayout = Auth::user()->nav_layout ?? 'side'; @endphp
<div class="container mx-auto p-5 max-w-5xl">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold" id="formPageTitle">New Lead</h1>
            <p class="text-zinc-500 text-sm">Fill in each stage. You may save and continue later.</p>
        </div>
        <button id="btnBackToList"
            class="border border-zinc-300 dark:border-zinc-700 px-4 py-2 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ← Back to List
        </button>
    </div>

    {{-- When the user's nav layout preference is "top", the horizontal top
         menu bar already fills the top of the screen - mirror that choice
         here by running the stepper as a left side-rail instead of another
         horizontal bar, rather than stacking two horizontal bars. --}}
    <div class="{{ $navLayout === 'top' ? 'flex gap-6 items-start' : '' }}">

    @php
        // flex-1 makes sense across a horizontal bar (equal-width tabs) but
        // would stretch each button to fill the sidebar's height when
        // stacked vertically - use full width instead, left-align the
        // label like the app's own side nav, and stop hiding the label at
        // small breakpoints since a 14rem-wide rail always has room for it.
        $stepBtnClass = $navLayout === 'top'
            ? 'w-full px-3 py-2 rounded-lg text-sm font-semibold border-2 flex items-center justify-start gap-2'
            : 'flex-1 px-3 py-2 rounded-lg text-sm font-semibold border-2 flex items-center justify-center gap-2';
        $stepLabelClass = $navLayout === 'top' ? '' : 'hidden sm:inline';
    @endphp
    <div class="{{ $navLayout === 'top' ? 'flex flex-col gap-2 w-56 shrink-0' : 'flex items-center gap-2 mb-2' }}" id="stageStepper">
        <button type="button" class="stage-btn substep-btn {{ $stepBtnClass }}"
            data-stage="1" data-substep="contact">
            <span class="step-indicator flex items-center justify-center w-5 h-5 rounded-full border border-current text-[11px] font-bold shrink-0">1</span>
            <span class="{{ $stepLabelClass }}">Contact</span>
        </button>
        <button type="button" class="stage-btn substep-btn {{ $stepBtnClass }}"
            data-stage="1" data-substep="company">
            <span class="step-indicator flex items-center justify-center w-5 h-5 rounded-full border border-current text-[11px] font-bold shrink-0">2</span>
            <span class="{{ $stepLabelClass }}">Company</span>
        </button>
        <button type="button" class="stage-btn substep-btn {{ $stepBtnClass }}"
            data-stage="1" data-substep="address">
            <span class="step-indicator flex items-center justify-center w-5 h-5 rounded-full border border-current text-[11px] font-bold shrink-0">3</span>
            <span class="{{ $stepLabelClass }}">Address</span>
        </button>
        <button type="button" id="stage2TabBtn"
            class="stage-btn {{ $stepBtnClass }} disabled:opacity-50 disabled:cursor-not-allowed"
            data-stage="2" disabled title="Save Stage 1 first">
            <span class="step-indicator flex items-center justify-center w-5 h-5 rounded-full border border-current text-[11px] font-bold shrink-0">🔒</span>
            <span class="{{ $stepLabelClass }}">Requirements</span>
        </button>
    </div>
    <div class="{{ $navLayout === 'top' ? 'flex-1 min-w-0' : '' }}">
    <div id="stepHint" class="text-xs text-zinc-500 dark:text-zinc-400 mb-6">
        Save Stage 1 (Contact, Company &amp; Address) to unlock Booking Requirements.
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">

        {{-- ===================== STAGE 1 ===================== --}}
        <div class="stage-panel" data-panel="1">
            <form id="stage1Form" class="space-y-6">

                {{-- ============ SUB-STEP: CONTACT ============ --}}
                <div class="substep-panel space-y-6" data-substep="contact">

                    {{-- Lead Classification --}}
                    <div>
                        <p class="font-semibold text-zinc-700 dark:text-zinc-200 mb-3">Lead Classification</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label class="text-xs font-medium text-zinc-400 uppercase">Client Type <span
                                        class="req-asterisk">*</span></label>
                                <div class="flex items-center gap-4 mt-2">
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="radio" name="client_type" value="corporate" checked
                                            class="client-type-radio">
                                        Corporate
                                    </label>
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="radio" name="client_type" value="individual"
                                            class="client-type-radio">
                                        Individual
                                    </label>
                                </div>
                            </div>
                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="source_select" class="text-xs font-medium text-zinc-400 uppercase">Lead
                                    Source <span class="req-asterisk">*</span></label>
                                <select id="source_select" name="source_select" required
                                    class="leadSourceDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                    <option value="">Select Source</option>
                                </select>
                                <input type="text" name="source_other" id="source_other" placeholder="Specify source"
                                    class="hidden w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-2">
                                <input type="text" name="source_referral_name" id="source_referral_name"
                                    placeholder="Name of referral"
                                    class="hidden w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-2">
                                <input type="text" name="source_social_media_platform" id="source_social_media_platform"
                                    placeholder="Specify social media platform"
                                    class="hidden w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-2">
                            </div>
                            <div class="md:col-span-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="requires_proposal" id="requires_proposal" checked>
                                    <span class="text-sm dark:text-zinc-200">Require Proposal</span>
                                </label>
                                <p class="text-xs text-zinc-400 mt-1">
                                    When checked, this lead can only be converted to a Client once it has an
                                    accepted Proposal. Uncheck for opportunities that don't need one.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Minimum to keep this lead --}}
                    <div class="border-t dark:border-zinc-700 pt-4">
                        <div
                            class="border-2 border-orange-400 dark:border-orange-600 bg-orange-50 dark:bg-orange-950/20 rounded-xl p-4">
                            <p class="font-semibold text-sm text-orange-700 dark:text-orange-300">Minimum to keep
                                this lead</p>
                            <p class="text-xs text-orange-600/80 dark:text-orange-400/80 mb-3">If nothing else gets
                                filled in today, this is what lets you follow up.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="title" class="text-xs font-medium text-zinc-400 uppercase">Title
                                        <span id="titleReqAsterisk" class="req-asterisk hidden">*</span></label>
                                    <select id="title" name="title"
                                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                        <option value="">Select Title</option>
                                        <option value="Atty.">Atty.</option>
                                        <option value="Dr.">Dr.</option>
                                        <option value="Engr.">Engr.</option>
                                        <option value="Mr.">Mr.</option>
                                        <option value="Mrs.">Mrs.</option>
                                        <option value="Ms.">Ms.</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="gender" class="text-xs font-medium text-zinc-400 uppercase">Gender</label>
                                    <select id="gender" name="gender"
                                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                        <option value="">Select Gender</option>
                                        @foreach (\App\Models\CrmLead::GENDERS as $genderOption)
                                            <option value="{{ $genderOption }}">{{ $genderOption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                    <label for="first_name" class="text-xs font-medium text-zinc-400 uppercase">First
                                        Name <span class="req-asterisk">*</span></label>
                                    <input type="text" id="first_name" name="first_name" required
                                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                </div>
                                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                    <label for="last_name" class="text-xs font-medium text-zinc-400 uppercase">Last
                                        Name
                                        <span class="req-asterisk">*</span></label>
                                    <input type="text" id="last_name" name="last_name" required
                                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="middle_name" class="text-xs font-medium text-zinc-400 uppercase">Middle
                                        Name</label>
                                    <input type="text" id="middle_name" name="middle_name"
                                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                </div>
                                <div class="md:col-span-2 border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                    <label for="mobile" class="text-xs font-medium text-zinc-400 uppercase">Mobile
                                        Number
                                        &amp; Type <span class="req-asterisk">*</span></label>
                                    <div class="flex gap-2 mt-1">
                                        <input type="text" id="mobile" name="mobile" required
                                            class="format-mobile flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                        <select name="mobile_type" required
                                            class="w-32 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-2 text-sm">
                                            <option value="">Type</option>
                                            <option value="personal">Personal</option>
                                            <option value="business">Business</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- More Contact Details --}}
                    <div class="border-t dark:border-zinc-700 pt-4">
                        <p class="font-semibold text-zinc-700 dark:text-zinc-200 mb-3">More Contact Details <span
                                class="text-xs font-normal normal-case text-zinc-400">— optional</span></p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="position"
                                    class="text-xs font-medium text-zinc-400 uppercase">Position
                                    <span id="positionReqAsterisk" class="req-asterisk hidden">*</span></label>
                                <input type="text" id="position" name="position"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                            </div>

                            <div>
                                <label for="landline_number" class="text-xs font-medium text-zinc-400 uppercase">Landline
                                    Number</label>
                                <div class="flex gap-2 mt-1">
                                    <input type="text" id="landline_number" name="landline_number"
                                        class="flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                    <select name="landline_type"
                                        class="w-32 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-2 text-sm">
                                        <option value="">Type</option>
                                        <option value="personal">Personal</option>
                                        <option value="business">Business</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="email" class="text-xs font-medium text-zinc-400 uppercase">Email
                                    <span id="emailReqAsterisk" class="req-asterisk hidden">*</span></label>
                                <div class="flex gap-2 mt-1">
                                    <input type="email" id="email" name="email"
                                        class="flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                    <select name="email_type"
                                        class="w-32 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-2 text-sm">
                                        <option value="">Type</option>
                                        <option value="personal">Personal</option>
                                        <option value="business">Business</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t dark:border-zinc-700">
                        <button type="button" id="contactContinueBtn"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium">
                            Continue to Company →
                        </button>
                    </div>
                </div>

                {{-- ============ SUB-STEP: COMPANY ============ --}}
                {{-- Company / Account Information - the name field always applies
                     (labeled "Company Name" for corporate, "Account Name" for
                     individual); type of business, industry, and authorized
                     signatory are corporate-only concepts and stay hidden for
                     Individual leads. --}}
                <div class="substep-panel hidden space-y-6" data-substep="company">
                    <div id="companyInfoSection">
                        <p class="font-semibold text-zinc-700 dark:text-zinc-200 mb-3" id="companyInfoTitle">Company
                            Information</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2 border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="company_name" class="text-xs font-medium text-zinc-400 uppercase"
                                    id="companyNameLabel">Company
                                    Name <span class="req-asterisk">*</span></label>
                                <input type="text" id="company_name" name="company_name" required
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                            </div>
                        </div>

                        <div id="corporateOnlyFields">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                    <label for="type_of_business"
                                        class="text-xs font-medium text-zinc-400 uppercase">Business Type <span
                                            class="req-asterisk">*</span></label>
                                    <select id="type_of_business" name="type_of_business" required
                                        class="typeOfBusinessDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                        <option value="">Select Business Type</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label for="industry_description"
                                        class="text-xs font-medium text-zinc-400 uppercase">Industry Description /
                                        Details</label>
                                    <textarea id="industry_description" name="industry_description" rows="2"
                                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1"></textarea>
                                </div>
                            </div>

                            {{-- Authorized Signatory --}}
                            <div class="border-t dark:border-zinc-700 mt-4 pt-4">
                                <p class="font-semibold text-zinc-700 dark:text-zinc-200 mb-3">Authorized Signatory</p>

                                <label class="flex items-center gap-2 text-sm mb-3">
                                    <input type="checkbox" id="signatoryMirrorCheck" checked>
                                    Same as contact person above
                                </label>
                                <p class="text-xs text-zinc-400 mb-3">
                                    While checked, the contact's Title, Position, and Email above are
                                    also required - they'll be used as the authorized signatory's.
                                </p>

                                <p id="signatoryMirrorSummary"
                                    class="text-sm text-zinc-600 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 mb-3">
                                </p>

                                <div id="signatoryMirrorFields" class="hidden">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                            <label for="authorized_signatory_title"
                                                class="text-xs font-medium text-zinc-400 uppercase">Title <span
                                                    class="req-asterisk">*</span></label>
                                            <select id="authorized_signatory_title" name="authorized_signatory_title"
                                                required
                                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                                <option value="">Select Title</option>
                                                <option value="Atty.">Atty.</option>
                                                <option value="Dr.">Dr.</option>
                                                <option value="Engr.">Engr.</option>
                                                <option value="Mr.">Mr.</option>
                                                <option value="Mrs.">Mrs.</option>
                                                <option value="Ms.">Ms.</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="authorized_signatory_gender"
                                                class="text-xs font-medium text-zinc-400 uppercase">Gender</label>
                                            <select id="authorized_signatory_gender"
                                                name="authorized_signatory_gender"
                                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                                <option value="">Select Gender</option>
                                                @foreach (\App\Models\CrmLead::GENDERS as $genderOption)
                                                    <option value="{{ $genderOption }}">{{ $genderOption }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                            <label for="authorized_signatory_first_name"
                                                class="text-xs font-medium text-zinc-400 uppercase">First Name <span
                                                    class="req-asterisk">*</span></label>
                                            <input type="text" id="authorized_signatory_first_name"
                                                name="authorized_signatory_first_name" required
                                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                        </div>
                                        <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                            <label for="authorized_signatory_last_name"
                                                class="text-xs font-medium text-zinc-400 uppercase">Last Name <span
                                                    class="req-asterisk">*</span></label>
                                            <input type="text" id="authorized_signatory_last_name"
                                                name="authorized_signatory_last_name" required
                                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                        </div>

                                        <div>
                                            <label for="authorized_signatory_middle_name"
                                                class="text-xs font-medium text-zinc-400 uppercase">Middle
                                                Name</label>
                                            <input type="text" id="authorized_signatory_middle_name"
                                                name="authorized_signatory_middle_name"
                                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                        </div>
                                        <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                            <label for="authorized_signatory_position"
                                                class="text-xs font-medium text-zinc-400 uppercase">Position <span
                                                    class="req-asterisk">*</span></label>
                                            <input type="text" id="authorized_signatory_position"
                                                name="authorized_signatory_position" required
                                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                        </div>

                                        <div>
                                            <label for="authorized_signatory_mobile"
                                                class="text-xs font-medium text-zinc-400 uppercase">Mobile
                                                Number</label>
                                            <div class="flex gap-2 mt-1">
                                                <input type="text" id="authorized_signatory_mobile"
                                                    name="authorized_signatory_mobile"
                                                    class="format-mobile flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                                <select name="authorized_signatory_mobile_type"
                                                    class="w-32 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-2 text-sm">
                                                    <option value="">Type</option>
                                                    <option value="personal">Personal</option>
                                                    <option value="business">Business</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="authorized_signatory_landline"
                                                class="text-xs font-medium text-zinc-400 uppercase">Landline
                                                Number</label>
                                            <div class="flex gap-2 mt-1">
                                                <input type="text" id="authorized_signatory_landline"
                                                    name="authorized_signatory_landline"
                                                    class="flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                                <select name="authorized_signatory_landline_type"
                                                    class="w-32 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-2 text-sm">
                                                    <option value="">Type</option>
                                                    <option value="personal">Personal</option>
                                                    <option value="business">Business</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="md:col-span-2 border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                            <label for="authorized_signatory_email"
                                                class="text-xs font-medium text-zinc-400 uppercase">Email <span
                                                    class="req-asterisk">*</span></label>
                                            <div class="flex gap-2 mt-1">
                                                <input type="email" id="authorized_signatory_email"
                                                    name="authorized_signatory_email" required
                                                    class="flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                                <select name="authorized_signatory_email_type" required
                                                    class="w-32 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-2 text-sm">
                                                    <option value="">Type</option>
                                                    <option value="personal">Personal</option>
                                                    <option value="business">Business</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between gap-2 pt-4 border-t dark:border-zinc-700">
                        <button type="button" id="companyBackBtn"
                            class="border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 px-5 py-2 rounded-lg text-sm font-medium">
                            ← Back
                        </button>
                        <button type="button" id="companyContinueBtn"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium">
                            Continue to Address →
                        </button>
                    </div>
                </div>

                {{-- ============ SUB-STEP: ADDRESS ============ --}}
                <div class="substep-panel hidden space-y-6" data-substep="address">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <p class="font-semibold text-zinc-700 dark:text-zinc-200">Address(es) <span
                                    class="req-asterisk">*</span>
                            </p>
                            <button type="button" id="addAddressBtn"
                                class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">+
                                Add
                                Address</button>
                        </div>
                        <div id="addressesContainer" class="space-y-4"></div>
                    </div>

                    <div class="flex justify-between gap-2 pt-4 border-t dark:border-zinc-700">
                        <button type="button" id="addressBackBtn"
                            class="border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 px-5 py-2 rounded-lg text-sm font-medium">
                            ← Back
                        </button>
                        <button type="button" id="saveStage1Btn"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium">
                            Save & Continue
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ===================== STAGE 2 ===================== --}}
        <div class="stage-panel hidden" data-panel="2">
            <div class="flex justify-between items-center mb-3">
                <p class="font-semibold text-zinc-700 dark:text-zinc-200">Booking Requirements</p>
                <button type="button" id="addContainerBtn"
                    class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">+
                    Add
                    Booking Requirement</button>
            </div>
            <div id="containersContainer" class="space-y-4"></div>

            <div class="flex justify-between gap-2 mt-6 pt-4 border-t dark:border-zinc-700">
                <button
                    class="stage-prev border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 px-5 py-2 rounded-lg text-sm font-medium"
                    data-target="1">Previous</button>
                <button id="saveStage2Btn"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium">
                    Save & Finish
                </button>
            </div>
        </div>
    </div>
    </div>
    </div>
</div>

<script>
    (function() {
        let leadUuid = window.crmLeadFormUuid || null;
        window.crmLeadFormUuid = null;
        let currentStage = 1;
        let currentSubStep = 'contact';

        const CONTAINER_TYPES = [{
                value: 'CV',
                label: 'Container Van (CV)'
            },
            {
                value: 'FR',
                label: 'Flatrack (FR)'
            },
            {
                value: 'RF',
                label: 'Reefer Van (RF)'
            },
            {
                value: 'LC',
                label: 'Loose Cargo (LC)'
            },
            {
                value: 'RC',
                label: 'Rolling Cargo (RC)'
            },
        ];
        const SERVICE_MODE_OPTIONS = [{
                value: 'PIER',
                label: 'Pier'
            },
            {
                value: 'DOOR',
                label: 'Door'
            },
        ];

        // Which extra field groups show for each container type. Reefer Van
        // has no ConVan class field at all (hidden, not just non-required),
        // and every type now splits Service Mode into origin/destination -
        // Loose Cargo and Rolling Cargo included - matching CV/FR/RF. Keep
        // this in sync with containerRowErrors()'s $typeFlags in
        // CrmLeadController.php.
        const TYPE_FIELD_VISIBILITY = {
            CV: {
                convanClass: true,
                convanSize: true,
                temperature: false,
                cbmTon: false,
                splitServiceMode: true
            },
            FR: {
                convanClass: false,
                convanSize: false,
                temperature: false,
                cbmTon: false,
                splitServiceMode: true
            },
            RF: {
                convanClass: false,
                convanSize: false,
                temperature: true,
                cbmTon: false,
                splitServiceMode: true
            },
            LC: {
                convanClass: false,
                convanSize: false,
                temperature: false,
                cbmTon: true,
                splitServiceMode: true
            },
            RC: {
                convanClass: false,
                convanSize: false,
                temperature: false,
                cbmTon: true,
                splitServiceMode: true
            },
        };
        let portsOptionsHtml = '';
        let locationsOptionsHtml = '';
        let portsData = [];
        // Class/size lists are owned per-Container (see Container::syncCatalog())
        // and keyed here by Container.code, which lines up with CONTAINER_TYPES'
        // values (CV/RF/FR/LC/RC) - not global lookups anymore.
        let containerCatalogByCode = {};
        // Cargo Type is LOV-backed (Option "Cargo Type") but, unlike
        // typeOfBusiness/leadSource, needs to render into a fresh select per
        // container card rather than a single page-level element - so it's
        // fetched once into a cached options-html string (same pattern as
        // portsOptionsHtml/locationsOptionsHtml above) and interpolated into
        // every containerCardHtml() render instead of DOM-inserted afterward.
        let cargoTypeOptionsHtml = '<option value="">Select Cargo Type</option>';

        function portOptionsForLocation(locationId) {
            const ports = locationId ?
                portsData.filter((p) => String(p.location_id) === String(locationId)) :
                portsData;

            return ports.map((p) => `<option value="${p.port_id}">${p.name}</option>`).join('');
        }

        function refreshSearchable(el) {
            el?._searchableSelect?.refresh();
        }

        async function loadContainerLookups() {
            const [portsRes, locationsRes, containersRes] = await Promise.all([
                apiCall({
                    mode: 'GET',
                    url: '/api/ports?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/locations?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/containers?per_page=200'
                }),
            ]);

            if (portsRes.success) {
                portsData = portsRes.data.data;
                portsOptionsHtml = portsData
                    .map((p) => `<option value="${p.port_id}">${p.location?.name ?? '-'} - ${p.name}</option>`)
                    .join('');
            }
            if (locationsRes.success) {
                locationsOptionsHtml = locationsRes.data.data
                    .map((l) => `<option value="${l.location_id}">${l.name}</option>`)
                    .join('');
            }
            if (containersRes.success) {
                containerCatalogByCode = {};
                containersRes.data.data.forEach((container) => {
                    containerCatalogByCode[container.code] = {
                        sizes: container.sizes ?? [],
                        classes: container.classes ?? [],
                    };
                });
            }
        }

        function populateSizeClassOptions(card) {
            const type = card.querySelector('.type-select').value;
            const catalog = containerCatalogByCode[type] ?? {
                sizes: [],
                classes: []
            };

            card.querySelector('[data-field="container_size_id"]').innerHTML =
                '<option value="">Select Size</option>' +
                catalog.sizes.map((s) => `<option value="${s.id}">${s.size}</option>`).join('');
        }

        async function fillCargoType() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/listofval/cargotype'
            });
            if (!Array.isArray(response)) return;
            cargoTypeOptionsHtml = '<option value="">Select Cargo Type</option>' +
                response.map(lov => `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join('');
        }

        const serviceModeOptionsHtml = (placeholder) =>
            `<option value="">${placeholder}</option>` +
            SERVICE_MODE_OPTIONS.map((o) => `<option value="${o.value}">${o.label}</option>`).join('');


        // -------------------- STEPPER --------------------
        // Highlights whichever stepper item matches the current stage/sub-step
        // combo. The first three items all share data-stage="1" and are
        // differentiated by data-substep; the Requirements item has no
        // data-substep and is simply matched on stage === 2.
        function updateStepperHighlight() {
            document.querySelectorAll('.stage-btn').forEach(b => {
                const stage = Number(b.dataset.stage);
                const active = stage === 2 ?
                    currentStage === 2 :
                    (currentStage === 1 && b.dataset.substep === currentSubStep);
                b.classList.toggle('border-orange-500', active);
                b.classList.toggle('text-orange-600', active);
                b.classList.toggle('border-zinc-200', !active);
                b.classList.toggle('dark:border-zinc-700', !active);
                b.classList.toggle('text-zinc-400', !active);
                b.classList.toggle('dark:text-zinc-500', !active);
            });
        }

        function showStage(stage) {
            currentStage = stage;
            document.querySelectorAll('.stage-panel').forEach(p => p.classList.toggle('hidden', Number(p.dataset
                .panel) !== stage));
            updateStepperHighlight();
        }

        // -------------------- SUB-STEPS (Stage 1: contact / company / address) --------------------
        // Same show-one-hide-others pattern as showStage(), nested one level
        // deeper - all three sub-steps live inside the same #stage1Form so
        // FormData(form) still captures every field regardless of which
        // sub-step is currently visible (hidden via the "hidden" class only,
        // never removed from the DOM).
        function showSubStep(name) {
            currentSubStep = name;
            document.querySelectorAll('.substep-panel').forEach(p => p.classList.toggle('hidden', p.dataset
                .substep !== name));
            updateStepperHighlight();
        }

        document.querySelectorAll('.stage-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const stage = Number(btn.dataset.stage);
                if (stage === 2) {
                    if (!leadUuid) return;
                    showStage(2);
                    return;
                }
                showStage(1);
                if (btn.dataset.substep) showSubStep(btn.dataset.substep);
            });
        });
        document.querySelectorAll('.stage-prev').forEach(btn => {
            btn.addEventListener('click', () => showStage(Number(btn.dataset.target)));
        });

        document.getElementById('contactContinueBtn').addEventListener('click', () => showSubStep('company'));
        document.getElementById('companyBackBtn').addEventListener('click', () => showSubStep('contact'));
        document.getElementById('companyContinueBtn').addEventListener('click', () => showSubStep('address'));
        document.getElementById('addressBackBtn').addEventListener('click', () => showSubStep('company'));

        // Stage 2's tab is disabled until a lead uuid exists (i.e. Stage 1 has
        // been saved at least once) - clicking it before then used to silently
        // no-op with no feedback. The first three stepper items switch from a
        // plain number to a checkmark at the same time, since they share the
        // same trigger condition (Stage 1 saved at least once); the
        // Requirements item switches from a lock icon to a plain number.
        function updateStepIndicators() {
            const done = Boolean(leadUuid);
            document.querySelectorAll('.substep-btn').forEach((btn, i) => {
                const indicator = btn.querySelector('.step-indicator');
                if (indicator) indicator.textContent = done ? '✓' : String(i + 1);
            });
            const stage2Indicator = document.querySelector('#stage2TabBtn .step-indicator');
            if (stage2Indicator) stage2Indicator.textContent = done ? '4' : '🔒';
        }

        function updateStage2TabAvailability() {
            const stage2Tab = document.getElementById('stage2TabBtn');
            if (!stage2Tab) return;
            stage2Tab.disabled = !leadUuid;
            stage2Tab.title = leadUuid ? '' : 'Save Stage 1 first';
            document.getElementById('stepHint')?.classList.toggle('hidden', Boolean(leadUuid));
            updateStepIndicators();
        }
        updateStage2TabAvailability();

        // -------------------- CLIENT TYPE (individual vs corporate) --------------------
        function applyClientTypeVisibility() {
            const clientType = document.querySelector('input[name="client_type"]:checked')?.value ?? 'corporate';
            const isIndividual = clientType === 'individual';

            // The name field itself always applies (it's just labeled
            // differently) - only type of business / industry / authorized
            // signatory are corporate-only concepts.
            document.getElementById('corporateOnlyFields').classList.toggle('hidden', isIndividual);

            const title = document.getElementById('companyInfoTitle');
            if (title) title.textContent = isIndividual ? 'Account Information' : 'Company Information';

            const nameLabel = document.getElementById('companyNameLabel');
            if (nameLabel) nameLabel.innerHTML = (isIndividual ? 'Account Name ' : 'Company Name ') +
                '<span class="req-asterisk">*</span>';

            applyContactFieldsRequiredForSignatoryMirror();
        }

        document.querySelectorAll('.client-type-radio').forEach(radio => {
            radio.addEventListener('change', applyClientTypeVisibility);
        });
        applyClientTypeVisibility();

        // -------------------- AUTHORIZED SIGNATORY MIRROR --------------------
        // Only matters while #corporateOnlyFields is visible (Corporate client
        // type) - no special handling needed for Individual, since the whole
        // block is already hidden then via applyClientTypeVisibility().
        const SIGNATORY_MIRROR_MAP = {
            title: 'authorized_signatory_title',
            gender: 'authorized_signatory_gender',
            first_name: 'authorized_signatory_first_name',
            last_name: 'authorized_signatory_last_name',
            middle_name: 'authorized_signatory_middle_name',
            position: 'authorized_signatory_position',
            mobile: 'authorized_signatory_mobile',
            mobile_type: 'authorized_signatory_mobile_type',
            email: 'authorized_signatory_email',
            email_type: 'authorized_signatory_email_type',
            landline_number: 'authorized_signatory_landline',
            landline_type: 'authorized_signatory_landline_type',
        };

        function updateSignatoryMirrorSummary() {
            const stage1Form = document.getElementById('stage1Form');
            const get = (name) => stage1Form.querySelector(`[name="${name}"]`)?.value?.trim() || '';
            const name = [get('first_name'), get('last_name')].filter(Boolean).join(' ') || '—';
            const position = get('position') || '—';
            const mobile = get('mobile') || '—';
            const summaryEl = document.getElementById('signatoryMirrorSummary');
            if (summaryEl) summaryEl.textContent = `${name} · ${position} · ${mobile}`;
        }

        // Live-syncs the actual authorized_signatory_* input/select values
        // (not just the display) from their Contact-field counterparts -
        // saveStage1Btn does a blind FormData(form) collection, so the hidden
        // fields must already hold the correct mirrored values at submit time.
        function syncSignatoryMirror() {
            const checkbox = document.getElementById('signatoryMirrorCheck');
            if (!checkbox || !checkbox.checked) return;
            const stage1Form = document.getElementById('stage1Form');
            Object.entries(SIGNATORY_MIRROR_MAP).forEach(([sourceName, targetName]) => {
                const source = stage1Form.querySelector(`[name="${sourceName}"]`);
                const target = stage1Form.querySelector(`[name="${targetName}"]`);
                if (source && target) target.value = source.value;
            });
            updateSignatoryMirrorSummary();
        }

        function applySignatoryMirrorVisibility() {
            const checkbox = document.getElementById('signatoryMirrorCheck');
            const checked = checkbox?.checked ?? true;
            document.getElementById('signatoryMirrorSummary')?.classList.toggle('hidden', !checked);
            document.getElementById('signatoryMirrorFields')?.classList.toggle('hidden', checked);
            if (checked) syncSignatoryMirror();
            applyContactFieldsRequiredForSignatoryMirror();
        }

        // The Authorized Signatory's own fields require Title, Position, and
        // Email (see #signatoryMirrorFields above) - while the mirror is on,
        // those same Contact fields feed the signatory directly, so they must
        // become required too. Otherwise a Contact left with a blank Title/
        // Position/Email silently mirrors into an incomplete signatory with
        // no visible required-field indicator, since the real signatory
        // fields are hidden while mirroring - see the "requirement input
        // field on authorized signatory" bug this closes.
        function applyContactFieldsRequiredForSignatoryMirror() {
            const clientType = document.querySelector('input[name="client_type"]:checked')?.value ?? 'corporate';
            const mirrorChecked = document.getElementById('signatoryMirrorCheck')?.checked ?? true;
            const needed = clientType === 'corporate' && mirrorChecked;
            const stage1Form = document.getElementById('stage1Form');

            [
                ['title', 'titleReqAsterisk'],
                ['position', 'positionReqAsterisk'],
                ['email', 'emailReqAsterisk'],
                ['email_type', null],
            ].forEach(([field, asteriskId]) => {
                const el = stage1Form.querySelector(`[name="${field}"]`);
                if (el) el.required = needed;
                if (asteriskId) document.getElementById(asteriskId)?.classList.toggle('hidden', !needed);
            });
        }

        document.getElementById('signatoryMirrorCheck')?.addEventListener('change', applySignatoryMirrorVisibility);

        // While unchecked, whatever the user has typed into the real
        // signatory fields is left alone - only sync while checked.
        Object.keys(SIGNATORY_MIRROR_MAP).forEach(sourceName => {
            const source = document.querySelector(`#stage1Form [name="${sourceName}"]`);
            if (!source) return;
            source.addEventListener('input', syncSignatoryMirror);
            source.addEventListener('change', syncSignatoryMirror);
        });

        applySignatoryMirrorVisibility();

        // -------------------- TYPE OF BUSINESS / LEAD SOURCE DROPDOWNS (LOV-backed) --------------------
        async function fillTypeOfBusiness() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/listofval/typeofbusiness'
            });
            const select = document.querySelector('.typeOfBusinessDropdown');
            if (!select || !Array.isArray(response)) return;
            response.forEach(lov => {
                select.insertAdjacentHTML('beforeend',
                    `<option value="${lov.lov_name}">${lov.lov_name}</option>`);
            });
        }

        async function fillLeadSource() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/listofval/leadsource'
            });
            const select = document.querySelector('.leadSourceDropdown');
            if (!select || !Array.isArray(response)) return;
            response.forEach(lov => {
                select.insertAdjacentHTML('beforeend',
                    `<option value="${lov.lov_name}">${lov.lov_name}</option>`);
            });
        }

        // Lead Source reveals a follow-up input for a few specific values -
        // "Other" needs the actual source typed out, "Referral" needs who
        // referred them, "Social Media" needs which platform. Only one of
        // the three is ever relevant at a time, so switching between them
        // hides+clears whichever one(s) no longer apply.
        const SOURCE_FOLLOWUP_INPUTS = {
            'Other': 'source_other',
            'Referral': 'source_referral_name',
            'Social Media': 'source_social_media_platform',
        };

        document.querySelector('.leadSourceDropdown').addEventListener('change', function() {
            const activeField = SOURCE_FOLLOWUP_INPUTS[this.value];

            Object.values(SOURCE_FOLLOWUP_INPUTS).forEach((name) => {
                const input = document.querySelector(`[name="${name}"]`);
                const isActive = name === activeField;
                input.classList.toggle('hidden', !isActive);
                input.required = isActive;
                if (!isActive) input.value = '';
            });
        });

        // -------------------- STAGE 1 --------------------
        function collectAddresses() {
            return Array.from(document.querySelectorAll('.address-card')).map(card => {
                const obj = {};
                card.querySelectorAll('[data-field]').forEach(el => {
                    obj[el.dataset.field] = el.value;
                });
                obj.is_primary = card.querySelector('.primary-radio')?.checked ?? false;
                return obj;
            });
        }

        // A blank type next to a filled-in value is ambiguous (personal or
        // business number?) - checked against the flat `data` object built
        // from FormData just before save, for both Contact and (whether
        // manually entered or mirrored) Authorized Signatory fields.
        const VALUE_TYPE_PAIRS = [
            ['mobile', 'mobile_type', 'Mobile Number'],
            ['landline_number', 'landline_type', 'Landline Number'],
            ['email', 'email_type', 'Email'],
            ['authorized_signatory_mobile', 'authorized_signatory_mobile_type', "Authorized Signatory's Mobile Number"],
            ['authorized_signatory_landline', 'authorized_signatory_landline_type', "Authorized Signatory's Landline Number"],
            ['authorized_signatory_email', 'authorized_signatory_email_type', "Authorized Signatory's Email"],
        ];

        function findMissingTypeFields(data, pairs) {
            return pairs
                .filter(([valueField, typeField]) => data[valueField] && !data[typeField])
                .map(([, , label]) => label);
        }

        document.getElementById('saveStage1Btn').addEventListener('click', async function() {
            const form = document.getElementById('stage1Form');
            const data = Object.fromEntries(new FormData(form).entries());

            // "Other" reveals a text input whose value REPLACES the source entirely;
            // "Referral"/"Social Media" instead APPEND detail after the category,
            // so the base category stays identifiable in the stored string.
            if (data.source_select === 'Other') {
                data.source = data.source_other || '';
            } else if (data.source_select === 'Referral' && data.source_referral_name) {
                data.source = `Referral - ${data.source_referral_name}`;
            } else if (data.source_select === 'Social Media' && data.source_social_media_platform) {
                data.source = `Social Media - ${data.source_social_media_platform}`;
            } else {
                data.source = data.source_select || '';
            }
            delete data.source_select;
            delete data.source_other;
            delete data.source_referral_name;
            delete data.source_social_media_platform;

            data.requires_proposal = document.getElementById('requires_proposal').checked;

            // A phone/email number without a Personal/Business type is
            // ambiguous data - block the save rather than let it through
            // silently with a blank type.
            const missingTypeFields = findMissingTypeFields(data, VALUE_TYPE_PAIRS);
            if (missingTypeFields.length) {
                showMessage({
                    status: 'error',
                    title: 'Select a type for each filled-in field',
                    message: `Choose a type for: ${missingTypeFields.join(', ')}.`,
                });
                return;
            }

            // Submission here is fetch-driven, not a native form submit, so the
            // `required` attribute set by applyContactFieldsRequiredForSignatoryMirror()
            // is purely visual and never actually blocks anything on its own -
            // this is the actual gate. Without it, a Corporate lead with the
            // mirror checked but Contact's Title/Position/Email left blank
            // saves "successfully" here, only to later fail the Authorized
            // Signatory completeness check with no clear indication why.
            if (
                document.querySelector('input[name="client_type"]:checked')?.value !== 'individual' &&
                document.getElementById('signatoryMirrorCheck')?.checked
            ) {
                const missingMirrorFields = [];
                if (!data.title) missingMirrorFields.push('Title');
                if (!data.position) missingMirrorFields.push('Position');
                if (!data.email) missingMirrorFields.push('Email');
                if (missingMirrorFields.length) {
                    showMessage({
                        status: 'error',
                        title: 'Authorized Signatory needs more info',
                        message: `Since "Same as contact person above" is checked, the contact's ` +
                            `${missingMirrorFields.join(', ')} ${missingMirrorFields.length > 1 ? 'are' : 'is'} ` +
                            `also required. Fill ${missingMirrorFields.length > 1 ? 'them' : 'it'} in, or ` +
                            `uncheck the box to enter the signatory's details separately.`,
                    });
                    return;
                }
            }

            const addresses = collectAddresses();
            if (!addresses.length) {
                showMessage({
                    status: 'error',
                    title: 'Add at least one address.'
                });
                return;
            }
            data.addresses = addresses;

            if (leadUuid) data.uuid = leadUuid;

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: data,
                url: '/api/crm/leads/stage1',
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error Saving',
                    message: response.message ?? 'Please check the required fields.'
                });
                return;
            }

            leadUuid = response.data.uuid;
            updateStage2TabAvailability();
            showMessage({
                status: 'success',
                title: 'Contact & Company Information Saved'
            });
            showStage(2);
        });

        // -------------------- STAGE 2: dynamic container cards --------------------
        function containerCardHtml(index) {
            return `
    <div class="container-card border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-3" data-index="${index}">
        <div class="flex justify-between items-center gap-2">
            <button type="button" class="card-toggle flex items-center gap-2 min-w-0 flex-1 text-left">
                <span class="card-toggle-chevron text-xs text-zinc-400 dark:text-zinc-500 transition-transform duration-200 rotate-180">▼</span>
                <span class="card-type-dot hidden w-2.5 h-2.5 rounded-full shrink-0"></span>
                <span class="card-summary text-sm font-medium text-zinc-600 dark:text-zinc-300 truncate">New Booking Requirement</span>
                <span class="card-qty-badge hidden shrink-0 text-[11px] font-semibold px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300"></span>
            </button>
            <select data-field="container_type" class="type-select border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm font-semibold shrink-0">
                ${CONTAINER_TYPES.map(t => `<option value="${t.value}">${t.label}</option>`).join('')}
            </select>
            <button type="button" class="remove-container text-red-500 text-xs font-medium shrink-0">✕ Remove</button>
        </div>

        <input type="hidden" data-field="booking_unit_type">

        <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="md:col-span-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">Container & Quantity</p>
            </div>
            <div class="field-convan-size hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="container_${index}_container_size_id" class="text-[11px] text-zinc-400 uppercase">ConVan Size <span class="req-asterisk">*</span></label>
                <select id="container_${index}_container_size_id" data-field="container_size_id" required class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Size</option>
                </select>
            </div>
            <div class="field-temperature hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="container_${index}_minimum_temperature" class="text-[11px] text-zinc-400 uppercase">Minimum Temperature (°C) <span class="req-asterisk">*</span></label>
                <input type="number" step="0.1" id="container_${index}_minimum_temperature" data-field="minimum_temperature" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div class="field-cbm-ton hidden">
                <label for="container_${index}_estimated_cbm" class="text-[11px] text-zinc-400 uppercase">Estimated CBM/s</label>
                <input type="number" step="0.01" id="container_${index}_estimated_cbm" data-field="estimated_cbm" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div class="field-cbm-ton hidden">
                <label for="container_${index}_estimated_ton" class="text-[11px] text-zinc-400 uppercase">Estimated Ton/s</label>
                <input type="number" step="0.01" id="container_${index}_estimated_ton" data-field="estimated_ton" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="container_${index}_quantity" class="text-[11px] text-zinc-400 uppercase">Quantity<span class="req-asterisk">*</span></label>
                <input type="number" id="container_${index}_quantity" data-field="quantity" required class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
    <label for="container_${index}_frequency" class="text-[11px] text-zinc-400 uppercase">Frequency <span class="req-asterisk">*</span></label>
    <select id="container_${index}_frequency" data-field="frequency" required class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
        <option value="">-</option>
        <option value="daily">Daily</option>
        <option value="Weekly">Weekly</option>
        <option value="Monthly">Monthly</option>
    </select>
</div>

            <div class="md:col-span-2 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">Route</p>
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="container_${index}_origin_location" class="text-[11px] text-zinc-400 uppercase">Origin Location <span class="req-asterisk">*</span></label>
                <select id="container_${index}_origin_location" class="origin-location-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Location</option>${locationsOptionsHtml}
                </select>
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="container_${index}_origin_port_id" class="text-[11px] text-zinc-400 uppercase">Origin Port <span class="req-asterisk">*</span></label>
                <select id="container_${index}_origin_port_id" data-field="origin_port_id" required disabled class="origin-port-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm disabled:opacity-50 disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
                    <option value="">Select Port</option>${portsOptionsHtml}
                </select>
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="container_${index}_destination_location" class="text-[11px] text-zinc-400 uppercase">Destination Location <span class="req-asterisk">*</span></label>
                <select id="container_${index}_destination_location" class="destination-location-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Location</option>${locationsOptionsHtml}
                </select>
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="container_${index}_destination_port_id" class="text-[11px] text-zinc-400 uppercase">Destination Port <span class="req-asterisk">*</span></label>
                <select id="container_${index}_destination_port_id" data-field="destination_port_id" required disabled class="destination-port-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm disabled:opacity-50 disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
                    <option value="">Select Port</option>${portsOptionsHtml}
                </select>
            </div>
            <div class="field-split-service hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="container_${index}_service_mode_origin" class="text-[11px] text-zinc-400 uppercase">Service Mode - Origin <span class="req-asterisk">*</span></label>
                <select id="container_${index}_service_mode_origin" data-field="service_mode_origin" required class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    ${serviceModeOptionsHtml('Select Mode')}
                </select>
            </div>
            <div class="field-split-service hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="container_${index}_service_mode_destination" class="text-[11px] text-zinc-400 uppercase">Service Mode - Destination <span class="req-asterisk">*</span></label>
                <select id="container_${index}_service_mode_destination" data-field="service_mode_destination" required class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    ${serviceModeOptionsHtml('Select Mode')}
                </select>
            </div>
            <div class="field-single-service hidden md:col-span-2 border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="container_${index}_service_mode" class="text-[11px] text-zinc-400 uppercase">Service Mode <span class="req-asterisk">*</span></label>
                <select id="container_${index}_service_mode" data-field="service_mode" required class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    ${serviceModeOptionsHtml('Select Mode')}
                </select>
            </div>

            <div class="md:col-span-2 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">Cargo Details</p>
            </div>
            <div class="md:col-span-2">
                <label for="container_${index}_cargo_type" class="text-[11px] text-zinc-400 uppercase">Cargo Type</label>
                <select id="container_${index}_cargo_type" data-field="cargo_type" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    ${cargoTypeOptionsHtml}
                </select>
            </div>
            <div class="md:col-span-2 border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="container_${index}_general_cargo_description" class="text-[11px] text-zinc-400 uppercase">General Cargo Description <span class="req-asterisk">*</span></label>
                <textarea id="container_${index}_general_cargo_description" data-field="general_cargo_description" required rows="2" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
            </div>
            <div>
                <label for="container_${index}_declared_value_per_unit" class="text-[11px] text-zinc-400 uppercase">Declared Value per Unit</label>
                <input type="text" inputmode="decimal" id="container_${index}_declared_value_per_unit" data-field="declared_value_per_unit" class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div class="md:col-span-2">
                <label for="container_${index}_special_requirements" class="text-[11px] text-zinc-400 uppercase">Special Requirements</label>
                <textarea id="container_${index}_special_requirements" data-field="special_requirements" rows="2" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
            </div>
            <div class="md:col-span-2">
                <label for="container_${index}_special_notes" class="text-[11px] text-zinc-400 uppercase">Special Notes</label>
                <textarea id="container_${index}_special_notes" data-field="special_notes" rows="2" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
            </div>
            <div class="md:col-span-2">
                <label for="container_${index}_dangerous_cargo" class="flex items-center gap-2">
                    <input type="checkbox" id="container_${index}_dangerous_cargo" data-field="dangerous_cargo" class="dg-checkbox">
                    <span class="text-sm dark:text-zinc-200">Dangerous Cargo (DG)</span>
                </label>
            </div>
            <div class="md:col-span-2">
                <label for="container_${index}_dg_file" class="text-[11px] text-zinc-400 uppercase">DG Documentary Requirement</label>
                <input type="file" id="container_${index}_dg_file" class="dg-file-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"
                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                <p class="text-xs text-zinc-400 mt-1">
                    Upload the supporting DG document (e.g. MSDS, DG declaration). PDF, JPG, PNG, DOC/DOCX up to 10MB.
                </p>
                <p class="dg-file-status text-xs text-zinc-500 mt-1"></p>
                <input type="hidden" data-field="dg_documentary_requirement">
            </div>
        </div>
        </div>
    </div>`;
        }

        function applyTypeVisibility(card) {
            const type = card.querySelector('.type-select').value;
            const flags = TYPE_FIELD_VISIBILITY[type];

            card.querySelector('.field-convan-size').classList.toggle('hidden', !flags.convanSize);
            card.querySelector('.field-temperature').classList.toggle('hidden', !flags.temperature);
            card.querySelectorAll('.field-cbm-ton').forEach(el => el.classList.toggle('hidden', !flags.cbmTon));
            card.querySelectorAll('.field-split-service').forEach(el => el.classList.toggle('hidden', !flags
                .splitServiceMode));
            card.querySelector('.field-single-service').classList.toggle('hidden', flags.splitServiceMode);
        }

        function syncBookingUnitType(card) {
            const typeSelect = card.querySelector('.type-select');
            const label = typeSelect.options[typeSelect.selectedIndex]?.textContent ?? '';
            card.querySelector('[data-field="booking_unit_type"]').value = label;
        }

        // Duplicated from logic_crm.js's CONTAINER_TYPE_ACCENT (dot colors
        // only) since this file doesn't share JS scope with that one.
        const CONTAINER_TYPE_DOT_COLOR = {
            CV: 'bg-orange-500',
            FR: 'bg-amber-500',
            RF: 'bg-cyan-500',
            LC: 'bg-purple-500',
            RC: 'bg-blue-500',
        };

        // Cards default to expanded when freshly added so the user can fill
        // them in immediately; hydrateExisting() collapses each one after
        // populating it, since that's the actual "can't tell 2-3 cards apart"
        // scenario the summary line is meant to solve. Collapsing hides the
        // editable type-select in favor of the compact dot/label/qty-badge
        // row; expanding restores it - the full sectioned form below
        // (.card-body) is unaffected either way.
        function setContainerCardExpanded(card, expanded) {
            const body = card.querySelector('.card-body');
            const chevron = card.querySelector('.card-toggle-chevron');
            body?.classList.toggle('hidden', !expanded);
            chevron?.classList.toggle('rotate-180', expanded);

            card.querySelector('.type-select')?.classList.toggle('hidden', !expanded);
            card.querySelector('.card-type-dot')?.classList.toggle('hidden', expanded);
            card.querySelector('.card-qty-badge')?.classList.toggle('hidden', expanded);
        }

        function updateContainerCardSummary(card) {
            const typeSelect = card.querySelector('.type-select');
            const typeLabel = typeSelect?.options[typeSelect.selectedIndex]?.textContent ?? '';

            const selectedLabel = (select) => (select && select.value) ?
                (select.options[select.selectedIndex]?.textContent ?? '—') : '—';

            const originPortLabel = selectedLabel(card.querySelector('.origin-port-select'));
            const originLabel = originPortLabel !== '—' ? originPortLabel :
                selectedLabel(card.querySelector('.origin-location-select'));

            const destinationPortLabel = selectedLabel(card.querySelector('.destination-port-select'));
            const destinationLabel = destinationPortLabel !== '—' ? destinationPortLabel :
                selectedLabel(card.querySelector('.destination-location-select'));

            const quantity = card.querySelector('[data-field="quantity"]')?.value || '—';

            const summaryEl = card.querySelector('.card-summary');
            if (summaryEl) summaryEl.textContent =
                `${typeLabel} · ${originLabel} → ${destinationLabel} · Qty: ${quantity}`;

            const dot = card.querySelector('.card-type-dot');
            if (dot) {
                Object.values(CONTAINER_TYPE_DOT_COLOR).forEach(cls => dot.classList.remove(cls));
                dot.classList.add(CONTAINER_TYPE_DOT_COLOR[typeSelect?.value] ?? 'bg-zinc-400');
            }

            const qtyBadge = card.querySelector('.card-qty-badge');
            if (qtyBadge) qtyBadge.textContent = `Qty ${quantity}`;
        }


        // -------------------- Duplicate booking-requirement detection --------------------
        // Same container type + route + size as an existing card almost always means the
        // rep re-entered a requirement instead of bumping its quantity - point them back at
        // the existing card (collapsed + highlighted) rather than silently allowing a duplicate.
        function containerCardSignature(card) {
            return {
                type: card.querySelector('.type-select')?.value || '',
                originPortId: card.querySelector('[data-field="origin_port_id"]')?.value || '',
                destinationPortId: card.querySelector('[data-field="destination_port_id"]')?.value || '',
                sizeId: card.querySelector('[data-field="container_size_id"]')?.value || '',
            };
        }

        function findDuplicateContainerCard(card) {
            const sig = containerCardSignature(card);
            if (!sig.type || !sig.originPortId || !sig.destinationPortId) return null;

            return Array.from(document.querySelectorAll('.container-card')).find(other => {
                if (other === card) return false;
                const otherSig = containerCardSignature(other);
                return otherSig.type === sig.type &&
                    otherSig.originPortId === sig.originPortId &&
                    otherSig.destinationPortId === sig.destinationPortId &&
                    otherSig.sizeId === sig.sizeId;
            }) || null;
        }

        function clearDuplicateHighlight(card) {
            card.classList.remove('border-2', 'border-orange-500', 'dark:border-orange-500');
            card.removeAttribute('title');
            card.removeAttribute('aria-label');
        }

        function flagDuplicateContainerCard(duplicateCard) {
            setContainerCardExpanded(duplicateCard, false);
            duplicateCard.classList.add('border-2', 'border-orange-500', 'dark:border-orange-500');
            const tooltip = 'Edit this if you want to add more quantity';
            duplicateCard.title = tooltip;
            duplicateCard.setAttribute('aria-label', tooltip);
        }

        function checkContainerCardDuplicate(card) {
            document.querySelectorAll('.container-card').forEach(clearDuplicateHighlight);

            const duplicate = findDuplicateContainerCard(card);
            if (!duplicate) return;

            showMessage({
                status: 'warning',
                title: 'Already on the list',
                message: 'A booking requirement with the same container type, route, and size is already on the list.',
            });
            flagDuplicateContainerCard(duplicate);
        }

        async function uploadDgFile(file) {
            const formData = new FormData();
            formData.append('dg_document', file);

            const response = await apiCall({
                mode: 'POST',
                isJson: false,
                payload: formData,
                url: '/api/crm/leads/uploadDgDocument',
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Upload failed',
                    message: response.message ?? 'Unable to upload the DG document.',
                });
                return null;
            }

            return response.data.path;
        }



        function addContainerCard() {
            const wrap = document.getElementById('containersContainer');
            const index = wrap.children.length;
            wrap.insertAdjacentHTML('beforeend', containerCardHtml(index));
            const card = wrap.lastElementChild;

            // Exclusive expand also applies when a new card is added, not just
            // on manual toggle clicks - otherwise adding a 2nd/3rd requirement
            // leaves every prior card still open, recreating the "wall of
            // fields" the collapse/summary treatment exists to prevent. The
            // newly-added card keeps its own default-expanded state.
            document.querySelectorAll('.container-card').forEach(other => {
                if (other !== card) setContainerCardExpanded(other, false);
            });

            ['.origin-location-select', '.origin-port-select', '.destination-location-select',
                '.destination-port-select'
            ]
            .forEach((sel) => makeSearchableSelect(card.querySelector(sel)));

            card.querySelector('.type-select').addEventListener('change', () => {
                applyTypeVisibility(card);
                syncBookingUnitType(card);
                populateSizeClassOptions(card);
                updateContainerCardSummary(card);
                checkContainerCardDuplicate(card);
            });
            card.querySelector('[data-field="container_size_id"]').addEventListener('change', () =>
                checkContainerCardDuplicate(card));
            card.querySelector('.remove-container').addEventListener('click', () => card.remove());

            card.querySelector('.card-toggle').addEventListener('click', () => {
                const expand = card.querySelector('.card-body').classList.contains('hidden');
                // Exclusive expand - opening a card collapses every other
                // currently-open card first. A lone card (the common case for
                // a brand-new lead) is unaffected since there are no siblings.
                if (expand) {
                    document.querySelectorAll('.container-card').forEach(other => {
                        if (other !== card) setContainerCardExpanded(other, false);
                    });
                }
                setContainerCardExpanded(card, expand);
            });

            populateSizeClassOptions(card);

            card.querySelector('.origin-location-select').addEventListener('change', function() {
                const portSelect = card.querySelector('.origin-port-select');
                portSelect.innerHTML =
                    `<option value="">Select Port</option>${portOptionsForLocation(this.value)}`;
                portSelect.disabled = !this.value;
                refreshSearchable(portSelect);
                updateContainerCardSummary(card);
            });
            card.querySelector('.destination-location-select').addEventListener('change', function() {
                const portSelect = card.querySelector('.destination-port-select');
                portSelect.innerHTML =
                    `<option value="">Select Port</option>${portOptionsForLocation(this.value)}`;
                portSelect.disabled = !this.value;
                refreshSearchable(portSelect);
                updateContainerCardSummary(card);
            });
            card.querySelector('.origin-port-select').addEventListener('change', () => {
                updateContainerCardSummary(card);
                checkContainerCardDuplicate(card);
            });
            card.querySelector('.destination-port-select').addEventListener('change', () => {
                updateContainerCardSummary(card);
                checkContainerCardDuplicate(card);
            });
            card.querySelector('[data-field="quantity"]').addEventListener('input', () =>
                updateContainerCardSummary(card));

            card.querySelector('.dg-file-input').addEventListener('change', async function() {
                const file = this.files[0];
                const statusEl = card.querySelector('.dg-file-status');
                const hiddenField = card.querySelector('[data-field="dg_documentary_requirement"]');

                if (!file) return;

                statusEl.textContent = 'Uploading...';
                const path = await uploadDgFile(file);

                if (!path) {
                    statusEl.textContent = '';
                    this.value = '';
                    return;
                }

                hiddenField.value = path;
                statusEl.textContent = `Uploaded: ${file.name}`;
            });

            applyTypeVisibility(card);
            syncBookingUnitType(card);
            updateContainerCardSummary(card);
        }

        document.getElementById('addContainerBtn').addEventListener('click', addContainerCard);

        function collectContainers() {
            return Array.from(document.querySelectorAll('.container-card')).map(card => {
                const obj = {};
                card.querySelectorAll('[data-field]').forEach(el => {
                    if (el.type === 'checkbox') {
                        obj[el.dataset.field] = el.checked;
                    } else if (el.classList.contains('currency-input')) {
                        obj[el.dataset.field] = parseCurrencyValue(el.value);
                    } else {
                        obj[el.dataset.field] = el.value;
                    }
                });
                return obj;
            });
        }

        document.getElementById('saveStage2Btn').addEventListener('click', async function() {
            if (!leadUuid) {
                showMessage({
                    status: 'error',
                    title: 'Save Stage 1 first'
                });
                return;
            }

            const containers = collectContainers();

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {
                    containers
                },
                url: `/api/crm/leads/${leadUuid}/stage2`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error Saving',
                    message: response.message ?? ''
                });
                return;
            }

            // The backend only actually promotes to Opportunity when every
            // requirement is met - report what really happened instead of
            // always claiming it moved. Only navigate away when the lead is
            // actually complete (moved or is_complete) - if requirements are
            // still missing, stay put so the rep can see the warning and act
            // on it (e.g. add the missing requirement) without losing their place.
            if (response.moved_to_opportunity) {
                showMessage({
                    status: 'success',
                    title: 'Lead moved to Opportunity!'
                });
                loadPage({
                    title: 'CRM Leads',
                    link: '/page_crm'
                });
            } else if (response.data.is_complete) {
                showMessage({
                    status: 'success',
                    title: 'Saved'
                });
                loadPage({
                    title: 'CRM Leads',
                    link: '/page_crm'
                });
            } else {
                showMessage({
                    status: 'warning',
                    title: 'Saved, but not yet moved to Opportunity',
                    message: `Still missing: ${(response.missing_requirements ?? []).join(', ')}`
                });
            }
        });

        document.getElementById('btnBackToList').addEventListener('click', () => {
            loadPage({
                title: 'CRM Leads',
                link: '/page_crm'
            });
        });

        // -------------------- HYDRATE EXISTING (edit / resume) --------------------
        async function hydrateExisting() {
            if (!leadUuid) return;

            document.getElementById('formPageTitle').textContent = 'Edit Lead';

            const response = await apiCall({
                mode: 'GET',
                url: `/api/crm/leads/${leadUuid}`
            });
            if (!response.success) return;
            const lead = response.data;
            const company = lead.company ?? {};

            const stage1Form = document.getElementById('stage1Form');
            [
                'title', 'first_name', 'middle_name', 'last_name', 'gender',
                'position', 'mobile', 'mobile_type',
                'landline_number', 'landline_type', 'email', 'email_type',
            ].forEach(key => {
                const el = stage1Form.querySelector(`[name="${key}"]`);
                if (el) el.value = lead[key] ?? '';
            });

            const clientType = lead.client_type || 'corporate';
            const clientTypeRadio = stage1Form.querySelector(
                `input[name="client_type"][value="${clientType}"]`);
            if (clientTypeRadio) clientTypeRadio.checked = true;
            applyClientTypeVisibility();

            document.getElementById('requires_proposal').checked = lead.requires_proposal !== false;

            ['company_name', 'type_of_business', 'industry_description'].forEach(key => {
                const el = stage1Form.querySelector(`[name="${key}"]`);
                if (el) el.value = company[key] ?? '';
            });

            [
                'authorized_signatory_title', 'authorized_signatory_first_name',
                'authorized_signatory_middle_name', 'authorized_signatory_last_name',
                'authorized_signatory_gender', 'authorized_signatory_position',
                'authorized_signatory_mobile', 'authorized_signatory_mobile_type',
                'authorized_signatory_landline', 'authorized_signatory_landline_type',
                'authorized_signatory_email', 'authorized_signatory_email_type',
            ].forEach(key => {
                const el = stage1Form.querySelector(`[name="${key}"]`);
                if (el) el.value = company[key] ?? '';
            });
            // Refresh the mirror summary's display text (not the sync itself)
            // so it reflects the hydrated Contact fields - hydration sets
            // values directly without dispatching input/change events, so
            // syncSignatoryMirror()'s listeners never fire here. Deliberately
            // NOT calling syncSignatoryMirror(), which would overwrite the
            // authorized_signatory_* values just set above from saved data.
            updateSignatoryMirrorSummary();

            // Lead source - select the matching option; reconstruct the
            // Referral/Social Media category + detail from the stored
            // "Category - detail" string, or fall back to "Other" + the
            // free-text input when the saved value isn't one of the known
            // options at all.
            const sourceSelect = document.querySelector('.leadSourceDropdown');
            const knownSource = Array.from(sourceSelect.options).some(o => o.value === lead
                .source);
            const referralMatch = lead.source?.match(/^Referral - (.*)$/);
            const socialMediaMatch = lead.source?.match(/^Social Media - (.*)$/);

            if (lead.source && knownSource) {
                sourceSelect.value = lead.source;
            } else if (referralMatch) {
                sourceSelect.value = 'Referral';
            } else if (socialMediaMatch) {
                sourceSelect.value = 'Social Media';
            } else if (lead.source) {
                sourceSelect.value = 'Other';
            }
            sourceSelect.dispatchEvent(new Event('change'));

            if (referralMatch) {
                stage1Form.querySelector('[name="source_referral_name"]').value = referralMatch[1];
            } else if (socialMediaMatch) {
                stage1Form.querySelector('[name="source_social_media_platform"]').value = socialMediaMatch[1];
            } else if (lead.source && !knownSource) {
                stage1Form.querySelector('[name="source_other"]').value = lead.source;
            }

            document.getElementById('addressesContainer').innerHTML = '';
            const addresses = (lead.addresses && lead.addresses.length) ? lead.addresses : [{
                is_primary: true
            }];
            for (const address of addresses) {
                const card = await addAddressCard();
                await hydrateAddressCard(card, address);
            }

            document.getElementById('containersContainer').innerHTML = '';
            (lead.containers || []).forEach(c => {
                addContainerCard();
                const card = document.getElementById('containersContainer').lastElementChild;

                card.querySelector('.type-select').value = c.container_type;
                applyTypeVisibility(card);
                syncBookingUnitType(card);
                populateSizeClassOptions(card);

                Object.entries(c).forEach(([key, val]) => {
                    const el = card.querySelector(`[data-field="${key}"]`);
                    if (!el) return;
                    if (el.type === 'checkbox') el.checked = Boolean(val);
                    else if (el.classList.contains('currency-input')) el.value =
                        formatCurrencyDisplay(val ?? '');
                    else el.value = val ?? '';
                });

                const originPort = portsData.find((p) => String(p.port_id) === String(c
                    .origin_port_id));
                if (originPort) {
                    card.querySelector('.origin-location-select').value = originPort.location_id ?? '';
                    card.querySelector('.origin-port-select').disabled = !originPort.location_id;
                }
                const destinationPort = portsData.find((p) => String(p.port_id) === String(c
                    .destination_port_id));
                if (destinationPort) {
                    card.querySelector('.destination-location-select').value = destinationPort
                        .location_id ?? '';
                    card.querySelector('.destination-port-select').disabled = !destinationPort
                        .location_id;
                }

                ['.origin-location-select', '.origin-port-select', '.destination-location-select',
                    '.destination-port-select'
                ]
                .forEach((sel) => refreshSearchable(card.querySelector(sel)));

                if (c.dg_documentary_requirement) {
                    card.querySelector('.dg-file-status').textContent =
                        `Existing file on record. Choose a new file only if you want to replace it.`;
                }

                updateContainerCardSummary(card);
                // Loaded leads can have 2-3 pre-filled cards that otherwise look
                // identical until scanned field-by-field - collapse each to its
                // summary line by default; the user expands the one they need.
                setContainerCardExpanded(card, false);
            });

            showStage(lead.current_stage || 1);
        }

        // -------------------- INIT --------------------
        showStage(1);
        showSubStep('contact');

        Promise.all([
            fillTypeOfBusiness(),
            fillLeadSource(),
            fillAddressTypeOptions(),
            loadContainerLookups(),
            fillCargoType(),
        ]).then(() => {
            if (leadUuid) {
                hydrateExisting();
            } else {
                addAddressCard(); // start with one blank address row
                addContainerCard(); // start with one blank container row
            }
        });


        const API = "https://psgc.cloud/api";

        async function request(url) {
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`Failed to fetch ${url}`);
            }

            return response.json();
        }

        function resetSelect(select, placeholder) {

            select.innerHTML = "";

            const option = document.createElement("option");
            option.value = "";
            option.textContent = placeholder;

            select.appendChild(option);
            select.disabled = true;

        }

        function populateSelect(select, items, placeholder) {

            resetSelect(select, placeholder);

            items.forEach(item => {

                const option = document.createElement("option");

                // The PSGC API returns some province/city/barangay names with
                // inconsistent trailing whitespace. Laravel's TrimStrings
                // middleware trims it server-side on save, so an untrimmed
                // option value here would never match the trimmed value
                // that comes back on hydration - the dropdown would silently
                // fail to re-select, breaking the city/barangay cascade.
                const name = (item.name || '').trim();
                option.value = name;
                option.textContent = name;

                option.dataset.code = item.code;
                // Only cities/municipalities carry a zip_code from the PSGC API -
                // PH postal codes are assigned per city/municipality, not per
                // barangay, so this is blank for province/barangay options.
                if (item.zip_code) option.dataset.zip = item.zip_code;

                select.appendChild(option);

            });

            select.disabled = false;

        }

        // -------------------- ADDRESSES: repeatable cards --------------------
        const COUNTRIES = [
            'Philippines', 'United States', 'Singapore', 'Hong Kong', 'China', 'Japan',
            'South Korea', 'Malaysia', 'Indonesia', 'Thailand', 'Vietnam', 'Taiwan',
            'Australia', 'United Kingdom', 'Canada', 'United Arab Emirates', 'Other',
        ];
        const countryOptionsHtml = COUNTRIES
            .map(c => `<option value="${c}" ${c === 'Philippines' ? 'selected' : ''}>${c}</option>`)
            .join('');

        let addressTypeOptionsHtml = '<option value="">Select Address Type</option>';

        async function fillAddressTypeOptions() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/listofval/addresstype'
            });
            if (!Array.isArray(response)) return;
            addressTypeOptionsHtml += response.map(lov =>
                `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join('');
        }

        function addressCardHtml(index) {
            return `
    <div class="address-card border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-3" data-index="${index}">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <select data-field="address_type" required class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm font-semibold">
                    ${addressTypeOptionsHtml}
                </select>
                <label class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400 whitespace-nowrap">
                    <input type="radio" name="address_primary_radio" class="primary-radio">
                    Primary
                </label>
            </div>
            <button type="button" class="remove-address text-red-500 text-xs font-medium">✕ Remove</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label for="address_${index}_no" class="text-[11px] text-zinc-400 uppercase">No.</label>
                <input type="text" id="address_${index}_no" data-field="address_no" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label for="address_${index}_building" class="text-[11px] text-zinc-400 uppercase">Building</label>
                <input type="text" id="address_${index}_building" data-field="address_building" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label for="address_${index}_street" class="text-[11px] text-zinc-400 uppercase">Street</label>
                <input type="text" id="address_${index}_street" data-field="address_street" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label for="address_${index}_country" class="text-[11px] text-zinc-400 uppercase">Country</label>
                <select id="address_${index}_country" data-field="address_country" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    ${countryOptionsHtml}
                </select>
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="address_${index}_province" class="text-[11px] text-zinc-400 uppercase">Province <span class="req-asterisk">*</span></label>
                <select id="address_${index}_province" data-field="address_province" required class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Province</option>
                </select>
            </div>
            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                <label for="address_${index}_town_city" class="text-[11px] text-zinc-400 uppercase">Town/City <span class="req-asterisk">*</span></label>
                <select id="address_${index}_town_city" data-field="address_town_city" required class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm" disabled>
                    <option value="">Select Town/City</option>
                </select>
            </div>
            <div>
                <label for="address_${index}_barangay" class="text-[11px] text-zinc-400 uppercase">Barangay</label>
                <select id="address_${index}_barangay" data-field="address_barangay" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm" disabled>
                    <option value="">Select Barangay</option>
                </select>
            </div>
            <div>
                <label for="address_${index}_postal_code" class="text-[11px] text-zinc-400 uppercase">Postal Code</label>
                <input type="text" id="address_${index}_postal_code" data-field="address_postal_code" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
        </div>
    </div>`;
        }

        async function addAddressCard() {
            const wrap = document.getElementById('addressesContainer');
            const index = wrap.children.length;
            wrap.insertAdjacentHTML('beforeend', addressCardHtml(index));
            const card = wrap.lastElementChild;

            card.querySelector('.remove-address').addEventListener('click', () => card.remove());
            if (index === 0) card.querySelector('.primary-radio').checked = true;

            await initializePhilippineAddress(card);
            return card;
        }

        document.getElementById('addAddressBtn').addEventListener('click', () => addAddressCard());

        async function hydrateAddressCard(card, address) {
            ['address_no', 'address_building', 'address_street', 'address_postal_code']
            .forEach(field => {
                const el = card.querySelector(`[data-field="${field}"]`);
                if (el) el.value = address[field] ?? '';
            });

            const typeSelect = card.querySelector('[data-field="address_type"]');
            if (typeSelect) typeSelect.value = address.address_type ?? '';

            const countrySelect = card.querySelector('[data-field="address_country"]');
            if (countrySelect) countrySelect.value = address.address_country || 'Philippines';

            card.querySelector('.primary-radio').checked = Boolean(address.is_primary);

            const {
                loadCitiesForProvince,
                loadBarangaysForCity
            } = card._addressLookups ?? {};
            const provinceSelect = card.querySelector('[data-field="address_province"]');
            const citySelect = card.querySelector('[data-field="address_town_city"]');
            const barangaySelect = card.querySelector('[data-field="address_barangay"]');

            if (address.address_province && provinceSelect) {
                provinceSelect.value = address.address_province;
                const provinceCode = provinceSelect.selectedOptions[0]?.dataset.code;

                if (loadCitiesForProvince) await loadCitiesForProvince(provinceCode);

                if (address.address_town_city && citySelect) {
                    citySelect.value = address.address_town_city;
                    const cityCode = citySelect.selectedOptions[0]?.dataset.code;

                    if (loadBarangaysForCity) await loadBarangaysForCity(cityCode);

                    if (address.address_barangay && barangaySelect) {
                        barangaySelect.value = address.address_barangay;
                    }
                }
            }
        }

        async function initializePhilippineAddress(container) {

            const province = container.querySelector('[data-field="address_province"]');
            const city = container.querySelector('[data-field="address_town_city"]');
            const barangay = container.querySelector('[data-field="address_barangay"]');

            if (!province || !city || !barangay) return;

            resetSelect(city, "Select Town/City");
            resetSelect(barangay, "Select Barangay");

            async function loadCitiesForProvince(provinceCode) {
                resetSelect(city, "Loading...");
                resetSelect(barangay, "Select Barangay");

                if (!provinceCode) {
                    resetSelect(city, "Select Town/City");
                    return;
                }

                const cities = await request(
                    `${API}/provinces/${provinceCode}/cities-municipalities`
                );
                cities.sort((a, b) => a.name.localeCompare(b.name));
                populateSelect(city, cities, "Select Town/City");
            }

            async function loadBarangaysForCity(cityCode) {
                resetSelect(barangay, "Loading...");

                if (!cityCode) {
                    resetSelect(barangay, "Select Barangay");
                    return;
                }

                const barangays = await request(
                    `${API}/cities-municipalities/${cityCode}/barangays`
                );
                barangays.sort((a, b) => a.name.localeCompare(b.name));
                populateSelect(barangay, barangays, "Select Barangay");
            }

            // Load Provinces
            const provinces = await request(`${API}/provinces`);
            provinces.sort((a, b) => a.name.localeCompare(b.name));
            populateSelect(province, provinces, "Select Province");

            province.addEventListener("change", function() {
                const provinceCode = this.selectedOptions[0]?.dataset.code;
                loadCitiesForProvince(provinceCode);
            });

            city.addEventListener("change", function() {
                const cityCode = this.selectedOptions[0]?.dataset.code;
                loadBarangaysForCity(cityCode);

                // Postal codes in the Philippines are assigned per city/
                // municipality, not per barangay - auto-fill from the PSGC
                // API's zip_code field the moment the city is picked, rather
                // than waiting on/tying it to barangay selection.
                const postalInput = container.querySelector('[data-field="address_postal_code"]');
                const zip = this.selectedOptions[0]?.dataset.zip;
                if (postalInput && zip) postalInput.value = zip;
            });

            // Exposed so hydrateAddressCard() can cascade a saved province/city
            // selection exactly the way a manual selection would, without
            // re-registering listeners or re-fetching the province list.
            container._addressLookups = {
                loadCitiesForProvince,
                loadBarangaysForCity
            };
        }

    })();
</script>
