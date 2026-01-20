<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $casts = [
        'involved_office' => 'array',
    ];
    protected $fillable = [
        'action',
        'office_id',
        'document_id',
        'final_approval',
        'document_control_number',
        'to_external',
        'involved_office',
        'from_user_id',
        'user_id',
        'routed_to',
        'final_remarks',
    ];
    protected $table = 'activities';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    public function routedUser()
    {
        return $this->belongsTo(User::class, 'routed_to', 'id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'document_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }
    public function recipient()
    {

        return $this->belongsTo(User::class, 'routed_to', 'id');
    }
}
