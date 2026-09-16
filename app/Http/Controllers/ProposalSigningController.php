<?php

namespace App\Http\Controllers;

use App\Models\ClientProposal;
use App\Services\FileUploadService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

/**
 * Public (unauthenticated, signed-URL-gated) counterpart to the signature
 * step of the proposal workflow - reached by the client's authorized
 * signatory via the link in ProposalSignatureRequest mail, not by an
 * internal app user. All routes live outside the `auth` group and are
 * protected by Laravel's `signed` middleware instead.
 */
class ProposalSigningController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function show(ClientProposal $proposal)
    {
        return $this->renderSignPage($proposal);
    }

    public function downloadPdf(ClientProposal $proposal)
    {
        $proposal->load([
            'client',
            'client.addresses',
            'lead.company',
            'lead.addresses',
            'creator',
            'rates.originPort.location',
            'rates.originPickupArea',
            'rates.destinationPickupArea',
            'rates.destinationPort.location',
            'rates.container',
            'rates.containerClass',
            'rates.containerSize',
            'rates.ancillaryServices',
        ]);

        $pdf = Pdf::loadView('pdf.clientProposal', ['proposal' => $proposal]);

        return $pdf->download($proposal->code . '.pdf');
    }

    public function upload(Request $request, ClientProposal $proposal)
    {
        if ($proposal->status !== ClientProposal::STATUS_APPROVED) {
            return $this->renderSignPage($proposal, 'This proposal is no longer awaiting a signature.');
        }

        $validator = Validator::make($request->all(), [
            'signed_document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return $this->renderSignPage($proposal, $validator->errors()->first());
        }

        $paths = $this->fileUploadService->uploadFile(
            [$request->file('signed_document')],
            'uploads/doc/pdf'
        );

        $proposal->update([
            'signed_document_path' => $paths[0] ?? null,
            'signed_at' => now(),
            'status' => ClientProposal::STATUS_ACCEPTED,
        ]);

        return $this->renderSignPage($proposal->fresh(), null, true);
    }

    /**
     * Shared render path for show() and upload()'s error/success outcomes -
     * always mints fresh signed sub-links rather than reusing the inbound
     * request's own signature, which is only valid for the exact URL it was
     * generated for.
     */
    private function renderSignPage(ClientProposal $proposal, ?string $error = null, bool $uploaded = false)
    {
        $proposal->load(['client', 'lead.company', 'creator']);

        return view('pages_public.proposal_sign', [
            'proposal' => $proposal,
            'downloadUrl' => URL::temporarySignedRoute(
                'proposal.sign.pdf',
                now()->addDays(30),
                ['proposal' => $proposal->uuid]
            ),
            'uploadUrl' => URL::temporarySignedRoute(
                'proposal.sign.upload',
                now()->addDays(30),
                ['proposal' => $proposal->uuid]
            ),
            'error' => $error,
            'uploaded' => $uploaded,
        ]);
    }
}
