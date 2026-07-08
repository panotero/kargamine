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
        $query = Container::with(['type', 'variants.containerClass', 'variants.containerSize']);

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
        $container->load(['type', 'variants.containerClass', 'variants.containerSize']);

        return response()->json(['success' => true, 'data' => $container]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'container_type_id' => ['required', 'exists:container_type,id'],
            'code' => ['required', 'string', 'max:255', 'unique:containers,code'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.container_class_id' => ['required', 'exists:container_class,id'],
            'variants.*.container_size_id' => ['required', 'exists:container_size,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'invalid_fields' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $container = DB::transaction(function () use ($data) {
            $container = Container::create([
                'container_type_id' => $data['container_type_id'],
                'code' => $data['code'],
                'name' => $data['name'],
                'is_active' => $data['is_active'] ?? true,
            ]);

            $variants = collect($data['variants'])
                ->unique(fn($v) => $v['container_class_id'] . '-' . $v['container_size_id'])
                ->map(fn($v) => [
                    'container_class_id' => $v['container_class_id'],
                    'container_size_id' => $v['container_size_id'],
                    'is_active' => true,
                ]);

            $container->variants()->createMany($variants->all());

            return $container;
        });

        $container->load(['type', 'variants.containerClass', 'variants.containerSize']);

        return response()->json(['success' => true, 'data' => $container], 201);
    }

    public function update(Request $request, Container $container)
    {
        $validator = Validator::make($request->all(), [
            'container_type_id' => ['sometimes', 'required', 'exists:container_type,id'],
            'code' => ['sometimes', 'required', 'string', 'max:255', 'unique:containers,code,' . $container->id],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'variants' => ['sometimes', 'array'],
            'variants.*.container_class_id' => ['required_with:variants', 'exists:container_class,id'],
            'variants.*.container_size_id' => ['required_with:variants', 'exists:container_size,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'invalid_fields' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        DB::transaction(function () use ($data, $container) {
            $container->fill(array_filter([
                'container_type_id' => $data['container_type_id'] ?? null,
                'code' => $data['code'] ?? null,
                'name' => $data['name'] ?? null,
            ], fn($v) => $v !== null));

            if (array_key_exists('is_active', $data)) {
                $container->is_active = $data['is_active'];
            }

            $container->save();

            // Full replace when variants are supplied. A removed variant
            // that already has lane tariff pricing tied to it will fail
            // here (restrictOnDelete) - that's intentional, it protects
            // existing pricing history.
            if (array_key_exists('variants', $data)) {
                $keep = collect($data['variants'])
                    ->unique(fn($v) => $v['container_class_id'] . '-' . $v['container_size_id']);

                $container->variants()->delete();

                $container->variants()->createMany(
                    $keep->map(fn($v) => [
                        'container_class_id' => $v['container_class_id'],
                        'container_size_id' => $v['container_size_id'],
                        'is_active' => true,
                    ])->all()
                );
            }
        });

        $container->load(['type', 'variants.containerClass', 'variants.containerSize']);

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
            ->with(['container.type', 'containerClass', 'containerSize'])
            ->whereHas('container', fn($q) => $q->where('is_active', true))
            ->where('is_active', true)
            ->get();

        return response()->json(['success' => true, 'data' => $variants]);
    }
}
