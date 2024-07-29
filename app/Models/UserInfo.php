<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
class UserInfo extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'uid',
        'profile_pic',
        'valid_id',
        'phone_number',
        'sex',
        'address',
        'barangay',
        'region',
        'province',
        'municipality',
        'req_no',
        'verified',
        'active',
        'reset_hash','first_time_seeker'
    ];

    protected $dates = ['deleted_at'];

    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
