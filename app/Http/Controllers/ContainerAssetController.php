<?php

namespace App\Http\Controllers;

use App\Models\ContainerAsset;
use App\Models\ContainerAssetLocationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ContainerAssetController extends Controller
{
    protected function withDisplayRelations($query)
    {
        return $query->with([
            'containerVariant.container',
            'containerVariant.containerClass',
            'containerVariant.containerSize',
            'currentPort',
        ]);
    }

    public function index(Request $request)
    {
        $assets = $this->withDisplayRelations(ContainerAsset::query())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('container_variant_id'), fn ($q) => $q->where('container_variant_id', $request->container_variant_id))
            ->when($request->filled('current_port_id'), fn ($q) => $q->where('current_port_id', $request->current_port_id))
            ->when($request->filled('search'), fn ($q) => $q->where('container_no', 'like', "%{$request->search}%"))
            ->latest('id')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $assets,
        ]);
    }

    public function show(ContainerAsset $containerAsset)
    {
        $containerAsset->load([
            'containerVariant.container',
            'containerVariant.containerClass',
            'containerVariant.containerSize',
            'currentPort',
            'locationHistory' => fn ($q) => $q->with(['port', 'recordedBy'])->limit(20),
        ]);

        return response()->json([
            'success' => true,
            'data' => $containerAsset,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'container_variant_id' => ['required', 'integer', 'exists:container_variants,id'],
            'container_no' => ['required', 'string', 'max:20', 'unique:container_assets,container_no'],
            'current_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'current_pier_reference' => ['nullable', 'string', 'max:100'],
            'condition_notes' => ['nullable', 'string'],
        ]);

        $asset = DB::transaction(function () use ($validated, $request) {
            $asset = ContainerAsset::create([
                'container_variant_id' => $validated['container_variant_id'],
                'container_no' => strtoupper($validated['container_no']),
                'status' => ContainerAsset::STATUS_AVAILABLE,
                'current_port_id' => $validated['current_port_id'] ?? null,
                'current_pier_reference' => $validated['current_pier_reference'] ?? null,
                'condition_notes' => $validated['condition_notes'] ?? null,
            ]);

            if ($asset->current_port_id) {
                $asset->applyChange(
                    ['current_port_id' => $asset->current_port_id, 'current_pier_reference' => $asset->current_pier_reference],
                    ContainerAssetLocationHistory::SOURCE_MANUAL_RELOCATION,
                    $request->user()?->id
                );
            }

            return $asset;
        });

        return response()->json([
            'success' => true,
            'data' => $asset,
        ], 201);
    }

    public function update(Request $request, ContainerAsset $containerAsset)
    {
        $validated = $request->validate([
            'container_no' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('container_assets', 'container_no')->ignore($containerAsset->id),
            ],
            'condition_notes' => ['nullable', 'string'],
        ]);

        if (isset($validated['container_no'])) {
            $validated['container_no'] = strtoupper($validated['container_no']);
        }

        $containerAsset->update($validated);

        return response()->json([
            'success' => true,
            'data' => $containerAsset,
        ]);
    }

    public function markUnderRepair(Request $request, ContainerAsset $containerAsset)
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        if (in_array($containerAsset->status, [ContainerAsset::STATUS_BOOKED, ContainerAsset::STATUS_IN_TRANSIT], true)) {
            return response()->json([
                'success' => false,
                'message' => 'This container is booked or in transit - release it from its booking before marking it under repair.',
            ], 422);
        }

        if ($containerAsset->status === ContainerAsset::STATUS_UNDER_REPAIR) {
            return response()->json([
                'success' => false,
                'message' => 'This container is already under repair.',
            ], 422);
        }

        $containerAsset->applyChange(
            ['status' => ContainerAsset::STATUS_UNDER_REPAIR],
            ContainerAssetLocationHistory::SOURCE_MANUAL_RELOCATION,
            $request->user()?->id
        );

        if (! empty($validated['reason'])) {
            $containerAsset->update(['condition_notes' => $validated['reason']]);
        }

        return response()->json([
            'success' => true,
            'data' => $containerAsset->fresh(),
        ]);
    }

    public function markAvailable(Request $request, ContainerAsset $containerAsset)
    {
        if ($containerAsset->status !== ContainerAsset::STATUS_UNDER_REPAIR) {
            return response()->json([
                'success' => false,
                'message' => 'Only a container currently Under Repair can be marked Available this way.',
            ], 422);
        }

        $containerAsset->applyChange(
            ['status' => ContainerAsset::STATUS_AVAILABLE],
            ContainerAssetLocationHistory::SOURCE_MANUAL_RELOCATION,
            $request->user()?->id
        );

        return response()->json([
            'success' => true,
            'data' => $containerAsset->fresh(),
        ]);
    }

    public function markOutOfService(Request $request, ContainerAsset $containerAsset)
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        if (in_array($containerAsset->status, [ContainerAsset::STATUS_BOOKED, ContainerAsset::STATUS_IN_TRANSIT], true)) {
            return response()->json([
                'success' => false,
                'message' => 'This container is booked or in transit - release it from its booking first.',
            ], 422);
        }

        $containerAsset->applyChange(
            ['status' => ContainerAsset::STATUS_OUT_OF_SERVICE],
            ContainerAssetLocationHistory::SOURCE_MANUAL_RELOCATION,
            $request->user()?->id
        );

        if (! empty($validated['reason'])) {
            $containerAsset->update(['condition_notes' => $validated['reason']]);
        }

        return response()->json([
            'success' => true,
            'data' => $containerAsset->fresh(),
        ]);
    }

    public function relocate(Request $request, ContainerAsset $containerAsset)
    {
        $validated = $request->validate([
            'port_id' => ['required', 'integer', 'exists:ports,port_id'],
            'pier_reference' => ['nullable', 'string', 'max:100'],
        ]);

        $containerAsset->applyChange(
            [
                'current_port_id' => $validated['port_id'],
                'current_pier_reference' => $validated['pier_reference'] ?? null,
            ],
            ContainerAssetLocationHistory::SOURCE_MANUAL_RELOCATION,
            $request->user()?->id
        );

        return response()->json([
            'success' => true,
            'data' => $containerAsset->fresh(),
        ]);
    }

    /**
     * Ranked candidate list for a booking line: same current port as the
     * booking's origin first, then longest-idle. Used directly by the
     * manual-pick dropdown, and internally by reserve()'s auto mode -
     * both paths agree on what "best available" means.
     */
    public function available(Request $request)
    {
        $validated = $request->validate([
            'container_variant_id' => ['required', 'integer', 'exists:container_variants,id'],
            'origin_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $assets = $this->rankedAvailableQuery($validated)
            ->when($validated['quantity'] ?? null, fn ($q, $qty) => $q->limit($qty))
            ->get();

        return response()->json([
            'success' => true,
            'data' => $assets,
        ]);
    }

    protected function rankedAvailableQuery(array $criteria)
    {
        return $this->withDisplayRelations(ContainerAsset::query())
            ->where('container_variant_id', $criteria['container_variant_id'])
            ->where('status', ContainerAsset::STATUS_AVAILABLE)
            ->when(
                $criteria['origin_port_id'] ?? null,
                fn ($q, $portId) => $q->orderByRaw('CASE WHEN current_port_id = ? THEN 0 ELSE 1 END', [$portId])
            )
            ->orderBy('last_movement_at');
    }

    public function reserve(Request $request)
    {
        $validated = $request->validate([
            'auto' => ['sometimes', 'boolean'],
            'container_asset_ids' => ['required_if:auto,false', 'array'],
            'container_asset_ids.*' => ['integer', 'exists:container_assets,id'],
            'container_variant_id' => ['required_if:auto,true', 'integer', 'exists:container_variants,id'],
            'origin_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'quantity' => ['required_if:auto,true', 'integer', 'min:1'],
        ]);

        return DB::transaction(function () use ($validated, $request) {
            if ($request->boolean('auto')) {
                $candidates = $this->rankedAvailableQuery($validated)
                    ->lockForUpdate()
                    ->limit($validated['quantity'])
                    ->get();

                if ($candidates->count() < $validated['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Not enough available containers of this type to auto-assign.',
                    ], 422);
                }

                $ids = $candidates->pluck('id')->all();
            } else {
                $ids = $validated['container_asset_ids'];
            }

            $assets = ContainerAsset::whereIn('id', $ids)->lockForUpdate()->get();

            if ($assets->count() !== count($ids) || $assets->contains(fn ($a) => $a->status !== ContainerAsset::STATUS_AVAILABLE)) {
                return response()->json([
                    'success' => false,
                    'message' => 'One or more selected containers are no longer available.',
                ], 422);
            }

            $assets->each(function (ContainerAsset $asset) use ($request) {
                $asset->applyChange(
                    ['status' => ContainerAsset::STATUS_BOOKED],
                    ContainerAssetLocationHistory::SOURCE_BOOKING_ASSIGNMENT,
                    $request->user()?->id
                );
            });

            return response()->json([
                'success' => true,
                'data' => ContainerAsset::whereIn('id', $ids)->get(),
            ]);
        });
    }

    public function release(Request $request)
    {
        $validated = $request->validate([
            'container_asset_ids' => ['required', 'array', 'min:1'],
            'container_asset_ids.*' => ['integer', 'exists:container_assets,id'],
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $assets = ContainerAsset::whereIn('id', $validated['container_asset_ids'])->lockForUpdate()->get();

            if ($assets->contains(fn ($a) => $a->status !== ContainerAsset::STATUS_BOOKED)) {
                return response()->json([
                    'success' => false,
                    'message' => 'One or more selected containers are not currently booked.',
                ], 422);
            }

            $assets->each(function (ContainerAsset $asset) use ($request) {
                $asset->applyChange(
                    ['status' => ContainerAsset::STATUS_AVAILABLE],
                    ContainerAssetLocationHistory::SOURCE_BOOKING_RELEASE,
                    $request->user()?->id
                );
            });

            return response()->json([
                'success' => true,
                'data' => ContainerAsset::whereIn('id', $validated['container_asset_ids'])->get(),
            ]);
        });
    }
}
