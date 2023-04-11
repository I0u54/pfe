<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Tweet;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    use HttpResponses;
    public function like($id){
        if(!Tweet::where('id',$id)->first()){
            return $this->error([],'tweet not found',404);
        }
        if(Like::where('idTweet',$id)->where('idUser',Auth::user()->id)->first()){
            return $this->error([],'tweet already liked',404);
        }
        Like::create([
            'idUser'=>Auth::user()->id,
            'idTweet'=>$id
        ]);
        return $this->success([],'like has been applied');

    }
    public function disLike($id){
        if(!Tweet::where('id',$id)->first()){
            return $this->error([],'tweet not found',404);
        }
        if(!Like::where('idTweet',$id)->where('idUser',Auth::user()->id)->first()){
            return $this->error([],'there is no like in this tweet',404);
        }
        Like::where('idTweet',$id)->where('idUser',Auth::user()->id)->delete();
        return $this->success([],'like has been removed');

    }

}
