<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrawManager extends Model
{
    use HasFactory;

    protected $fillable = [
        'draw_id',
        'recorded_by_id',
        'certified_by_id',
    ];

    /**
     * Get the draw
     */
    public function draw()
    {
        return $this->belongsTo(Draw::class);
    }

    /**
     * Get the recorder
     */
    public function recordedBy()
    {
        return $this->belongsTo(Manager::class, 'recorded_by_id');
    }

    /**
     * Get the certifier
     */
    public function certifiedBy()
    {
        return $this->belongsTo(Manager::class, 'certified_by_id');
    }
}
