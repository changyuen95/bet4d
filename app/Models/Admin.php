<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUlids, SoftDeletes;
    protected $guard_name = 'admin-api';
              //  ^ Hi, the above guard_name supposed to be admin-api right?

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
        'Active' => 'active',
        'Inactive' => 'inactive',
        'Disabled' => 'disabled',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->reference_id = uniqid();
        });
    }

    public function topUpMorph()
    {
        return $this->morphMany(TopUp::class, 'creatable');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'receivable');
    }


     public static function generatePassword()
     {
        $characters = 'ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz123456789';

        $characters_length = strlen($characters);
        $random_string = '';
        for ($i = 0; $i < 8; $i++) {
            $random_string .= $characters[rand(0, $characters_length - 1)];
        }
        $pwd = $random_string;

        return $pwd;
     }



    /********  Attribute  ********/
    public function getStringRoleAttribute()
    {
        $role = $this->role;
        if($role == "super_admin")
        {
            $stringRole = 'Superadmin';

        }else if($role == "operator"){

            $stringRole = 'Operator';

        }else{
            $stringRole = '-';
        }

        return $stringRole;
    }


}
