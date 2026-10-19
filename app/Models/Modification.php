<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modification extends Model
{
    use HasFactory;

    protected $primaryKey = 'modification_id';

    protected $fillable = [
        'document_id',
        'modified_by',
        'modification_type',
        'old_value',
        'new_value',
        'modified_at',
    ];

    public $timestamps = false;

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'document_id');
    }
}
