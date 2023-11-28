<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditTransaction extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    protected $guarded = ['id'];
    protected $appends = ['transaction_type','description','status'];
    protected $with = ['user'];
    const TYPE = [
        'Increase' => 'increase',
        'Decrease' => 'decrease'
    ];
    
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function targetable()
    {
        return $this->morphTo();
    }

    public function getTransactionTypeAttribute()
    {
        return str_replace('App\\Models\\',"",$this->targetable_type);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusAttribute()
    {
        return 'Completed';
    }

    public function getDescriptionAttribute()
    {
        $relatedModel = $this->targetable;
        $description = '';
        if($this->targetable_type == 'App\Models\TopUp'){
            if($relatedModel->top_up_with == TopUp::TOP_UP_WITH['QR']){
                $description = trans('messages.credit_top_up_by_scan_qr');
            }else{
                $description = trans('messages.credit_top_up_at_outlet');
            }
        }elseif($this->targetable_type == 'App\Models\Ticket'){
            if($this->type == self::TYPE['Increase']){
                if($relatedModel->status == Ticket::STATUS['TICKET_CANCELLED']){
                    $description = trans('messages.ticket_request_cancelled');
                }elseif($relatedModel->status == Ticket::STATUS['TICKET_REJECTED']){
                    $description = trans('messages.ticket_request_rejected');
                }
            }else{
                $description = trans('messages.ticket_request');
            }
        }

        return $description;
    }
}
