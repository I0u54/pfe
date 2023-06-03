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

    public function retweet_tweet(){
        return $this->hasMany(Retweet::class , 'idTweet');
    }

    public function liked_tweet(){
        return   $this->hasOne(Like::class, 'idTweet')->where('idUser' , auth()->user()->id);
    }

    public function tweet_save(){

        return   $this->hasOne(Sauvguarde::class, 'idTweet')->where('idUser' , auth()->user()->id);

    }
}
