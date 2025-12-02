<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{

    public function index()
    {
        $activities = Activity::with(['document', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($activities);
    }

    public function show($id)
    {
        $activity = Activity::with(['document', 'user'])->find($id);

        if (!$activity) {
            return response()->json(['error' => 'Activity not found'], 404);
        }

        return response()->json($activity);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|max:100',
            'document_id' => 'nullable|integer',
            'document_control_number' => 'required|string|max:100',
            'user_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $activity = Activity::create($request->all());
        return response()->json(['message' => 'Activity logged successfully', 'data' => $activity], 201);
    }

    public function destroy($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json(['error' => 'Activity not found'], 404);
        }

        $activity->delete();

        return response()->json(['message' => 'Activity deleted successfully']);
    }
}
