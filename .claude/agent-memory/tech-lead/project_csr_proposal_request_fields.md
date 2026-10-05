---
name: csr-proposal-request-fields
description: CSR-spec field decisions for the Prospect Requirements + RFP Products flow — service_type, quantity, Break Bulk label, and what already existed
metadata:
  type: project
---

CSR_Canva_v16 spec implementation for the Prospect → Proposal Request flow (2026-09-27). Key decisions:

**Service Type is now a fixed 4-value string, not a delivery-type FK.** A `service_type` VARCHAR column (values exactly `Door - Door` | `Door - Pier` | `Pier - Door` | `Pier - Pier`) was added to `proposal_request_containers`, `proposal_request_product_containers`, `proposal_request_product_rolling_cargo`, `proposal_request_product_loose_cargo`. The old `delivery_type_id` FK column + `deliveryType()` relation are intentionally **left in place but deprecated** (no longer required/read for service type).
- **Why:** client correction — Service Type is a fixed door/pier matrix, not the app-wide Delivery Types setting. `delivery_type_id` wasn't dropped to avoid data-loss risk and because display code still eager-loads the relation.
- **How to apply:** for these tables, `service_type` is the source of truth for service type. Container Dispatch Mode enable/disable derives from splitting the string on ` - ` (Pier side disables that side's dispatch). Don't reintroduce delivery_type_id as service type; don't drop it either without a cleanup pass.

**"Break Bulk Cargo (BB)" is the display label for the internal `LC` / `loose_cargo` code.** Only user-facing labels were changed (CRM Requirements dropdown, RFP Products picker, ContainerCatalogSeeder `name`, the relevant Blade text). The table `proposal_request_product_loose_cargo`, model `ProposalRequestProductLooseCargo`, `container_type='LC'`, and option value `loose_cargo` are all UNCHANGED.
- **Why:** a live table/FK rename is riskier and out of proportion to a label change. The seeder name change only affects fresh seeds — an already-seeded `LC` catalog row keeps its old name until renamed via container maintenance UI.
- **How to apply:** never assume "Break Bulk" implies a `BB` code — it's `LC` everywhere internally. Booking/PDF/tariff/maintenance "Loose Cargo" text was left alone (out of scope).

**Products Container + Trucking gained a required `quantity`** (unsignedInteger, DB-nullable, controller-validated `required|integer|min:1`). Rolling/Loose already had `cargo_quantity` serving that role.

**Already-existed-before-this-work (brief's research was stale):** the charter port-charges sub-table (`proposal_request_product_charter_ports` charge columns) and its controller/form/recap were already fully implemented; and the RFP Confirmation recap is entirely client-side (`buildProposalRequestRecapHtml`, exported on `window`, shared with the Prospect Info summary modal). The Confirmation "grouped line items with running `{code}-NN` numbering" is a display-only frontend change. See [[proposal-request-od-design]] and [[nullable-class-size]].
