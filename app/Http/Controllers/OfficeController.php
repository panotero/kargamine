<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OfficeController extends Controller
{

    public function index()
    {
        return response()->json(Office::with(['parentOfficeInfo'])->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'office_name' => [
                'required',
                'string',
                'max:100',

            ],
            'office_code' => [
                'required',
                'string',
                'max:20',
                'unique:office_table',

            ],
            'parentOffice' => [
                'required',
                'integer',
                'max:200',
            ],
        ]);





        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        $validated = $request->validate([
            'office_name' => 'required|string|max:100',
            'office_code' => 'required|string|max:20|unique:office_table',
            'parentOffice' => 'required|integer|max:200',
        ]);

        Log::info('Creating new office record', $validated);

        // try {
        // dd($validated);
        $officeLevel = 1;
        //check if the office input have parent_office_id
        //make this loop until it reaches the parent office that has no parent office id.

        $parentOfficeInfo = Office::where("office_id", $validated['parentOffice'])->first();
        // dd($parentOfficeInfo);

        if ($parentOfficeInfo) {
            $officeLevel++;
        }
        while ($parentOfficeInfo && $parentOfficeInfo->parent_office_id !== null) {
            $officeLevel++;

            $parentOfficeInfo = Office::where(
                'office_id',
                $parentOfficeInfo->parent_office_id
            )->first();
        }

        //count all the passed parent office create $officeLevel
        $office = Office::create([
            'office_name' => $validated['office_name'],
            'office_code' => $validated['office_code'],
            'parent_office_id' => $validated['parentOffice'],
            'office_level' => $officeLevel, //here the value should be the countent parent office..
            'created_at' => now(),
        ]);

        Log::info('Office record created successfully', ['office_id' => $office->id]);

        return response()->json($office, 201);
        // } catch (\Exception $e) {
        //     Log::error('Failed to create office record', [
        //         'error' => $e->getMessage(),
        //         'data' => $validated,
        //     ]);

        //     return response()->json(['error' => 'Failed to create office record.'], 500);
        // }
    }

    public function destroy($id)
    {
        $office = Office::findOrFail($id);

        Log::info('Deleting office record', [
            'office_id' => $office->id,
            'office_name' => $office->office_name,
            'office_code' => $office->office_code,
            'deleted_by' => auth()->user()->id ?? 'system',
            'timestamp' => now(),
        ]);

        $office->delete();

        Log::info('Office deleted successfully', [
            'office_id' => $id,
            'timestamp' => now(),
        ]);

        return response()->json(['message' => 'Office deleted successfully']);
    }
}
