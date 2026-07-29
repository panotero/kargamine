<div class="flex flex-col lg:flex-row lg:h-[calc(100vh-73px)] lg:overflow-hidden" id="helpPage">

    {{-- In-page table of contents - fixed in place, outside the content's
         own scroll box entirely, so it's never carried along by scrolling.
         73px matches the app shell's header height (dashboard.blade.php's
         <header>: px-6 py-4 + its h-10 avatar + border-b). A percentage
         h-full didn't reliably reach 100% up this ancestor chain, so this
         uses an explicit viewport calc instead - see the click handler
         below for the other half of the fix (stopping the browser's
         default anchor-jump, which was scrolling the outer shell). --}}
    <nav class="lg:w-64 shrink-0 lg:h-[calc(100vh-73px)] lg:overflow-y-auto p-5 border-b lg:border-b-0 lg:border-r border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-3">On this page</p>
        <ul class="space-y-1 text-sm">
            <li><a href="#getting-started" class="help-toc-link block px-2 py-1.5 rounded-lg text-orange-600 dark:text-orange-400 font-semibold hover:bg-orange-50 dark:hover:bg-orange-950/40">1. Getting Started (Initial Setup)</a></li>
            <li><a href="#crm" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">2. CRM (Leads)</a></li>
            <li><a href="#clients" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">3. Clients</a></li>
            <li><a href="#proposals" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">4. Proposals</a></li>
            <li><a href="#contracts" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">5. Contracts</a></li>
            <li><a href="#container-inventory" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">6. Container Inventory</a></li>
            <li><a href="#bookings" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">7. Bookings</a></li>
            <li><a href="#cargo-build-up" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">8. Cargo Build-Up Dashboard</a></li>
            <li><a href="#dispatch" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">9. Dispatch (ATW / CAN)</a></li>
            <li><a href="#cv-assignment" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">10. CV Assignment</a></li>
            <li><a href="#pier-checkin" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">11. Pier Check-In (Gate Pass)</a></li>
            <li><a href="#eir" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">12. EIR (Equipment Interchange Receipt)</a></li>
            <li><a href="#vessel-voyages" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">13. Vessel Voyages &amp; Loadlist</a></li>
            <li><a href="#users-teams" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">14. Users, Roles &amp; Teams</a></li>
            <li><a href="#app-settings" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">15. App Settings Reference</a></li>
            <li><a href="#notifications" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">16. Notifications</a></li>
            <li><a href="#small-features" class="help-toc-link block px-2 py-1.5 rounded-lg text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">17. Small Features Reference</a></li>
        </ul>
    </nav>

    {{-- Content - the only element that actually scrolls on desktop. --}}
    <div id="helpContent" class="flex-1 min-w-0 lg:h-[calc(100vh-73px)] lg:overflow-y-auto px-5 py-8 lg:px-10 max-w-4xl">

        <div class="mb-10">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Help &amp; Documentation</h1>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">How to set up and use every part of this system, from
                first login to generating a vessel loadlist.</p>
        </div>

        {{-- ============================================================ --}}
        <section id="getting-started" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">1. Getting Started (Initial Setup)</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-4">Do these in order on a brand-new install. Skipping ahead
                (e.g. trying to create a booking before Ports/Container Classes exist) will just produce empty
                dropdowns and confusing validation errors.</p>

            <div class="rounded-lg border-l-4 border-orange-500 bg-orange-50 dark:bg-orange-950/30 p-4 mb-6">
                <p class="text-sm font-semibold text-orange-800 dark:text-orange-300">Golden rule: populate App
                    Settings (Maintenance) before you touch anything else.</p>
                <p class="text-sm text-orange-700 dark:text-orange-400 mt-1">Almost every dropdown in the CRM,
                    Clients, and Booking modules is sourced from Maintenance master data. If a dropdown is empty,
                    this is why.</p>
            </div>

            <ol class="space-y-4 text-sm text-zinc-700 dark:text-zinc-300">
                <li>
                    <p class="font-semibold text-zinc-900 dark:text-white">Step 1 — App Settings (sidebar → Settings
                        → App Settings)</p>
                    <p>Fill in this master data first, roughly in this order since later tabs reference earlier
                        ones:</p>
                    <ol class="list-decimal list-inside ml-2 mt-1 space-y-0.5 text-zinc-600 dark:text-zinc-400">
                        <li><strong>Ports</strong> — every port/pier you ship through.</li>
                        <li><strong>Containers, Container Classes, Container Sizes</strong> — the physical container
                            catalog (type/class/size combinations become "Container Variants").</li>
                        <li><strong>Charge Types</strong> — port charge and general charge categories.</li>
                        <li><strong>Delivery Types</strong> — Door-Door / Door-Pier / Pier-Door / Pier-Pier service
                            modes.</li>
                        <li><strong>Serviceable Areas</strong> — trucking areas per port.</li>
                        <li><strong>Lanes</strong> — origin/destination port pairs.</li>
                        <li><strong>Lane Tariff Rates</strong> — the FRT rate table per lane, versioned by effective
                            date.</li>
                        <li><strong>General Charges, Port Charges, Handling Fees, Trucking Tariffs, VAT Rates</strong>
                            — the remaining pricing tables the rate engine needs.</li>
                        <li><strong>Vessel Voyages</strong> — only needed once bookings start reaching the yard (see
                            §13) — can be filled in later.</li>
                        <li><strong>General Lookups</strong> — generic dropdown categories (Type of Business, Address
                            Type, Lead Source, etc.) used by CRM forms.</li>
                    </ol>
                    <p class="mt-1 text-xs text-zinc-500">See §15 for what each tab is actually for.</p>
                </li>
                <li>
                    <p class="font-semibold text-zinc-900 dark:text-white">Step 2 — Users, Roles &amp; Teams</p>
                    <p>Create user accounts, assign roles (superadmin/admin/user/developer), and build your team
                        hierarchy if you use team-based approval/visibility. See §14.</p>
                </li>
                <li>
                    <p class="font-semibold text-zinc-900 dark:text-white">Step 3 — Theme (sidebar → Developer Option
                        → Theme)</p>
                    <p>Pick your main/accent/secondary/danger colors. This is a one-time branding step — it applies
                        app-wide immediately, no restart needed.</p>
                </li>
                <li>
                    <p class="font-semibold text-zinc-900 dark:text-white">Step 4 — Mailer (sidebar → Developer
                        Option → Mailer)</p>
                    <p>Configure outgoing mail so system notifications (new lead assigned, proposal status changes,
                        etc.) actually deliver.</p>
                </li>
                <li>
                    <p class="font-semibold text-zinc-900 dark:text-white">Step 5 — Menus (sidebar → Developer Option
                        → Menus)</p>
                    <p>Only needed if you want to change which roles can see which sidebar item — the defaults
                        already cover every module in this guide.</p>
                </li>
            </ol>
            <p class="mt-4 text-sm text-zinc-600 dark:text-zinc-400">Once that's done, the day-to-day order of work
                is: <strong>CRM lead → Client → Proposal → Contract → Booking</strong> — each step is covered below in
                that order.</p>
        </section>

        {{-- ============================================================ --}}
        <section id="crm" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">2. CRM (Leads)</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-3">Sidebar → CRM. Every client starts life here as a Lead.</p>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li><strong>Creating a lead</strong> is a two-stage form: <em>Stage 1</em> (contact info — Title,
                    First/Middle/Last Name, Gender, Mobile/Landline/Email with type, source, and — for Corporate
                    leads — company info plus an Authorized Signatory section with the same name/contact structure)
                    and <em>Stage 2</em> (Booking Requirements — one or more cargo/container specs: type, size,
                    class, minimum temperature for reefers, quantity, weight/volume, service mode).</li>
                <li>A lead needs <strong>at least one complete address</strong> and <strong>at least one Booking
                    Requirement</strong> to be considered complete.</li>
                <li><strong>Promotion to "Opportunity"</strong> happens automatically once every required field is
                    filled in (contact name, mobile, source, company info for Corporate leads, Authorized Signatory
                    for Corporate leads, a complete address, and at least one Booking Requirement) — the save
                    confirmation tells you exactly what's still missing if it isn't promoted yet, instead of a vague
                    "saved" message.</li>
                <li>A Booking Requirement row can't be saved half-filled — origin/destination port, quantity, and the
                    type-specific fields (ConVan class/size, temperature for reefers, service mode) are all required
                    before it's accepted. Zero rows is fine (still Tentative); a partially-filled row is not.</li>
                <li><strong>Notes and Activity</strong> log everything against the lead automatically (status
                    changes, proposal events) — add manual notes from the Lead Info panel.</li>
                <li><strong>Visibility</strong> follows your team: a regular team member only sees leads assigned to
                    them; a team leader sees every lead assigned to anyone in their team or any sub-team beneath
                    them. superadmin always sees everything.</li>
            </ul>
        </section>

        {{-- ============================================================ --}}
        <section id="clients" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">3. Clients</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-3">Sidebar → Clients. Once a lead is worth pursuing, it
                converts into a Client Master record.</p>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li>Client Master is also a multi-stage form (company details → addresses → billing/finance), the
                    same completion-tracking pattern as CRM leads.</li>
                <li>Every client gets an auto-generated <strong>customer code</strong> once created.</li>
                <li>From here you move on to writing a Proposal, then (once accepted) a Contract.</li>
            </ul>
        </section>

        {{-- ============================================================ --}}
        <section id="proposals" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">4. Proposals</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-3">Sidebar → Proposals. A quoted rate sheet you send a
                client before signing a contract.</p>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li>Add one rate row per lane/container combination, with the discount you're offering and an
                    optional <strong>Minimum Quantity</strong> the client must book to actually qualify for that
                    discounted rate.</li>
                <li><strong>Approval isn't role-based, it's team-based</strong>: only the team leader directly over
                    the lead's assigned rep (or a leader further up that chain), or superadmin, can approve/disapprove
                    a proposal.</li>
                <li><strong>Rejection</strong> is different: it's config-driven by role, plus the client's own
                    assigned Sales Rep can always reject a proposal for their own client.</li>
                <li><strong>Uploading the signed document</strong> is open to anyone on the same team as the
                    approving leader (not just the leader themselves).</li>
                <li>Download a PDF of any proposal directly from its row.</li>
            </ul>
        </section>

        {{-- ============================================================ --}}
        <section id="contracts" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">5. Contracts</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-3">Sidebar → Contracts. Created from an accepted Proposal.</p>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li>A contract carries forward the proposal's rates, each with its own <strong>Minimum Quantity</strong>
                    if one was set.</li>
                <li>Set the contract's valid-from/valid-to window and upload the signed copy once executed.</li>
                <li>Contract status (Draft/Active/Expired/Terminated) drives whether it's still eligible to be picked
                    up automatically at Booking time (see §7).</li>
            </ul>
        </section>

        {{-- ============================================================ --}}
        <section id="container-inventory" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">6. Container Inventory</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-3">Sidebar → Container Inventory. The physical fleet of
                containers this system tracks and reserves for bookings.</p>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li>Each container asset has a <strong>status</strong>: Available, Booked, In Transit, Under Repair,
                    Damaged, or Out of Service — plus a current port and an optional pier reference.</li>
                <li>Every status/location change is recorded in that container's <strong>location history</strong>,
                    so you can trace where a specific container has been.</li>
                <li>You can manually mark a container Under Repair / Available / Out of Service, or relocate it to a
                    different port, from here.</li>
                <li>This is what Bookings actually reserve against — see §7.</li>
            </ul>
        </section>

        {{-- ============================================================ --}}
        <section id="bookings" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">7. Bookings</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-3">Sidebar → Bookings. This is the start of a long pipeline
                — a booking moves through Draft → Confirmed → the Cargo Build-Up stages (§8–13) as it's actually
                processed through the yard and loaded onto a vessel.</p>

            <h3 class="font-semibold text-zinc-900 dark:text-white mt-4 mb-1.5">Creating a booking</h3>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li>Search for the client first — this pre-fills contract-aware suggestions (quick-add chips showing
                    that client's contracted lanes/containers/rates) so you're not typing a booking from scratch.</li>
                <li>Every booking has one or more <strong>cargo lines</strong>, each with its own origin/destination
                    port, delivery mode (Door/Pier), container type, and quantity — different lines in the same
                    booking can ship to entirely different destinations.</li>
                <li><strong>Rate resolution</strong>: search a client → the system checks their active contract for a
                    matching lane/container rate. If found <em>and</em> the line's quantity meets that rate's Minimum
                    Quantity, the contract discount applies. Otherwise it falls back to the standard tariff rate, no
                    discount. No client search at all always means standard tariff.</li>
                <li><strong>Container assignment</strong> per line is either Auto-assign (system picks the
                    longest-idle available container) or Choose specific container(s) — both are restricted to
                    containers <em>currently at that line's origin port</em>, never a different port.</li>
                <li>Each cargo line has a <strong>Transaction Details</strong> section (consignee name/address/contact,
                    cargo type, other cargo details, declared value, delivery date + notes + first/last delivery
                    date) — this is optional at save time, but every line needs it filled in before the booking counts
                    as "Live" on the Cargo Build-Up board (§8).</li>
                <li><strong>Save as Draft</strong> reserves containers but doesn't lock pricing yet.</li>
            </ul>

            <h3 class="font-semibold text-zinc-900 dark:text-white mt-4 mb-1.5">Confirming a booking</h3>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li>Open the booking row (only Draft bookings open the edit form directly) — the <strong>Confirm
                    Booking</strong> button is on that edit form.</li>
                <li>Confirming requires every container unit on the booking to already be assigned, re-resolves
                    final pricing in case rates moved since Draft, and generates the booking's Invoice and Bill of
                    Lading.</li>
                <li>You can Cancel a Draft or Confirmed booking (releases any reserved containers back to Available)
                    and advance a confirmed booking through In Transit → Delivered → Completed.</li>
            </ul>
        </section>

        {{-- ============================================================ --}}
        <section id="cargo-build-up" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">8. Cargo Build-Up Dashboard</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-3">Sidebar → Cargo Build-Up. The single screen that answers
                "where is this shipment right now, and what needs to happen to it next?" — every confirmed booking's
                containers move through these 13 stages in order.</p>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs mb-3">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Bucket</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">What it means</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        <tr><td class="px-3 py-2 font-medium">Cargo Build-Up</td><td class="px-3 py-2">Master view — every booking with at least one cargo line.</td></tr>
                        <tr><td class="px-3 py-2 font-medium">Tentative</td><td class="px-3 py-2">Booking lodged, but Transaction Details aren't filled in on every line yet.</td></tr>
                        <tr><td class="px-3 py-2 font-medium">Live</td><td class="px-3 py-2">Every line's Transaction Details are complete.</td></tr>
                        <tr><td class="px-3 py-2 font-medium">For ATW / For CAN</td><td class="px-3 py-2">Live, but the line's dispatch document hasn't been generated yet (§9).</td></tr>
                        <tr><td class="px-3 py-2 font-medium">For Documentation</td><td class="px-3 py-2">Dispatch document exists, but EIR Out hasn't been issued yet (§12).</td></tr>
                        <tr><td class="px-3 py-2 font-medium">For CV Assignment</td><td class="px-3 py-2">Dispatch document(s) done, but a container still needs Proforma BL/Waybill/Seal filled in (§10).</td></tr>
                        <tr><td class="px-3 py-2 font-medium">For Gate Out</td><td class="px-3 py-2">EIR Out issued, ready to physically scan out — still in the yard.</td></tr>
                        <tr><td class="px-3 py-2 font-medium">Pick-up In Transit</td><td class="px-3 py-2">Scanned out, not yet scanned back in (§11).</td></tr>
                        <tr><td class="px-3 py-2 font-medium">Partial In Yard</td><td class="px-3 py-2">Physically back, but EIR In hasn't been issued yet.</td></tr>
                        <tr><td class="px-3 py-2 font-medium">In Yard</td><td class="px-3 py-2">EIR In done, and not a "foul" trip — ready for a vessel.</td></tr>
                        <tr><td class="px-3 py-2 font-medium">For Vessel Loading</td><td class="px-3 py-2">In Yard, with a vessel voyage assigned (§13).</td></tr>
                        <tr><td class="px-3 py-2 font-medium">Shut Out</td><td class="px-3 py-2">Missed its vessel's actual cutoff — needs the next voyage assigned.</td></tr>
                    </tbody>
                </table>
            </div>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li>Click any tile to drill into the bookings/containers in that bucket. <strong>For Gate Out</strong>
                    and <strong>Pick-up In Transit</strong> send you straight to Pier Check-In instead (§11) —
                    that's where the actual scanning happens.</li>
                <li>The <strong>Voyage</strong> column in the table is where you Assign Voyage / Shut Out / Reassign
                    a container directly (§13) — the SOP explicitly places this action here, not on the booking
                    itself.</li>
            </ul>
        </section>

        {{-- ============================================================ --}}
        <section id="dispatch" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">9. Dispatch (ATW / CAN)</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-3">Generated from the booking's View modal, one per cargo
                line. ATW (Authorization To Withdraw) and CAN (Cargo Acceptance Note) are the two dispatch document
                types — the system picks the right one for you automatically.</p>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li><strong>The routing rule:</strong> a line whose origin is serviced Door (Door-Door or Door-Pier)
                    gets an <strong>ATW</strong>. A line whose origin is Pier (Pier-Door or Pier-Pier) gets a
                    <strong>CAN</strong> — unless the client is flagged to always route ATW regardless (a per-client
                    override for special-handling accounts).</li>
                <li>The form captures Trip Type (Tandem / Tandem Foul / Single / Single Foul), trailer capacity,
                    truck &amp; personnel (trucker, plate number, driver, helper, coordinator/checker), Single
                    Pickup / Advance Pull Out flags, and the CY Operations timestamps (empty pull-out, stuffing,
                    stripping, delivery, estimated departure/arrival).</li>
                <li>A line can only get one dispatch document, ever — there's no re-issuing.</li>
                <li><strong>Important:</strong> a "Foul" trip type here is what later excludes a container from
                    ever counting as "In Yard" on the Cargo Build-Up board, even after EIR In is done.</li>
            </ul>
        </section>

        {{-- ============================================================ --}}
        <section id="cv-assignment" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">10. CV Assignment</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-3">Also on the booking's View modal, one row per physical
                container. Fill in each container's <strong>Proforma BL Number</strong>, <strong>Waybill
                Number</strong>, and <strong>Seal Number</strong>, then Save per row.</p>
        </section>

        {{-- ============================================================ --}}
        <section id="pier-checkin" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">11. Pier Check-In (Gate Pass)</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-3">Sidebar → Pier Check-In. Built for yard/pier personnel to
                confirm containers physically leaving and returning, without touching the full booking screens.</p>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li>Every container gets a unique <strong>Gate Pass Code</strong> (e.g. <code>GP-BK-2026-0001-01</code>)
                    the moment its booking is confirmed.</li>
                <li>Click <strong>Print List</strong> to get a printable sheet of QR codes for every container
                    currently awaiting a gate action — hand this to the person at the gate.</li>
                <li><strong>Start Camera</strong> to scan a QR code, or type the code in manually if scanning isn't
                    available or the camera can't read it.</li>
                <li>You never pick "in" or "out" yourself — the system detects which leg a scan represents based on
                    the container's current state, so there's no way to mis-tap the wrong direction.</li>
                <li>A container can't be scanned out until its EIR Out is issued (§12); it can't be scanned out
                    twice without an intervening scan-in.</li>
            </ul>
        </section>

        {{-- ============================================================ --}}
        <section id="eir" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">12. EIR (Equipment Interchange Receipt)</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-3">Also on the booking's View modal — the paperwork side of
                a container leaving and returning, separate from the quick scan in Pier Check-In.</p>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li><strong>EIR Out</strong> — issue this before a container can be scanned out at Pier Check-In.
                    Captures damage codes/remarks, an uploaded ConVan checklist, uploaded damage photos, and (for
                    Pier-origin, non-special-handling clients) a required Shipper's Representative / Driver name plus
                    an ID photo upload.</li>
                <li><strong>EIR In</strong> — issue this after a container has actually been scanned back in.
                    Captures the same damage fields plus the confirmed ConVan Class.</li>
                <li>Each direction can only be issued once per container.</li>
            </ul>
        </section>

        {{-- ============================================================ --}}
        <section id="vessel-voyages" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">13. Vessel Voyages &amp; Loadlist</h2>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li><strong>Create voyage legs</strong> under App Settings → Vessel Voyages: vessel name, voyage
                    mnemonic (e.g. "Lady Callista 84-A"), leg letter, origin/destination port, and estimated
                    departure/arrival dates. One vessel's full rotation is usually several legs (A, B, C…).</li>
                <li><strong>Assign a voyage</strong> to a container from the Cargo Build-Up dashboard's In Yard
                    bucket (§8) — pick the voyage, and optionally an Equivalent TEU (Flat Rack/Rolling/Loose Cargo
                    only) and a Relay Port if the cargo needs to transfer partway.</li>
                <li><strong>Shut Out</strong> tags a container that missed its vessel's actual cutoff — assigning it
                    to the next voyage automatically clears the tag.</li>
                <li><strong>Generate Loadlist</strong> from the Vessel Voyages tab's row action — downloads a PDF
                    manifest of every container assigned to that leg, including each one's existing Bill of Lading
                    number (see below).</li>
                <li><strong>Bill of Lading</strong> is generated automatically per booking the moment it's Confirmed
                    — download it from the booking's View modal at any time, no separate action needed.</li>
            </ul>
        </section>

        {{-- ============================================================ --}}
        <section id="users-teams" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">14. Users, Roles &amp; Teams</h2>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li><strong>Users</strong> (sidebar → Users, superadmin only): create accounts, assign a role
                    (superadmin / admin / user / developer) and deactivate accounts without deleting them.</li>
                <li><strong>Team Management</strong> (sidebar → Settings → Team Management): teams form a tree via
                    parent/child relationships. A user belongs to at most one team and may be marked its leader.</li>
                <li>A team leader's visibility <strong>cascades down</strong> through every sub-team beneath
                    them — this is what drives CRM lead visibility (§2) and Proposal approval rights (§4).</li>
                <li>superadmin bypasses all of this and always sees/can-approve everything.</li>
                <li><strong>Permissions</strong> are granular action-level gates (e.g. "confirm a booking", "issue an
                    EIR") layered on top of roles — if a button 403s for a non-superadmin user, check their role has
                    the matching permission.</li>
            </ul>
        </section>

        {{-- ============================================================ --}}
        <section id="app-settings" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">15. App Settings Reference</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-3">Sidebar → Settings → App Settings. Every tab here is
                master data consumed elsewhere in the app. Click each for details.</p>

            <details class="group border border-zinc-200 dark:border-zinc-700 rounded-lg mb-2">
                <summary class="cursor-pointer px-4 py-2.5 font-medium text-sm">Ports</summary>
                <div class="px-4 pb-3 text-sm text-zinc-600 dark:text-zinc-400">Every port/pier the business ships
                    through. Referenced by nearly everything: CRM addresses, container assets' current location,
                    lanes, booking lines, vessel voyages.</div>
            </details>
            <details class="group border border-zinc-200 dark:border-zinc-700 rounded-lg mb-2">
                <summary class="cursor-pointer px-4 py-2.5 font-medium text-sm">Containers / Container Classes /
                    Container Sizes</summary>
                <div class="px-4 pb-3 text-sm text-zinc-600 dark:text-zinc-400">The physical container catalog. A
                    specific Type + Class + Size combination becomes a "Container Variant", which is what bookings
                    and rate tables actually reference.</div>
            </details>
            <details class="group border border-zinc-200 dark:border-zinc-700 rounded-lg mb-2">
                <summary class="cursor-pointer px-4 py-2.5 font-medium text-sm">Charge Types</summary>
                <div class="px-4 pb-3 text-sm text-zinc-600 dark:text-zinc-400">Categories of extra charges, each
                    flagged as applicable to Port Charges, General Charges, or both — Port Charges and General
                    Charges tabs each only offer the charge types applicable to them.</div>
            </details>
            <details class="group border border-zinc-200 dark:border-zinc-700 rounded-lg mb-2">
                <summary class="cursor-pointer px-4 py-2.5 font-medium text-sm">Delivery Types</summary>
                <div class="px-4 pb-3 text-sm text-zinc-600 dark:text-zinc-400">Door-Door / Door-Pier / Pier-Door /
                    Pier-Pier service modes, each flagging whether origin and/or destination trucking is included.
                    This is also what determines ATW vs. CAN routing (§9).</div>
            </details>
            <details class="group border border-zinc-200 dark:border-zinc-700 rounded-lg mb-2">
                <summary class="cursor-pointer px-4 py-2.5 font-medium text-sm">Serviceable Areas</summary>
                <div class="px-4 pb-3 text-sm text-zinc-600 dark:text-zinc-400">Trucking-serviceable areas attached
                    to a port — used for origin/destination area selection on booking lines.</div>
            </details>
            <details class="group border border-zinc-200 dark:border-zinc-700 rounded-lg mb-2">
                <summary class="cursor-pointer px-4 py-2.5 font-medium text-sm">Lanes</summary>
                <div class="px-4 pb-3 text-sm text-zinc-600 dark:text-zinc-400">Origin/destination port pairs. Every
                    tariff rate is written against a lane.</div>
            </details>
            <details class="group border border-zinc-200 dark:border-zinc-700 rounded-lg mb-2">
                <summary class="cursor-pointer px-4 py-2.5 font-medium text-sm">Lane Tariff Rates</summary>
                <div class="px-4 pb-3 text-sm text-zinc-600 dark:text-zinc-400">The standard (non-contract) FRT rate
                    per lane and container variant. Versioned by effective/expiration date — adding a new rate
                    creates a new version rather than overwriting history.</div>
            </details>
            <details class="group border border-zinc-200 dark:border-zinc-700 rounded-lg mb-2">
                <summary class="cursor-pointer px-4 py-2.5 font-medium text-sm">General Charges / Port Charges /
                    Handling Fees / Trucking Tariffs / VAT Rates</summary>
                <div class="px-4 pb-3 text-sm text-zinc-600 dark:text-zinc-400">The remaining pieces of the pricing
                    engine — port-specific and general surcharges, handling fees, trucking cost tables, and the VAT
                    percentage applied to every booking's grand total.</div>
            </details>
            <details class="group border border-zinc-200 dark:border-zinc-700 rounded-lg mb-2">
                <summary class="cursor-pointer px-4 py-2.5 font-medium text-sm">Vessel Voyages</summary>
                <div class="px-4 pb-3 text-sm text-zinc-600 dark:text-zinc-400">See §13 — one row per vessel leg,
                    plus a "Loadlist" download action per row.</div>
            </details>
            <details class="group border border-zinc-200 dark:border-zinc-700 rounded-lg mb-2">
                <summary class="cursor-pointer px-4 py-2.5 font-medium text-sm">General Lookups</summary>
                <div class="px-4 pb-3 text-sm text-zinc-600 dark:text-zinc-400">Generic dropdown categories used
                    across CRM forms — Type of Business, Address Type, Lead Source, and any other simple
                    category → value list. Add a new category, then add values under it.</div>
            </details>
        </section>

        {{-- ============================================================ --}}
        <section id="notifications" class="mb-14 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">16. Notifications</h2>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li>The bell icon in the top header shows in-app notifications — new lead assigned, proposal status
                    changes, and other team-relevant events.</li>
                <li>Notifications also send email if Mailer is configured (§1, Step 4).</li>
                <li>superadmin can send a test notification from Developer Option → Notification Test to confirm
                    delivery is working.</li>
            </ul>
        </section>

        {{-- ============================================================ --}}
        <section id="small-features" class="mb-16 scroll-mt-4">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">17. Small Features Reference</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-3">Easy to miss, used everywhere.</p>
            <ul class="list-disc list-inside space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                <li><strong>Search bars on every list page</strong> search as you type (debounced — it waits for a
                    short pause before querying) or press Enter / click Search to search immediately.</li>
                <li><strong>Red field borders on save</strong>: if a form submission fails validation, the specific
                    field(s) that failed are outlined in red instead of just a generic error toast.</li>
                <li><strong>Dark mode</strong> follows your browser/OS preference automatically — there's no manual
                    toggle in this version.</li>
                <li><strong>Single active session</strong>: logging in from another browser/device automatically
                    signs you out here. If you're unexpectedly logged out, that's usually why.</li>
                <li><strong>Browser back button is disabled</strong> on most pages inside the app shell — use the
                    sidebar or in-app "Back" buttons to navigate instead.</li>
                <li><strong>PDF downloads</strong> are available for: CRM/Client proposals, contracts, a booking's
                    Bill of Lading, and a vessel voyage's Loadlist — look for a Download/PDF action wherever one of
                    those records is shown.</li>
                <li><strong>Table pagination</strong> is consistent everywhere — Prev/Next plus numbered pages, with
                    a "Showing X–Y of Z" count.</li>
                <li><strong>Theme colors are live, not per-user</strong>: changing the theme (§1, Step 3) changes it
                    for everyone immediately.</li>
            </ul>
        </section>

    </div>
</div>

<script>
    (function() {
        const container = document.getElementById('helpContent');

        document.querySelectorAll('.help-toc-link').forEach((link) => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.getElementById(this.getAttribute('href').slice(1));
                if (!target) return;

                // Scroll the content box itself when it actually has its own
                // scrollbar; otherwise fall back to the browser's normal
                // scroll-into-view - either way, this never lets the click
                // fall through to the native anchor-jump, which is what was
                // scrolling the outer page shell instead of this container.
                if (container && container.scrollHeight > container.clientHeight) {
                    const offset = target.getBoundingClientRect().top -
                        container.getBoundingClientRect().top + container.scrollTop;
                    container.scrollTo({
                        top: offset,
                        behavior: 'smooth'
                    });
                } else {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    })();
</script>
