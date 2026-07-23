<?php

namespace App\Http\Controllers;

use App\Models\Office;
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

class UserController extends Controller
{
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

    public function deactivate($id)
    {
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

    public function reactivate($id)
    {

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


        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();
            $data = array_filter($validated, function ($value) {
                return !is_null($value) && $value !== '';
            });
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            if ($id) {
                $user = User::findOrFail($id);
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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'] ?? null,
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
