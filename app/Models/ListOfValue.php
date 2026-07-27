<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListOfValue extends Model
{
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    use HasFactory;

    protected $table = 'list_of_values_table';
    protected $primaryKey = 'lov_id';

    protected $fillable = [
        'lov_code',
        'lov_optionId',
        'lov_name',
        'lov_description',
    ];

    // Relationship: LOV belongs to Option
    public function option()
    {
        return $this->belongsTo(Option::class, 'lov_optionId', 'option_id');
    }
}
