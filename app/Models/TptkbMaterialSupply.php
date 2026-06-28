<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TptkbMaterialSupply extends Model
{
    protected $primaryKey = 'supply_id';

    protected $fillable = [
        'tptkb_id',
        'supply_kayu',
        'tipe',
        'jumlah',
        'gambar_supply_path',
        'spesifikasi_tambahan'
    ];

    public function tptkb(): BelongsTo
    {
        return $this->belongsTo(Tptkb::class, 'tptkb_id');
    }
}
