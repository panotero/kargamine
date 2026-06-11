<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CrmActivity;
use Illuminate\Support\Facades\DB;
use App\Models\CRMLead;

class CrmActivityController extends Controller
{
    //
    public function index()
    {
        return CrmActivity::all();
    }

    public function store(Request $request)
    {
        try {
            $lead = CrmLead::where('uuid', $request->leadUUId)->firstOrFail();
            $updatepayload = [
                'status' => $request->status,
            ];
            DB::beginTransaction();
            CrmActivity::create([
                'lead_id' => $lead->id,
                'type' => $request->type,
                'description' => $request->activity,
                'created_by' => auth()->id(),
            ]);
            $lead->update($updatepayload);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'activity saved!'
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'error saving'
            ]);
        }
    }

    public function show($id)
    {
        return CrmActivity::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $activity = CrmActivity::findOrFail($id);
        $activity->update($request->all());

        return $activity;
    }

    public function destroy($id)
    {
        CrmActivity::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
