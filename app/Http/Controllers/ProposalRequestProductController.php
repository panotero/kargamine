<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesProposalRequestAccess;
use App\Http\Controllers\Concerns\ResolvesLaneRates;
use App\Models\ProposalRequest;
use App\Models\ProposalRequestProductAncillaryService;
use App\Models\ProposalRequestProductCharter;
use App\Models\ProposalRequestProductRollingCargo;
use App\Models\Prospect;
use App\Models\SpecialCharge;
use App\Models\TruckingTariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * The Request for Proposal wizard's "Products" tab - a deliberately
 * parallel, richer set of product forms (Container/Rolling Cargo/Loose
 * Cargo/Trucking/Charter) alongside the simpler Freight/Trucking/Charter
 * already covered by ProspectController's Requirements-tab endpoints. Kept
 * in its own controller given the volume of endpoints here rather than
 * growing ProspectController further.
 *
 * Every method here addresses an EXPLICIT Proposal Request by id
 * (`$proposalRequestId`, from the route) - unlike the original
 * Requirements tab, which lazily get-or-creates "the latest draft" via
 * Prospect::proposalRequest(). The wizard always knows exactly which
 * request it's editing (freshly created, or resumed via its card), and
 * several Draft/Under Review requests can exist for the same prospect at
 * once, so "latest" would be the wrong resolution here.
 *
 * Every top-level product row saves on its own (no strict "required
 * field" guards beyond structural type/format - the brief didn't ask for
 * hard validation here). Ancillary Services / Top Load Cargo / Charter
 * Cargo Info / Charter Ports are only ever added AFTER their parent row is
 * saved, via a row's own "View" modal - not built inline before the parent
 * row's first save (a deliberate departure from the Requirements tab's
 * Charter, which builds cargo/ports inline before that row is ever saved).
 */
class ProposalRequestProductController extends Controller
{
    use AuthorizesProposalRequestAccess;
    use ResolvesLaneRates;

    private function findProposalRequestFor(Prospect $lead, $proposalRequestId): ProposalRequest
    {
        return $lead->proposalRequests()->where('id', $proposalRequestId)->firstOrFail();
    }

    // ------------------------------------------------------------------
    // CONTAINER (CV/FR/RF unified)
    // ------------------------------------------------------------------
    public function storeContainer(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        // Dispatch Mode (origin/destination) has no sensible default - the
        // frontend used to pre-select "Single" so this was always
        // technically filled in; it now ships with a blank placeholder so
        // the user must consciously pick one, and this validation backs
        // that up so a direct API call can't slip past it either. Only
        // required on a side that actually has trucking (not "Pier"),
        // mirroring the frontend's applyDispatchModeGating() disable logic.
        $sides = explode(' - ', (string) $request->input('service_type'), 2);
        $originIsPier = ($sides[0] ?? '') === 'Pier';
        $destIsPier = ($sides[1] ?? '') === 'Pier';

        $validated = $request->validate([
            'container_type' => ['required', Rule::in(['CV', 'FR', 'RF'])],
            'quantity' => ['required', 'integer', 'min:1'],
            'container_size_id' => ['nullable', 'integer', 'exists:container_size,id'],
            'minimum_temperature' => ['nullable', 'numeric'],
            'delivery_type_id' => ['nullable', 'integer', 'exists:delivery_types,delivery_type_id'],
            'service_type' => ['nullable', 'string', Rule::in(['Door - Door', 'Door - Pier', 'Pier - Door', 'Pier - Pier'])],
            'origin_prospect_location_id' => ['nullable', 'integer', Rule::exists('prospect_locations', 'id')->where(fn ($q) => $q->where('prospect_id', $lead->id))],
            'destination_prospect_location_id' => ['nullable', 'integer', Rule::exists('prospect_locations', 'id')->where(fn ($q) => $q->where('prospect_id', $lead->id))],
            'origin_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'destination_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'dispatch_mode_origin' => [$originIsPier ? 'nullable' : 'required', Rule::in(['single', 'tandem'])],
            'dispatch_mode_destination' => [$destIsPier ? 'nullable' : 'required', Rule::in(['single', 'tandem'])],
            'cargo_type' => ['nullable', 'string'],
            'cargo_description' => ['nullable', 'string'],
            'terms_of_payment' => ['nullable', 'string'],
        ]);

        $row = $proposalRequest->productContainers()->create($validated);

        return response()->json(['success' => true, 'data' => $row->load($this->productContainerRelations())]);
    }

    public function destroyContainer(Request $request, $uuid, $proposalRequestId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $proposalRequest->productContainers()->where('id', $id)->firstOrFail()->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Read-only FRT + trucking-fee lookup for one Container product row.
     * FRT: resolves the container variant (class is always null;
     * ProposalRequestProductContainer has no container_class_id column)
     * and, if origin/destination ports and a matching variant are all
     * present, the active lane/tariff price for it.
     * Trucking: for whichever side(s) of service_type are "Door", matches
     * that side's prospect location's municipality (free-text
     * address_town_city) against a ServiceableArea's area_name within the
     * same Location as that side's port, then looks up the active
     * TruckingTariff for that (area, port) pair. Any missing link (no port,
     * no prospect location, no matching area, no active tariff row)
     * resolves to 0 rather than an error - a trucking tariff just hasn't
     * been configured for that combination yet.
     */
    public function containerRate(Request $request, $uuid, $proposalRequestId, $containerId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $row = $proposalRequest->productContainers()
            ->with(['originLocation', 'destinationLocation', 'originPort', 'destinationPort'])
            ->where('id', $containerId)
            ->firstOrFail();

        $variant = $this->resolveContainerVariant($row->container_type, $row->container_size_id);

        $frt = null;
        if ($row->origin_port_id && $row->destination_port_id && $variant) {
            $lookup = $this->resolveRate($row->origin_port_id, $row->destination_port_id, $variant->id);
            $frt = $lookup['frt'] ?? null;
        }

        [$originMode, $destinationMode] = array_pad(explode(' - ', $row->service_type ?? ''), 2, null);
        $isDoorOrigin = $originMode === 'Door';
        $isDoorDestination = $destinationMode === 'Door';

        $originTrucking = $isDoorOrigin
            ? $this->resolveTruckingAmount($row->originLocation, $row->originPort)
            : 0.0;

        $destinationTrucking = $isDoorDestination
            ? $this->resolveTruckingAmount($row->destinationLocation, $row->destinationPort)
            : 0.0;

        return response()->json(['success' => true, 'data' => [
            'frt' => $frt,
            'is_door_origin' => $isDoorOrigin,
            'is_door_destination' => $isDoorDestination,
            'origin_trucking' => $originTrucking,
            'destination_trucking' => $destinationTrucking,
        ]]);
    }

    /**
     * @param  \App\Models\ProspectLocation|null  $prospectLocation
     * @param  \App\Models\Port|null  $port
     */
    protected function resolveTruckingAmount($prospectLocation, $port): float
    {
        $municipality = trim((string) ($prospectLocation?->address_town_city ?? ''));
        // PSGC's official name for a highly-urbanized/independent city is
        // usually "City of {Name}" (e.g. "City of Antipolo") - that's what
        // address_town_city stores (sourced from the PSGC municipality
        // dropdown), but Serviceable Areas here are named with just the
        // short form ("Antipolo"). Strip that prefix before matching so
        // "City of Antipolo" still finds "Rizal - Antipolo".
        $municipality = preg_replace('/^city of\s+/i', '', $municipality);

        if (! $port || $municipality === '') {
            return 0.0;
        }

        // Not scoped by the port's own location_id - a Serviceable Area's
        // trucking port isn't necessarily owned by the same Location as the
        // area itself (plenty of areas are filed under a Location that owns
        // no ports of its own). Searching directly for a configured
        // TruckingTariff for this port is also more robust than guessing
        // the "right" area first when the municipality name could match
        // areas under more than one Location.
        return (float) (TruckingTariff::where('port_id', $port->port_id)
            ->whereHas('serviceableArea', fn ($q) => $q->where('area_name', 'like', "%{$municipality}%"))
            ->activeOn()
            ->value('amount') ?? 0);
    }

    // ------------------------------------------------------------------
    // ROLLING CARGO
    // ------------------------------------------------------------------
    public function storeRollingCargo(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);
        $validated = $this->validateCargoRow($request, $lead);

        $row = $proposalRequest->productRollingCargo()->create($validated);

        return response()->json(['success' => true, 'data' => $row->load($this->productCargoRelations(withTopLoad: true))]);
    }

    public function destroyRollingCargo(Request $request, $uuid, $proposalRequestId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $proposalRequest->productRollingCargo()->where('id', $id)->firstOrFail()->delete();

        return response()->json(['success' => true]);
    }

    // ------------------------------------------------------------------
    // LOOSE CARGO (same shape as Rolling Cargo, no Top Load)
    // ------------------------------------------------------------------
    public function storeLooseCargo(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);
        $validated = $this->validateCargoRow($request, $lead);

        $row = $proposalRequest->productLooseCargo()->create($validated);

        return response()->json(['success' => true, 'data' => $row->load($this->productCargoRelations())]);
    }

    public function destroyLooseCargo(Request $request, $uuid, $proposalRequestId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $proposalRequest->productLooseCargo()->where('id', $id)->firstOrFail()->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Rolling Cargo and Loose Cargo share the exact same field shape.
     */
    private function validateCargoRow(Request $request, Prospect $lead): array
    {
        return $request->validate([
            'cargo_type' => ['nullable', 'string'],
            'cargo_details' => ['nullable', 'string'],
            'cargo_quantity' => ['nullable', 'integer', 'min:0'],
            'cargo_units' => ['nullable', 'string'],
            'revenue_ton' => ['nullable', 'numeric', 'min:0'],
            'revenue_ton_unit' => ['nullable', Rule::in(['CBM', 'MT'])],
            'cargo_measurement' => ['nullable', 'string'],
            'delivery_type_id' => ['nullable', 'integer', 'exists:delivery_types,delivery_type_id'],
            'service_type' => ['nullable', 'string', Rule::in(['Door - Door', 'Door - Pier', 'Pier - Door', 'Pier - Pier'])],
            'origin_prospect_location_id' => ['nullable', 'integer', Rule::exists('prospect_locations', 'id')->where(fn ($q) => $q->where('prospect_id', $lead->id))],
            'destination_prospect_location_id' => ['nullable', 'integer', Rule::exists('prospect_locations', 'id')->where(fn ($q) => $q->where('prospect_id', $lead->id))],
            'origin_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'destination_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'terms_of_payment' => ['nullable', 'string'],
        ]);
    }

    // ------------------------------------------------------------------
    // TRUCKING
    // ------------------------------------------------------------------
    public function storeTrucking(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $validated = $request->validate([
            'trucking_cargo_type' => ['nullable', 'string'],
            'quantity' => ['required', 'integer', 'min:1'],
            // No "Pier" concept on the standalone Trucking product (it's
            // always door-to-door trucking), so unlike Container's
            // dispatch_mode_origin/destination this one is unconditionally
            // required - see storeContainer() above for why this field has
            // no default and must be chosen explicitly.
            'dispatch_mode' => ['required', Rule::in(['single', 'tandem'])],
            'origin_prospect_location_id' => ['nullable', 'integer', Rule::exists('prospect_locations', 'id')->where(fn ($q) => $q->where('prospect_id', $lead->id))],
            'destination_prospect_location_id' => ['nullable', 'integer', Rule::exists('prospect_locations', 'id')->where(fn ($q) => $q->where('prospect_id', $lead->id))],
            'cargo_type' => ['nullable', 'string'],
            'cargo_description' => ['nullable', 'string'],
            'terms_of_payment' => ['nullable', 'string'],
        ]);

        $row = $proposalRequest->productTruckings()->create($validated);

        return response()->json(['success' => true, 'data' => $row->load($this->productTruckingRelations())]);
    }

    public function destroyTrucking(Request $request, $uuid, $proposalRequestId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $proposalRequest->productTruckings()->where('id', $id)->firstOrFail()->delete();

        return response()->json(['success' => true]);
    }

    // ------------------------------------------------------------------
    // CHARTER (main row only - Cargo Info/Ports added afterward)
    // ------------------------------------------------------------------
    public function storeCharter(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $validated = $request->validate([
            'charter_service_id' => ['nullable', 'integer', 'exists:charter_services,charter_service_id'],
            'vessel_name' => ['nullable', 'string'],
            'vessel_dead_weight' => ['nullable', 'numeric', 'min:0'],
            'charter_start_date' => ['nullable', 'date'],
            'charter_end_date' => ['nullable', 'date', 'after_or_equal:charter_start_date'],
            'loading_date' => ['nullable', 'date'],
            'laytime_loading_days' => ['nullable', 'integer', 'min:0'],
            'laytime_unloading_days' => ['nullable', 'integer', 'min:0'],
            'demurrage_charges' => ['nullable', 'boolean'],
            'lashing_service' => ['nullable', 'boolean'],
            'insurance_services' => ['nullable', 'boolean'],
            'other_charges' => ['nullable', 'string'],
            'terms_of_payment' => ['nullable', 'string'],
            'declared_value' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'weight_unit' => ['nullable', Rule::in(['kg', 'mt'])],
        ]);

        $row = $proposalRequest->productCharters()->create($validated);

        return response()->json(['success' => true, 'data' => $row->load($this->productCharterRelations())]);
    }

    public function destroyCharter(Request $request, $uuid, $proposalRequestId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $proposalRequest->productCharters()->where('id', $id)->firstOrFail()->delete();

        return response()->json(['success' => true]);
    }

    private function findProductCharter(Prospect $lead, $proposalRequestId, $charterId): ProposalRequestProductCharter
    {
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        return $proposalRequest->productCharters()->where('id', $charterId)->firstOrFail();
    }

    // ------------------------------------------------------------------
    // CHARTER CARGO INFO (nested, multiple)
    // ------------------------------------------------------------------
    public function storeCharterCargo(Request $request, $uuid, $proposalRequestId, $charterId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $charter = $this->findProductCharter($lead, $proposalRequestId, $charterId);

        $validated = $request->validate([
            'cargo_type' => ['nullable', 'string'],
            'cargo_description' => ['nullable', 'string'],
            'special_requirements' => ['nullable', 'string'],
        ]);

        $charter->cargoItems()->create($validated);

        return response()->json(['success' => true, 'data' => $charter->fresh()->load($this->productCharterRelations())]);
    }

    public function destroyCharterCargo(Request $request, $uuid, $proposalRequestId, $charterId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $charter = $this->findProductCharter($lead, $proposalRequestId, $charterId);

        $charter->cargoItems()->where('id', $id)->firstOrFail()->delete();

        return response()->json(['success' => true, 'data' => $charter->fresh()->load($this->productCharterRelations())]);
    }

    // ------------------------------------------------------------------
    // CHARTER PORTS (nested, multiple, ordered - "FINAL PORT" is purely
    // the frontend's label for the last row, no stored flag)
    // ------------------------------------------------------------------
    public function storeCharterPort(Request $request, $uuid, $proposalRequestId, $charterId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $charter = $this->findProductCharter($lead, $proposalRequestId, $charterId);

        $validated = $request->validate([
            'port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'port_charge_account' => ['nullable', Rule::in(['direct', 'invoice'])],
            'port_charge_amount' => ['nullable', 'numeric', 'min:0'],
            'cargoes_for_loading' => ['nullable', 'integer', 'min:0'],
            'cargo_measurement_for_loading' => ['nullable', 'string'],
            'revenue_ton_unit_loading' => ['nullable', Rule::in(['CBM', 'MT'])],
            'cargoes_for_unloading' => ['nullable', 'integer', 'min:0'],
            'cargo_measurement_for_unloading' => ['nullable', 'string'],
            'revenue_ton_unit_unloading' => ['nullable', Rule::in(['CBM', 'MT'])],
        ]);

        // Direct Payment means the charterer/shipper pays the port
        // directly - there's nothing for us to invoice, so any amount is
        // dropped server-side too (not just grayed out client-side).
        if (($validated['port_charge_account'] ?? null) === 'direct') {
            $validated['port_charge_amount'] = null;
        }

        $validated['sort_order'] = $charter->ports()->count();

        $charter->ports()->create($validated);

        return response()->json(['success' => true, 'data' => $charter->fresh()->load($this->productCharterRelations())]);
    }

    public function destroyCharterPort(Request $request, $uuid, $proposalRequestId, $charterId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $charter = $this->findProductCharter($lead, $proposalRequestId, $charterId);

        $charter->ports()->where('id', $id)->firstOrFail()->delete();

        // Renumber remaining ports so sort_order stays a dense 0..n-1
        // sequence (matches the frontend's array-index "PORT N" labeling).
        $charter->ports()->orderBy('sort_order')->get()->values()->each(function ($port, $index) {
            if ($port->sort_order !== $index) {
                $port->update(['sort_order' => $index]);
            }
        });

        return response()->json(['success' => true, 'data' => $charter->fresh()->load($this->productCharterRelations())]);
    }

    // ------------------------------------------------------------------
    // TOP LOAD CARGO (nested under a Rolling Cargo row, multiple)
    // ------------------------------------------------------------------
    private function findRollingCargo(Prospect $lead, $proposalRequestId, $rollingCargoId): ProposalRequestProductRollingCargo
    {
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        return $proposalRequest->productRollingCargo()->where('id', $rollingCargoId)->firstOrFail();
    }

    public function storeTopLoadCargo(Request $request, $uuid, $proposalRequestId, $rollingCargoId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $rollingCargo = $this->findRollingCargo($lead, $proposalRequestId, $rollingCargoId);

        $validated = $request->validate([
            'top_load_type' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'units' => ['nullable', 'string'],
            'revenue_ton' => ['nullable', 'numeric', 'min:0'],
            'revenue_ton_unit' => ['nullable', Rule::in(['CBM', 'MT'])],
            'measurement' => ['nullable', 'string'],
        ]);

        $rollingCargo->topLoadCargo()->create($validated);

        return response()->json(['success' => true, 'data' => $rollingCargo->fresh()->load($this->productCargoRelations(withTopLoad: true))]);
    }

    public function destroyTopLoadCargo(Request $request, $uuid, $proposalRequestId, $rollingCargoId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $rollingCargo = $this->findRollingCargo($lead, $proposalRequestId, $rollingCargoId);

        $rollingCargo->topLoadCargo()->where('id', $id)->firstOrFail()->delete();

        return response()->json(['success' => true, 'data' => $rollingCargo->fresh()->load($this->productCargoRelations(withTopLoad: true))]);
    }

    // ------------------------------------------------------------------
    // RATE MATRIX (one row per priced product line, upserted by
    // product_type + product_id)
    // ------------------------------------------------------------------
    public function storeProductRate(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $validated = $request->validate([
            'product_type' => ['required', Rule::in(['container', 'rolling_cargo', 'loose_cargo', 'trucking', 'charter'])],
            'product_id' => ['required', 'integer', 'min:1'],
            'item_no' => ['required', 'string'],
            'base_rate' => ['required', 'numeric'],
            'adjustment' => ['required', 'numeric'],
            'total_rate' => ['required', 'numeric'],
            'fee_breakdown' => ['nullable', 'array'],
            'fee_breakdown.*.detail' => ['nullable', 'string'],
            'fee_breakdown.*.base' => ['nullable', 'numeric'],
            'fee_breakdown.*.adjustment' => ['nullable', 'numeric'],
            // Ancillary Services pricing - edited here (Build Rate Matrix),
            // never on the Products tab, which only ever sets quantity at
            // add time. Saved in the same request/transaction as the rate
            // itself so the two tables in ProductSpecificationModal share
            // one Save button.
            'ancillary_adjustments' => ['nullable', 'array'],
            'ancillary_adjustments.*.id' => ['required', 'integer'],
            'ancillary_adjustments.*.quantity' => ['required', 'numeric', 'min:0'],
            'ancillary_adjustments.*.base_value' => ['required', 'numeric'],
            'ancillary_adjustments.*.adjustment' => ['nullable', 'numeric'],
        ]);

        $row = DB::transaction(function () use ($proposalRequest, $validated) {
            $rateRow = $proposalRequest->productRates()->updateOrCreate(
                [
                    'product_type' => $validated['product_type'],
                    'product_id' => $validated['product_id'],
                ],
                [
                    'item_no' => $validated['item_no'],
                    'base_rate' => $validated['base_rate'],
                    'adjustment' => $validated['adjustment'],
                    'total_rate' => $validated['total_rate'],
                    'fee_breakdown' => $validated['fee_breakdown'] ?? null,
                ]
            );

            $relation = $this->ancillaryableRelationMap()[$validated['product_type']] ?? null;

            if ($relation && ! empty($validated['ancillary_adjustments'])) {
                $product = $proposalRequest->{$relation}()->where('id', $validated['product_id'])->first();
                $validIds = $product ? $product->ancillaryServices()->pluck('id')->all() : [];

                foreach ($validated['ancillary_adjustments'] as $adj) {
                    if (! in_array($adj['id'], $validIds, true)) {
                        continue;
                    }
                    $baseValue = (float) $adj['base_value'];
                    $quantity = (float) $adj['quantity'];
                    $adjustment = (float) ($adj['adjustment'] ?? 0);
                    ProposalRequestProductAncillaryService::where('id', $adj['id'])->update([
                        'base_value' => $baseValue,
                        'quantity' => $quantity,
                        'adjustment' => $adjustment,
                        'total_value' => round($baseValue * $quantity + $adjustment, 2),
                    ]);
                }
            }

            return $rateRow;
        });

        return response()->json(['success' => true, 'data' => $row]);
    }

    // ------------------------------------------------------------------
    // TERMS & CONDITIONS (Terms of Payment + ancillary fee grid)
    // ------------------------------------------------------------------
    public function storeTermsAndConditions(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $validated = $request->validate([
            'terms_of_payment' => ['nullable', 'array'],
            'terms_of_payment.*.section' => ['required', 'string'],
            'terms_of_payment.*.terms_of_payment' => ['required', 'string'],
            'ancillary_fees' => ['nullable', 'array'],
            'ancillary_fees.*.section' => ['required', 'string'],
            'ancillary_fees.*.service_name' => ['required', 'string'],
            'ancillary_fees.*.fee_amount' => ['nullable', 'numeric'],
        ]);

        DB::transaction(function () use ($proposalRequest, $validated) {
            foreach ($validated['terms_of_payment'] ?? [] as $term) {
                $proposalRequest->termsOfPayment()->updateOrCreate(
                    ['section' => $term['section']],
                    ['terms_of_payment' => $term['terms_of_payment']]
                );
            }

            foreach ($validated['ancillary_fees'] ?? [] as $fee) {
                $proposalRequest->ancillaryFees()->updateOrCreate(
                    [
                        'section' => $fee['section'],
                        'service_name' => $fee['service_name'],
                    ],
                    [
                        'fee_amount' => $fee['fee_amount'] ?? 0,
                    ]
                );
            }
        });

        return response()->json([
            'success' => true,
            'data' => $proposalRequest->fresh()->load(['productRates', 'ancillaryFees', 'termsOfPayment']),
        ]);
    }

    // ------------------------------------------------------------------
    // ANCILLARY SERVICES (polymorphic - Container/Rolling/Loose/Trucking)
    // ------------------------------------------------------------------
    private function ancillaryableRelationMap(): array
    {
        return [
            'container' => 'productContainers',
            'rolling_cargo' => 'productRollingCargo',
            'loose_cargo' => 'productLooseCargo',
            'trucking' => 'productTruckings',
        ];
    }

    public function storeAncillaryService(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $validated = $request->validate([
            'ancillaryable_type' => ['required', Rule::in(array_keys($this->ancillaryableRelationMap()))],
            'ancillaryable_id' => ['required', 'integer'],
            'special_charge_id' => ['required', 'integer', 'exists:special_charges,special_charge_id'],
            'cargo_yard_id' => ['nullable', 'integer', 'exists:cargo_yards,cargo_yard_id'],
            'ancillary_unit' => ['nullable', 'string'],
            'ancillary_remarks' => ['nullable', 'string'],
            'quantity' => ['required', 'numeric', 'min:0'],
        ]);

        $relation = $this->ancillaryableRelationMap()[$validated['ancillaryable_type']];
        $parentExists = $proposalRequest->{$relation}()->where('id', $validated['ancillaryable_id'])->exists();

        if (! $parentExists) {
            return response()->json(['success' => false, 'message' => 'Invalid product row.'], 422);
        }

        // Type and base value are a snapshot of the picked SpecialCharge at
        // add time, not a live client-sent value - keeps displaying
        // correctly even if the catalog entry is later renamed/deleted.
        $charge = SpecialCharge::findOrFail($validated['special_charge_id']);
        $quantity = $validated['quantity'];
        $baseValue = (float) ($charge->rates()
            ->where('unit', $validated['ancillary_unit'] ?? null)
            ->value('base_value') ?? 0);

        $row = ProposalRequestProductAncillaryService::create([
            'ancillaryable_type' => $validated['ancillaryable_type'],
            'ancillaryable_id' => $validated['ancillaryable_id'],
            'special_charge_id' => $charge->special_charge_id,
            'ancillary_type' => $charge->name,
            'cargo_yard_id' => $validated['cargo_yard_id'] ?? null,
            'ancillary_unit' => $validated['ancillary_unit'] ?? null,
            'ancillary_remarks' => $validated['ancillary_remarks'] ?? null,
            'base_value' => $baseValue,
            'quantity' => $quantity,
            'adjustment' => 0,
            'total_value' => round($baseValue * $quantity, 2),
        ]);

        return response()->json(['success' => true, 'data' => $row->load(['cargoYard', 'specialCharge'])]);
    }

    public function destroyAncillaryService(Request $request, $uuid, $proposalRequestId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user(), $proposalRequestId);
        $this->authorizeProspectFullyAssigned($lead, $proposalRequestId);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $service = ProposalRequestProductAncillaryService::findOrFail($id);
        $relation = $this->ancillaryableRelationMap()[$service->ancillaryable_type] ?? null;

        if (! $relation || ! $proposalRequest->{$relation}()->where('id', $service->ancillaryable_id)->exists()) {
            abort(404);
        }

        $service->delete();

        return response()->json(['success' => true]);
    }

    // ------------------------------------------------------------------
    // Eager-load sets
    // ------------------------------------------------------------------
    private function productContainerRelations(): array
    {
        return [
            'containerSize:id,size',
            'deliveryType:delivery_type_id,code,name',
            'originLocation', 'destinationLocation',
            'originPort:port_id,location_id,name', 'destinationPort:port_id,location_id,name',
            'ancillaryServices.cargoYard',
        ];
    }

    /**
     * Rolling Cargo only - Loose Cargo has no topLoadCargo() relation at
     * all (that section is Rolling Cargo exclusive), so it must not be
     * requested for a Loose Cargo instance.
     */
    private function productCargoRelations(bool $withTopLoad = false): array
    {
        $relations = [
            'deliveryType:delivery_type_id,code,name',
            'originLocation', 'destinationLocation',
            'originPort:port_id,location_id,name', 'destinationPort:port_id,location_id,name',
            'ancillaryServices.cargoYard',
        ];

        if ($withTopLoad) {
            $relations[] = 'topLoadCargo';
        }

        return $relations;
    }

    private function productTruckingRelations(): array
    {
        return [
            'originLocation', 'destinationLocation',
            'ancillaryServices.cargoYard',
        ];
    }

    private function productCharterRelations(): array
    {
        return [
            'cargoItems',
            'ports.port:port_id,location_id,name',
            'ports.port.location:location_id,name',
        ];
    }
}
