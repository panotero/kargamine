<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\ListOfValue;
use Illuminate\Support\Facades\DB;

class OptionController extends Controller
{
    //Display all options
    public function index()
    {
        $options = Option::with('values')->get();
        return response()->json($options);
    }

    // Store new option
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'option_name' => 'required|string|max:255|unique:options_table,option_name',
            'option_description' => 'nullable|string',
        ]);


        //enclose the database transaction logic

        $result = DB::transaction(function () use ($request) {


            // 1. Create Option
            $option = Option::create([
                'option_name' => $request->option_name,
                'option_description' => $request->option_description,
            ]);

            // 2. Create LOVs
            foreach ($request->lovData as $lov) {
                ListOfValue::create([
                    'lov_code' => $lov['lov_code'],
                    'lov_optionId' => $option->option_id,
                    'lov_name' => $lov['lov_name'],
                    'lov_description' => $lov['lov_description'],
                ]);
            }

            return $option;
        });


        return response()->json([
            'success' => true,
            'message' => 'Option created successfully',
            'data' => $result,
        ]);
    }

    // Show single option
    public function show($id)
    {
        return Option::with('values')->findOrFail($id);
    }

    // Update option
    public function update(Request $request, $id)
    {
        $option = Option::findOrFail($id);

        $request->validate([
            'option_name' => 'required|string|max:255|unique:options_table,option_name,' . $id . ',option_id',
            'option_description' => 'nullable|string',
        ]);

        $option->update($request->only([
            'option_name',
            'option_description'
        ]));

        return response()->json([
            'message' => 'Option updated successfully',
            'data' => $option
        ]);
    }

    // Delete option (cascade deletes LOVs)
    public function destroy(Request $request)
    {

        $result = DB::transaction(function () use ($request) {
            $option = Option::findOrFail($request['optionID']);
            $option->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Option deleted successfully',
            'data' => $result,
        ]);
    }
    public function byOption($optionId)
    {
        $values = ListOfValue::where('lov_optionId', $optionId)
            ->with('option')
            ->get();

        return response()->json([
            'option_id' => $optionId,
            'data' => $values
        ]);
    }
    public function storeByOption(Request $request, $optionId)
    {
        $request->validate([
            'lov_name' => 'required|string|max:255',
            'lov_description' => 'nullable|string',
        ]);

        // Ensure option exists
        $existsOption = \App\Models\Option::where('option_id', $optionId)->exists();

        if (!$existsOption) {
            return response()->json([
                'message' => 'Option not found'
            ], 404);
        }

        // Prevent duplicate value under same option
        $duplicate = ListOfValue::where('lov_optionId', $optionId)
            ->where('lov_name', $request->lov_name)
            ->exists();

        if ($duplicate) {
            return response()->json([
                'message' => 'This value already exists under this option'
            ], 422);
        }

        $lov = ListOfValue::create([
            'lov_optionId' => $optionId,
            'lov_name' => $request->lov_name,
            'lov_description' => $request->lov_description,
        ]);

        return response()->json([
            'message' => 'Value created successfully under option',
            'data' => $lov
        ]);
    }
}
