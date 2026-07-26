<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'user_id',
        'from_user_id',
        'title',
        'message',
        'link_title',
        'link_url',
        'data',
        'is_read',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'user_id' => 'integer',
        'from_user_id' => 'integer',
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}
