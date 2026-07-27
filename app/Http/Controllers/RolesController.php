<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\SettingRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RolesController extends Controller
{
    public function index()
    {
        $roles = SettingRole::withCount('users')
            ->with('permissions')
            ->orderBy('role_name')
            ->get();

        return response()->json(['success' => true, 'data' => $roles]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => ['required', 'string', 'max:255', 'unique:setting_role,role_name'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ]);

        $role = SettingRole::create([
            'role_name' => $validated['role_name'],
            'is_system' => false,
        ]);

        $role->permissions()->sync($validated['permission_ids'] ?? []);

        return response()->json([
            'success' => true,
            'data' => $role->load('permissions'),
        ], 201);
    }

    public function update(Request $request, SettingRole $role)
    {
        $validated = $request->validate([
            'role_name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('setting_role', 'role_name')->ignore($role->id),
            ],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ]);

        if (isset($validated['role_name'])) {
            $role->role_name = $validated['role_name'];
            $role->save();
        }

        if ($request->has('permission_ids')) {
            $role->permissions()->sync($validated['permission_ids'] ?? []);
        }

        return response()->json([
            'success' => true,
            'data' => $role->fresh(['permissions', 'users']),
        ]);
    }

    public function destroy(SettingRole $role)
    {
        if ($role->is_system) {
            return response()->json([
                'success' => false,
                'message' => 'This role is a system role and cannot be deleted.',
            ], 422);
        }

        $userCount = $role->users()->count();

        if ($userCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "This role is still assigned to {$userCount} user(s) - reassign them before deleting it.",
            ], 422);
        }

        $role->permissions()->detach();
        $role->delete();

        return response()->json(['success' => true, 'data' => null]);
    }

    /**
     * All available permissions, grouped by module - powers the
     * checkbox UI on the role create/edit form.
     */
    public function permissions()
    {
        $grouped = Permission::orderBy('module')
            ->orderBy('label')
            ->get()
            ->groupBy('module');

        return response()->json(['success' => true, 'data' => $grouped]);
    }
}
