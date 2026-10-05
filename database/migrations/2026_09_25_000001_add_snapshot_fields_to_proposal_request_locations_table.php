<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Origin & Destination entries stop being a pure prospect_location_id FK
 * link and become their own address snapshot (same field set as
 * prospect_locations) - the "Add" form is now editable, so whatever the
 * user has in the form at Save time must actually persist, not just which
 * existing address was clicked. prospect_location_id is kept, now nullable,
 * as an optional "seeded from" reference only - no longer the sole source
 * of truth for what this entry's address is.
 *
 * The unique(proposal_request_id, prospect_location_id) pair is dropped:
 * since entries are now independent snapshots, the same seed address can
 * legitimately back more than one entry (suggestion chips stay clickable
 * after use - see logic_prospect_request_proposal.js/logic_prospect_add_modals.js).
 *
 * Two MySQL/InnoDB gotchas here, both existence-guarded so this stays
 * safely re-runnable:
 *  - pr_location_unique's LEADING column is proposal_request_id, and it
 *    turns out to be the only index backing THAT column's own foreign key
 *    (proposal_request_locations_proposal_request_id_foreign) - MySQL
 *    refuses to drop it until a replacement plain index exists first. Same
 *    fix as 2026_09_19_000002_allow_multiple_proposal_requests_per_prospect.php.
 *  - Changing prospect_location_id's nullability via doctrine/dbal
 *    (->change()) drops ITS OWN foreign key constraint as a side effect
 *    (the single-column index survives, the constraint doesn't) - so it's
 *    re-added explicitly afterward, this time nullOnDelete() instead of
 *    cascadeOnDelete() since deleting the seed address should no longer
 *    delete this now-independent snapshot row, just null the reference.
 *
 * SQLite can't drop/inspect named foreign keys the same way MySQL does, so
 * the FK-specific steps are skipped there (same convention as the rest of
 * this migrations directory) - SQLite's own test DB doesn't exercise this
 * constraint either way.
 *
 * Known gap, accepted: existing rows (created under the old FK-only model)
 * will have prospect_location_id set but every new snapshot column NULL
 * after this runs. This is pre-production/dev data, so no backfill.
 */
return new class extends Migration
{
    private const SNAPSHOT_COLUMNS = [
        'address_type',
        'address_no',
        'address_building',
        'address_street',
        'address_barangay',
        'address_town_city',
        'address_province',
        'address_country',
        'address_postal_code',
    ];

    public function up(): void
    {
        Schema::table('proposal_request_locations', function (Blueprint $table) {
            if (! Schema::hasColumn('proposal_request_locations', 'address_type')) {
                $table->string('address_type')->nullable()->after('prospect_location_id');
            }
            if (! Schema::hasColumn('proposal_request_locations', 'address_no')) {
                $table->string('address_no')->nullable()->after('address_type');
            }
            if (! Schema::hasColumn('proposal_request_locations', 'address_building')) {
                $table->string('address_building')->nullable()->after('address_no');
            }
            if (! Schema::hasColumn('proposal_request_locations', 'address_street')) {
                $table->string('address_street')->nullable()->after('address_building');
            }
            if (! Schema::hasColumn('proposal_request_locations', 'address_barangay')) {
                $table->string('address_barangay')->nullable()->after('address_street');
            }
            if (! Schema::hasColumn('proposal_request_locations', 'address_town_city')) {
                $table->string('address_town_city')->nullable()->after('address_barangay');
            }
            if (! Schema::hasColumn('proposal_request_locations', 'address_province')) {
                $table->string('address_province')->nullable()->after('address_town_city');
            }
            if (! Schema::hasColumn('proposal_request_locations', 'address_country')) {
                $table->string('address_country')->default('Philippines')->after('address_province');
            }
            if (! Schema::hasColumn('proposal_request_locations', 'address_postal_code')) {
                $table->string('address_postal_code', 20)->nullable()->after('address_country');
            }

            $table->foreignId('prospect_location_id')->nullable()->change();
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($this->indexExists('proposal_request_locations', 'pr_location_unique')) {
            if (! $this->indexExists('proposal_request_locations', 'proposal_request_locations_proposal_request_id_index')) {
                Schema::table('proposal_request_locations', function (Blueprint $table) {
                    $table->index('proposal_request_id', 'proposal_request_locations_proposal_request_id_index');
                });
            }

            Schema::table('proposal_request_locations', function (Blueprint $table) {
                $table->dropUnique('pr_location_unique');
            });
        }

        if ($driver !== 'sqlite' && ! $this->hasForeignKey('proposal_request_locations', 'prospect_location_id')) {
            Schema::table('proposal_request_locations', function (Blueprint $table) {
                $table->foreign('prospect_location_id')->references('id')->on('prospect_locations')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'sqlite' && $this->hasForeignKey('proposal_request_locations', 'prospect_location_id')) {
            Schema::table('proposal_request_locations', function (Blueprint $table) {
                $table->dropForeign(['prospect_location_id']);
            });
        }

        if (! $this->indexExists('proposal_request_locations', 'pr_location_unique')) {
            Schema::table('proposal_request_locations', function (Blueprint $table) {
                $table->unique(['proposal_request_id', 'prospect_location_id'], 'pr_location_unique');
            });
        }

        if ($this->indexExists('proposal_request_locations', 'proposal_request_locations_proposal_request_id_index')) {
            Schema::table('proposal_request_locations', function (Blueprint $table) {
                $table->dropIndex('proposal_request_locations_proposal_request_id_index');
            });
        }

        Schema::table('proposal_request_locations', function (Blueprint $table) {
            $table->foreignId('prospect_location_id')->nullable(false)->change();
            $table->dropColumn(self::SNAPSHOT_COLUMNS);
        });

        if ($driver !== 'sqlite' && ! $this->hasForeignKey('proposal_request_locations', 'prospect_location_id')) {
            Schema::table('proposal_request_locations', function (Blueprint $table) {
                $table->foreign('prospect_location_id')->references('id')->on('prospect_locations')->cascadeOnDelete();
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        return array_key_exists($indexName, Schema::getIndexes($table))
            || collect(Schema::getIndexes($table))->pluck('name')->contains($indexName);
    }

    private function hasForeignKey(string $table, string $column): bool
    {
        return DB::table('information_schema.key_column_usage')
            ->whereRaw('table_schema = database()')
            ->where('table_name', $table)
            ->where('column_name', $column)
            ->whereNotNull('referenced_table_name')
            ->exists();
    }
};
