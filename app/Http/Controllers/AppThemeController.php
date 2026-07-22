<?php

namespace App\Http\Controllers;

use App\Models\AppThemeSetting;
use App\Support\TailwindPalette;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppThemeController extends Controller
{
    public function show()
    {
        return response()->json([
            'success' => true,
            'data' => AppThemeSetting::current(),
            'hues' => TailwindPalette::hueNames(),
            'dark_modes' => AppThemeSetting::DARK_MODES,
        ]);
    }

    public function update(Request $request)
    {
        $hueRule = 'required|string|in:'.implode(',', TailwindPalette::hueNames());

        $validator = Validator::make($request->all(), [
            'main_color' => $hueRule,
            'accent_color' => $hueRule,
            'button_secondary_color' => $hueRule,
            'button_danger_color' => $hueRule,
            'dark_mode' => 'required|string|in:'.implode(',', AppThemeSetting::DARK_MODES),
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'invalid_fields' => $validator->errors()], 422);
        }

        $theme = AppThemeSetting::current();
        $theme->update($validator->validated());

        return response()->json(['success' => true, 'data' => $theme]);
    }
}
