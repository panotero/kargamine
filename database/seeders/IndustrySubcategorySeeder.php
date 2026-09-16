<?php

namespace Database\Seeders;

use App\Models\ListOfValue;
use App\Models\Option;
use Illuminate\Database\Seeder;

class IndustrySubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $industryOption = Option::where('option_name', 'Industry')->first();
        if (! $industryOption) {
            return;
        }

        $option = Option::firstOrCreate(['option_name' => 'Industry Sub-Category']);

        $map = [
            'Food & Beverage' => ['Beverages', 'Packaged Foods', 'Fresh & Perishable', 'Dairy Products'],
            'Agriculture & Fisheries' => ['Crop Production', 'Livestock & Poultry', 'Fisheries & Aquaculture'],
            'Retail & E-commerce' => ['Online Marketplace', 'Brick & Mortar Retail', 'Department Stores'],
            'Wholesale & Distribution' => ['General Trading', 'Bulk Distribution', 'Import/Export Trading'],
            'Manufacturing' => ['Light Manufacturing', 'Heavy Manufacturing', 'Contract Manufacturing'],
            'Construction & Building Materials' => ['Residential Construction', 'Commercial Construction', 'Building Supplies'],
            'Automotive & Transportation' => ['Vehicle Parts & Accessories', 'Automotive Dealership', 'Fleet Services'],
            'Consumer Goods / FMCG' => ['Personal Care', 'Household Products', 'Packaged Consumer Goods'],
            'Electronics & Appliances' => ['Consumer Electronics', 'Home Appliances', 'Electronic Components'],
            'Pharmaceuticals & Healthcare' => ['Pharmaceutical Manufacturing', 'Medical Devices', 'Hospitals & Clinics'],
            'Industrial & Machinery' => ['Heavy Equipment', 'Industrial Tools', 'Machine Parts'],
            'Chemicals & Industrial Supplies' => ['Industrial Chemicals', 'Agrochemicals', 'Safety & Supplies'],
            'Textiles, Apparel & Footwear' => ['Apparel Manufacturing', 'Footwear', 'Textile Materials'],
            'Furniture & Home Furnishings' => ['Furniture Manufacturing', 'Home Decor', 'Office Furniture'],
            'Mining, Metals & Aggregates' => ['Metal Ores', 'Quarrying & Aggregates', 'Metal Fabrication'],
            'Energy & Utilities' => ['Power Generation', 'Oil & Gas', 'Renewable Energy'],
            'Hospitality & Food Service' => ['Hotels & Resorts', 'Restaurants & Catering', 'Food Service Supply'],
            'Government & Institutional' => ['National Government', 'Local Government Units', 'NGOs & Institutions'],
            'Logistics & Transportation' => ['Freight Forwarding', 'Warehousing', 'Courier & Last-Mile'],
            'Other Services / Other' => ['Professional Services', 'Other'],
        ];

        foreach ($map as $industryName => $subcategories) {
            $parent = ListOfValue::where('lov_optionId', $industryOption->option_id)
                ->where('lov_name', $industryName)
                ->first();

            if (! $parent) {
                continue;
            }

            foreach ($subcategories as $name) {
                ListOfValue::firstOrCreate([
                    'lov_optionId' => $option->option_id,
                    'parent_lov_id' => $parent->lov_id,
                    'lov_name' => $name,
                ], ['lov_code' => strtoupper(substr($name, 0, 3))]);
            }
        }
    }
}
