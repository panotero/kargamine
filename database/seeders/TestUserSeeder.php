<?php

namespace Database\Seeders;

use App\Models\SettingRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Seeds 9 standalone test accounts (3 CSR, 3 Relationship Manager,
     * 3 Management) onto the team hierarchy created by TeamSeeder, to
     * exercise ClientProposal::canBeApprovedBy / TeamService's pyramid
     * logic. Deliberately kept out of UserSeeder - these are QA fixtures,
     * not real provisioning.
     *
     * Shape chosen (simple but a real, usable pyramid):
     *
     *   Management Team            <- mgmt1 is_team_leader=true: leader
     *     |                           of the whole subtree below, so can
     *     |                           approve for every other user here.
     *     |-- mgmt2, mgmt3           plain members, no subtree reach -
     *     |                           these are the "non-leader management"
     *     |                           control accounts that should NOT be
     *     |                           able to approve anyone's proposal.
     *     |
     *     |-- Sales Team A
     *     |     |-- rm1 (leader)      leader of Team A: can approve for
     *     |     |-- rm2 (member)      rm2, csr1, csr2, but not Team B.
     *     |     |-- csr1 (member)
     *     |     |-- csr2 (member)
     *     |
     *     |-- Sales Team B
     *           |-- rm3 (leader)      leader of Team B: can approve only
     *           |-- csr3 (member)     for csr3.
     *
     * Role lookups: the CSR role doesn't exist in the live seeders yet, so
     * it's created here. Relationship Manager and admin ("Management", per
     * RoleHelper) are looked up by role_name via firstOrCreate rather than
     * hardcoded ids - this is a defensive no-op against the existing rows
     * seeded by Setting_roleSeeder when run order is normal, but also makes
     * this seeder safe to run standalone/before Setting_roleSeeder (see
     * DatabaseSeeder, which now calls this seeder before Setting_roleSeeder).
     * Never assigns superadmin to a test account.
     */
    public function run(): void
    {
        $csrRole = SettingRole::firstOrCreate(
            ['role_name' => 'Customer Service Representative'],
            ['is_system' => false]
        );

        $rmRole = SettingRole::firstOrCreate(
            ['role_name' => 'Relationship Manager'],
            ['is_system' => false]
        );

        $adminRole = SettingRole::firstOrCreate(
            ['role_name' => 'admin'],
            ['is_system' => true]
        );

        $managementTeam = Team::where('name', 'Management Team')->first();
        $salesTeamA = Team::where('name', 'Sales Team A')->first();
        $salesTeamB = Team::where('name', 'Sales Team B')->first();

        // Management (admin role, not superadmin)
        User::firstOrCreate(
            ['email' => 'mgmt1@test.kargamine'],
            [
                'name' => 'Test Management One',
                'password' => Hash::make('Testing123'),
                'role_id' => $adminRole->id,
                'team_id' => $managementTeam?->id,
                'is_team_leader' => true,
                'must_change_password' => false,
            ]
        );

        User::firstOrCreate(
            ['email' => 'mgmt2@test.kargamine'],
            [
                'name' => 'Test Management Two',
                'password' => Hash::make('Testing123'),
                'role_id' => $adminRole->id,
                'team_id' => $managementTeam?->id,
                'is_team_leader' => false,
                'must_change_password' => false,
            ]
        );

        User::firstOrCreate(
            ['email' => 'mgmt3@test.kargamine'],
            [
                'name' => 'Test Management Three',
                'password' => Hash::make('Testing123'),
                'role_id' => $adminRole->id,
                'team_id' => $managementTeam?->id,
                'is_team_leader' => false,
                'must_change_password' => false,
            ]
        );

        // Relationship Managers
        User::firstOrCreate(
            ['email' => 'rm1@test.kargamine'],
            [
                'name' => 'Test Relationship Manager One',
                'password' => Hash::make('Testing123'),
                'role_id' => $rmRole->id,
                'team_id' => $salesTeamA?->id,
                'is_team_leader' => true,
                'must_change_password' => false,
            ]
        );

        User::firstOrCreate(
            ['email' => 'rm2@test.kargamine'],
            [
                'name' => 'Test Relationship Manager Two',
                'password' => Hash::make('Testing123'),
                'role_id' => $rmRole->id,
                'team_id' => $salesTeamA?->id,
                'is_team_leader' => false,
                'must_change_password' => false,
            ]
        );

        User::firstOrCreate(
            ['email' => 'rm3@test.kargamine'],
            [
                'name' => 'Test Relationship Manager Three',
                'password' => Hash::make('Testing123'),
                'role_id' => $rmRole->id,
                'team_id' => $salesTeamB?->id,
                'is_team_leader' => true,
                'must_change_password' => false,
            ]
        );

        // CSRs
        User::firstOrCreate(
            ['email' => 'csr1@test.kargamine'],
            [
                'name' => 'Test CSR One',
                'password' => Hash::make('Testing123'),
                'role_id' => $csrRole->id,
                'team_id' => $salesTeamA?->id,
                'is_team_leader' => false,
                'must_change_password' => false,
            ]
        );

        User::firstOrCreate(
            ['email' => 'csr2@test.kargamine'],
            [
                'name' => 'Test CSR Two',
                'password' => Hash::make('Testing123'),
                'role_id' => $csrRole->id,
                'team_id' => $salesTeamA?->id,
                'is_team_leader' => false,
                'must_change_password' => false,
            ]
        );

        User::firstOrCreate(
            ['email' => 'csr3@test.kargamine'],
            [
                'name' => 'Test CSR Three',
                'password' => Hash::make('Testing123'),
                'role_id' => $csrRole->id,
                'team_id' => $salesTeamB?->id,
                'is_team_leader' => false,
                'must_change_password' => false,
            ]
        );
    }
}
