<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::with('office', 'userConfig')->get());
    }

    public function create()
    {

        return view('users.create');
    }

    public function show($id)
    {
        try {
            $user = User::with('office', 'userConfig')->findOrFail($id);

            return response()->json($user, 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch user', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'User not found.'], 404);
        }
    }

    public function deactivate($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = 'deactivated';
            $user->save();

            Log::info('User deactivated successfully', ['id' => $id]);

            return response()->json(['message' => 'User deactivated successfully.'], 200);
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
            $user->status = 'active';
            $user->save();

            Log::info('User deactivated successfully', ['id' => $id]);

            return response()->json(['message' => 'User deactivated successfully.'], 200);
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
            'role' => 'nullable|string',
            'office_id' => 'nullable|integer',
            'role_id' => 'nullable|integer',
        ]);

        Log::info('save user info triggered', [
            'inputs' => $request->all(),
            'id' => $id,
        ]);

        try {
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

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to save user', [
                'error' => $e->getMessage(),
                'data' => $validated,
            ]);

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
            'role' => 'nullable|string',
            'office_id' => 'nullable|integer',
            'role_id' => 'nullable|integer',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'] ?? null,
                'office_id' => $validated['office_id'] ?? null,
                'role_id' => $validated['role_id'] ?? null,
                'status' => 'active',
            ]);

            Log::info('User created successfully', ['user_id' => $user->id]);

            return response()->json($user, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create user', [
                'error' => $e->getMessage(),
                'data' => $validated,
            ]);

            return response()->json([
                'error' => 'Failed to create user.'
            ], 500);
        }
    }

    public function reports($officename)
    {
        $query = DB::table('users')
            ->join('office_table', 'office_table.office_id', '=', 'users.office_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'office_table.office_name',

                // Current documents (recipient-based)
                DB::raw('(
                    SELECT COUNT(*)
                    FROM documents
                    WHERE documents.recipient_id = users.id
                ) AS current_document_count'),

                // Processed documents (activity-based, distinct documents)
                DB::raw('(
                    SELECT COUNT(DISTINCT activities.document_id)
                    FROM activities
                    WHERE activities.user_id = users.id
                ) AS processed_document_count')
            );

        /**
         * Office filter rule
         */
        if ($officename !== 'ODDG-PP') {
            $query->where('office_table.office_name', $officename);
        }

        $users = $query->orderBy('users.name')->get();

        return response()->json([
            'office_filter' => $officename,
            'total_users'   => $users->count(),
            'data'          => $users
        ]);
        // dd($officename);
    }
}
