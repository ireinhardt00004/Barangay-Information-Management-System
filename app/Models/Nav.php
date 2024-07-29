<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Nav extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'nav_name',
        'page_id',
        'user_id'
    ];

    protected $dates = ['deleted_at'];
    
    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
