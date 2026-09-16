<?php

namespace App\Http\Controllers;

use App\Models\ListOfValue;
use App\Models\Option;
use App\Models\Route;
use App\Models\Service;
use App\Models\VanClass;
use App\Models\VanSize;
use App\Models\VanType;
use Illuminate\Http\Request;

class LovController extends Controller
{
    //

    public function route()
    {
        $routes = Route::all();

        return $routes;
    }

    public function service()
    {
        $services = Service::all();

        return $services;
    }

    public function vantype()
    {
        $vansize = VanType::all();

        return $vansize;
    }

    public function vansize()
    {
        $vansize = VanSize::all();

        return $vansize;
    }

    public function vanclass()
    {
        $vansize = VanClass::all();

        return $vansize;
    }

    public function typeOfBusiness()
    {
        $option = Option::where('option_name', 'Type of Business')->first();

        return $option ? $option->values : collect();
    }

    public function addressType()
    {
        $option = Option::where('option_name', 'Address Type')->first();

        return $option ? $option->values : collect();
    }

    public function leadSource()
    {
        $option = Option::where('option_name', 'Lead Source')->first();

        return $option ? $option->values : collect();
    }

    public function cargoType()
    {
        $option = Option::where('option_name', 'Cargo Type')->first();

        return $option ? $option->values : collect();
    }

    public function industry()
    {
        $option = Option::where('option_name', 'Industry')->first();

        return $option ? $option->values : collect();
    }

    /**
     * Cascades off Industry via parent_lov_id - pass ?parent_lov_id={the
     * selected Industry row's lov_id} to get just that industry's
     * sub-categories, same shape as the location->port cascade.
     */
    public function industrySubcategory(Request $request)
    {
        $option = Option::where('option_name', 'Industry Sub-Category')->first();
        if (! $option) {
            return collect();
        }

        return ListOfValue::where('lov_optionId', $option->option_id)
            ->when($request->filled('parent_lov_id'), fn($q) => $q->where('parent_lov_id', $request->parent_lov_id))
            ->get();
    }

    public function organizationType()
    {
        $option = Option::where('option_name', 'Type of Organization')->first();

        return $option ? $option->values : collect();
    }

    public function clientCategory()
    {
        $option = Option::where('option_name', 'Client Category')->first();

        return $option ? $option->values : collect();
    }

    public function clientClassification()
    {
        $option = Option::where('option_name', 'Client Classification')->first();

        return $option ? $option->values : collect();
    }

    public function contactDepartment()
    {
        $option = Option::where('option_name', 'Contact Department')->first();

        return $option ? $option->values : collect();
    }

    public function unit()
    {
        $option = Option::where('option_name', 'Unit')->first();

        return $option ? $option->values : collect();
    }
}
