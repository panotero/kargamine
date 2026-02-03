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
            'allowed_offices' => [
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
                'allowed_offices' => 'nullable',
                'parent_menu' => 'nullable|integer',
                'menu_order' => 'nullable|integer',
            ]);

            $parentId = $data['parent_menu'] ?? 0;
            $maxOrder = \App\Models\NavMenu::where('parent_menu', $parentId)->max('menu_order');
            $maxOrder = $maxOrder ? intval($maxOrder) : 0;
            if (empty($data['menu_order']) || $data['menu_order'] <= 0) {
                $data['menu_order'] = $maxOrder + 1;
            } else {
                \App\Models\NavMenu::where('parent_menu', $parentId)
                    ->where('menu_order', '>=', $data['menu_order'])
                    ->increment('menu_order');
            }

            $menu = \App\Models\NavMenu::create($data);

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
            'allowed_office' => [
                'nullable',
                'string',
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
                'allowed_office' => 'nullable|string',
                'parent_menu' => 'nullable|integer',
            ]);

            $menu->update($data);

            Log::info('UPDATE success', ['updated_menu' => $menu]);
            $childMenus = NavMenu::where('parent_menu', $id)->get();

            foreach ($childMenus as $child) {
                $child->update([
                    'allowed_office' => $menu->allowed_office,
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

            Log::info('DELETE success', ['id' => $id]);

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
                DB::table('nav_menus')
                    ->where('id', $menu1->id)
                    ->update(['menu_order' => $menu2->menu_order]);

                DB::table('nav_menus')
                    ->where('id', $menu2->id)
                    ->update(['menu_order' => $menu1->menu_order]);
            });

            Log::info('Swap completed successfully', [
                'swapped' => [
                    'menu1' => ['id' => $menu1->id, 'new_order' => $menu2->menu_order],
                    'menu2' => ['id' => $menu2->id, 'new_order' => $menu1->menu_order],
                ]
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
