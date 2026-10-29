---
name: rate-matrix-real-lookup
description: Build Rate Matrix freight + trucking lookup is real for Containers; other product types are deliberately still placeholder
metadata:
  type: project
---

Build Rate Matrix (RFP wizard Products tab) got a real freight base-rate AND trucking-fee lookup for Container rows — know what is real vs deliberately-still-fake before "fixing" the rest.

- **Container rows**: Base Freight Charge is a real lookup via the shared `ResolvesLaneRates` trait (`resolveContainerVariant` → `resolveRate`, lane→tariff→priced variant, `frt` or null). Trucking Fee (Door-Origin and/or Door-Destination, whichever side(s) of `service_type` are "Door") is now also a real lookup: match that side's `ProspectLocation.address_town_city` (free text) against a `ServiceableArea.area_name` within the same Location as that side's port, then `TruckingTariff::where('area_id', ...)->where('port_id', ...)->activeOn()`. Endpoint: `GET /api/crm/prospects/{uuid}/proposalRequests/{proposalRequestId}/products/containers/{containerId}/rate` → `{frt, is_door_origin, is_door_destination, origin_trucking, destination_trucking}`. Any missing link (no port, no prospect location, no area match, no active tariff) resolves to 0, never an error - see `ProposalRequestProductController::containerRate()`/`resolveTruckingAmount()`.
- `trucking_tariffs` was reworked (2026-10-23) from a (serviceable_area, delivery_type) key to a (serviceable_area, port) key — a trucking rate is "cost to truck between this area and this port," same amount regardless of which side is the door leg, so delivery_type_id was dropped from that table entirely (and from `RateResolutionService`'s booking-stage lookup, which now also keys on port_id). Maintenance UI (`/page_maintenance` → Trucking Tariffs) is now a bespoke modal (Location → cascading Area + Port selects), not the generic `ENTITY_CONFIG` form.
- **Rolling Cargo / Loose Cargo / Trucking / Charter rows** still use the fabricated `sampleFeeBreakdown()` placeholder (15000/1200/500/800 etc.) — deliberate, unchanged. The user's requests so far have been Container-only. These are a KNOWN visible gap, not forgotten.

**Why:** reported after live testing that Container base rate was 100% fake placeholder; later asked specifically to wire up the trucking fee too, which required reworking the `trucking_tariffs` catalog key first (it had no port column at all).
**How to apply:** if asked to "make Build Rate Matrix rates real," Containers (freight + trucking) are done — the other 4 product types are the remaining work. Reuse `ResolvesLaneRates` rather than re-deriving the lane lookup. Related: [[searchable-select-decision]] is unrelated; see nullable class/size note [[project_nullable_class_size]] for variant matching (ProposalRequestProductContainer has NO container_class_id column — variant lookup always passes class=null).
