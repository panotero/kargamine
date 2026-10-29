<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\SettingRole;
use App\Models\UserDepartment;
use App\Models\UserStatus;
use App\Support\RoleHelper;

class UserController extends Controller
{
    /**
     * User-account mutations (create/edit/deactivate/reactivate) stay
     * restricted to this narrower set even though /page_users itself is
     * visible more broadly (it now also hosts the migrated Team
     * Management section) - mirrors the assignOwners() page-vs-action
     * decoupling used for the Proposals consolidation.
     */
    private function assertCanManageUsers(Request $request): void
    {
        abort_unless(
            RoleHelper::hasAnyRole($request->user(), ['superadmin', 'developer']),
            403,
            'Only Superadmin/Developer can manage user accounts.'
        );
    }

    public function index(Request $request)
    {
        $users = User::query()
            ->select(
                'id',
                'name',
                'email',
                'role_id',
                'status',
                'created_at',
                'updated_at'
            )
            ->with([
                'role:id,role_name',
            ])

            // Search
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('role', function ($q) use ($search) {
                            $q->where('role_name', 'like', "%{$search}%");
                        });
                });
            })

            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function create()
    {

        return view('users.create');
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json($user, 200);
        } catch (\Exception $e) {

            return response()->json(['error' => 'User not found.'], 404);
        }
    }

    public function deactivate(Request $request, $id)
    {
        $this->assertCanManageUsers($request);

        if ((int) $id === (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => "You can't deactivate your own account.",
            ], 422);
        }

        $target = User::find($id);

        if ($target && \App\Support\RoleHelper::hasAnyRole($target, ['superadmin'])) {
            $otherActiveSuperadmins = User::where('id', '!=', $id)
                ->where('status', User::STATUS_ACTIVE)
                ->whereHas('role', fn ($q) => $q->whereRaw('LOWER(role_name) = ?', ['superadmin']))
                ->exists();

            if (! $otherActiveSuperadmins) {
                return response()->json([
                    'success' => false,
                    'message' => 'This is the last active superadmin - deactivating them would lock everyone out of admin access.',
                ], 422);
            }
        }

        try {
            $user = User::findOrFail($id);
            $user->status = 1;
            $user->save();

            Log::info('User deactivated successfully', ['id' => $id]);


            return response()->json([
                'success' => true,
                'message' => 'User Deactivated successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to deactivate user', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Failed to deactivate user.'], 500);
        }
    }

    public function reactivate(Request $request, $id)
    {
        $this->assertCanManageUsers($request);

        try {
            $user = User::findOrFail($id);
            $user->status = 0;
            $user->save();

            Log::info('User deactivated successfully', ['id' => $id]);


            return response()->json([
                'success' => true,
                'message' => 'User Deactivated successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to deactivate user', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Failed to deactivate user.'], 500);
        }
    }

    public function save_info($id = false, Request $request)
    {
        $this->assertCanManageUsers($request);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'nullable|integer',
            'team_id' => 'nullable|integer|exists:teams,id',
        ]);

        try {
            DB::beginTransaction();
            $data = array_filter($validated, function ($value) {
                return !is_null($value) && $value !== '';
            });
            // team_id must survive being explicitly cleared (unassign from
            // team), which array_filter above would otherwise strip.
            if (array_key_exists('team_id', $validated)) {
                $data['team_id'] = $validated['team_id'];
            }
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            if ($id) {
                $user = User::findOrFail($id);
                // Moving/clearing a user's team drops any leader flag they
                // held on the old team - leadership is granted per-team via
                // the Team Management members modal, not carried across.
                if (array_key_exists('team_id', $data) && (int) $user->team_id !== (int) ($data['team_id'] ?? 0)) {
                    $data['is_team_leader'] = false;
                }
                $user->update($data);
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save user information',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $this->assertCanManageUsers($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer',
            'team_id' => 'nullable|integer|exists:teams,id',
        ]);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'] ?? null,
                'team_id' => $validated['team_id'] ?? null,
                'must_change_password' => true,
            ]);
            DB::commit();

            return response()->json([
                'success' => true,
                "message" => "User Created"
            ], 201);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getUserSettings()
    {
        // return true;
        return [
            'roles' => SettingRole::all(),
            'departments' => UserDepartment::all(),
            'statuses' => UserStatus::all(),
        ];
    }

    /**
     * Active users filtered by role name (case-insensitive) - e.g. populating
     * the Credit Officer dropdown on the Client Master finance stage.
     */
    public function byRole(Request $request)
    {
        $role = $request->query('role');

        $users = User::query()->select('id', 'name')
            ->where('status', User::STATUS_ACTIVE)
            ->whereHas('role', fn ($q) => $q->whereRaw('LOWER(role_name) = ?', [strtolower($role)]))
            ->orderBy('name')->get();

        return response()->json(['success' => true, 'data' => $users]);
    }

    public function counts()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total'    => (int) User::count(),
                'active'   => (int) User::where('status', User::STATUS_ACTIVE)->count(),
                'inactive' => (int) User::where('status', User::STATUS_INACTIVE)->count(),
                'roles'    => (int) SettingRole::count(),
            ],
        ]);
    }
}
