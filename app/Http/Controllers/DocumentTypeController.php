<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentType;
use App\Models\LabelType;
use Illuminate\Support\Facades\Log;

class DocumentTypeController extends Controller
{
    public function index()
    {
        return DocumentType::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $docType = DocumentType::create($request->all());

        return response()->json($docType, 201);
    }

    public function show(string $id)
    {
        return DocumentType::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $docType = DocumentType::findOrFail($id);
        $docType->update($request->all());

        return response()->json($docType);
    }

    public function destroy(string $id)
    {
        DocumentType::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function getlabel()
    {
        return LabelType::all();
    }
    public function storelabel(Request $request)
    {

        $validated = $request->validate([
            'label_name' => 'required|string|max:255',
        ]);

        Log::info([
            'message'           => 'Document created successfully',
            'postdata'              => $validated,
        ]);
        return $validated;
        $labeltype = LabelType::create([
            'label_name'  => $validated['label_name'],
        ]);

        return response()->json($labeltype, 201);
    }

    public function showlabel(string $id)
    {
        return LabelType::findOrFail($id);
    }

    public function updatelabel(Request $request, string $id)
    {
        $request->validate([
            'label' => 'required|string|max:255',
        ]);

        $docType = LabelType::findOrFail($id);
        $docType->update($request->all());

        return response()->json($docType);
    }

    public function destroylabel(string $id)
    {
        LabelType::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
