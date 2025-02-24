<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Ticket extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    protected $guarded = ['id'];
    protected $appends = ['sub_total','total_amount','creatable_type','creatable_id','total_refund','is_claimable','claim_status'];
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
        $query = $this->hasMany(TicketNumber::class, 'ticket_id');

        if (Auth::check() && in_array(Auth::user()->role, [Role::NORMAL_USER, Role::MEMBER,null])) {
            $query->where('is_main', 1);
        }

        return $query;
    }

    public function requestWinner()
    {
        return $this->hasMany(UserRequestPrize::class, 'ticket_id');
    }

    public function pendingWinner()
    {
        return $this->hasMany(UserRequestPrize::class, 'ticket_id')->where('status', 'pending');
    }

    public function allTicketNumbers()
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

    public function receipts()
    {
        return $this->hasMany(TicketReceipt::class, 'ticket_id');
    }

    public function draws()
    {
        return $this->belongsTo(Draw::class, 'draw_id');
    }

    public function getWinnerAttribute(){
        $winner=[];
        $ticketNumbers = $this->allTicketNumbers;
        foreach($ticketNumbers as $ticketNumber){
            $winner[] = $ticketNumber->win;
        }
        return $winner;
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class, 'platform_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function scopeFilterByDate($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeFilterByOutlet($query, $outletId)
    {
        return $query->where('outlet_id', $outletId);
    }

    public function scopeFilterByStaff($query, $staffId)
    {
        return $query->where('action_by', $staffId);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function getTotalRefundAttribute(){
        $ticketNumbers = $this->allTicketNumbers;
        $totalAmount = 0;
        foreach($ticketNumbers as $ticketNumber){
            $totalAmount += ($ticketNumber->small_amount - $ticketNumber->actual_small_amount) + ($ticketNumber->big_amount - $ticketNumber->actual_big_amount) + ($ticketNumber->tax_amount - $ticketNumber->actual_tax_amount);
        }
        return number_format((float)$totalAmount, 2, '.', '');
    }

    public function getSubTotalAttribute(){
        $ticketNumbers = $this->allTicketNumbers;
        $totalAmount = 0;
        foreach($ticketNumbers as $ticketNumber){
            $totalAmount += ($ticketNumber->actual_small_amount + $ticketNumber->actual_big_amount);
        }
        return number_format((float)$totalAmount, 2, '.', '');
    }

    public function getTotalAmountAttribute(){
        $ticketNumbers = $this->allTicketNumbers;
        $totalAmount = 0;
        foreach($ticketNumbers as $ticketNumber){
            $totalAmount += ($ticketNumber->actual_small_amount + $ticketNumber->actual_big_amount + $ticketNumber->actual_tax_amount);
        }
        return number_format((float)$totalAmount, 2, '.', '');
    }

    public function getTotalTaxAttribute(){
        $ticketNumbers = $this->allTicketNumbers;
        $totalAmount = 0;
        foreach($ticketNumbers as $ticketNumber){
            $totalAmount += ($ticketNumber->actual_tax_amount);
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

    public function getIsClaimableAttribute()
    {
        if (Auth::check() && in_array(Auth::user()->role, [Role::OPERATOR])) {
            //staff
            $ticketNumbers = $this->allTicketNumbers->pluck('id');
            $winner = WinnerList::whereIn('ticket_number_id', $ticketNumbers)->where('is_distribute',0)->where('is_request',1)->get();

            if(count($winner)){

                return true;

            }else{
                return false;
            }
        }else{
            //user
            $ticketNumbers = $this->allTicketNumbers->pluck('id');
            $winner = WinnerList::whereIn('ticket_number_id', $ticketNumbers)->where('is_request',0)->get();

            if(count($winner)){

                return true;

            }else{
                return false;
            }

        }




    }

    public function getClaimStatusAttribute()
    {
        $ticketNumbers = $this->allTicketNumbers->pluck('id');
        $winner = WinnerList::whereIn('ticket_number_id', $ticketNumbers)->where('is_request',0)->first();

        if($winner){

            if($winner->is_request)
            {
                return 'Requested';
            }elseif($winner->is_distribute){
                return 'Claimed';
            }else{
                return 'Ready To Request';
            }

        }else{
            return 'No Result';
        }
    }


}
