<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrawWitness extends Model
{
    use HasFactory;

    protected $fillable = [
        'draw_id',
        'witness_id',
        'selected_at',
        'has_signed',
        'signed_at',
        'signature_path',
    ];

    protected $casts = [
        'selected_at' => 'datetime',
        'signed_at' => 'datetime',
        'has_signed' => 'boolean',
    ];

    /**
     * Get the draw
     */
    public function draw()
    {
        return $this->belongsTo(Draw::class);
    }

    /**
     * Get the witness
     */
    public function witness()
    {
        return $this->belongsTo(Witness::class);
    }
}
