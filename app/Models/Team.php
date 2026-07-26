<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'parent_id' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(Team::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Team::class, 'parent_id');
    }

    public function members()
    {
        return $this->hasMany(User::class, 'team_id');
    }

    public function leaders()
    {
        return $this->hasMany(User::class, 'team_id')->where('is_team_leader', true);
    }
}
