<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retweet extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function tweet_retweet()
    {
       
        return $this->belongsTo(Tweet::class, 'idTweet');
        
    }

    public function retweet_user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }
}
