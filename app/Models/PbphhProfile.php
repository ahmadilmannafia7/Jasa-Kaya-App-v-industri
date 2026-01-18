<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PbphhProfile extends Model
{
    protected $primaryKey = 'pbphh_id';

    protected $fillable = [
        'user_id',
        'company_name',
        'nib_path',
        'sk_pbphh_path',
        'penanggung_jawab',
        'phone',
        'alamat_perusahaan',
        'coordinate_lat',
        'coordinate_lng',
        'kapasitas_izin_produksi_m3',
        'rencana_produksi_tahunan_m3',
        'jenis_produk_utama',
        'tahun_berdiri',
        'jumlah_karyawan',
        'website',
        'deskripsi_perusahaan'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function materialNeeds(): HasMany
    {
        return $this->hasMany(PbphhMaterialNeed::class, 'pbphh_id', 'pbphh_id');
    }

    // ✅ Perbaikan: Tambahkan relasi permintaanKerjasama
    public function permintaanKerjasama(): HasMany
    {
        return $this->hasMany(\App\Models\PermintaanKerjasama::class, 'pbphh_id', 'pbphh_id');
    }

    /**
     * Relasi ke partnership yang diajukan oleh PBPHH ini
     */
    public function sentPartnerships(): HasMany
    {
        return $this->hasMany(\App\Models\PbphhPartnership::class, 'requester_pbphh_id', 'pbphh_id');
    }

    /**
     * Relasi ke partnership yang diterima oleh PBPHH ini
     */
    public function receivedPartnerships(): HasMany
    {
        return $this->hasMany(\App\Models\PbphhPartnership::class, 'partner_pbphh_id', 'pbphh_id');
    }

    // ✅ Accessor untuk menghitung umur perusahaan
    public function getCompanyAgeAttribute()
    {
        if (!$this->tahun_berdiri) {
            return null;
        }
        return now()->year - $this->tahun_berdiri;
    }

    // ✅ Accessor untuk menghitung total kebutuhan bulanan
    public function getTotalMonthlyNeedAttribute()
    {
        return $this->materialNeeds->sum('volume_m3_per_bulan') ?? 0;
    }
}
