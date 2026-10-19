<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    use HasFactory;

    protected $table = 'options_table';

    protected $primaryKey = 'option_id';

    protected $fillable = [
        'option_name',
        'option_description',
    ];

    // Relationship: Option has many LOVs
    public function values()
    {
        return $this->hasMany(ListOfValue::class, 'lov_optionId', 'option_id');
    }
}
