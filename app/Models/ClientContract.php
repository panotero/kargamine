<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientContract extends Model
{
    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_EXPIRED = 3;
    public const STATUS_TERMINATED = 4;

    protected $fillable = [
        'uuid',
        'code',
        'client_id',
        'client_proposal_id',
        'signed_date',
        'valid_from',
        'valid_to',
        'status',
        'signed_document_path',
        'created_by',
    ];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }
    public function proposal()
    {
        return $this->belongsTo(ClientProposal::class, 'client_proposal_id');
    }
    public function rates()
    {
        return $this->hasMany(ClientContractRate::class, 'contract_id');
    }
}
