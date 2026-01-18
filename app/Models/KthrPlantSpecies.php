<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KthrPlantSpecies extends Model
{
    protected $primaryKey = 'plant_id';
    protected $fillable = [
        'kthr_id',
        'jenis_tanaman',
        'tipe',
        'jumlah_pohon',
        'tahun_tanam',
        'gambar_tegakan_path'
    ];

    public function kthr(): BelongsTo
    {
        return $this->belongsTo(Kthr::class);
    }
}
