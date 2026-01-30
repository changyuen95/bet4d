<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model
{
    use HasFactory, SoftDeletes;

    const ROLE = [
        'recorder' => 1,
        'certifier' => 2,
        'both' => 3,
    ];

    protected $fillable = [
        'name',
        'ic',
        'phone',
        'role',
        'is_active',
        'remarks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'role' => 'integer',
    ];

    /**
     * Format IC for display (xxxxxx-xx-xxxx)
     */
    public function getFormattedIcAttribute()
    {
        $ic = preg_replace('/[^0-9]/', '', $this->ic);
        
        if (strlen($ic) == 12) {
            return substr($ic, 0, 6) . '-' . substr($ic, 6, 2) . '-' . substr($ic, 8);
        }
        
        return $this->ic;
    }

    /**
     * Get role name
     */
    public function getRoleNameAttribute()
    {
        return match($this->role) {
            self::ROLE['recorder'] => 'Recorder',
            self::ROLE['certifier'] => 'Certifier',
            self::ROLE['both'] => 'Both',
            default => 'Unknown',
        };
    }

    /**
     * Scope to get only recorders
     */
    public function scopeRecorders($query)
    {
        return $query->where('is_active', true)
                     ->whereIn('role', [self::ROLE['recorder'], self::ROLE['both']]);
    }

    /**
     * Scope to get only certifiers
     */
    public function scopeCertifiers($query)
    {
        return $query->where('is_active', true)
                     ->whereIn('role', [self::ROLE['certifier'], self::ROLE['both']]);
    }
}
