<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientProposal extends Model
{
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
