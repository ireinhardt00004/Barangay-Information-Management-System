<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class HeaderBtn extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'link',
        'outline',
        'user_id'
    ];
    protected $dates = ['deleted_at'];
}
