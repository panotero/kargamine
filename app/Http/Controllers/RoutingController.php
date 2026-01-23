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
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\error;
use App\Services\ApplicationMailer;

class RoutingController extends Controller
{

    protected ApplicationMailer $mailer;

    public function __construct(ApplicationMailer $mailer)
    {
        $this->mailer = $mailer;
    }
    public function routeDocument(Request $request)
    {
        //BUG ID: 7
        $validator = Validator::make($request->all(), [
            'document_id' => [
                'required',
                'integer'
            ],
            'destination_office' => [
                'required',
                'string',
                'safe_text'
            ],
            'recipient_user_id' => [
                'nullable',
                'integer'
            ],
            'approval_type' => [
                'nullable',
                'string',
                'safe_text'
            ],
            'status' => [
                'nullable',
                'string',
                'safe_text'
            ],

            //BUG ID: 9
            'remarks' => [
                'nullable',
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

        try {

            $auth = Auth::user();

            $user = User::with(['userConfig', 'office'])
                ->findOrFail($auth->id);
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
            // dd($validated);

            $document = Document::findOrFail($validated['document_id']);

            $documentStatus = $document?->status;
            $originOffice      = $document->office_origin;
            $destinationOffice = $validated['destination_office'];
            $recipientUserId   = $validated['recipient_user_id'] ?? null;
            $sameOffice        = ($destinationOffice === $user->office->office_code);
            $backToOrigin      = ($destinationOffice === $originOffice);

            if (!$sameOffice) {
                $activityData['to_external'] = 1;

                $admin_users = User::with(['userConfig', 'office'])
                    ->whereHas('userConfig', function ($q) {
                        $q->where('approval_type', 'routing')
                            ->where('status', 'active');
                    })
                    ->whereHas('office', function ($q) use ($request) {
                        $q->where('office_code', $request->destination_office);
                    })
                    ->get();
                $activityData = [
                    'action'                  => 'route',
                    'document_id'             => $document->document_id,
                    'office_id'             => $user->office->office_name,
                    // 'final_approval'          => $sameOffice ? ($destinationOffice === $originOffice ? 1 : 0) : ($backToOrigin ? 1 : 1),
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

                    $this->mailer->send(
                        [
                            'subject' => 'Document Route',
                            'title'   => "Below document control number has been routed to $destinationOffice by  $user->name",
                            'message' => "",
                            'docControlNumber' => $document->document_control_number,
                            'button'  => [
                                'url'  => url('/dashboard'),
                                'text' => 'Go to Dashboard',
                            ],
                        ],
                        $adminuser->id
                    );
                }
                $updateData = [
                    'recipient_id' => null,
                    'sender_id' => $user->id,
                    'receipt_confirmation' => 0,
                    'receipt_confirmed_by' => 0,
                    'date_forwarded' => now(),
                    'updated_at' => now(),
                ];

                if ($documentStatus === 'completed') {
                    $updateData['status'] = 'Routed';
                }

                DB::table('documents')
                    ->where('document_id', $document->document_id)
                    ->update($updateData);
            } else {
                $activityData = [
                    'action'                  => 'route',
                    'document_id'             => $document->document_id,
                    'office_id'             => $user->office->office_name,
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


                $this->mailer->send(
                    [
                        'subject' => 'Document Route',
                        'title'   => "Below document control number has been routed you by  $user->name for approval",
                        'message' => "",
                        'docControlNumber' => $document->document_control_number,
                        'button'  => [
                            'url'  => url('/dashboard'),
                            'text' => 'Go to Dashboard',
                        ],
                    ],
                    $recipientUserId
                );
                if ($validated['status'] === 'disapproved') {
                    DB::table('documents')
                        ->where('document_id', $document->document_id)
                        ->update([
                            'sender_id' => $user->id,
                            'status' => 'Remanded',
                            'recipient_id'   =>  null,
                            'date_forwarded' => now(),
                            'updated_at' => now(),
                        ]);
                } else {

                    DB::table('documents')
                        ->where('document_id', $document->document_id)
                        ->update([
                            'sender_id' => $user->id,
                            'status' => 'For Approval',
                            'date_forwarded' => now(),
                            'updated_at' => now(),
                        ]);
                }
                DB::table('approval_table')
                    ->where('user_id', $user->id)
                    ->update([
                        'status' => 1,
                        'updated_at' => now(),
                    ]);

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

                'updated_at' => now(),
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
