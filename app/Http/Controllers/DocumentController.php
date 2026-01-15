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

    public function index($office_name)
    {

        $documentsQuery = Document::with([
            'files',
            'activities',
            'user',          // user who uploaded/created
            'recipient',     // recipient user
            'confirmedBy'    // user who confirmed
        ]);

        if ($office_name !== 'ODDG-PP') {
            $documentsQuery->where(function ($q) use ($office_name) {
                $q->where('office_origin', $office_name)
                    ->orWhere('destination_office', $office_name)
                    ->orWhereJsonContains('involved_office', $office_name);
            });
        }

        $documents = $documentsQuery
            ->orderBy('updated_at', 'desc')
            ->get();

        // Optionally map extra info if needed
        $documents->transform(function ($doc) {
            return [
                'document_id' => $doc->document_id,
                'document_control_number' => $doc->document_control_number,
                'document_code' => $doc->document_code,
                'date_received' => $doc->date_received,
                'label' => $doc->label,
                'particular' => $doc->particular,
                'office_origin' => $doc->office_origin,
                'destination_office' => $doc->destination_office,
                'user_id' => $doc->user_id,
                'user_name' => $doc->user->name ?? 'Unknown',
                'recipient_id' => $doc->recipient_id,
                'recipient_name' => $doc->recipient->name ?? 'Unknown',
                'document_form' => $doc->document_form,
                'document_type' => $doc->document_type,
                'date_of_document' => $doc->date_of_document,
                'due_date' => $doc->due_date,
                'signatory' => $doc->signatory,
                'date_forwarded' => $doc->date_forwarded,
                'receipt_confirmation' => $doc->receipt_confirmation,
                'receipt_confirmed_by' => $doc->receipt_confirmed_by,
                'confirmed_by_name' => $doc->confirmedBy->name ?? 'Unknown',
                'involved_office' => $doc->involved_office,
                'action_taken' => $doc->action_taken,
                'status' => $doc->status,
                'revision_status' => $doc->revision_status,
                'remarks' => $doc->remarks,
                'confidentiality' => $doc->confidentiality,
                'created_at' => $doc->created_at,
                'updated_at' => $doc->updated_at,
                'files' => $doc->files,
                'activities' => $doc->activities
            ];
        });

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
        if ($document->office_origin === $user->office->office_code) {
            $status = "Completed";
        }
        Document::where('document_id', $request->document_id)
            ->update([
                'receipt_confirmation' => 1,
                'receipt_confirmed_by' => $request->user_id,
                'recipient_id' => $request->user_id,
                'status' => $status,
                'updated_at' => now(),
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
            'activities.routedUser',
            'approvals',
        )
            ->where('document_id', $id)
            ->first();

        if (!$document) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        return response()->json($document);
    }

    public function revise(Request $request)
    {

        //BUG ID: 7
        $validator = Validator::make($request->all(), [
            'revisedocControlNumber' => [
                'required',
                'string',
                'safe_text'
            ],
            'user_id' => [
                'required',
                'integer'
            ],
            'document_form' => [
                'required',
                'string',
                'safe_text'
            ],
            'file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:50240'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        $data = $request->validate([
            'revisedocControlNumber' => 'required|string',
            'user_id' => 'required|integer',
            'revisedocControlNumber' => 'required|string',
            'document_form'         => 'required|string',
            'file'                  => 'required|file|mimes:pdf|max:50240',
        ]);

        //get document info by document control number
        $document = Document::with(
            'files',
            'activities',
            'activities.user',
            'activities.fromUser',
            'activities.routedUser',
            'approvals',
        )
            ->where('document_control_number', $data['revisedocControlNumber'])
            ->first();
        $originalDocControlNumber = $data['revisedocControlNumber'];

        if (preg_match('/^R-(\d{2})-(.+)$/', $originalDocControlNumber, $matches)) {
            // Case 2: Already has R-XX- → increment revision
            $currentRevision = (int) $matches[1];
            $newRevision = str_pad($currentRevision + 1, 2, '0', STR_PAD_LEFT);

            $documentControlNumber = 'R-' . $newRevision . '-' . $matches[2];
        } else {
            // Case 1: No R-XX- yet → start with R-01-
            $documentControlNumber = 'R-01-' . $originalDocControlNumber;
        }

        //get office info of sender id

        $sender = User::with(['userConfig', 'office'])
            ->findOrFail($document->sender_id);

        $involved_office = [
            $document->office_origin,
            $sender->office->office_code,
        ];

        if ($request->destination_office !== $request->office_origin) {
            $involved_office[] = $request->destination_office;
        }

        $reviseddocument = Document::create([
            'document_code'           => $document->document_code,
            'document_control_number' => $documentControlNumber,
            'date_received'           => $document->date_received,
            'particular'              => $document->particular,
            'office_origin'           => $document->office_origin,
            'destination_office'      => $sender->office->office_code,
            'involved_office'         => $involved_office,
            'user_id'                 => $data['user_id'],
            'date_forwarded'          => now(),
            'document_form'           => $document->document_form,
            'document_type'           => $document->document_type,
            'date_of_document'        => $document->date_of_document,
            'signatory'               => $document->signatory,
            'remarks'                 => $document->remarks,
        ]);


        if ($request->hasFile('file')) {
            $file          = $request->file('file');
            $officeFolder  = $document->office_origin ?? 'UnknownOffice';
            $cleanOriginal = str_replace(' ', '_', $file->getClientOriginalName());
            $fileName      = uniqid() . '-' . $cleanOriginal;
            $folder = "storage/assets/documents/$officeFolder/pdf";
            $folderPath    = $folder;
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $file->move($folderPath, $fileName);

            $filePath = "$folder/$fileName";

            DB::table('files')->insert([
                'document_id'      => $reviseddocument->document_id,
                'file_name'        => $cleanOriginal,
                'file_path'        => $filePath,
                'file_password'    => null,
                'uploading_office' => $reviseddocument->office_origin,
                'uploaded_by'      => $reviseddocument->user_id,
                'uploaded_at'      => now(),
            ]);
        }
        $admin_users = User::with(['userConfig', 'office'])
            ->whereHas('userConfig', function ($q) {
                $q->where('approval_type', 'routing')
                    ->where('status', 'active');
            })
            ->whereHas('office', function ($q) use ($sender) {
                $q->where('office_code', $sender->office->office_code);
            })
            ->get();



        foreach ($admin_users as $admin) {
            DB::table('notifications')->insert([
                'document_id'        => $reviseddocument->document_id,
                'office_origin'      => $reviseddocument->office_origin,
                'destination_office' => $reviseddocument->destination_office,
                'routed_to'          => $reviseddocument->routed_to,
                'from_user_id'       => $reviseddocument->user_id,
                'user_id'            => $admin->id,
                'message'            => "New document uploaded: {$document->document_code}",
                'is_read'            => 0,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        Activity::create([
            'action'                  => 'upload',
            'document_id'             => $reviseddocument->document_id,
            'final_approval'          => 0,
            'document_control_number' => $reviseddocument->document_control_number,
            'user_id'                 => $reviseddocument->user_id,
            'from_user_id'            => $reviseddocument->user_id,
            'routed_to'               => null,
            'final_remarks'           => $reviseddocument->remarks ?? null,
        ]);


        Log::info([
            'message'           => 'Document created successfully',
            'data'              => $reviseddocument,
            'userlist'          => $admin_users,
            'docControlNumber'  => $reviseddocument->document_control_number,
        ]);
        //update document revised status to 1 to prevent multiple revision on a single remanded document

        Document::where('document_control_number', $originalDocControlNumber)
            ->update([
                'revision_status' => 1,

                'updated_at' => now(),
            ]);


        return response()->json([
            'message'           => 'Document created successfully'
        ], 201);
    }



    public function esign(Request $request)
    {

        //BUG ID: 7
        $validator = Validator::make($request->all(), [
            'docControlNumber' => [
                'required',
                'string',
                'safe_text'
            ],
            'remarks' => [
                'nullable',
                'string',
                'safe_text'
            ],
            'document_form' => [
                'required',
                'string',
                'safe_text'
            ],
            'file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:50240'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();

        $validated = $request->validate([
            'docControlNumber' => 'required|string',
            'remarks' => 'nullable|string',
            'document_form'         => 'required|string',
            'file'                  => 'required|file|mimes:pdf|max:50240',
        ]);

        // dd($validated);
        $document = Document::where('document_control_number', $validated['docControlNumber'])->first();
        if (!$document) {
            return response()->json(['message' => 'Invalid document control number.'], 404);
        }
        //upload file

        if ($request->hasFile('file')) {
            $file          = $request->file('file');
            $officeFolder  = $document->office_origin ?? 'UnknownOffice';
            $cleanOriginal = str_replace(' ', '_', $file->getClientOriginalName());
            $fileName      = uniqid() . '-' . $cleanOriginal;
            $folder = "storage/assets/documents/$officeFolder/pdf";
            $folderPath    = $folder;
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $file->move($folderPath, $fileName);

            $filePath = "$folder/$fileName";

            DB::table('files')->insert([
                'document_id'      => $document->document_id,
                'file_name'        => $cleanOriginal,
                'file_path'        => $filePath,
                'file_password'    => null,
                'uploading_office' => $document->office_origin,
                'uploaded_by'      => $user->id,
                'uploaded_at'      => now(),
            ]);
        }
        //update status to Signed

        Document::where('document_control_number', $validated['docControlNumber'])
            ->update([
                'status' => "Signed",
                'date_forwarded' => now(),
                'updated_at' => now(),
                'recipient_id' => null
            ]);

        //create notification for internal admins


        $admin_users = User::with(['userConfig', 'office'])
            ->whereHas('userConfig', function ($q) {
                $q->where('approval_type', 'routing')
                    ->where('status', 'active');
            })
            ->whereHas('office', function ($q) use ($request) {
                $q->where('office_code', $request->destination_office);
            })
            ->get();

        //create activity

        Activity::create([
            'action'                  => 'sign',
            'document_id'             => $document->document_id,
            'final_approval'          => 0,
            'document_control_number' => $validated['docControlNumber'],
            'user_id'                 => $user->id,
            'from_user_id'            => $user->id,
            'routed_to'               => null,
            'final_remarks'           => $request->remarks ?? null,
        ]);


        foreach ($admin_users as $admin) {
            DB::table('notifications')->insert([
                'document_id'        => $document->document_id,
                'office_origin'      => $document->office_origin,
                'destination_office' => $document->destination_office,
                'routed_to'          => $document->routed_to,
                'from_user_id'       => $user->id,
                'user_id'            => $admin->id,
                'message'            => "New document uploaded: {$document->document_code}",
                'is_read'            => 0,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        //remove recepient if it has recepient id


        return response()->json([
            'message'           => 'Document eSigned successfully'
        ], 201);
    }
    public function store(Request $request)
    {
        $user = User::with(['userConfig', 'office'])
            ->findOrFail($request->user_id);

        //BUG ID: 7
        $validator = Validator::make($request->all(), [
            'document_code' => [
                'required',
                'string',
                'max:25',
                'safe_text'
            ],
            'date_received' => 'required|date',
            'particular' => [
                'required',
                'string',
                'safe_text',
            ],
            'office_origin' => [
                'required',
                'string',
                'max:100',
                'safe_text',
                Rule::exists('office_table', 'office_code')
            ],
            'destination_office' => [
                'nullable',
                'string',
                'max:100',
                'safe_text',
                Rule::exists('office_table', 'office_code')
            ],
            'user_id' => 'required|integer',
            'document_form' => [
                'required',
                'string',
                'max:50',
                'safe_text',
            ],
            'document_type' => [
                'required',
                'string',
                'max:50',
                'safe_text',
                Rule::exists('document_types', 'document_type')
            ],
            'date_of_document' => 'nullable|date',
            'due_date' => 'nullable|date',
            'signatory' => [
                'required',
                'string',
                'max:100',
                'safe_text',
            ],
            'remarks' => [
                'nullable',
                'string',
                'safe_text',
            ],
            'file' => 'required|file|mimes:pdf|max:50480',
        ]);



        if ($validator->fails()) {
            return response()->json([
                'success' => false,
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
            $user->office->office_code,
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
            'date_of_document'        => $request->document_date,
            'due_date'                => $request->due_date,
            'signatory'               => $request->signatory,
            'remarks'                 => $request->remarks,
            'status'                 => "Routed",
        ]);


        if ($request->hasFile('file')) {
            $file          = $request->file('file');
            $officeFolder  = $document->office_origin ?? 'UnknownOffice';
            $cleanOriginal = str_replace(' ', '_', $file->getClientOriginalName());
            $fileName      = uniqid() . '-' . $cleanOriginal;
            $folder = "storage/assets/documents/$officeFolder/pdf";
            $folderPath    = $folder;
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $file->move($folderPath, $fileName);

            $filePath = "$folder/$fileName";

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
                $q->where('office_code', $request->destination_office);
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


        Log::info([
            'message'           => 'Document created successfully',
            'data'              => $document,
            'userlist'          => $admin_users,
            'docControlNumber'  => $documentControlNumber,
        ]);
        return response()->json([
            'message'           => 'Document created successfully'
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        $document->update($request->all(),);
        return response()->json(['message' => 'Document updated successfully', 'data' => $document]);
    }

    public function update_status(Request $request)
    {
        //BUG ID: 7
        $validator = Validator::make($request->all(), [
            'document_id' => [
                'required',
                'integer'
            ],
            'status' => [
                'required',
                'string',
                'safe_text'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        $validated = $request->validate([
            'document_id' => 'required|integer',
            'status' => 'required|string',
        ]);
        // dd($validated);
        // return;
        if ($validated['status'] === "overdue") {

            Document::where('document_id', $validated['document_id'])
                ->update([
                    'status' => "Overdue",

                    'updated_at' => now(),
                ]);
        } else {
            Document::where('document_id', $validated['document_id'])
                ->update([
                    'status' => "Due Today",

                    'updated_at' => now(),
                ]);
        }
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
