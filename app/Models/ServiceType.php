<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class ServiceType extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
       
        'request_type',
        'user_id',
        'photo',
        'description',
    ];

    protected $dates = ['deleted_at'];

    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }
}