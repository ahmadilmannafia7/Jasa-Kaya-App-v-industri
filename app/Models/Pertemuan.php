<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pertemuan extends Model {
    protected $table = 'pertemuan';
    protected $primaryKey = 'meeting_id';

    protected $fillable = [
        'request_id',
        'scheduled_by_user_id',
        'location',
        'method',
        'scheduled_time',
        'actual_start_time',
        'actual_end_time',
        'meeting_notes',
        'meeting_summary',
        'status'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'actual_start_time' => 'datetime',
        'actual_end_time' => 'datetime',
    ];

    public function permintaanKerjasama(): BelongsTo {
        return $this->belongsTo(PermintaanKerjasama::class, 'request_id');
    }

    // ✅ Perbaikan: Ganti nama relasi ini agar sesuai Controller (dashboard KTHR)
    public function scheduledBy(): BelongsTo {
        return $this->belongsTo(User::class, 'scheduled_by_user_id', 'user_id');
    }

    public function kesepakatan(): HasOne {
        return $this->hasOne(KesepakatanKerjasama::class, 'meeting_id');
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute(): string {
        switch ($this->status) {
            case 'Dijadwalkan':
                return 'bg-primary';
            case 'Berlangsung':
                return 'bg-warning text-dark';
            case 'Selesai':
                return 'bg-success';
            case 'Dibatalkan':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    // Accessor untuk formatted volume
    public function getFormattedVolumeAttribute(): string {
        if ($this->permintaanKerjasama && $this->permintaanKerjasama->volume_needed) {
            return number_format($this->permintaanKerjasama->volume_needed, 0, ',', '.') . ' m³';
        }
        return 'Tidak ditentukan';
    }

    // Scope untuk filter berdasarkan region
    public function scopeInRegion($query, $regionId) {
        return $query->whereHas('permintaanKerjasama.kthr.user', function ($q) use ($regionId) {
            $q->where('region_id', $regionId);
        });
    }

    // Scope untuk pertemuan yang akan datang
    public function scopeUpcoming($query) {
        return $query->where('status', 'Dijadwalkan')
                    ->where('scheduled_time', '>', now());
    }

    // Scope untuk pertemuan aktif
    public function scopeActive($query) {
        return $query->whereIn('status', ['Dijadwalkan', 'Berlangsung']);
    }
}
