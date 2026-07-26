<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Services\TeamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::withCount('members')
            ->with('leaders:id,name,team_id')
            ->orderBy('name')
            ->get(['id', 'name', 'description', 'parent_id', 'is_active']);

        return response()->json($teams);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:teams,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        $team = Team::create($validator->validated());

        return response()->json(['success' => true, 'data' => $team]);
    }

    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:teams,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $parentId = $data['parent_id'] ?? null;

        if ($parentId && ((int) $parentId === $team->id || TeamService::isWithinSubtree((int) $parentId, $team->id))) {
            return response()->json([
                'success' => false,
                'message' => 'A team cannot be moved under itself or one of its own descendants.',
            ], 422);
        }

        $team->update($data);

        return response()->json(['success' => true, 'data' => $team]);
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);

        if (Team::where('parent_id', $team->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This team still has child teams. Reassign or delete them first.',
            ], 422);
        }

        if (User::where('team_id', $team->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This team still has members. Remove them first.',
            ], 422);
        }

        $team->delete();

        return response()->json(['success' => true]);
    }

    public function members($id)
    {
        Team::findOrFail($id);

        $members = User::where('team_id', $id)
            ->orderBy('name')
            ->get(['id', 'name', 'is_team_leader']);

        return response()->json($members);
    }

    public function availableUsers()
    {
        $users = User::with('team:id,name')
            ->orderBy('name')
            ->get(['id', 'name', 'team_id']);

        return response()->json($users);
    }

    public function addMember(Request $request, $id)
    {
        Team::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        $user = User::findOrFail($request->input('user_id'));
        $user->update(['team_id' => $id, 'is_team_leader' => false]);

        return response()->json(['success' => true, 'data' => $user]);
    }

    public function updateMember(Request $request, $id, $userId)
    {
        $user = User::where('team_id', $id)->findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'is_team_leader' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        $user->update(['is_team_leader' => $request->boolean('is_team_leader')]);

        return response()->json(['success' => true, 'data' => $user]);
    }

    public function removeMember($id, $userId)
    {
        $user = User::where('team_id', $id)->findOrFail($userId);
        $user->update(['team_id' => null, 'is_team_leader' => false]);

        return response()->json(['success' => true]);
    }
}
