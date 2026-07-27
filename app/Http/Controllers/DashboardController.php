<?php

namespace App\Http\Controllers;

use App\Models\ClientContract;
use App\Models\ClientMaster;
use App\Models\ClientProposal;
use App\Models\ContainerAsset;
use App\Models\CrmLead;
use App\Services\TeamService;
use App\Support\RoleHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Everything the dashboard shows, scoped the same way as the CRM lead
     * list and Proposals page: a regular member sees only their own book of
     * business, a team leader sees their team's subtree, superadmin sees
     * everything. Container inventory is the one exception - it's shared
     * physical stock, not owned by any one user, so it's never scoped.
     */
    public function summary(Request $request)
    {
        $user = $request->user();
        $visibleUserIds = RoleHelper::hasAnyRole($user, ['superadmin'])
            ? null
            : TeamService::accessibleUserIds($user)->all();

        $leadsQuery = CrmLead::query()
            ->when($visibleUserIds !== null, fn($q) => $q->whereIn('assigned_to', $visibleUserIds));

        $proposalsQuery = ClientProposal::query()
            ->when($visibleUserIds !== null, function ($q) use ($visibleUserIds) {
                $q->where(function ($q) use ($visibleUserIds) {
                    $q->whereHas('lead', fn($q) => $q->whereIn('assigned_to', $visibleUserIds))
                        ->orWhereHas('client.lead', fn($q) => $q->whereIn('assigned_to', $visibleUserIds));
                });
            });

        $contractsQuery = ClientContract::query()->visibleTo($visibleUserIds);

        $clientsQuery = ClientMaster::query()->visibleTo($visibleUserIds);

        return response()->json([
            'success' => true,
            'data' => [
                'kpi' => [
                    'total_clients' => (clone $clientsQuery)->count(),
                    'open_leads' => (clone $leadsQuery)->whereHas(
                        'crmStatus',
                        fn($q) => $q->whereNotIn('status', ['WIN', 'LOST'])
                    )->count(),
                    'pending_proposals' => (clone $proposalsQuery)->where('status', ClientProposal::STATUS_PENDING)->count(),
                    'active_contracts' => (clone $contractsQuery)->where('status', ClientContract::STATUS_ACTIVE)->count(),
                ],
                'leads_by_status' => $this->leadsByStatus($leadsQuery),
                'proposals_by_status' => $this->proposalsByStatus($proposalsQuery),
                'contracts_by_status' => $this->contractsByStatus($contractsQuery),
                'contracts_expiring_soon' => (clone $contractsQuery)
                    ->where('status', ClientContract::STATUS_ACTIVE)
                    ->whereDate('valid_to', '>=', Carbon::today())
                    ->whereDate('valid_to', '<=', Carbon::today()->addMonth())
                    ->count(),
                'containers_by_status' => $this->containersByStatus(),
                'lead_trend' => $this->leadTrend($leadsQuery),
                'top_clients' => (clone $clientsQuery)
                    ->select(['id', 'uuid', 'company_name', 'customer_code'])
                    ->withCount(['contracts', 'proposals'])
                    ->orderByDesc('contracts_count')
                    ->orderByDesc('proposals_count')
                    ->limit(5)
                    ->get(),
            ],
        ]);
    }

    protected function leadsByStatus($leadsQuery): array
    {
        $labels = ['LEAD', 'QUALIFIED', 'OPPORTUNITY', 'NEGOTIATION', 'WIN', 'LOST'];

        $counts = (clone $leadsQuery)
            ->join('crm_status', 'crm_leads.status', '=', 'crm_status.id')
            ->select('crm_status.status as label', DB::raw('count(*) as total'))
            ->groupBy('crm_status.status')
            ->pluck('total', 'label');

        return collect($labels)->mapWithKeys(fn($label) => [$label => (int) ($counts[$label] ?? 0)])->all();
    }

    protected function proposalsByStatus($proposalsQuery): array
    {
        $counts = (clone $proposalsQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return collect(ClientProposal::STATUS_LABELS)
            ->mapWithKeys(fn($label, $status) => [$label => (int) ($counts[$status] ?? 0)])
            ->all();
    }

    protected function contractsByStatus($contractsQuery): array
    {
        $counts = (clone $contractsQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return collect(ClientContract::STATUS_LABELS)
            ->mapWithKeys(fn($label, $status) => [$label => (int) ($counts[$status] ?? 0)])
            ->all();
    }

    protected function containersByStatus(): array
    {
        $counts = ContainerAsset::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return collect(ContainerAsset::STATUS_LABELS)
            ->mapWithKeys(fn($label, $status) => [$label => (int) ($counts[$status] ?? 0)])
            ->all();
    }

    /**
     * Leads created per day over the last 14 days, zero-filled so the chart
     * doesn't have gaps on days nothing happened.
     */
    protected function leadTrend($leadsQuery): array
    {
        $start = Carbon::today()->subDays(13);

        $counts = (clone $leadsQuery)
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $days = [];
        for ($i = 0; $i < 14; $i++) {
            $date = $start->copy()->addDays($i)->toDateString();
            $days[] = ['date' => $date, 'count' => (int) ($counts[$date] ?? 0)];
        }

        return $days;
    }
}
