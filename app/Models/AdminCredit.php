<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminCredit extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function admin_credit_transaction()
    {
        return $this->hasMany(AdminCreditTransaction::class, 'admin_id', 'admin_id');
    }
}
