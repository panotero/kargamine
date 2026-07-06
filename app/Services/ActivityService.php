<?php

namespace App\Services;

use App\Models\CrmActivity;

class ActivityService
{
    /**
     * Create a CRM activity.
     *
     * @param int $leadId
     * @param string $type
     * @param string $description
     * @param int|null $createdBy
     * @return \App\Models\CrmActivity
     */
    public function create(
        int $leadId,
        string $type,
        string $description,
        ?int $createdBy = null
    ) {
        CrmActivity::create([
            'lead_id'     => $leadId,
            'type'        => $type,
            'description' => $description,
            'created_by'  => $createdBy ?? auth()->id(),
        ]);
    }
}
