<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserConfig;
use Illuminate\Support\Facades\Log;

class UserConfigController extends Controller
{

    public function index()
    {
        return response()->json(UserConfig::all());
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'designation' => 'required|string|max:100',
            'approval_type' => 'required|in:pre-approval,final-approval,routing',
        ]);

        Log::info('Creating new user config record', $validated);

        try {
            $exists = UserConfig::where('designation', $validated['designation'])->exists();

            if ($exists) {
                Log::warning('Attempted to create duplicate designation', [
                    'designation' => $validated['designation']
                ]);

                return response()->json([
                    'error' => 'Designation already exists.'
                ], 422);
            }

            $userConfig = UserConfig::create($validated);

            Log::info('User config record created successfully', [
                'userconfig_id' => $userConfig->id
            ]);

            return response()->json($userConfig, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create user config record', [
                'error' => $e->getMessage(),
                'data' => $validated,
            ]);
            return response()->json(['error' => 'Failed to create user config record.'], 500);
        }
    }

    public function destroy($id)
    {
        $config = UserConfig::findOrFail($id);
        $config->delete();

        return response()->json(['message' => 'User config deleted successfully']);
    }
}
