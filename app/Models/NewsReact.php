<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsReact extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'user_id',
        'react',
        'comment',
    ];
    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
