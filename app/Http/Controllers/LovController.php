<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Route;
use App\Models\VanType;
use App\Models\VanSize;
use App\Models\VanClass;


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
}
