<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Witness extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'ic',
        'phone',
        'address',
        'remarks',
    ];

    /**
     * Get the draws that this witness participated in
     */
    public function draws()
    {
        return $this->belongsToMany(Draw::class, 'draw_witnesses')
                    ->withPivot(['selected_at', 'has_signed', 'signed_at', 'signature_path'])
                    ->withTimestamps();
    }

    /**
     * Get the witness's participation records
     */
    public function drawWitnesses()
    {
        return $this->hasMany(DrawWitness::class);
    }

    /**
     * Format IC for display (xxx,xxx,xx,xx,xxx)
     */
    public function getFormattedIcAttribute()
    {
        $ic = preg_replace('/[^0-9]/', '', $this->ic);
        
        if (strlen($ic) === 12) {
            return substr($ic, 0, 3) . ',' . substr($ic, 3, 3) . ',' . substr($ic, 6, 2) . ',' . substr($ic, 8, 2) . ',' . substr($ic, 10, 3);
        }
        
        return $this->ic;
    }
}
