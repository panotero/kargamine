<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppInformationSetting extends Model
{
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    protected $fillable = [
        'app_name',
        'logo_path',
        'icon_path',
    ];

    public static function defaults(): array
    {
        return [
            'app_name' => 'Management System',
            'logo_path' => null,
            'icon_path' => null,
        ];
    }

    /**
     * App-wide, not per-user - always the same singleton row.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate(['id' => 1], self::defaults());
    }
}
