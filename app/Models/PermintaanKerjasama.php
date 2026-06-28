<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class PermintaanKerjasama extends Model
{
    protected $table = 'permintaan_kerjasama';
    protected $primaryKey = 'request_id';
    protected $fillable = [
        'pbphh_id',
        'kthr_id',
        'tptkb_id',
        'wood_type',
        'monthly_volume_m3',
        'status',
        'rejection_reason',
        'additional_notes'
    ];

    public function pbphhProfile()
    {
        return $this->belongsTo(\App\Models\PbphhProfile::class, 'pbphh_id', 'pbphh_id');
    }


    public function kthr(): BelongsTo
    {
        return $this->belongsTo(Kthr::class, 'kthr_id');
    }

    public function tptkb(): BelongsTo
    {
        return $this->belongsTo(Tptkb::class, 'tptkb_id');
    }

    public function pertemuan(): HasOne
    {
        return $this->hasOne(Pertemuan::class, 'request_id');
    }

    public function pertemuans(): HasMany
    {
        return $this->hasMany(Pertemuan::class, 'request_id');
    }

    public function kesepakatanKerjasama(): HasOneThrough
    {
        return $this->hasOneThrough(
            KesepakatanKerjasama::class,
            Pertemuan::class,
            'request_id', // Foreign key on pertemuan table
            'meeting_id', // Foreign key on kesepakatan_kerjasama table
            'request_id', // Local key on permintaan_kerjasama table
            'meeting_id'  // Local key on pertemuan table
        );
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'Terkirim' => 'bg-warning',
            'Disetujui' => 'bg-success',
            'Ditolak' => 'bg-danger',
            'Dibatalkan' => 'bg-danger',
            'Menunggu Jadwal' => 'bg-warning',
            'Dijadwalkan' => 'bg-info',
            'Menunggu Tanda Tangan' => 'bg-primary',
            'Selesai' => 'bg-success',
            default => 'bg-secondary'
        };
    }
}
