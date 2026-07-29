<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Splits the single contact_name text field into title/first_name/
     * middle_name/last_name/gender. Existing rows are backfilled with a
     * best-effort whitespace split (first word -> first_name, last word
     * -> last_name, anything between -> middle_name) so nothing is left
     * blank; title/gender start empty for pre-existing leads since there's
     * nothing to derive them from.
     */
    public function up(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->string('title')->nullable()->after('client_type');
            $table->string('first_name')->nullable()->after('title');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_name');
            $table->string('gender')->nullable()->after('last_name');
        });

        DB::table('crm_leads')->whereNotNull('contact_name')->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $parts = preg_split('/\s+/', trim($row->contact_name), -1, PREG_SPLIT_NO_EMPTY);

                    if (empty($parts)) {
                        continue;
                    }

                    $first = array_shift($parts);
                    $last = count($parts) ? array_pop($parts) : null;
                    $middle = $parts ? implode(' ', $parts) : null;

                    DB::table('crm_leads')->where('id', $row->id)->update([
                        'first_name' => $first,
                        'middle_name' => $middle,
                        'last_name' => $last,
                    ]);
                }
            });

        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropColumn('contact_name');
        });
    }

    public function down(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->string('contact_name')->nullable()->after('client_type');
        });

        DB::table('crm_leads')->orderBy('id')->chunkById(200, function ($rows) {
            foreach ($rows as $row) {
                $name = trim(implode(' ', array_filter([$row->first_name, $row->middle_name, $row->last_name])));
                DB::table('crm_leads')->where('id', $row->id)->update(['contact_name' => $name ?: null]);
            }
        });

        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropColumn(['title', 'first_name', 'middle_name', 'last_name', 'gender']);
        });
    }
};
