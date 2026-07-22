<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppThemeSetting extends Model
{
    protected $fillable = [
        'main_color',
        'accent_color',
        'button_secondary_color',
        'button_danger_color',
        'dark_mode',
    ];

    public const DARK_MODES = ['light', 'dark', 'system'];

    public static function defaults(): array
    {
        return [
            'main_color' => 'blue',
            'accent_color' => 'orange',
            'button_secondary_color' => 'slate',
            'button_danger_color' => 'red',
            'dark_mode' => 'system',
        ];
    }

    /**
     * The theme is app-wide, not per-user - always the same singleton row.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate(['id' => 1], self::defaults());
    }
}
