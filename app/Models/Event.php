<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Event extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'start_datetime',
        'end_datetime',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
