<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProspectNote;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Prospect;

class CrmNoteController extends Controller
{
    //


    public function store(Request $request)
    {
        try {
            $lead = Prospect::where('uuid', $request->leadUUId)->firstOrFail();
            db::beginTransaction();
            ProspectNote::create([
                'prospect_id' => $lead->id,
                'note' => $request->note,
                'created_by' => auth()->id(),
            ]);
            db::commit();

            return response()->json([
                'success' => true,
                'message' => 'activity saved!'
            ]);
        } catch (\Exception $ex) {
            db::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }
}
