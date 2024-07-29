<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
class Record extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'address',
        'cellphone',
        'householdNumber',
        'housingType',
        'housingType2',
        'kuryente',
        'tubig',
        'palikuran',
        'table_data', 
        'user_id'
    ];
    protected $dates = ['deleted_at'];

    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
