<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $option = \App\Models\Option::firstOrCreate(['option_name' => 'Industry']);
        foreach ([
            'Food & Beverage',
            'Agriculture & Fisheries',
            'Retail & E-commerce',
            'Wholesale & Distribution',
            'Manufacturing',
            'Construction & Building Materials',
            'Automotive & Transportation',
            'Consumer Goods / FMCG',
            'Electronics & Appliances',
            'Pharmaceuticals & Healthcare',
            'Industrial & Machinery',
            'Chemicals & Industrial Supplies',
            'Textiles, Apparel & Footwear',
            'Furniture & Home Furnishings',
            'Mining, Metals & Aggregates',
            'Energy & Utilities',
            'Hospitality & Food Service',
            'Government & Institutional',
            'Logistics & Transportation',
            'Other Services / Other',
        ] as $name) {
            \App\Models\ListOfValue::firstOrCreate([
                'lov_optionId' => $option->option_id,
                'lov_name' => $name,
            ], ['lov_code' => strtoupper(substr($name, 0, 3))]);
        }
    }
}
