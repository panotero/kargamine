<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Seeds a minimal, real team hierarchy for exercising TeamService's
     * pyramid logic (see App\Services\TeamService) in the proposal
     * approval / team-hierarchy flow:
     *
     *   Management Team (root)
     *     |-- Sales Team A
     *     |-- Sales Team B
     *
     * TestUserSeeder re-queries these by name rather than relying on ids,
     * so this just needs to run before TestUserSeeder.
     */
    public function run(): void
    {
        $managementTeam = Team::firstOrCreate(
            ['name' => 'Management Team'],
            [
                'description' => 'Top-level management team for test/QA accounts.',
                'parent_id' => null,
                'is_active' => true,
            ]
        );

        Team::firstOrCreate(
            ['name' => 'Sales Team A'],
            [
                'description' => 'Test/QA sales sub-team under Management Team.',
                'parent_id' => $managementTeam->id,
                'is_active' => true,
            ]
        );

        Team::firstOrCreate(
            ['name' => 'Sales Team B'],
            [
                'description' => 'Test/QA sales sub-team under Management Team.',
                'parent_id' => $managementTeam->id,
                'is_active' => true,
            ]
        );
    }
}
