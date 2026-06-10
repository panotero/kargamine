<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CrmNote;
use Exception;
use Illuminate\Support\Facades\DB;

class CrmNoteController extends Controller
{
    //


    public function store(Request $request)
    {
        try {

            db::beginTransaction();
            CrmNote::create([
                'lead_id' => $request->leadId,
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
                'message' => 'error saving',
            ]);
        }
    }
}
