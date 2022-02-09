<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'startDate', 'endDate',
        'title', 'poster', 'desc', 'content', 'artist', 'price',
        'remainTicket',
        'openDate', 'closeDate',
        'playTime', 'reEndDate'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('use', 'gRank', 'pRank');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
