<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Service extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'request_type',
        'tracking_code',
        'status',
        'comment',
        'data', 'modified_by'
    ];

    protected $dates = ['deleted_at'];
    protected $casts = [
        'data' => 'array',
    ];
    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by', 'id');
    }

    // public function scopeSearch($query, $searchTerm)
    // {
    //     return $query->where(function($q) use ($searchTerm) {
    //         $q->where('user_id', 'like', "%{$searchTerm}%")
    //           ->orWhere('request_type', 'like', "%{$searchTerm}%")
    //           ->orWhere('tracking_code', 'like', "%{$searchTerm}%")
    //           ->orWhere('status', 'like', "%{$searchTerm}%")
    //           ->orWhere('comment', 'like', "%{$searchTerm}%")
    //           ->orWhere('data', 'like', "%{$searchTerm}%")
    //           ->orWhereDate('created_at', 'like', "%{$searchTerm}%")
    //           ->orWhereDate('updated_at', 'like', "%{$searchTerm}%");
    //     });
    // }
}
