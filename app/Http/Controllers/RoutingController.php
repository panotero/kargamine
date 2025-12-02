<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use App\Models\Activity;
use App\Models\Notification;
use App\Models\Approvals;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

use function Laravel\Prompts\error;

class RoutingController extends Controller
{
    public function routeDocument(Request $request)
    {
        try {

            $user = Auth::user();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'document_id'        => 'required|integer',
                'destination_office' => 'required|string',
                'recipient_user_id'  => 'nullable|integer',
                'approval_type'      => 'nullable|string',
                'status'             => 'nullable|string',
                'remarks'            => 'nullable|string',
            ]);

            $document = Document::findOrFail($validated['document_id']);

            $originOffice      = $document->office_origin;
            $destinationOffice = $validated['destination_office'];
            $recipientUserId   = $validated['recipient_user_id'] ?? null;
            $sameOffice        = ($destinationOffice === $user->office->office_name);
            $backToOrigin      = ($destinationOffice === $originOffice);

            if (!$sameOffice) {
                $activityData['to_external'] = 1;

                $admin_users = User::with(['userConfig', 'office'])
                    ->whereHas('userConfig', function ($q) {
                        $q->where('approval_type', 'routing')
                            ->where('status', 'active');
                    })
                    ->whereHas('office', function ($q) use ($request) {
                        $q->where('office_name', $request->destination_office);
                    })
                    ->get();

                if ($request->hasFile('pdf_file') && $request->file('pdf_file')->isValid()) {

                    $file = $request->file('pdf_file');
                    $officeFolder = $document->office_origin ?: 'UnknownOffice';
                    $cleanOriginal = str_replace(' ', '_', $file->getClientOriginalName());
                    $fileName = uniqid() . '-' . $cleanOriginal;
                    $publicPath = public_path("assets/documents/{$officeFolder}/pdf");

                    if (!is_dir($publicPath)) {
                        mkdir($publicPath, 0777, true);
                    }

                    $file->move($publicPath, $fileName);
                    $filePath = "assets/documents/{$officeFolder}/pdf/{$fileName}";

                    DB::table('files')->insert([
                        'document_id'      => $document->document_id,
                        'file_name'        => $cleanOriginal,
                        'file_path'        => $filePath,
                        'file_password'    => null,
                        'uploading_office' => $destinationOffice,
                        'uploaded_by'      => $user->id,
                        'uploaded_at'      => now(),
                    ]);
                    if ($document->status === "Approved") {

                        foreach ($admin_users as $adminuser) {

                            $activityData = [
                                'action'                  => 'signed',
                                'document_id'             => $document->document_id,
                                'final_approval'          => $sameOffice ? ($destinationOffice === $originOffice ? 1 : 0) : ($backToOrigin ? 1 : 1),
                                'document_control_number' => $document->document_control_number,
                                'user_id'                 => $user->id,
                                'from_user_id' => $user->id,
                                'routed_to'               => $recipientUserId,
                                'to_external' => 1,
                                'final_remarks'           => $validated['remarks'] ?? null,
                            ];
                            DB::table('notifications')->insert([
                                'document_id'        => $document->document_id,
                                'office_origin'      => $originOffice,
                                'destination_office' => $destinationOffice,
                                'from_user_id' => $user->id,
                                'user_id'            => $adminuser->id,
                                'message'            => "$document->document_control_number has been signed ",
                                'is_read'            => 0,
                                'created_at'         => now(),
                                'updated_at'         => now(),
                            ]);
                        }
                        DB::table('documents')
                            ->where('document_id', $document->document_id)
                            ->update([
                                'recipient_id' => null,
                                'receipt_confirmation' => 0,
                                'receipt_confirmed_by' => 0,
                                'status' => "Signed",
                                'date_forwarded' => now(),
                            ]);
                    } else {
                        $activityData = [
                            'action'                  => 'route',
                            'document_id'             => $document->document_id,
                            'final_approval'          => $sameOffice ? ($destinationOffice === $originOffice ? 1 : 0) : ($backToOrigin ? 1 : 1),
                            'document_control_number' => $document->document_control_number,
                            'user_id'                 => $user->id,
                            'from_user_id' => $user->id,
                            'to_external' => 1,
                            'routed_to'               => $recipientUserId,
                            'final_remarks'           => $validated['remarks'] ?? null,
                        ];
                        foreach ($admin_users as $adminuser) {
                            DB::table('notifications')->insert([
                                'document_id'        => $document->document_id,
                                'office_origin'      => $originOffice,
                                'destination_office' => $destinationOffice,
                                'from_user_id' => $user->id,
                                'user_id'            => $adminuser->id,
                                'message'            => "$document->document_control_number has been routed to $destinationOffice by  $user->name",
                                'is_read'            => 0,
                                'created_at'         => now(),
                                'updated_at'         => now(),
                            ]);
                        }
                        DB::table('documents')
                            ->where('document_id', $document->document_id)
                            ->update([
                                'recipient_id' => null,
                                'receipt_confirmation' => 0,
                                'receipt_confirmed_by' => 0,
                                'date_forwarded' => now(),
                            ]);
                    }
                } else {
                    return "no pdf available";
                }
            } else {
                $activityData = [
                    'action'                  => 'route',
                    'document_id'             => $document->document_id,
                    'final_approval'          => $sameOffice ? ($destinationOffice === $originOffice ? 1 : 0) : ($backToOrigin ? 1 : 1),
                    'document_control_number' => $document->document_control_number,
                    'user_id'                 => $user->id,
                    'from_user_id' => $user->id,
                    'to_external' => 1,
                    'routed_to'               => $recipientUserId,
                    'final_remarks'           => $validated['remarks'] ?? null,
                ];


                DB::table('notifications')->insert([
                    'document_id'        => $document->document_id,
                    'office_origin'      => $originOffice,
                    'destination_office' => $destinationOffice,
                    'from_user_id' => $user->id,
                    'user_id'            => $recipientUserId,
                    'message'            => "$document->document_control_number has been routed you by  $user->name for approval",
                    'is_read'            => 0,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
                DB::table('documents')
                    ->where('document_id', $document->document_id)
                    ->update(['status' => 'For Approval', 'date_forwarded' => now(),]);
                DB::table('approval_table')
                    ->where('user_id', $user->id)
                    ->update(['status' => 1]);

                Approvals::create([
                    'document_id' => $document->document_id,
                    'user_id' => $recipientUserId,
                    'approval_type' => $validated['approval_type'],

                    'remarks' => $validated['remarks'],
                    'status' => 0,
                ]);
            }
            Activity::create($activityData);
            $document->update([
                'destination_office' => $destinationOffice,
                'recipient_id'       => $recipientUserId,
            ]);
            if ($sameOffice) {
                return response()->json([
                    'status'  => 'success',
                    'message' => "routing to internal office",
                ]);
            }

            if ($backToOrigin) {
                return response()->json([
                    'status'  => 'success',
                    'message' => "routing to origin office",
                ]);
            }

            return response()->json([
                'status'  => 'success',
                'message' => "routing to external office",
            ]);
        } catch (Exception $e) {

            return response()->json([
                'status'  => 'error',
                'message' => "Error on routing: {$e->getMessage()}",
            ]);
        }
    }
}
