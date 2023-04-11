<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function like_tweet()
    {
        return $this->belongsTo(Tweet::class , 'idTweet' );
    }

}
