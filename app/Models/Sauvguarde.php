<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sauvguarde extends Model
{
    use HasFactory;
    protected $guarded = [];

    
    public function save_tweets()
    {
        return $this->belongsTo(Tweet::class , 'idTweet');
    }
}
