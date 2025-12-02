<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $primaryKey = 'document_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'document_code',
        'document_control_number',
        'date_received',
        'particular',
        'office_origin',
        'destination_office',
        'user_id',
        'recipient_id',
        'document_form',
        'document_type',
        'date_of_document',
        'involved_office',
        'due_date',
        'signatory',
        'remarks',
        'receipt_confirmation',
        'receipt_confirmed_by',
        'date_forwarded',
    ];

    protected $casts = [
        'involved_office' => 'array',
    ];

    public function files()
    {
        return $this->hasMany(File::class, 'document_id', 'document_id');
    }

    public function modifications()
    {
        return $this->hasMany(Modification::class, 'document_id', 'document_id');
    }
    public function activities()
    {
        return $this->hasMany(Activity::class, 'document_control_number', 'document_control_number');
    }
}
