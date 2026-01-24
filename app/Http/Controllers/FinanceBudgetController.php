<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\FinanceBudget;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FinanceBudgetController extends Controller
{
    //
    /**
     * Fetch all budgets or a specific year
     */
    public function index(Request $request)
    {
        $authuser = Auth::user();
        $user = User::with(['userConfig', 'office'])
            ->findOrFail($authuser->id);
        $query = FinanceBudget::query();

        if ($request->filled('year')) {
            $query->where('year', $request->year)->where('office_code', $user->office->office_code);
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('year', 'desc')->get(),
        ]);
    }

    /**
     * Store a new budget
     */
    public function store(Request $request)
    {

        $authuser = Auth::user();
        $user = User::with(['userConfig', 'office'])
            ->findOrFail($authuser->id);
        $validator = Validator::make($request->all(), [

            'amount' => [
                'required',
                'numeric',

            ],
            'year' => [
                'required',
                'string',

            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $authuser = Auth::user();
        $user = User::with(['userConfig', 'office'])
            ->findOrFail($authuser->id);
        $budget = FinanceBudget::create([
            'year'   => $request->year,
            'amount' => $request->amount,
            'office_code'   => $user->office->office_code,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Budget created successfully.',
            'data'    => $budget,
        ], 201);
    }

    /**
     * Update budget by year
     */
    public function update(Request $request)
    {
        $authuser = Auth::user();
        $user = User::with(['userConfig', 'office'])
            ->findOrFail($authuser->id);
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'amount' => [
                'required',
                'numeric',

            ],
            'year' => [
                'required',
                'string',

            ],
        ]);
        $budget = FinanceBudget::where('year', $request->year)->where('office_code', $user->office->office_code)->first();

        if (!$budget) {
            return response()->json([
                'success' => false,
                'message' => 'Budget not found.',
            ], 404);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }
        $amount = str_replace(',', '', $request->amount);
        $budget->update([
            'amount' => $amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Budget updated successfully.',
            'data'    => $budget,
        ]);
    }

    /**
     * Fetch single budget by year
     */
    public function show($year)
    {
        $authuser = Auth::user();
        $user = User::with(['userConfig', 'office'])
            ->findOrFail($authuser->id);
        $budget = FinanceBudget::where('year', $year)->where('office_code', $user->office->office_code)->first();

        if (!$budget) {
            return response()->json([
                'success' => false,
                'message' => 'Budget not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $budget,
        ]);
    }
}
