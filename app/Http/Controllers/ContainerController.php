<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\ContainerVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContainerController extends Controller
{
    public function index(Request $request)
    {
        $query = Container::with(['classes', 'sizes', 'variants.containerClass', 'variants.containerSize']);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $containers = $query->orderBy('name')->paginate($request->query('per_page', 15));

        return response()->json(['success' => true, 'data' => $containers]);
    }

    public function show(Container $container)
    {
        $container->load(['classes', 'sizes', 'variants.containerClass', 'variants.containerSize']);

        return response()->json(['success' => true, 'data' => $container]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'max:255', 'unique:containers,code'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'sizes' => ['present', 'array'],
            'sizes.*.id' => ['nullable', 'integer'],
            'sizes.*.size' => ['required', 'string', 'max:255'],
            'classes' => ['sometimes', 'array'],
            'classes.*.id' => ['nullable', 'integer'],
            'classes.*.class' => ['required', 'string', 'max:255'],
        ]);

        $validator->after(function ($validator) use ($request) {
            // Loose Cargo / Rolling Cargo have no fixed size, so sizes can
            // be empty - but a container needs at least one size or class.
            if (empty($request->input('sizes')) && empty($request->input('classes'))) {
                $validator->errors()->add('sizes', 'Add at least one size, or at least one class for containers with no fixed size.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['success' => false, 'invalid_fields' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $container = DB::transaction(function () use ($data) {
            $container = Container::create([
                'code' => $data['code'],
                'name' => $data['name'],
                'is_active' => $data['is_active'] ?? true,
            ]);

            $container->syncCatalog($data['classes'] ?? [], $data['sizes']);

            return $container;
        });

        $container->load(['classes', 'sizes', 'variants.containerClass', 'variants.containerSize']);

        return response()->json(['success' => true, 'data' => $container], 201);
    }

    public function update(Request $request, Container $container)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['sometimes', 'required', 'string', 'max:255', 'unique:containers,code,' . $container->id],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'sizes' => ['sometimes', 'array'],
            'sizes.*.id' => ['nullable', 'integer'],
            'sizes.*.size' => ['required', 'string', 'max:255'],
            'classes' => ['sometimes', 'array'],
            'classes.*.id' => ['nullable', 'integer'],
            'classes.*.class' => ['required', 'string', 'max:255'],
        ]);

        $validator->after(function ($validator) use ($request) {
            // Loose Cargo / Rolling Cargo have no fixed size, so sizes can
            // be empty - but a container needs at least one size or class.
            if ($request->has('sizes') && empty($request->input('sizes')) && empty($request->input('classes'))) {
                $validator->errors()->add('sizes', 'Add at least one size, or at least one class for containers with no fixed size.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['success' => false, 'invalid_fields' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        try {
            DB::transaction(function () use ($data, $container) {
                $container->fill(array_filter([
                    'code' => $data['code'] ?? null,
                    'name' => $data['name'] ?? null,
                ], fn ($v) => $v !== null));

                if (array_key_exists('is_active', $data)) {
                    $container->is_active = $data['is_active'];
                }

                $container->save();

                // syncCatalog() only removes sizes/classes that were dropped
                // from the submitted lists - kept ones (and their variants)
                // are left alone. A removed size/class takes its lane tariff
                // prices and container assets with it, but still fails here
                // if a variant is tied to a proposal, contract or booking
                // line (restrictOnDelete), or one of its assets is already
                // assigned to a booking (ProtectedRecordException) - that's
                // intentional, it protects existing operational/financial
                // records.
                if (array_key_exists('sizes', $data)) {
                    $container->syncCatalog($data['classes'] ?? [], $data['sizes']);
                }
            });
        } catch (\Illuminate\Database\QueryException|\App\Exceptions\ProtectedRecordException $e) {
            if ($e instanceof \App\Exceptions\ProtectedRecordException || $e->getCode() === '23000') {
                return response()->json([
                    'success' => false,
                    'message' => $e instanceof \App\Exceptions\ProtectedRecordException
                        ? $e->getMessage()
                        : 'Unable to remove one or more of those sizes/classes - they still have proposals, contracts, or bookings tied to them. Keep those entries (or clear out the related records first) and try again.',
                ], 422);
            }

            throw $e;
        }

        $container->load(['classes', 'sizes', 'variants.containerClass', 'variants.containerSize']);

        return response()->json(['success' => true, 'data' => $container]);
    }

    public function destroy(Container $container)
    {
        $container->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Flat list of active variants across all containers - this is what
     * the Lane Tariff Rate form uses to build its per-combination pricing
     * grid.
     */
    public function variants()
    {
        $variants = ContainerVariant::query()
            ->with(['container', 'containerClass', 'containerSize'])
            ->whereHas('container', fn ($q) => $q->where('is_active', true))
            ->where('is_active', true)
            ->get();

        return response()->json(['success' => true, 'data' => $variants]);
    }
}
