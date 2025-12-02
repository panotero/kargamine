<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentType;

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
}
