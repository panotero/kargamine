<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $option = \App\Models\Option::firstOrCreate(['option_name' => 'Type of Business']);

        // Sample only - the final individual-only list hasn't been defined
        // yet. These flag lov_is_individual = true, which is what now drives
        // the Corporate/Individual distinction that used to be a separate
        // manual toggle on the Prospect Identity tab (see
        // Prospect::stageCompletionFlags() and ProspectController::saveStage1()).
        foreach ([
            'Sole Proprietorship',
            'Self-Employed / Professional',
            'Freelancer / Mixed Income Earner',
        ] as $name) {
            \App\Models\ListOfValue::firstOrCreate([
                'lov_optionId' => $option->option_id,
                'lov_name' => $name,
            ], ['lov_code' => strtoupper(substr($name, 0, 3)), 'lov_is_individual' => true]);
        }

        foreach ([
            'Accommodation and Food Service Activities',
            'Activities of Extraterritorial Organizations and Bodies',
            'Activities of Households as Employers; Undifferentiated Goods- and Services-Producing Activities of Households for own use',
            'Administrative and Support Service Activities',
            'Agriculture, Forestry and Fishing',
            'Arts, Sports and Recreation',
            'Construction',
            'Education',
            'Electricity, Gas, Steam and Air Conditioning Supply',
            'Financial and Insurance Activities',
            'Human Health and Social Work Activities',
            'Manufacturing',
            'Mining and Quarrying',
            'Other Service Activities',
            'Professional, Scientific, and Technical Activities',
            'Public Administration and Defense; Compulsory Social Security',
            'Publishing, Broadcasting, and Content Production and Distribution Activities',
            'Real Estate Activities',
            'Telecommunications, Computer Programming, Consultancy, Computing Infrastructure, and Other Information Service Activities',
            'Transportation and Storage',
            'Water Supply; Sewerage, Waste Management and Remediation',
            'Wholesale and Retail Trade',
        ] as $name) {
            \App\Models\ListOfValue::firstOrCreate([
                'lov_optionId' => $option->option_id,
                'lov_name' => $name,
            ], ['lov_code' => strtoupper(substr($name, 0, 3))]);
        }
    }
}
