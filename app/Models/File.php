<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $primaryKey = 'file_id';

    protected $fillable = [
        'document_id',
        'file_path',
        'file_password',
        'uploading_office',
        'uploaded_by',
        'uploaded_at',
    ];

    public $timestamps = false;

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'document_id');
    }
}
