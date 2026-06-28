<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tptkb extends Model
{
    protected $primaryKey = 'tptkb_id';

    protected $fillable = [
        'registered_by_user_id',
        'region_id', 
        'tptkb_name',
        'ketua_ktp_path',
        'sk_tptkb_path',
        'nama_pendamping_tptkb',
        'phone',
        'alamat_tptkb',
        'coordinate_lat',
        'coordinate_lng',
        'shp_file_path',
        'is_siap_mitra',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by_user_id', 'user_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    public function materialSupplies(): HasMany
    {
        return $this->hasMany(\App\Models\TptkbMaterialSupply::class, 'tptkb_id', 'tptkb_id');
    }

    // ✅ Perbaikan: Tambahkan relasi permintaanKerjasama
    public function permintaanKerjasama(): HasMany
    {
        return $this->hasMany(\App\Models\PermintaanKerjasama::class, 'tptkb_id', 'tptkb_id');
    }
}
