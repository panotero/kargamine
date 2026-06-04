<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CrmActivity;

class CrmActivityController extends Controller
{
    //
    public function index()
    {
        return CrmActivity::all();
    }

    public function store(Request $request)
    {
        return CrmActivity::create([
            'lead_id' => $request->lead_id,
            'type' => $request->type,
            'description' => $request->description,
            'created_by' => auth()->id(),
        ]);
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
