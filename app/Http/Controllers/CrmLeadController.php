<?php

namespace App\Http\Controllers;

use App\Models\CrmLead;
use App\Models\CrmCompanyInfo;
use App\Models\CrmNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

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
                'position' => $request->position ?? null,
                'status' => $request->status,
                'source' => $request->source,
                'assigned_to' => auth()->id(),
                'estimated_value' => $request->est_value,
                'expected_close_date' => Carbon::now()->addWeek(),
                'status_updated_at' => now(),
            ]);

            CrmCompanyInfo::create([
                'lead_id' => $lead->id,
                'company_name' => $request->company_name,
            ]);
            if (isset($request->notes)) {

                CrmNote::create([
                    'lead_id' => $lead->id,
                    'note' => $request->notes,
                    'created_by' => auth()->id(),
                ]);
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
        return CrmLead::select(
            'id',
            'uuid',
            'contact_name',
            'email',
            'mobile',
            'status',
            'assigned_to',
            'created_at',
            'updated_at'
        )
            ->with([
                'company:id,lead_id,company_name',
                'status:id,status',
                'user:id,name',
            ])
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function show($uuid)
    {

        $lead = CrmLead::with(
            'company',
            'notes.user',
            'activities.user',
            'status:id,status',
            'user',
            'proposals.status'
        )->where('uuid', $uuid)->firstOrFail();

        return response()->json([

            'success' => true,
            'data' => $lead
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $updatePayload = [
            'contact_name' => $request->contact_name,
            'email' => $request->contact_email,
            'mobile' => $request->contact_mobile,
        ];
        try {
            DB::beginTransaction();

            CrmLead::where('uuid', $uuid)->firstOrFail()->update($updatePayload);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
        return response()->json([

            'success' => true,
            'message' => 'updated!'
        ]);
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
