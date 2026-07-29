<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\SettingRole;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Seeds the permission keys that exist so far. This table is meant
     * to grow incrementally as each module adopts permission-gating -
     * it isn't meant to be fully populated up front.
     */
    public function run(): void
    {
        $permissions = [
            ['key' => 'roles.manage', 'label' => 'Manage roles & permissions', 'module' => 'Roles'],
            ['key' => 'booking.create', 'label' => 'Create a booking', 'module' => 'Booking'],
            ['key' => 'booking.confirm', 'label' => 'Confirm a booking', 'module' => 'Booking'],
            ['key' => 'booking.cancel', 'label' => 'Cancel a booking', 'module' => 'Booking'],
            ['key' => 'booking.advance-status', 'label' => 'Advance a booking\'s status', 'module' => 'Booking'],
            ['key' => 'booking.generate-dispatch-document', 'label' => 'Generate a booking line\'s ATW/CAN', 'module' => 'Booking'],
            ['key' => 'booking.assign-cv', 'label' => 'Assign ConVan/Proforma BL/Waybill/Seal to a container unit', 'module' => 'Booking'],
            ['key' => 'booking.gate-scan', 'label' => 'Scan a container in/out at the gate (Pier Check-In)', 'module' => 'Booking'],
            ['key' => 'booking.issue-eir', 'label' => 'Issue a container\'s EIR Out/In', 'module' => 'Booking'],
            ['key' => 'booking.assign-voyage', 'label' => 'Assign or shut out a container\'s vessel voyage', 'module' => 'Booking'],
            ['key' => 'booking.generate-loadlist', 'label' => 'Generate a vessel voyage\'s loadlist', 'module' => 'Booking'],
            ['key' => 'contract.create', 'label' => 'Create a contract from an accepted proposal', 'module' => 'Contract'],
            ['key' => 'contract.terminate', 'label' => 'Terminate a contract', 'module' => 'Contract'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['key' => $permission['key']],
                ['label' => $permission['label'], 'module' => $permission['module']]
            );
        }

        // superadmin gets everything seeded so far by default - assign
        // booking permissions to other roles (e.g. sales/ops) via the
        // Roles panel on the Users Management page as the role list settles.
        $superadmin = SettingRole::where('role_name', 'superadmin')->first();

        if ($superadmin) {
            $superadmin->permissions()->syncWithoutDetaching(
                Permission::whereIn('key', array_column($permissions, 'key'))->pluck('id')
            );
        }
    }
}
