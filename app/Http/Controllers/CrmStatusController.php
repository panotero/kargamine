<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\CrmStatus;

class CrmStatusController extends Controller
{
    //

    public function index()
    {
        return CrmStatus::all();
    }

    public function store(Request $request)
    {
        return CrmStatus::create($request->all());
    }

    public function show($id)
    {
        return CrmStatus::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $status = CrmStatus::findOrFail($id);
        $status->update($request->all());

        return $status;
    }

    public function destroy($id)
    {
        CrmStatus::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
