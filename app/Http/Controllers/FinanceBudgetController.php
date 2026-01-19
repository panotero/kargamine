<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\FinanceBudget;
use Illuminate\Support\Facades\Validator;

class FinanceBudgetController extends Controller
{
    //
    /**
     * Fetch all budgets or a specific year
     */
    public function index(Request $request)
    {
        $query = FinanceBudget::query();

        if ($request->filled('year')) {
            $query->where('year', $request->year);
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
        $validator = Validator::make($request->all(), [
            'year'   => 'required|integer|min:2000|max:2100|unique:finance_budget,year',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $budget = FinanceBudget::create([
            'year'   => $request->year,
            'amount' => $request->amount,
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
    public function update(Request $request, $year)
    {
        $budget = FinanceBudget::where('year', $year)->first();

        if (!$budget) {
            return response()->json([
                'success' => false,
                'message' => 'Budget not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $budget->update([
            'amount' => $request->amount,
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
        $budget = FinanceBudget::where('year', $year)->first();

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
