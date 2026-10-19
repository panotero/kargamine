<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'proposals';

    protected $fillable = [
        'uuid',
        'prospect_id',
        'created_by',
        'status',
        'code',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rates()
    {
        return $this->hasMany(ProposalInfo::class, 'proposal_id');
    }

    public function status()
    {
        return $this->belongsTo(ProposalStatus::class, 'status');
    }
}
