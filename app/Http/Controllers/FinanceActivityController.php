<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinanceActivity;

class FinanceActivityController extends Controller
{
    //
    /**
     * GET /api/finance-activity
     * List finance activities
     */
    public function index($finance_id, Request $request)
    {

        $activities = FinanceActivity::where('finance_id', $finance_id)->orderBy('timestamp', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $activities,
        ]);
    }

    /**
     * POST /api/finance-activity
     * Store a new finance activity
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity' => ['required', 'string', 'max:255'],
            'remarks'  => ['nullable', 'string'],
            'finance_id'  => ['nullable', 'integer'],
            'status'   => ['nullable', 'string', 'max:50'],
            'timestamp' => ['nullable', 'date'],
        ]);

        $activity = FinanceActivity::create([
            'activity'  => $validated['activity'],
            'finance_id'  => $validated['finance_id'],
            'remarks'   => $validated['remarks'] ?? null,
            'status'    => $validated['status'] ?? 'pending',
            'timestamp' => $validated['timestamp'] ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Finance activity recorded successfully.',
            'data'    => $activity,
        ], 201);
    }
}
