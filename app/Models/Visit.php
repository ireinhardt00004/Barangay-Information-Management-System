<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'login', 'logout'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    // Visit.php (Visit model)

protected $dates = ['login', 'logout']; // Add 'login' and 'logout' to the $dates array

protected $casts = [
    'login' => 'datetime', // Add this line to cast 'login' as a datetime
    'logout' => 'datetime',
];


   
}
