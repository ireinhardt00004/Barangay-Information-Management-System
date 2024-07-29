<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class HomeCard extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'img',
        'title',
        'title',
        'link',
        'user_id'
    ];

    
    protected $dates = ['deleted_at'];
}
