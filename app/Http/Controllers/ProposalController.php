<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\ProposalInfo;
use App\Models\CrmLead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CrmCompanyInfo;
use App\Models\ProposalStatus;
use App\Services\ApplicationMailer;
use App\Services\ActivityService;
use Carbon\Carbon;
use MessageFormatter;

class ProposalController extends Controller
{

    protected ApplicationMailer $mailer;
    protected ActivityService $activityService;


    public function __construct(ApplicationMailer $mailer,  ActivityService $activityService)
    {
        $this->mailer = $mailer;
        $this->activityService = $activityService;
    }
    //
    public function index(Request $request)
    {
        $proposals = Proposal::query()
            ->select(
                'id',
                'code',
                'lead_id',
                'created_by',
                'status',
                'created_at',
                'updated_at'
            )
            ->with(
                'lead:id,contact_name',
                'lead.company:id,lead_id,company_name',
                'rates',
                'creator:id,name',
                'status:id,status'
            )
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhereHas('lead', function ($q) use ($search) {
                            $q->where('contact_name', 'like', "%{$search}%")
                                ->orWhereHas('company', function ($q) use ($search) {
                                    $q->where('company_name', 'like', "%{$search}%");
                                });
                        });
                });
            })
            ->orderByDesc('updated_at')
            ->paginate($request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $proposals,
        ]);
    }
    public function store(Request $request)
    {
        // dd($request->all());
        try {

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
            DB::beginTransaction();
            //first get lead info
            $lead = CrmLead::where('uuid', $request->uuid)->first();
            $leadCompanyInfo = CrmCompanyInfo::where('lead_id', $lead->id)->first();
            //check first if there is existing record on the proposal table
            $proposal = Proposal::where('lead_id', $lead->id)->where('status', 1)->first();


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
                'rate_type' =>  $request->rate_type,
                'proposed_rate' => str_replace(',', '', $request->proposed_rate),
                'route_from' => $request->route_from,
                'route_to' => $request->route_to,
                'min_van_qty'   => $request->min_van_qty,
                'container_type' => $request->container_type,
                'container_class' => $request->container_class,
                'container_size' => $request->container_size,
                'origin_service_type' => $request->service_origin,
                'destination_service_type' => $request->service_destination,
            ];
            ProposalInfo::create($proposalRatePayload);
            $updateLeadCompanyPayload = [
                'company_name' =>  $request->company_name,
                'company_address' =>  $request->company_address,
                'authorized_signatory_name' =>  $request->authorized_signatory_name,
                'authorized_signatory_position' =>  $request->authorized_signatory_position,
            ];
            //update lead status to negotiation
            $lead->update([
                'status' => 3,
            ]);
            $leadCompanyInfo->update($updateLeadCompanyPayload);




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
                'message' => $ex->getMessage(),
            ]);
        }
    }
    public function createPdf($id)
    {
        $proposal = Proposal::with([
            'rates' => [
                'routeFrom',
                'routeTo',
                'vanClass',
                'vanType',
                'vanSize',
                'serviceOrigin',
                'serviceDestination',
            ],
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

    public function getByCode($proposalCode)
    {
        // dd($proposalCode);
        try {
            DB::beginTransaction();
            $proposal = Proposal::with([
                'lead.company',
                'creator',
                'status',
                'rates' => [
                    'routeFrom',
                    'routeTo',
                    'vanClass',
                    'vanType',
                    'vanSize',
                    'serviceOrigin',
                    'serviceDestination',
                ],
            ])->where('code', $proposalCode)->firstOrFail();
            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $proposal,
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }
    }
    public function processApprovals(Request $request)
    {
        $auth = Auth::user();
        try {
            DB::beginTransaction();
            $proposal = Proposal::with('status')
                ->where('code', $request->proposalCode)
                ->firstOrFail();

            $proposal->update([
                'status' => $request->status,
            ]);
            $status = ProposalStatus::where('id', $proposal->status)->firstOrFail();
            DB::commit();
            $this->activityService->create(
                $proposal->lead_id,
                "Proposal Status Change",
                "Proposal with code: " . $proposal->code . " has been " . $status['status'] . " by:"  . $auth->name
            );
            return response()->json([
                'success' => true,
                'message' => 'success',
                'status' => $proposal,
            ]);
        } catch (\Exception $ex) {

            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }
    }
}
