<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUlids, SoftDeletes;
    protected $guard_name = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'phone_e164',
        'email',
        'password',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    const STATUS = [
        'Active' => 'active',
        'Inactive' => 'inactive',
        'Disabled' => 'disabled',
    ];

    protected $appends = ['is_finish_first_time_topup','is_bank_transferrable','winning_amount'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->reference_id = uniqid();
        });
    }

    public function tacs()
    {
        return $this->morphMany(Tac::class, 'ownerable');
    }

    public function topUpMorph()
    {
        return $this->morphMany(TopUp::class, 'creatable');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'receivable');
    }

    public function routeNotificationForVonage($notification)
    {
        return $this->phone_e164;
    }

    public function credit()
    {
        return $this->hasOne(UserCredit::class, 'user_id');
    }

    public function point()
    {
        return $this->hasOne(UserPoint::class, 'user_id');
    }

    public function pointTransaction()
    {
        return $this->hasMany(PointTransaction::class, 'user_id');
    }

    public function creditTransaction()
    {
        return $this->hasMany(CreditTransaction::class, 'user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function topup()
    {
        return $this->hasMany(TopUp::class, 'user_id');
    }

    public function transferDetails()
    {
        return $this->hasMany(UserTransferDetails::class, 'user_id');
    }

    public function winningHistory()
    {
        return $this->hasMany(WinnerList::class, 'user_id');
    }

    public function verifyProfile()
    {
        return $this->hasMany(VerifyProfile::class, 'user_id');
    }

    public function getIsFinishFirstTimeTopUpAttribute($status)
    {
        $isFinishFirstTimeTopUp = false;
        $topUpCount = $this->topup()->count();
        if($topUpCount > 0){
            $isFinishFirstTimeTopUp = true; 
        }
        return $isFinishFirstTimeTopUp;
    }
    
    public function getIsBankTransferrableAttribute($status)
    {
        $isBankTransferrable = false;
        $transferDetailsCount = $this->transferDetails()->count();
        if($transferDetailsCount > 0){
            $isBankTransferrable = true; 
        }
        return $isBankTransferrable;
    }

    public function getWinningAmountAttribute($status)
    {
        $winningAmount = $this->winningHistory()->sum('amount');
        return $winningAmount;
    }
}
