<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Program extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'cover',
        'title',
        'content',
        'program_date',
        'user_id'
    ];

    protected $dates = ['deleted_at'];
}
