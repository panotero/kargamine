<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Route;
use App\Models\VanType;
use App\Models\VanSize;
use App\Models\VanClass;
use App\Models\Option;


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
}
