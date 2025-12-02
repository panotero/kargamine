<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;
use Illuminate\Support\Facades\Log;

class OfficeController extends Controller
{

    public function index()
    {
        return response()->json(Office::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'office_name' => 'required|string|max:100',
            'office_code' => 'required|string|max:20|unique:office_table',
        ]);

        Log::info('Creating new office record', $validated);

        try {
            $office = Office::create([
                'office_name' => $validated['office_name'],
                'office_code' => $validated['office_code'],
                'created_at' => now(),
            ]);

            Log::info('Office record created successfully', ['office_id' => $office->id]);

            return response()->json($office, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create office record', [
                'error' => $e->getMessage(),
                'data' => $validated,
            ]);

            return response()->json(['error' => 'Failed to create office record.'], 500);
        }
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
