<?php

namespace App\Http\Controllers;

use App\Models\NavIcon;

class NavIconController extends Controller
{
    /**
     * The full icon library - small enough (dozens, not thousands) to
     * fetch once and cache client-side rather than paginate.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => NavIcon::orderBy('label')->get(['id', 'key', 'label', 'svg']),
        ]);
    }
}
