<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes; 
class Page extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'page_name','contents',
    ];

    protected $dates = ['deleted_at'];

    public function navs()
    {
        return $this->hasMany(Nav::class, 'page_id');
    }
}
