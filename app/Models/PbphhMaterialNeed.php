<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PbphhMaterialNeed extends Model
{
    protected $primaryKey = 'need_id';

    protected $fillable = [
        'pbphh_id',
        'jenis_kayu',
        'tipe',
        'kebutuhan_bulanan_m3',
        'spesifikasi_tambahan'
    ];

    public function pbphhProfile(): BelongsTo
    {
        return $this->belongsTo(PbphhProfile::class, 'pbphh_id');
    }

    // ✅ Accessor untuk format volume yang mudah dibaca
    public function getFormattedVolumeAttribute()
    {
        return number_format($this->kebutuhan_bulanan_m3, 2) . ' m³';
    }

    // ✅ Alias untuk konsistensi dengan accessor di PbphhProfile
    public function getVolumeM3PerBulanAttribute()
    {
        return $this->kebutuhan_bulanan_m3;
    }
}
