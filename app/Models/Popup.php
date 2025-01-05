<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image',
        'status',
        'start_time',
        'end_time',
    ];

    /**
     * Accessor to get the full URL of the image.
     */
    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    /**
     * Scope to get only active pop-ups.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where(function ($q) {
                         $q->whereNull('start_time')
                           ->orWhere('start_time', '<=', now());
                     })
                     ->where(function ($q) {
                         $q->whereNull('end_time')
                           ->orWhere('end_time', '>=', now());
                     });
    }
}
