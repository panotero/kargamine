---
name: proposal-request-od-design
description: Design decisions for the Proposal Request Origin & Destination picker and the proposal-request status lifecycle
metadata:
  type: project
---

Origin & Destination on a Proposal Request stays a **live FK reference** to an existing `prospect_locations` row — the add endpoint takes `{ prospect_location_id }`, never a copied address snapshot.

**Why:** the O&D list is defined as "live FK references, not copies" (see request-proposal-modal.blade.php header). When the user asked for the chip-picker's autofill target to be "a form, not just info," we mirrored the Identity address form's field set but rendered every field **disabled/read-only** and populated it directly from the clicked address — a styled preview of the exact row being attached, not a data-entry-then-copy flow. No PSGC cascade is wired to these read-only selects.

**How to apply:** if fully-editable per-request addresses (snapshots) are ever requested, that's a real endpoint/schema change (add takes a full address payload), not a UI tweak — raise it as a decision, don't silently swap the FK model. The two O&D hosts (wizard tab + standalone AddOriginDestinationModal) must keep identical field markup.

Endpoint gotcha that bit us twice (two separate 404s): the prospect has BOTH a singular `proposalRequest/...` route family (draft-only "prospect requirements," owned directly by the prospect) and a plural `proposalRequests/{id}/...` family (the wizard, addresses one request by id). O&D save AND remove must use the **plural** family with `lead.proposal_request.id`. Any new O&D action: use plural + the draft id, and guard for a null draft.

Status lifecycle is now `draft` -> `pending` (on submit) -> `cancelled` (soft, pending-only, via `POST .../proposalRequests/{id}/cancel`). Draft = hard Delete; pending = soft Cancel. No approver/routing workflow yet — roles not finalized. See [[nullable-class-size]] for the related rate-row nullability rules.
