<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    protected $guarded = ['id'];

    const STATUS = [
        'TICKET_IMCOMPLETED' => 0,
        'TICKET_COMPLETED' => 1,
        'TICKET_REQUESTED' => 2,
        'TICKET_IN_PROGRESS' => 3,
        'TICKET_CANCELLED' => 4
    ];

    public function ticketNumbers()
    {
        return $this->hasMany(TicketNumber::class, 'ticket_id');
    }

    public function creditTransaction()
    {
        return $this->morphOne(CreditTransaction::class, 'target');
    }

    public function draws()
    {
        return $this->belongsTo(Draw::class, 'draw_id');
    }
}
