<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model {
    protected $primaryKey = 'region_id';
    protected $fillable = ['region_code', 'name', 'type', 'parent_id'];

    public function parent(): BelongsTo {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    public function children(): HasMany {
        return $this->hasMany(Region::class, 'parent_id');
    }

    
    public function kthrs(): HasMany {
        return $this->hasMany(Kthr::class, 'region_id', 'region_id');
    }

    
    public function pbphhs(): HasMany {
        return $this->hasMany(User::class, 'region_id', 'region_id')
            ->where('role', 'PBPHH');
    }

    
    public function users(): HasMany {
        return $this->hasMany(User::class, 'region_id', 'region_id');
    }
}
