<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminCreditTransaction extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];
    protected $appends = ['date','description','status'];

    const TYPE = [
        'Increase' => 'increase',
        'Decrease' => 'decrease'
    ];

    const TRANSACTION_TYPE = [
        'TopUp' => 'topup',
        'Cleared' => 'cleared'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function admin_credit()
    {
        return $this->belongsTo(AdminCredit::class, 'admin_id', 'admin_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id','id');
    }

    public function targetable()
    {
        return $this->morphTo();
    }

    public function clear_credit_transaction()
    {
        return $this->belongsTo(AdminClearCreditTransaction::class, 'admin_clear_credit_transactions_id', 'id');
    }

    public function getDateAttribute(){
        return Carbon::parse($this->created_at)->format('d-m-Y');
    }

    public function getStatusAttribute()
    {
        return 'Completed';
    }

    public function getDescriptionAttribute()
    {
        $relatedModel = $this->targetable;
        $description = '';
        if($this->targetable_type == 'App\Models\AdminClearCreditTransaction'){
            $description = trans('messages.credit_clear_by_admin');
        }elseif($this->targetable_type == 'App\Models\TopUp'){
            $description = trans('messages.credit_distributed_to_customer');
        }

        return $description;
    }
}
