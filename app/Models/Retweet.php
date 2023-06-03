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

    public function retweet_comment()
    {
       
        return $this->hasMany(comment::class, 'idReTweet')->orderBy('created_at' , 'desc');
        
    }

    public function retweet_like()
    {
       
        return $this->hasMany(Like::class, 'idReTweet');
        
    }
    public function liked_retweet(){
        return   $this->hasOne(Like::class, 'idReTweet')->where('idUser' , auth()->user()->id);
     }

     public function retweet_save(){

        return   $this->hasOne(Sauvguarde::class, 'idTweet')->where('idUser' , auth()->user()->id);

    }

}
