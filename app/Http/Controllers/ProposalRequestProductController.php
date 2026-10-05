<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesProposalRequestAccess;
use App\Models\ProposalRequest;
use App\Models\ProposalRequestProductAncillaryService;
use App\Models\ProposalRequestProductCharter;
use App\Models\ProposalRequestProductRollingCargo;
use App\Models\Prospect;
use Illuminate\Http\Request;
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

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
            'dispatch_mode_origin' => ['nullable', Rule::in(['single', 'tandem'])],
            'dispatch_mode_destination' => ['nullable', Rule::in(['single', 'tandem'])],
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $proposalRequest->productContainers()->where('id', $id)->firstOrFail()->delete();

        return response()->json(['success' => true]);
    }

    // ------------------------------------------------------------------
    // ROLLING CARGO
    // ------------------------------------------------------------------
    public function storeRollingCargo(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);
        $validated = $this->validateCargoRow($request, $lead);

        $row = $proposalRequest->productRollingCargo()->create($validated);

        return response()->json(['success' => true, 'data' => $row->load($this->productCargoRelations(withTopLoad: true))]);
    }

    public function destroyRollingCargo(Request $request, $uuid, $proposalRequestId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);
        $validated = $this->validateCargoRow($request, $lead);

        $row = $proposalRequest->productLooseCargo()->create($validated);

        return response()->json(['success' => true, 'data' => $row->load($this->productCargoRelations())]);
    }

    public function destroyLooseCargo(Request $request, $uuid, $proposalRequestId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $validated = $request->validate([
            'trucking_cargo_type' => ['nullable', 'string'],
            'quantity' => ['required', 'integer', 'min:1'],
            'dispatch_mode' => ['nullable', Rule::in(['single', 'tandem'])],
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $validated = $request->validate([
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $rollingCargo = $this->findRollingCargo($lead, $proposalRequestId, $rollingCargoId);

        $rollingCargo->topLoadCargo()->where('id', $id)->firstOrFail()->delete();

        return response()->json(['success' => true, 'data' => $rollingCargo->fresh()->load($this->productCargoRelations(withTopLoad: true))]);
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
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $validated = $request->validate([
            'ancillaryable_type' => ['required', Rule::in(array_keys($this->ancillaryableRelationMap()))],
            'ancillaryable_id' => ['required', 'integer'],
            'ancillary_type' => ['nullable', 'string'],
            'cargo_yard_id' => ['nullable', 'integer', 'exists:cargo_yards,cargo_yard_id'],
            'ancillary_unit' => ['nullable', 'string'],
            'ancillary_remarks' => ['nullable', 'string'],
        ]);

        $relation = $this->ancillaryableRelationMap()[$validated['ancillaryable_type']];
        $parentExists = $proposalRequest->{$relation}()->where('id', $validated['ancillaryable_id'])->exists();

        if (! $parentExists) {
            return response()->json(['success' => false, 'message' => 'Invalid product row.'], 422);
        }

        $row = ProposalRequestProductAncillaryService::create($validated);

        return response()->json(['success' => true, 'data' => $row->load('cargoYard')]);
    }

    public function destroyAncillaryService(Request $request, $uuid, $proposalRequestId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
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
