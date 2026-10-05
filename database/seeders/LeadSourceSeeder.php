<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LeadSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $option = \App\Models\Option::firstOrCreate(['option_name' => 'Lead Source']);

        $names = ['Cold Call', 'Referral', 'Social Media', 'Walk-In', 'Others'];

        foreach ($names as $name) {
            \App\Models\ListOfValue::firstOrCreate([
                'lov_optionId' => $option->option_id,
                'lov_name' => $name,
            ], ['lov_code' => strtoupper(substr($name, 0, 3))]);
        }

        // Retire any previously-seeded values that aren't part of the
        // current 5-option list (e.g. old 'Website', 'Walk-in', 'Other').
        \App\Models\ListOfValue::where('lov_optionId', $option->option_id)
            ->whereNotIn('lov_name', $names)
            ->delete();
    }
}
