<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientProposal extends Model
{
    public const STATUS_PENDING = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_DISAPPROVED = 3;
    public const STATUS_ACCEPTED = 4;
    public const STATUS_REJECTED = 5;

    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_DISAPPROVED => 'Disapproved',
        self::STATUS_ACCEPTED => 'Accepted',
        self::STATUS_REJECTED => 'Rejected',
    ];

    protected $fillable = ['uuid', 'code', 'client_id', 'status', 'created_by'];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }
    public function rates()
    {
        return $this->hasMany(ClientProposalRate::class, 'proposal_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
