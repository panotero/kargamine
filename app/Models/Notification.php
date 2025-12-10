<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'notifications';

    protected $primaryKey = 'id';

    protected $fillable = [
        'office_origin',
        'destination_office',
        'routed_to',
        'document_id',
        'user_id',

        'from_user_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'routed_to' => 'integer',
        'document_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function approvals()
    {

        return $this->belongsTo(Approvals::class, 'document_id', 'document_id');
    }
}
