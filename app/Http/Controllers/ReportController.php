<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ReportController extends Controller
{
    //
    public function users()
    {
        return response()->json(User::with('office', 'userConfig')->get());
    }
}
