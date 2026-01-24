<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{

    public function index()
    {
        $activities = Activity::with(['document', 'user', 'sender', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($activities);
    }

    public function show($id)
    {
        $activity = Activity::with(['document', 'user', 'sender'])->find($id);

        if (!$activity) {
            return response()->json(['error' => 'Activity not found'], 404);
        }

        return response()->json($activity);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'action' => [
                'required',
                'string',
                'max:100',

            ],
            'document_id' => [
                'nullable',
                'integer'
            ],
            'document_control_number' => [
                'required',
                'string',
                'max:100',

            ],
            'user_id' => [
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

    public function getActivitiesByOffice($office_code)
    {
        $activities = Activity::with(['document', 'user'])
            ->when($office_code !== "ODDG-PP", function ($query) use ($office_code) {
                $query->whereHas('document', function ($q) use ($office_code) {
                    $q->whereJsonContains('involved_office', $office_code);
                });
            })
            ->where('action', '!=', "view")
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($activities->all());
        return response()->json($activities);


        // $ActivitiesQuery = Activity::with(['document', 'user']);
        // $ActivitiesQuery->where('action', '!=', "view");
        // $ActivitiesQuery->where('action', '!=', "view");

        // // if ($office_code !== 'ODDG-PP') {
        // //     $ActivitiesQuery->where(function ($q) use ($office_code) {
        // //         $q->where('office_origin', $office_code)
        // //             ->orWhere('destination_office', $office_code)
        // //             ->orWhereJsonContains('involved_office', $office_code);
        // //     });
        // // }


        // $activities = $ActivitiesQuery->get();
    }
}
