<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerifyProfile extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    protected $guarded = ['id'];
    protected $appends = ['front_ic_full_path','back_ic_full_path','selfie_ic_full_path'];
    const STATUS = [
        'Pending' => 'pending',
        'Success' => 'success',
        'Failed' => 'failed',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function getFrontICFullPathAttribute(){
        return asset('storage/'.$this->front_ic);
    }

    public function getBackICFullPathAttribute(){
        return asset('storage/'.$this->back_ic);

    }

    public function getSelfieICFullPathAttribute(){
        return asset('storage/'.$this->selfie_with_ic);
    }
}
