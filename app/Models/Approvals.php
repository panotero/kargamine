<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approvals extends Model
{
    use HasFactory;
    protected $table = 'approval_table';

    protected $fillable = [
        'document_id',
        'user_id',
        'approval_type',

        'remarks',
        'status',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'document_id')
            ->with([
                'files',
                'modifications',
                'activities'
            ]);
    }
}
