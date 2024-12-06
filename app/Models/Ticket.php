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
    protected $appends = ['sub_total','total_amount','creatable_type','creatable_id','total_refund'];
    const STATUS = [
        'TICKET_IMCOMPLETED' => 'incompleted',
        'TICKET_COMPLETED' => 'completed',
        'TICKET_REQUESTED' => 'requested',
        'TICKET_IN_PROGRESS' => 'in_progress',
        'TICKET_CANCELLED' => 'cancelled',
        'TICKET_REJECTED' => 'rejected',
    ];

    // Const ALL_STATUS = array_values($this::STATUS);


    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->reference_id = uniqid();
        });
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff()
    {
        return $this->hasOne(Admin::class, 'id','action_by');
    }

    public function ticketNumbers()
    {
        return $this->hasMany(TicketNumber::class, 'ticket_id');
    }

    public function creditTransaction()
    {
        return $this->morphOne(CreditTransaction::class, 'targetable');
    }

    public function barcode()
    {
        return $this->hasMany(BarCode::class, 'ticket_id');
    }

    public function draws()
    {
        return $this->belongsTo(Draw::class, 'draw_id');
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class, 'platform_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function getTotalRefundAttribute(){
        $ticketNumbers = $this->ticketNumbers;
        $totalAmount = 0;
        foreach($ticketNumbers as $ticketNumber){
            $totalAmount += ($ticketNumber->small_amount - $ticketNumber->actual_small_amount) + ($ticketNumber->big_amount - $ticketNumber->actual_big_amount);
        }
        return number_format((float)$totalAmount, 2, '.', '');
    }

    public function getSubTotalAttribute(){
        $ticketNumbers = $this->ticketNumbers;
        $totalAmount = 0;
        foreach($ticketNumbers as $ticketNumber){
            $totalAmount += ($ticketNumber->small_amount + $ticketNumber->big_amount);
        }
        return number_format((float)$totalAmount, 2, '.', '');
    }

    public function getTotalAmountAttribute(){
        $ticketNumbers = $this->ticketNumbers;
        $totalAmount = 0;
        foreach($ticketNumbers as $ticketNumber){
            $totalAmount += ($ticketNumber->small_amount + $ticketNumber->big_amount + $ticketNumber->tax_amount);
        }
        return number_format((float)$totalAmount, 2, '.', '');
    }

    public function getTotalTaxAttribute(){
        $ticketNumbers = $this->ticketNumbers;
        $totalAmount = 0;
        foreach($ticketNumbers as $ticketNumber){
            $totalAmount += ($ticketNumber->tax_amount);
        }
        return number_format((float)$totalAmount, 2, '.', '');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'targetable');
    }

    public function getCreatableIdAttribute(){
        return $this->action_by;
    }

    public function getCreatableTypeAttribute(){
        return 'App\\Models\\Admin';
    }
}
