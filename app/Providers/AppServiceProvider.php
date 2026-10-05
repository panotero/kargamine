<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Short aliases for the Products tab's polymorphic Ancillary
        // Service parent - stored in ancillaryable_type instead of a raw
        // class name, and doubles as the API's accepted value set.
        //
        // enforceMorphMap() applies to every polymorphic relation app-wide,
        // not just ancillaryable - Notification::notifiable() (see
        // TeamNotifier::notify()) is also morphTo() and needs every model
        // ever passed as 'notifiable' registered here too, or
        // getMorphClass() throws "No morph map defined for model type".
        Relation::enforceMorphMap([
            'container' => \App\Models\ProposalRequestProductContainer::class,
            'rolling_cargo' => \App\Models\ProposalRequestProductRollingCargo::class,
            'loose_cargo' => \App\Models\ProposalRequestProductLooseCargo::class,
            'trucking' => \App\Models\ProposalRequestProductTrucking::class,
            'prospect' => \App\Models\Prospect::class,
            'proposal_request' => \App\Models\ProposalRequest::class,
            'client_proposal' => \App\Models\ClientProposal::class,
        ]);
    }
}
