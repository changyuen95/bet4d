<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequestPrize extends Model
{
    use HasFactory;
    protected $table = 'user_request_prizes';
    protected $guarded = ['id'];
    protected $with = ['ticket', 'ticketNumber', 'winner'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticketNumber()
    {
        return $this->belongsTo(TicketNumber::class, 'ticket_number_id');
    }

    public function winner()
    {
        return $this->belongsTo(WinnerList::class, 'winner_list_id');
    }
}
