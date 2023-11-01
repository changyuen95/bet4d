<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUlids;
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
        'Active' => 1,
        'Inactive' => 2,
        'Disabled' => 3,
    ];

    protected $appends = ['is_finish_first_time_topup','is_bank_transferrable'];

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

    public function getStatusAttribute($status)
    {
        return $this->status = (int)$status;
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
}
