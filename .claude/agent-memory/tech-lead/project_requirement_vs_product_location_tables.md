---
name: requirement-vs-product-location-tables
description: Prospect requirement O&D and ProposalRequest product O&D point at two DIFFERENT location tables — they are not interchangeable
metadata:
  type: project
---

Prospect "requirement" rows and ProposalRequest "product" rows store origin/destination against **different tables**, so their location IDs are NOT interchangeable.

- **Requirement rows** (`ProposalRequestContainer` = `requirement_containers`, `ProposalRequestTrucking` = `requirement_truckings`): `origin_location_id`/`destination_location_id` → **`locations` master catalog** (FK `locations.location_id`). Validated `exists:locations,location_id`. Payload exposes `origin_location: {location_id, name}`.
- **Product rows** (`ProposalRequestProductContainer`/`...RollingCargo`/`...LooseCargo`/`...Trucking`): `origin_prospect_location_id`/`destination_prospect_location_id` → **`prospect_locations`** (the prospect's own addresses). Validated against `prospect_locations` scoped to the prospect. In the RFP wizard the product O&D `<select>` options come from the O&D-tab-curated `proposal_request_locations` list, not the master catalog.

**Why:** came up building the "prefill product form from prospect products" shortcut on the RFP wizard Products tab (2026-10-05). The obvious mapping (copy requirement's origin → product's origin select) is impossible: different ID spaces, and the product select only lists prospect addresses, never master-catalog locations.

**How to apply:** never map a requirement `origin_location_id` onto a product `origin_prospect_location_id` (or vice versa). Any "copy from requirement" feature must leave the product O&D selects blank for the user to pick manually. Also note the requirement payload key is `location_id` (not `id`) because the eager-load selects `location_id,name`.
