<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContainerClass extends Model
{
    protected $table = 'container_class';
    protected $fillable = ['container_id', 'class'];
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }
}
