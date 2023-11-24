<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WinnerList extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = ['distribute_attachment_full_path'];

    public function ticketNumber()
    {
        return $this->belongsTo(TicketNumber::class, 'ticket_number_id');
    }

    public function drawResult()
    {
        return $this->belongsTo(DrawResult::class, 'draw_result_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDistributeAttachmentFullPathAttribute(){
        if($this->distribute_attachment != ''){
            return asset('storage/'.$this->distribute_attachment);
        }else{
            return $this->distribute_attachment;
        }
    }
}
