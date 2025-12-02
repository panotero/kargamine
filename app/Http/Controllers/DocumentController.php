<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Validator;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{

    public function index()
    {
        $documents = Document::with('files', 'activities')->get();
        return response()->json($documents);
    }

    public function confirm(Request $request)
    {
        $document = Document::with('files', 'activities')
            ->where('document_id', $request->document_id)
            ->first();

        $user = User::with(['userConfig', 'office'])
            ->findOrFail($request->user_id);
        $status = "Pending";
        if ($document->office_origin === $user->office->office_name) {
            $status = "Complete";
        }
        Document::where('document_id', $request->document_id)
            ->update([
                'receipt_confirmation' => 1,
                'receipt_confirmed_by' => $request->user_id,
                'status' => $status,
            ]);




        DB::table('notifications')->insert([
            'document_id'        => $request->document_id,
            'office_origin'      => $document->office_origin,
            'destination_office' => $document->destination_office,
            'from_user_id'       => $request->user_id,
            'user_id'            => $document->user_id,
            'message'            => "{$request->user_id} has confirmed receipt of the document",
            'is_read'            => 0,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        $activityData = [
            'action'                  => 'confirm',
            'document_id'             => $document->document_id,
            'final_approval'          => 0,
            'document_control_number' => $document->document_control_number,
            'user_id'                 => $request->user_id,
            'from_user_id' => $document->user_id,
            'routed_to'               => null,
            'final_remarks'           => $validated['remarks'] ?? null,
        ];
        Activity::create($activityData);

        return response()->json([
            'message'           => 'Confirming receipt successfully',
        ], 201);
    }
    public function show($id)
    {
        $document = Document::with(
            'files',
            'activities',
            'activities.user',
            'activities.fromUser',
            'activities.routedUser'
        )
            ->where('document_id', $id)
            ->first();

        if (!$document) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        return response()->json($document);
    }
    public function store(Request $request)
    {
        $user = User::with(['userConfig', 'office'])
            ->findOrFail($request->user_id);

        $validator = Validator::make($request->all(), [
            'document_code' => [
                'required',
                'string',
                'max:25',
                'regex:/^[a-zA-Z0-9 _-]+$/'
            ],
            'date_received' => 'required|date',
            'particular' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9 ,.\'-]+$/'
            ],
            'office_origin' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9 -]+$/',
                Rule::exists('office_table', 'office_name')
            ],
            'destination_office' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9 -]+$/',
                Rule::exists('office_table', 'office_name')
            ],
            'user_id' => 'required|integer',
            'document_form' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9 ,.-]+$/'
            ],
            'document_type' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9 &,-]+$/',
                Rule::exists('document_types', 'document_type')
            ],
            'date_of_document' => 'nullable|date',
            'due_date' => 'nullable|date',
            'signatory' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9 .,-]+$/'
            ],
            'remarks' => [
                'nullable',
                'string',
                'regex:/^[a-zA-Z0-9 ,.\'-]+$/'
            ],
            'file' => 'required|file|mimes:pdf|max:20480',
        ]);



        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }


        $today  = now()->format('dmY');
        $prefix = "{$today}-";

        $lastDoc = DB::table('documents')
            ->where('document_control_number', 'like', "$prefix%")
            ->orderByDesc('document_control_number')
            ->first();

        $sequence = $lastDoc
            ? str_pad(((int)substr($lastDoc->document_control_number, strlen($prefix))) + 1, 5, '0', STR_PAD_LEFT)
            : '00001';

        $documentControlNumber = $prefix . $sequence;


        $involved_office = [
            $request->office_origin,
            $user->office->office_name,
        ];

        if ($request->destination_office !== $request->office_origin) {
            $involved_office[] = $request->destination_office;
        }


        $document = Document::create([
            'document_code'           => $request->document_code,
            'document_control_number' => $documentControlNumber,
            'date_received'           => $request->date_received,
            'particular'              => $request->particular,
            'office_origin'           => $request->office_origin,
            'destination_office'      => $request->destination_office,
            'involved_office'         => $involved_office,
            'user_id'                 => $request->user_id,
            'date_forwarded'          => now(),
            'document_form'           => $request->document_form,
            'document_type'           => $request->document_type,
            'date_of_document'        => $request->date_of_document,
            'due_date'                => $request->due_date,
            'signatory'               => $request->signatory,
            'remarks'                 => $request->remarks,
        ]);


        if ($request->hasFile('file')) {
            $file          = $request->file('file');
            $officeFolder  = $document->office_origin ?? 'UnknownOffice';

            $cleanOriginal = str_replace(' ', '_', $file->getClientOriginalName());
            $fileName      = uniqid() . '-' . $cleanOriginal;

            $folderPath    = public_path("assets/documents/$officeFolder/pdf");

            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $file->move($folderPath, $fileName);

            $filePath = "assets/documents/$officeFolder/pdf/$fileName";

            DB::table('files')->insert([
                'document_id'      => $document->document_id,
                'file_name'        => $cleanOriginal,
                'file_path'        => $filePath,
                'file_password'    => null,
                'uploading_office' => $document->office_origin,
                'uploaded_by'      => $document->user_id,
                'uploaded_at'      => now(),
            ]);
        }


        $admin_users = User::with(['userConfig', 'office'])
            ->whereHas('userConfig', function ($q) {
                $q->where('approval_type', 'routing')
                    ->where('status', 'active');
            })
            ->whereHas('office', function ($q) use ($request) {
                $q->where('office_name', $request->destination_office);
            })
            ->get();


        foreach ($admin_users as $admin) {
            DB::table('notifications')->insert([
                'document_id'        => $document->document_id,
                'office_origin'      => $request->office_origin,
                'destination_office' => $request->destination_office,
                'routed_to'          => $request->routed_to,
                'from_user_id'       => $request->user_id,
                'user_id'            => $admin->id,
                'message'            => "New document uploaded: {$document->document_code}",
                'is_read'            => 0,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        Activity::create([
            'action'                  => 'upload',
            'document_id'             => $document->document_id,
            'final_approval'          => 0,
            'document_control_number' => $documentControlNumber,
            'user_id'                 => $request->user_id,
            'from_user_id'            => $request->user_id,
            'routed_to'               => null,
            'final_remarks'           => $request->remarks ?? null,
        ]);


        return response()->json([
            'message'           => 'Document created successfully',
            'data'              => $document,
            'userlist'          => $admin_users,
            'docControlNumber'  => $documentControlNumber,
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        $document->update($request->all());
        return response()->json(['message' => 'Document updated successfully', 'data' => $document]);
    }

    public function destroy($id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        $document->delete();
        return response()->json(['message' => 'Document deleted successfully']);
    }
}
