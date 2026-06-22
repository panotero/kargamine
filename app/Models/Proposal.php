<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Proposal extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'proposals';
    protected $fillable = [
        'uuid',
        'lead_id',
        'created_by',
        'status',
        'code',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function lead()
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
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
        return  $this->belongsTo(ProposalStatus::class, 'status');
    }
}
