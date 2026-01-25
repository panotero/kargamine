<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $table = 'office_table';
    protected $primaryKey = 'office_id';
    public $timestamps = false;

    protected $fillable = [
        'office_name',
        'office_code',
        'parent_office_id',
        'office_level',
        'created_at',
    ];

    public function parentOfficeInfo()
    {
        return $this->belongsTo(Office::class, 'parent_office_id', 'office_id');
    }

    public function children()
    {
        return $this->hasMany(Office::class, 'parent_office_id', 'office_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
