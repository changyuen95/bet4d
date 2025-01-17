<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBonus extends Model
{
    use HasFactory;

    /**
     * The primary key type for the table.
     */
    protected $keyType = 'string'; // Since you are using UUIDs

    /**
     * Indicates if the IDs are incrementing.
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'user_id',
        'bonus_id',
        'amount',
        'description',
    ];

    /**
     * Relationships
     */

    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to the Bonus model
    public function bonus()
    {
        return $this->belongsTo(Bonus::class);
    }

    // Reverse relationship with the TopUp model
    public function topups()
    {
        return $this->hasMany(TopUp::class, 'user_bonus_id');
    }
}
