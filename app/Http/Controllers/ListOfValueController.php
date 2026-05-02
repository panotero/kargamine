<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListOfValue;
use Illuminate\Support\Facades\DB;

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
            'optionID' => 'required|exists:options_table,option_id',
            'addLOVCode' => 'required|string|max:255',
            'addLOVName' => 'required|string|max:255',
            'addLOVDescription' => 'nullable|string',
        ]);
        // dd($request->all());

        $result = DB::transaction(function () use ($request) {
            // Prevent duplicate per option
            $exists = ListOfValue::where('lov_optionId', $request->optionID)
                ->where('lov_name', $request->addLOVName)
                ->where('lov_code', $request->addLOVCode)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'This value already exists for the selected option'
                ], 422);
            }
            $inputdata = [
                'lov_optionId' => $request->optionID,
                'lov_code' => $request->addLOVCode,
                'lov_name' => $request->addLOVName,
                'lov_description' => $request->addLOVDescription,
            ];
            $lov = ListOfValue::create($inputdata);
            return $lov;
        });

        return response()->json([
            'success' => true,
            'message' => 'Option deleted successfully',
            'data' => $result,
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
    public function destroy(Request $request)
    {

        // dd($request->all());

        $result = DB::transaction(function () use ($request) {
            $lov = ListOfValue::findOrFail($request['lovID']);
            $lov->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'LOV Deleted',
            'data' => $result,
        ]);
    }
}
