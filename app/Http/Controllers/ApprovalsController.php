<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approvals;
use App\Models\User;
use App\Models\Document;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Services\ApplicationMailer;

class ApprovalsController extends Controller
{
    /* =========================================================
     * Public Endpoints
     * ========================================================= */

    protected ApplicationMailer $mailer;

    public function __construct(ApplicationMailer $mailer)
    {
        $this->mailer = $mailer;
    }
    public function getMyApprovals()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $approvals = Approvals::with('document')
            ->where('user_id', $user->id)
            ->where('status', 0)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'approvals' => $approvals
        ]);
    }

    public function handleApprovalAction(Request $request, $document_id)
    {


        //BUG ID: 7
        $validator = Validator::make($request->all(), [
            'action' => [
                'required',
                'string',
                'in:approved,disapproved,remand,for-discussion',
                'safe_text'
            ],
            'next_action' => [
                'nullable',
                'string',
                'in:pre-approval,final-approval',
                'safe_text'
            ],
            'next_user_id' => [
                'nullable',
                'integer',
                'exists:users,id'
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:500',
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
        $user = User::with(['office', 'userConfig'])->find(Auth::id());

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'action'       => 'required|in:approved,disapproved,remand,for-discussion',
            'next_action'  => 'nullable|in:pre-approval,final-approval',
            'next_user_id' => 'nullable|integer|exists:users,id',
            'remarks'      => 'nullable|string|max:500',
        ]);


        $approval = Approvals::with('document')
            ->where('document_id', $document_id)
            ->where('status', 0)
            ->firstOrFail();

        DB::transaction(function () use ($validated, $approval, $user) {
            match ($validated['action']) {
                'approved'        => $this->processApproval($approval, $validated, $user),
                'disapproved'     => $this->processDisapproval($approval, $validated, $user),
                'remand'          => $this->processRemand($approval, $validated, $user),
                'for-discussion'  => $this->processForDiscussion($approval, $validated, $user),
            };
        });

        return response()->json([
            'message' => 'Action completed successfully.',
            'status'  => 1,
        ]);
    }

    /* =========================================================
     * Action Handlers
     * ========================================================= */

    private function processDisapproval($approval, $validated, $user)
    {
        $user = Auth::user();
        // dd([
        //     $validated,
        //     $approval->from_user,
        //     $user,
        // ]);

        //get the last approval row on the approval table to get the id of the last sender
        // $lastSender = Approvals::where('id', $approval->id)
        //     ->first();
        //check if document is for final approval from the approval table
        if ($approval['approval_type'] === "final-approval") {
            //notify last sender

            Document::where('document_id', $approval->document_id)
                ->update([
                    'status'         => "Disapproved",
                    'recipient_id'   => $approval->from_user,
                    'date_forwarded' => now(),
                    'updated_at' => now(),
                ]);

            //notify the last sender

            DB::table('notifications')->insert([
                'document_id'        => $approval->document->document_id,
                'office_origin'      => $approval->document->office_origin,
                'destination_office' => $approval->document->destination_office,
                'from_user_id'       => $user->id,
                'user_id'            => $approval->from_user,
                'message'            => "{$approval->document->document_code} Has been Disapproved.",
                'is_read'            => 0,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);


            $this->mailer->send(
                [
                    'subject' => 'Disapproved document',
                    'title'   => 'Below document control number has been disapproved',
                    'message' => "",
                    'docControlNumber' => $approval->document->document_control_number,
                    'button'  => [
                        'url'  => url('/dashboard'),
                        'text' => 'Go to Dashboard',
                    ],
                ],
                $approval->from_user
            );
            $this->createActivity('disapproved', $approval, $user, $validated);
        } else {

            //check if validated from_user is equal to 0
            if ($approval->from_user === 0 || $approval->from_user === "0") {

                Document::where('document_id', $approval->document->document_id)
                    ->update([
                        'status'         => "Remanded",
                        'recipient_id'   =>  null,
                        'date_forwarded' => now(),
                        'updated_at' => now(),
                    ]);

                $this->notifyAdmins(
                    $approval,
                    $user,
                    "{$approval->document->document_code} Has been Disapproved. you may route this to the origin office",
                    "disapproval"
                );
                $this->createActivity('remand', $approval, $user, $validated);
            } else {

                Document::where('document_id', $approval->document_id)
                    ->update([
                        'status'         => "Disapproved",
                        'recipient_id'   => $approval->from_user,
                        'date_forwarded' => now(),
                        'updated_at' => now(),
                    ]);
                DB::table('notifications')->insert([
                    'document_id'        => $approval->document->document_id,
                    'office_origin'      => $approval->document->office_origin,
                    'destination_office' => $approval->document->destination_office,
                    'from_user_id'       => $user->id,
                    'user_id'            => $approval->from_user,
                    'message'            => "{$approval->document->document_code} Has been Disapproved.",
                    'is_read'            => 0,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);

                $this->mailer->send(
                    [
                        'subject' => 'Disapproved document',
                        'title'   => 'Below document control number has been disapproved',
                        'message' => "",
                        'docControlNumber' => $approval->document->document_control_number,
                        'button'  => [
                            'url'  => url('/dashboard'),
                            'text' => 'Go to Dashboard',
                        ],
                    ],
                    $approval->from_user
                );
                $this->createActivity('disapproved', $approval, $user, $validated);
            }
        }


        $this->finalizeApproval($approval);
    }

    private function processRemand($approval, $validated, $user)
    {
        $this->finalizeApproval($approval, 'Remanded', $validated['remarks']);

        $this->notifyAdmins(
            $approval,
            $user,
            "{$approval->document->document_code} Has been remanded. you may route this to the origin office",
            "remanded"
        );

        $this->notifyUploader(
            $approval,
            $user,
            "{$approval->document->document_code} Has been Remanded.",
            "remanded"
        );

        $this->createActivity('remand', $approval, $user, $validated);

        $this->updateDocument($approval, 'Remanded', true);
    }

    private function processForDiscussion($approval, $validated, $user)
    {
        $this->finalizeApproval($approval, 'For Discussion', $validated['remarks']);

        $message = "{$user->id} is requesting discussion for this document {$approval->document->document_code}.";

        $this->notifyAdmins($approval, $user, $message, "for discussion");
        $this->notifyUploader($approval, $user, $message, "for discussion");

        $this->createActivity('for-discussion', $approval, $user, $validated);

        $this->updateDocument($approval, 'For Discussion');
    }

    private function processApproval($approval, $validated, $user)
    {
        Log::info("approval has been initialized");
        // dd($user->id);
        $approval->remarks = 'Approved';

        if ($approval->approval_type === 'final-approval') {

            Log::info("final approval shit");
            $this->notifyAuthorizedSignatory(
                $approval,
                $user,
                "{$approval->document->document_code} Has been approved. you may route this to the origin office"
            );

            $this->createActivity('approved', $approval, $user, $validated, true);
            $this->updateDocument($approval, 'Approved', true);
            $this->finalizeApproval($approval);

            return;
        }

        $nextAction = $validated['next_action'] ?? null;

        if ($nextAction === 'pre-approval') {

            Log::info("next action is pre approval shit");

            Document::where('document_id', $approval->document->document_id)
                ->update([
                    'recipient_id'   => $validated['next_user_id'],
                    'date_forwarded' => now(),
                    'updated_at' => now(),
                ]);
            $this->createNextApproval(
                $approval->document->document_id,
                $validated['next_user_id'],
                $validated['remarks'],
                'pre-approval',
                $user->id
            );

            $this->createNotification($approval, $user, $validated['next_user_id']);
        }

        if ($nextAction === 'final-approval') {

            Log::info("next action is final approval shit");
            $finalApprover = $this->getFinalApprover(
                $user->office->office_code ?? null,
                'final-approval'
            );

            if (!$finalApprover) {
                throw new \Exception('No final approver found.');
            }
            Document::where('document_id', $approval->document->document_id)
                ->update([
                    'recipient_id'   => $finalApprover->id,
                    'date_forwarded' => now(),

                    'updated_at' => now(),
                ]);

            $this->createNextApproval(
                $approval->document->document_id,
                $finalApprover->id,
                $validated['remarks'],
                'final-approval',
                $user->id
            );

            $this->createNotification($approval, $user, $finalApprover->id);
        }

        $this->createActivity('approved', $approval, $user, $validated);
        $this->finalizeApproval($approval);
    }

    /* =========================================================
     * Shared Helpers
     * ========================================================= */

    private function finalizeApproval($approval)
    {

        return Approvals::where('id', $approval->id)
            ->where('status', 0) // only if still pending
            ->update([
                'status'     => 1,
                'updated_at' => now(),
            ]);
    }

    private function notifyAdmins($approval, $user, $message, $subject)
    {
        $admins = $this->getRoutingAdmins($approval);
        $title = "";
        $subj = $subject;
        switch ($subject) {
            case "for discussion":
                $subj = "Document For Discussion";
                $title = "Recepient office is requesting discussion for the document with control number";
                break;
            case "disapproval":
                $subj = "Document For Discussion";
                $title = "The document with below control number has been disapproved";
                break;
            case "remanded":
                $subj = "Document has been remanded";
                $title = "The document with below control number has been remanded";
                break;
        }

        foreach ($admins as $admin) {
            $this->insertNotification($approval, $user->id, $admin->id, $message);

            $this->mailer->send(
                [
                    'subject' => $subj,
                    'title'   => $title,
                    'message' => "",
                    'docControlNumber' => $approval->document->document_control_number,
                    'button'  => [
                        'url'  => url('/dashboard'),
                        'text' => 'Go to Dashboard',
                    ],
                ],
                $admin->id
            );
        }
    }
    private function notifyAuthorizedSignatory($approval, $user, $message)
    {
        $authorizedSignatory = $this->getAuthorizedSignatory($approval);

        foreach ($authorizedSignatory as $authSignatory) {
            $this->insertNotification($approval, $user->id, $authSignatory->id, $message);

            $this->mailer->send(
                [
                    'subject' => "Document Approval",
                    'title'   => "{$approval->document->document_control_number} has been approved and for your signature",
                    'message' => "",
                    'docControlNumber' => "",
                    'button'  => [
                        'url'  => url('/dashboard'),
                        'text' => 'Go to Dashboard',
                    ],
                ],
                $authSignatory->id
            );
        }
    }

    private function notifyUploader($approval, $user, $message, $subject)
    {
        $this->insertNotification(
            $approval,
            $user->id,
            $approval->document->user_id,
            $message
        );
        $title = "";
        $subj = $subject;

        switch ($subject) {
            case "for discussion":
                $subj = "Document For Discussion";
                $title = "Recepient office is requesting discussion for the document with control number";
                break;
            case "disapproval":
                $subj = "Document For Discussion";
                $title = "The document with below control number has been disapproved";
                break;
            case "remanded":
                $subj = "Document has been remanded";
                $title = "The document with below control number has been remanded";
                break;
        }
        $this->mailer->send(
            [
                'subject' => $subj,
                'title'   => $title,
                'message' => "",
                'docControlNumber' => $approval->document->document_control_number,
                'button'  => [
                    'url'  => url('/dashboard'),
                    'text' => 'Go to Dashboard',
                ],
            ],
            $approval->document->user_id
        );
    }

    private function insertNotification($approval, $fromUserId, $toUserId, $message)
    {
        DB::table('notifications')->insert([
            'document_id'        => $approval->document->document_id,
            'office_origin'      => $approval->document->office_origin,
            'destination_office' => $approval->document->destination_office,
            'from_user_id'       => $fromUserId,
            'user_id'            => $toUserId,
            'message'            => $message,
            'is_read'            => 0,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
    }

    private function createActivity($action, $approval, $user, $validated, $final = false)
    {
        Activity::create([
            'action'                  => $action,
            'document_id'             => $approval->document->document_id,
            'final_approval'          => $final ? 1 : 0,
            'document_control_number' => $approval->document->document_control_number,
            'user_id'                 => $user->id,
            'from_user_id'            => $user->id,
            'final_remarks'           => $validated['remarks'] ?? null,
        ]);
    }

    private function updateDocument($approval, $status, $resetRecipient = false)
    {
        Document::where('document_id', $approval->document->document_id)
            ->update([
                'status'         => $status,
                'recipient_id'   => $resetRecipient ? null : null,
                'date_forwarded' => now(),
                'updated_at' => now(),
            ]);
    }

    private function getRoutingAdmins($approval)
    {
        return User::with(['userConfig', 'office'])
            ->whereHas(
                'userConfig',
                fn($q) =>
                $q->where('approval_type', 'routing')->where('status', 'active')
            )
            ->whereHas(
                'office',
                fn($q) =>
                $q->where('office_code', $approval->document->destination_office)
            )
            ->get();
    }
    private function getAuthorizedSignatory($approval)
    {
        return User::with(['userConfig', 'office'])
            ->where(
                'authorize_signatory',
                '1'
            )
            ->whereHas(
                'office',
                fn($q) =>
                $q->where('office_code', $approval->document->destination_office)
            )
            ->get();
    }

    private function getFinalApprover($office, $approvalType)
    {
        return User::with(['userConfig', 'office'])
            ->whereHas(
                'userConfig',
                fn($q) =>
                $q->where('approval_type', $approvalType)
            )
            ->whereHas(
                'office',
                fn($q) =>
                $q->where('office_code', $office)
            )
            ->first();
    }

    private function createNextApproval($documentId, $userId, $remarks, $approvalType, $userAuthid)
    {

        $user = User::with(['office', 'userConfig'])->find(Auth::id());
        // dd($userAuthid);

        Approvals::create([
            'document_id'   => $documentId,
            'user_id'       => $userId,
            'from_user' => $user->id,
            'status'        => 0,
            'remarks'       => $remarks,
            'approval_type' => $approvalType,
        ]);
    }

    private function createNotification($approval, $user, $destinationUserId)
    {
        $this->insertNotification(
            $approval,
            $user->id,
            $destinationUserId,
            "{$approval->document->document_control_number} has been routed to you for approval"
        );

        $this->mailer->send(
            [
                'subject' => "Document Approval",
                'title'   => "{$approval->document->document_control_number} has been routed to you for approval",
                'message' => "",
                'docControlNumber' => "",
                'button'  => [
                    'url'  => url('/dashboard'),
                    'text' => 'Go to Dashboard',
                ],
            ],
            $destinationUserId
        );
    }
}
