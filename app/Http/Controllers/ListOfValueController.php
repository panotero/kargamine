<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListOfValue;

class ListOfValueController extends Controller
{
    //
    public function index()
    {
        return ListOfValue::with('option')->get();
    }

    // Store new value
    public function store(Request $request)
    {
        $request->validate([
            'lov_optionId' => 'required|exists:options_table,option_id',
            'lov_name' => 'required|string|max:255',
            'lov_description' => 'nullable|string',
        ]);

        // Prevent duplicate per option
        $exists = ListOfValue::where('lov_optionId', $request->lov_optionId)
            ->where('lov_name', $request->lov_name)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'This value already exists for the selected option'
            ], 422);
        }

        $lov = ListOfValue::create($request->all());

        return response()->json([
            'message' => 'Value created successfully',
            'data' => $lov
        ]);
    }

    // Show single value
    public function show($id)
    {
        return ListOfValue::with('option')->findOrFail($id);
    }

    // Update value
    public function update(Request $request, $id)
    {
        $lov = ListOfValue::findOrFail($id);

        $request->validate([
            'lov_optionId' => 'required|exists:options_table,option_id',
            'lov_name' => 'required|string|max:255',
            'lov_description' => 'nullable|string',
        ]);

        $lov->update($request->all());

        return response()->json([
            'message' => 'Value updated successfully',
            'data' => $lov
        ]);
    }

    // Delete value
    public function destroy($id)
    {
        $lov = ListOfValue::findOrFail($id);
        $lov->delete();

        return response()->json([
            'message' => 'Value deleted successfully'
        ]);
    }
}
