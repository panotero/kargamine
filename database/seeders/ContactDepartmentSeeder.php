<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ContactDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $option = \App\Models\Option::firstOrCreate(['option_name' => 'Contact Department']);
        foreach ([
            'Sales',
            'Operations',
            'Billing - Collection',
            'Billing - Finance',
            'Billing - Liquidation',
            'Billing - Submission',
            'Procurement',
        ] as $name) {
            \App\Models\ListOfValue::firstOrCreate([
                'lov_optionId' => $option->option_id,
                'lov_name' => $name,
            ], ['lov_code' => strtoupper(substr($name, 0, 3))]);
        }
    }
}
