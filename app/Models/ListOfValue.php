<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListOfValue extends Model
{
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'lov_is_individual' => 'boolean',
    ];

    use HasFactory;

    protected $table = 'list_of_values_table';
    protected $primaryKey = 'lov_id';

    protected $fillable = [
        'lov_code',
        'lov_optionId',
        'lov_name',
        'lov_description',
        'lov_is_individual',
        'parent_lov_id',
    ];

    // Relationship: LOV belongs to Option
    public function option()
    {
        return $this->belongsTo(Option::class, 'lov_optionId', 'option_id');
    }

    // Self-reference for cascading LOV groups (e.g. Industry Sub-Category -> Industry).
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_lov_id', 'lov_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_lov_id', 'lov_id');
    }
}
