---
name: rfp-to-clientproposal-bridge
description: How the RFP wizard persists rate matrix + T&C and bridges to ClientProposal at submit; which table holds RFP rates vs the old rates path
metadata:
  type: project
---

Submitting an RFP (`ProspectController::submitProposalRequest`) creates a lead-scoped `ClientProposal` (prospect_id set, client_id null, proposal_request_id set, status PENDING, CPR-code via the same lock/scan sequence as `ClientProposalController::storeForLead`), entering the EXISTING RM→Manager approval flow on Proposals → Approvals. No new approval screen.

**Why:** the `proposal_request_id` column on client_proposals was always a bridge nothing populated; this finally wires it. Decided with user, do not re-litigate.

**How to apply:**
- RFP rate matrix lives in its OWN tables, NOT `client_proposal_rates` (that table is container/port-centric and doesn't fit the 5 RFP product types). The native tables: `proposal_request_product_rates` (one row per product, upsert on proposal_request_id+product_type+product_id, carries item_no snapshot + base/adjustment/total + `fee_breakdown` JSON) and `proposal_request_ancillary_fees` (section+service_name+fee_amount). Two ToP columns live on `proposal_requests` (`terms_of_payment_standard`, `terms_of_payment_charter`).
- The Approvals modal (`logic_client_proposals_shared.js` `openProposalModal`) renders from the new tables ADDITIVELY when `p.proposal_request_id` + `p.proposal_request.product_rates` present; the old `p.rates` path stays untouched for proposals created via Client Masters → Add Proposal. Both paths must keep working.
- RFP endpoints live on `ProposalRequestProductController` under the `/api/crm/prospects/{uuid}/proposalRequests/{proposalRequestId}` group, guarded by the `AuthorizesProposalRequestAccess` trait (authorizeProspectOwnership + authorizeProspectFullyAssigned).
- Wizard JS `allProductRowsFlat()` emits `__type` values container/rolling_cargo/loose_cargo/trucking/charter — these ARE the product_type enum strings. Rate ✓ "reviewed" state is derived from a persisted product_rates row existing (survives reload), OR'd with the session-only `rpmMatrixConfirmedKeys` Set.
- The 5 T&C ancillary sections (Charter excluded): `For ConVans & Flatracks`, `For Reefer Vans`, `For Rolling Cargoes`, `For Break Bulk Cargo`, `For Trucking`; 4 services each: Trucking/Storage/Loading-Unloading/Documentation. The OTHER T&C numeric inputs (declared value caps, surcharge %, storage days, load limits, temperature, laytime) stay display-only/unsaved — explicitly out of scope.
