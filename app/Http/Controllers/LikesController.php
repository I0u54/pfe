<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Tweet;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\Like as NotificationsLike;

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

        //Notification for likes 

        $user = User::where('idTweet' , $id );
        $user_like = User::where('id' , Auth::user()->id)->with('extra_user')->first() ;
        $user->notify(new NotificationsLike($user_like)) ;

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
