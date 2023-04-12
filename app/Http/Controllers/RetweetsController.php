<?php

namespace App\Http\Controllers;

use App\Models\Retweet;
use App\Models\Tweet;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RetweetsController extends Controller
{
    use HttpResponses;
    public function reTweet($id){
        if(!Tweet::where('id',$id)->first()){
            return $this->error([],'tweet not found',404);
        }
        
        Retweet::create([
            'idUser'=>Auth::user()->id,
            'idTweet'=>$id
        ]);
        return $this->success([],'Retweet has been applied');

    }
    public function removeReTweet($id){
        if(!Tweet::where('id',$id)->first()){
            return $this->error([],'tweet not found',404);
        }
        
        Retweet::where('idTweet',$id)->where('idUser',Auth::user()->id)->delete();
        return $this->success([],'retweet has been removed');

    }

}
