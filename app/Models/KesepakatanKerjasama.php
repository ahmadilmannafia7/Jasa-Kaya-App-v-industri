<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KesepakatanKerjasama extends Model
{
    protected $primaryKey = 'agreement_id';
    protected $table = 'kesepakatan_kerjasama'; 

    protected $fillable = [
        'meeting_id', 
        'agreed_price_per_m3', 
        'payment_mechanism', 
        'delivery_schedule',
        'other_notes',
        'durasi_kontrak_bulan',
        'final_document_path', 
        'signed_by_kthr_at', 
        'signed_by_pbphh_at'
    ];

    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class, 'meeting_id');
    }
}
