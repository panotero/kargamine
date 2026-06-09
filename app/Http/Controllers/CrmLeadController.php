<?php

namespace App\Http\Controllers;

use App\Models\CrmLead;
use App\Models\CompanyInfo;
use App\Models\CrmNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CrmLeadController extends Controller
{
    //

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $lead = CrmLead::create([
                'contact_name' => $request->contact_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'status' => $request->status,
                'source' => $request->source,
                'assigned_to' => auth()->id(),
                'estimated_value' => $request->est_value,
                'expected_close_date' => Carbon::now()->addWeek(),
                'status_updated_at' => now(),
            ]);

            CompanyInfo::create([
                'lead_id' => $lead->id,
                'company_name' => $request->company_name,
                'position' => $request->position ?? null,
            ]);
            CrmNote::create([
                'lead_id' => $lead->id,
                'note' => $request->notes,
                'created_by' => auth()->id(),
            ]);


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
        return CrmLead::select(
            'id',
            'contact_name',
            'email',
            'mobile',
            'status',
            'assigned_to',
            'created_at'
        )
            ->with([
                'company:id,lead_id,company_name',
                'status:id,status',
                'user:id,name'
            ])
            ->latest()
            ->get();
    }

    public function show($id)
    {

        $lead = CrmLead::with('company', 'notes', 'activities', 'status:id,status', 'user')->findOrFail($id);

        return response()->json([

            'success' => true,
            'data' => $lead
        ]);
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
