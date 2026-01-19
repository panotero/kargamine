<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Finance;
use Illuminate\Support\Facades\Auth;
use App\Models\Office;

class FinanceController extends Controller
{
    //
    public function get()
    {

        $user = Auth::user();

        return response()->json(Finance::where("uploading_office", $user->office_id)
            ->orderBy('created_at', 'desc')
            ->get());
    }

    public function store(Request $request)
    {

        $user = Auth::user();
        // dd($user);
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'transaction' => ['required', 'string', 'in:ORS,DV'],
            'date_processed' => ['nullable', 'date'],
            'payee' => ['required', 'string', 'max:255', 'safe_text'],
            'particular' => ['required', 'string', 'max:1000', 'safe_text'],
            'responsibility_center' => ['nullable', 'string', 'max:255', 'safe_text'],
            'expenditure' => ['nullable', 'string', 'max:255', 'safe_text'],
            'uacs_object_code' => ['nullable', 'string', 'max:50', 'safe_text'],
            'amount' => ['required', 'numeric', 'min:0'],
            'fund_cluster' => ['nullable', 'string', 'max:50', 'safe_text'],
            'date_signed' => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $cleanOriginal = str_replace(' ', '_', $file->getClientOriginalName());
            $fileName = uniqid() . '-' . $cleanOriginal;

            $folderPath = storage_path("app/public/assets/finance/pdf");
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $file->move($folderPath, $fileName);
            $filePath = "storage/assets/finance/pdf/$fileName";

            $data['file_name'] = $cleanOriginal;
            $data['file_path'] = $filePath;
        }

        $data['uploading_office'] = $user->office_id;
        $data['uploaded_by'] = $user->id;
        $finance = Finance::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Finance record created successfully!',
            'data' => $finance
        ]);
    }
}
