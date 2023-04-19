<?php

namespace App\Http\Controllers;

use App\Models\Retweet;
use App\Models\Tweet;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User ;
use App\Notifications\Retweet as NotificationsRetweet;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;

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

        //Notifications for Retweet

        $this->retweetNotifications($id);
        
        return $this->success([],'Retweet has been applied');

    }
    public function removeReTweet($id){
        if(!Tweet::where('id',$id)->first()){
            return $this->error([],'tweet not found',404);
        }
        
        Retweet::where('idTweet',$id)->where('idUser',Auth::user()->id)->delete();
        return $this->success([],'retweet has been removed');

    }

    public function retweetNotifications($id)
    {

        $user_tweet = User::where('id' , Auth::user()->id)->with('extra_user')->first();

        $user_followers =  User::where('id' , Auth::user()->id)->with('user_follower.follower')->first();

        $followers =  Arr::pluck($user_followers->user_follower, ['follower']);
                
        Notification::send($followers , new NotificationsRetweet($user_tweet , $id)) ;

    }

}
