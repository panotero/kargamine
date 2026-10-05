<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 1 of the CrmLead -> Prospect rename: rename the parent table itself
 * first (before any FK column renames on child tables), so MySQL's
 * automatically-updated FK constraints keep pointing at the right table
 * name for every subsequent rename in this batch.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('crm_leads', 'prospects');
    }

    public function down(): void
    {
        Schema::rename('prospects', 'crm_leads');
    }
};
