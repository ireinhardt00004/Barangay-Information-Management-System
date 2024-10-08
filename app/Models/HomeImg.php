<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class HomeImg extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'img_path',
        'user_id'
    ];

    protected $dates = ['deleted_at'];
}
