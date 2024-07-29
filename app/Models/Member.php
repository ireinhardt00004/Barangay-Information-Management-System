<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Member extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'uid',
        'tracking_code',
        'status',
        'comment',
        'data'
    ];

    protected $dates = ['deleted_at'];

    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
