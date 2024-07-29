<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Announcement extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'cover', 'title' , 'content' , 'user_id'
    ];

    protected $dates = ['deleted_at'];
    protected $casts = [
        'cover' => 'string',
        'title' => 'string',
        'content' => 'string',
    ];
}
