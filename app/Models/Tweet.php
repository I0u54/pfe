<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function tweet_user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    public function tweet_comment()
    {
       
        return $this->hasMany(comment::class, 'idTweet')->orderBy('created_at' , 'desc');
        
    }

    public function tweet_like()
    {
       
        return $this->hasMany(Like::class, 'idTweet');
        
    }

    public function liked_tweet(){
        return   $this->hasOne(Like::class, 'idTweet');
     }
}
