<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PbphhPartnership extends Model
{
    protected $table = 'pbphh_partnerships';
    protected $primaryKey = 'partnership_id';

    protected $fillable = [
        'requester_pbphh_id',
        'partner_pbphh_id',
        'partnership_type',
        'description',
        'material_type',
        'volume_needed_m3',
        'duration_months',
        'status',
        'rejection_reason',
        'negotiation_notes',
        'approved_at',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Relasi ke PBPHH yang mengajukan permintaan
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(PbphhProfile::class, 'requester_pbphh_id', 'pbphh_id');
    }

    /**
     * Relasi ke PBPHH yang diminta bermitra
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(PbphhProfile::class, 'partner_pbphh_id', 'pbphh_id');
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'Terkirim' => 'bg-warning text-dark',
            'Disetujui' => 'bg-success',
            'Ditolak' => 'bg-danger',
            'Dibatalkan' => 'bg-danger',
            'Dalam Negosiasi' => 'bg-info',
            'Kesepakatan Dibuat' => 'bg-primary',
            'Aktif' => 'bg-success',
            'Selesai' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    /**
     * Accessor untuk formatted volume
     */
    public function getFormattedVolumeAttribute(): string
    {
        if ($this->volume_needed_m3) {
            return number_format($this->volume_needed_m3, 2, ',', '.') . ' m³';
        }
        return 'Tidak ditentukan';
    }

    /**
     * Scope untuk filter permintaan yang diterima
     */
    public function scopeReceivedBy($query, $pbphhId)
    {
        return $query->where('partner_pbphh_id', $pbphhId);
    }

    /**
     * Scope untuk filter permintaan yang dikirim
     */
    public function scopeSentBy($query, $pbphhId)
    {
        return $query->where('requester_pbphh_id', $pbphhId);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk partnership aktif
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['Disetujui', 'Dalam Negosiasi', 'Kesepakatan Dibuat', 'Aktif']);
    }

    /**
     * Check if partnership is still negotiable
     */
    public function isNegotiable(): bool
    {
        return in_array($this->status, ['Terkirim', 'Disetujui', 'Dalam Negosiasi']);
    }

    /**
     * Check if partnership can be cancelled
     */
    public function isCancellable(): bool
    {
        return in_array($this->status, ['Terkirim', 'Disetujui', 'Dalam Negosiasi']);
    }
}
