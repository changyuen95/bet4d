<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bonuses';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'decimal:2',
        'min_topup_amount' => 'decimal:2',
        'max_bonus' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    /**
     * Relationship: Users who claimed this bonus.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function creditTransactions()
    {
        return $this->hasMany(CreditTransaction::class, 'bonus_id');
    }
    /**
     * Scope to filter active bonuses.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter valid bonuses within a date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValid($query)
    {
        return $query->where(function ($query) {
            $query->where('valid_from', '<=', now())
                  ->orWhereNull('valid_from');
        })->where(function ($query) {
            $query->where('valid_until', '>=', now())
                  ->orWhereNull('valid_until');
        });
    }

    /**
     * Check if the bonus is currently valid.
     *
     * @return bool
     */
    public function isValid()
    {
        $now = now();

        $validFrom = $this->valid_from ? $this->valid_from->isBefore($now) : true;
        $validUntil = $this->valid_until ? $this->valid_until->isAfter($now) : true;

        return $validFrom && $validUntil && $this->status === 'active';
    }
}
