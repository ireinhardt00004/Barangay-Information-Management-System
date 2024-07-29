<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Footerz extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'gov',
        'social',
        'contact',
        'user_id'
    ];
     protected $dates = ['deleted_at'];
}
