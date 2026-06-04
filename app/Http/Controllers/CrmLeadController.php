<?php

namespace App\Http\Controllers;

use App\Models\CrmLead;
use App\Models\CompanyInfo;
use App\Models\CrmNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CrmLeadController extends Controller
{
    //

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $lead = CrmLead::create([
                'contact_name' => $request->contact_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'status' => $request->status,
                'source' => $request->source,
                'assigned_to' => auth()->id(),
                'estimated_value' => $request->estimated_value,
                'expected_close_date' => $request->expected_close_date,
                'status_updated_at' => now(),
            ]);

            if ($request->company) {
                CompanyInfo::create([
                    'lead_id' => $lead->id,
                    'company_name' => $request->company['company_name'],
                    'position' => $request->company['position'] ?? null,
                ]);
            }

            if ($request->notes && is_array($request->notes)) {
                foreach ($request->notes as $note) {
                    CrmNote::create([
                        'lead_id' => $lead->id,
                        'note' => $note,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully',
                'data' => $lead->load('company', 'notes')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create lead',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        return CrmLead::with('company', 'notes', 'activities')->get();
    }

    public function show($id)
    {
        return CrmLead::with('company', 'notes', 'activities')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $lead = CrmLead::findOrFail($id);
        $lead->update($request->all());

        return $lead;
    }

    public function destroy($id)
    {
        CrmLead::findOrFail($id)->delete();
        return response()->json([

            'success' => true,
            'message' => 'Deleted'
        ]);
    }
}
