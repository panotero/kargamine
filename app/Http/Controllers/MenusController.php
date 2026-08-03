<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NavMenu;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MenusController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $menus = NavMenu::orderBy('parent_menu', 'asc')
            ->orderBy('menu_order', 'asc')
            ->get();
        $filtered = $menus->filter(function ($menu) use ($user) {
            $allowedRoles = json_decode($menu->allowed_roles, true) ?? [];
            return in_array($user->role_id, $allowedRoles);
        })->values();

        $grouped = $filtered->where('parent_menu', 0)->map(function ($parent) use ($filtered) {
            $children = $filtered->where('parent_menu', $parent->id)->values();
            return [
                'id' => $parent->id,
                'title' => $parent->title,
                'icon' => $parent->icon,
                'link' => $parent->link,
                'menu_order' => $parent->menu_order,
                'allowed_roles' => $parent->allowed_roles,
                'children' => $children
            ];
        })->values();

        return response()->json($grouped);
    }


    public function menulist()
    {
        return response()->json(NavMenu::orderBy('parent_menu', 'asc')
            ->orderBy('menu_order', 'asc')
            ->get());
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'string',
            ],
            'icon' => [
                'nullable',
                'string',
            ],
            'link' => [
                'nullable',
                'string',
            ],
            'allowed_roles' => [
                'nullable'
            ],
            'parent_menu' => [
                'nullable',
                'integer'
            ],
            'menu_order' => [
                'nullable',
                'integer'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        Log::info('STORE request received', [
            'payload' => $request->all()
        ]);

        try {
            $data = $request->validate([
                'title' => 'required|string',
                'icon' => 'nullable|string',
                'link' => 'nullable|string',
                'allowed_roles' => 'nullable',
                'parent_menu' => 'nullable|integer',
                'menu_order' => 'nullable|integer',
            ]);

            $parentId = $data['parent_menu'] ?? 0;
            $maxOrder = NavMenu::where('parent_menu', $parentId)->max('menu_order');
            $maxOrder = $maxOrder ? intval($maxOrder) : 0;

            if (empty($data['menu_order']) || $data['menu_order'] <= 0) {
                $data['menu_order'] = $maxOrder + 1;
            } else {
                NavMenu::where('parent_menu', $parentId)
                    ->where('menu_order', '>=', $data['menu_order'])
                    ->increment('menu_order');
            }

            Log::info('STORE request received', [
                'maxOrder' => $data
            ]);

            $menu = NavMenu::create($data);

            Log::info('STORE success', ['menu' => $menu]);

            return response()->json([
                'success' => true,
                'message' => 'Menu created successfully',
                'data' => $menu
            ]);
        } catch (\Exception $e) {
            Log::error('STORE error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create menu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('UPDATE request received', [
            'id' => $id,
            'payload' => $request->all()
        ]);

        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'string',
            ],
            'icon' => [
                'nullable',
                'string',
            ],
            'link' => [
                'nullable',
                'string',
            ],
            'allowed_roles' => [
                'nullable'
            ],
            'parent_menu' => [
                'nullable',
                'integer'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        try {
            $menu = NavMenu::findOrFail($id);

            $data = $request->validate([
                'title' => 'required|string',
                'icon' => 'nullable|string',
                'link' => 'nullable|string',
                'allowed_roles' => 'nullable',
                'parent_menu' => 'nullable|integer',
            ]);

            $menu->update($data);

            Log::info('UPDATE success', ['updated_menu' => $menu]);
            $childMenus = NavMenu::where('parent_menu', $id)->get();

            foreach ($childMenus as $child) {
                $child->update([
                    'allowed_roles' => $menu->allowed_roles
                ]);
            }

            Log::info('Child menus updated', [
                'parent_id' => $id,
                'child_count' => $childMenus->count()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Menu updated successfully (including child menus)',
                'data' => $menu
            ]);
        } catch (\Exception $e) {
            Log::error('UPDATE error', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update menu',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        Log::info('DELETE request received', ['id' => $id]);

        try {
            $menu = NavMenu::findOrFail($id);
            $menu->delete();
            $child = NavMenu::where('parent_menu', $id)->get();
            if (count($child) > 0) {

                foreach ($child as $chilemenu) {
                    $cmenu = NavMenu::findOrFail($chilemenu->id);
                    $cmenu->delete();
                }
                Log::info('child menu DELETE success', ['id' => $chilemenu->id]);
            }
            Log::info('parent menu DELETE success', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Menu deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('DELETE error', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete menu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function swapMenuOrder(Request $request)
    {
        $id1 = $request->input('id1');
        $id2 = $request->input('id2');

        Log::info('Swap request received', [
            'id1' => $id1,
            'id2' => $id2
        ]);

        if (!$id1 || !$id2) {
            Log::warning('Invalid IDs for swap', ['id1' => $id1, 'id2' => $id2]);
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid menu IDs provided.'
            ], 400);
        }

        $menu1 = DB::table('nav_menus')->where('id', $id1)->first();
        $menu2 = DB::table('nav_menus')->where('id', $id2)->first();

        if (!$menu1 || !$menu2) {
            Log::warning('Menu not found for swap', [
                'menu1_found' => (bool) $menu1,
                'menu2_found' => (bool) $menu2
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'One or both menu items not found.'
            ], 404);
        }

        Log::info('Current menu order before swap', [
            'menu1' => ['id' => $menu1->id, 'title' => $menu1->title, 'menu_order' => $menu1->menu_order],
            'menu2' => ['id' => $menu2->id, 'title' => $menu2->title, 'menu_order' => $menu2->menu_order]
        ]);

        try {
            DB::transaction(function () use ($menu1, $menu2) {
                // Renumber the WHOLE sibling group sequentially by swapping
                // menu1/menu2's array position, rather than just exchanging
                // their two menu_order values. A plain value-swap silently
                // no-ops whenever two siblings already share the same
                // menu_order (which can happen - e.g. a row added without an
                // explicit order colliding with an existing one) - the
                // button visibly does nothing because both rows just keep
                // the same value they started with. Recomputing the whole
                // group's sequence from its current position order is
                // self-healing: it always produces a distinct, gapless
                // 0..N-1 sequence regardless of what was there before.
                $siblings = DB::table('nav_menus')
                    ->where('parent_menu', $menu1->parent_menu)
                    ->orderBy('menu_order')
                    ->orderBy('id')
                    ->get()
                    ->all();

                $idx1 = null;
                $idx2 = null;
                foreach ($siblings as $i => $row) {
                    if ($row->id === $menu1->id) {
                        $idx1 = $i;
                    }
                    if ($row->id === $menu2->id) {
                        $idx2 = $i;
                    }
                }

                if ($idx1 !== null && $idx2 !== null) {
                    [$siblings[$idx1], $siblings[$idx2]] = [$siblings[$idx2], $siblings[$idx1]];
                }

                foreach ($siblings as $position => $row) {
                    DB::table('nav_menus')->where('id', $row->id)->update(['menu_order' => $position]);
                }
            });

            Log::info('Swap completed successfully', [
                'menu1_id' => $menu1->id,
                'menu2_id' => $menu2->id,
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Swap failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while swapping menu order.'
            ], 500);
        }
    }
}
