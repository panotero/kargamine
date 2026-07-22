<?php

namespace App\Http\Controllers;

use App\Models\CrmActivity;
use App\Models\CrmLead;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrmActivityController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function index()
    {
        return CrmActivity::all();
    }

    public function store(Request $request)
    {
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $urls = $this->fileUploadService->uploadFile($request->file('attachment'), 'uploads/crm/activities');
            $attachmentPath = $urls[0] ?? null;
        }

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
                'attachment' => $attachmentPath,
                'created_by' => auth()->id(),
            ]);
            $lead->update($updatepayload);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'activity saved!',
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
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
