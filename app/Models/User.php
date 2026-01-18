<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'email',
        'password_hash',
        'role',
        'region_id',
        'approval_status',
        'approved_by_user_id',
        'approved_at',
        'rejection_reason'
    ];

    /**
     * Memberitahu Laravel bahwa password disimpan di kolom password_hash
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Relasi ke tabel regions
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    /**
     * Relasi ke user yang menyetujui (approver)
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Relasi ke KTHR (hanya satu)
     */
    public function kthr(): HasOne
    {
        return $this->hasOne(Kthr::class, 'registered_by_user_id', 'user_id');
    }

    /**
     * Relasi ke profil PBPHH (hanya satu)
     */
    public function pbphhProfile(): HasOne
    {
        return $this->hasOne(PbphhProfile::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke notifikasi
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
