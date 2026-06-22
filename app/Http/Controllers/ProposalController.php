<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\ProposalInfo;
use App\Models\CRMLead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ProposalController extends Controller
{
    //
    public function index()
    {
        $proposals = Proposal::select(
            'id',
            'code',
            'lead_id',
            'created_by',
            'status',
            'created_at',
            'updated_at'
        )->with('lead:id,contact_name', 'lead.company:id,lead_id,company_name', 'rates', 'creator:id,name', 'status:id,status')->get();
        return response()->json([
            'success' => true,
            'data' => $proposals,
        ]);
    }
    public function store(Request $request)
    {
        $now = Carbon::now();

        // YYYYMM format (202606)
        $yearMonth = $now->format('Ym');

        $prefix = 'PR';
        $lastProposal = Proposal::where('code', 'like', "{$prefix}-{$yearMonth}%")
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();
        if (!$lastProposal) {
            $sequence = 1;
        } else {
            // extract last 4 digits
            $lastCode = $lastProposal->code; // PR-202606-0003

            $lastSequence = (int) substr($lastCode, -4);

            $sequence = $lastSequence + 1;
        }

        // pad to 4 digits
        $sequencePadded = str_pad($sequence, 4, '0', STR_PAD_LEFT);

        $code = "{$prefix}-{$yearMonth}-{$sequencePadded}";

        try {
            DB::beginTransaction();
            //first get lead info
            $lead = CRMLead::where('uuid', $request->uuid)->first();
            //check first if there is existing record on the proposal table
            $proposal = Proposal::where('lead_id', $lead->id)->first();


            //if there is no existing record create the record first then return the id and create porposal rates with the proposal id.
            if (!$proposal) {
                $payload = [
                    'lead_id' => $lead->id,
                    'created_by' => auth()->id(),
                    'code' => $code,

                ];
                $proposal = Proposal::create($payload);
            }

            //if there is a record get the proposal id and create additional porposal rates with the proposal id
            $proposalRatePayload = [
                'proposal_id' => $proposal->id,
                'proposed_rate' => str_replace(',', '', $request->proposed_rate),
                'route_from' => $request->route_from,
                'route_to' => $request->route_to,
                'min_van_qty'   => $request->min_van_qty,
                'van_type' => $request->van_type,
                'van_size' => $request->van_size,
                'origin_service_type' => $request->service_origin,
                'destination_service_type' => $request->service_destination,
            ];
            ProposalInfo::create($proposalRatePayload);

            //update lead status to negotiation
            $lead->update(['status' => 3]);



            //commit DB
            DB::commit();
            //return
            return response()->json([
                'success' => true,
                'message' => 'proposal saved.'
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex
            ]);
        }
    }
    public function createPdf($id)
    {
        $proposal = Proposal::with([
            'rates',
            'rates.routeFrom',
            'rates.routeTo',
            'rates.vanType',
            'rates.vanSize',
            'rates.serviceOrigin',
            'rates.ServiceDestination',
            'lead',
            'lead.company',
            'creator'
        ])->findOrFail($id);
        // return $proposal;
        // dd(
        //     $proposal->rates->first()->route_from,
        //     gettype($proposal->rates->first()->route_from)
        // );
        $pdf = Pdf::loadView('pdf.proposal', [
            'proposal' => $proposal,
        ]);

        return $pdf->download('proposal.pdf');
    }
}
