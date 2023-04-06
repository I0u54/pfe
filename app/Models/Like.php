<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    public function tweet_like()
    {
        return $this->belongsTo(Tweet::class , 'idTweet' );
    }

}
