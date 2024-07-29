<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class GeneralConf extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'logo',
        'title',
        'meta_desc',
        'head_title',
        'about_title',
        'about_desc',
        'gcash_no',
        'payment_amt',
        'theme',
        'em_contacts',
        'max_requests',
        'user_id'
    ];

    protected $casts = [
        'theme' => 'array',
        'em_contacts' => 'array',
    ];
    protected $dates = ['deleted_at'];
}
