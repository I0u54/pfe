<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Tweet;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller

{
    use HttpResponses;
    public function index($slug){
        if(!User::where('pseudo',$slug)->first()){
            return $this->error([],'user not found',404);
        }
        $user=User::where('pseudo',$slug)->leftJoin('extar_users','users.id','=','extar_users.idUser')->select('users.id','name','email','pseudo','birthday','users.created_at','bio','adresse','pp','cover')->first();
        $following = Follow::where('users.pseudo',$slug)->leftJoin('users','users.id','=','follows.idFollower')->select(DB::raw('count(follows.id) as following'))->get();
        $followers = Follow::where('users.pseudo',$slug)->leftJoin('users','users.id','=','follows.idFollowing')->select(DB::raw('count(follows.id) as followers' ))->get();
        return $this->success([

            'user'=>$user,
            'following'=>$following[0]->following,
            'followers'=>$followers[0]->followers
        ],'user shiped');

    }
    public function getTweets($slug){
        if(!User::where('pseudo',$slug)->first()){
            return $this->error([],'user not found',404);
        }
        $tweets = Tweet::where('users.pseudo',$slug)
        ->select('tweets.idUser','tweets.idTweet','description','image','video','tweets.created_at','tweets.updated_at','name','pseudo','email',DB::raw('count(likes.idLike) as likes'),DB::raw('count(comments.idComment) as comments'),'pp')
        ->leftJoin('likes','likes.idTweet','=','tweets.idTweet')
        ->leftJoin('comments','comments.idTweet','=','tweets.idTweet')
        ->join('users','users.id','=','tweets.idUser')
        ->leftJoin('extar_users','extar_users.idUser','=','users.id')
        ->groupBy('tweets.idUser', 'tweets.idTweet', 'description', 'image', 'video', 'tweets.created_at', 'tweets.updated_at', 'name', 'pseudo', 'email','pp')
        ->orderBy('tweets.created_at','desc')
        ->get();
    

        $retweets = Tweet::where('retweets.idUser',$slug[strlen($slug)-1])
        ->join('retweets','retweets.idTweet','=','tweets.idTweet')
        ->select('tweets.idUser','retweets.idUser as orginaUserId','tweets.idTweet','description','image','video','tweets.created_at','tweets.updated_at','name','pseudo','email',DB::raw('count(likes.idLike) as likes'),DB::raw('count(comments.idComment) as comments'),'pp')
        ->join('users','users.id','=','tweets.idUser')
        ->leftJoin('likes','likes.idTweet','=','tweets.idTweet')
        ->leftJoin('comments','comments.idTweet','=','tweets.idTweet')
        ->leftJoin('extar_users','extar_users.idUser','=','users.id')
     
        ->groupBy('tweets.idUser', 'tweets.idTweet', 'description', 'image', 'video', 'tweets.created_at', 'tweets.updated_at', 'name', 'pseudo', 'email','pp','retweets.idUser')
        ->orderBy('tweets.created_at','desc')
        ->get();
        return $this->success([
            'tweets'=>$tweets,
            'retweets'=>$retweets

            
        ],'tweets shiped');

    }
}
