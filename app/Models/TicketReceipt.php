<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReceipt extends Model
{
    use HasFactory;
    protected $name = 'ticket_receipts';
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

}
