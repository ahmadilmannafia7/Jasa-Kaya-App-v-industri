<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kthr extends Model
{
    protected $primaryKey = 'kthr_id';

    protected $fillable = [
        'registered_by_user_id',
        'region_id', 
        'kthr_name',
        'ketua_ktp_path',
        'sk_register_path',
        'nama_pendamping',
        'phone',
        'alamat_sekretariat',
        'coordinate_lat',
        'coordinate_lng',
        'luas_areal_ha',
        'jumlah_anggota',
        'jumlah_pertemuan_tahunan',
        'shp_file_path',
        'is_siap_mitra',
        'is_siap_tebang'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by_user_id', 'user_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    public function plantSpecies(): HasMany
    {
        return $this->hasMany(KthrPlantSpecies::class, 'kthr_id', 'kthr_id');
    }

    // ✅ Perbaikan: Tambahkan relasi permintaanKerjasama
    public function permintaanKerjasama(): HasMany
    {
        return $this->hasMany(\App\Models\PermintaanKerjasama::class, 'kthr_id', 'kthr_id');
    }
}
