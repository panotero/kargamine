<?php

namespace App\Services;

use App\Models\ProspectActivity;

class ActivityService
{
    /**
     * Create a CRM activity.
     *
     * @param int $leadId
     * @param string $type
     * @param string $description
     * @param int|null $createdBy
     * @return \App\Models\ProspectActivity
     */
    public function create(
        int $leadId,
        string $type,
        string $description,
        ?int $createdBy = null
    ) {
        ProspectActivity::create([
            'prospect_id' => $leadId,
            'type'        => $type,
            'description' => $description,
            'created_by'  => $createdBy ?? auth()->id(),
        ]);
    }
}
