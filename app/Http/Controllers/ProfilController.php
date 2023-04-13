<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Tweet;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Http\Resources\profile\Follower as RcFollower;
use App\Http\Resources\profile\Following as RcFollowing;
use App\Http\Resources\profile\Likes as RcLikes ;
use App\Http\Resources\profile\Bookmarks as RcBookmarks;
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
        ->select('tweets.idUser','tweets.id','description','image','video','tweets.created_at','name','pseudo','email',DB::raw('count(likes.idLike) as likes'),DB::raw('count(comments.idComment) as comments'),'pp')
        ->leftJoin('likes','likes.idTweet','=','tweets.id')
        ->leftJoin('comments','comments.idTweet','=','tweets.id')
        ->join('users','users.id','=','tweets.idUser')
        ->leftJoin('extar_users','extar_users.idUser','=','users.id')
        ->groupBy('tweets.idUser', 'tweets.id', 'description', 'image', 'video', 'tweets.created_at', 'name', 'pseudo', 'email','pp')
        ->orderBy('tweets.created_at','desc')
        ->get();
    

        $retweets = Tweet::where('retweets.idUser',$slug[strlen($slug)-1])
        ->join('retweets','retweets.idTweet','=','tweets.id')
        ->select('tweets.idUser','retweets.idUser as orginaUserId','tweets.id','description','image','video','retweets.created_at','name','pseudo','email',DB::raw('count(likes.idLike) as likes'),DB::raw('count(comments.idComment) as comments'),'pp')
        ->join('users','users.id','=','tweets.idUser')
        ->leftJoin('likes','likes.idTweet','=','tweets.id')
        ->leftJoin('comments','comments.idTweet','=','tweets.id')
        ->leftJoin('extar_users','extar_users.idUser','=','users.id')
     
        ->groupBy('tweets.idUser', 'tweets.id', 'description', 'image', 'video', 'retweets.created_at', 'name', 'pseudo', 'email','pp','retweets.idUser')
        ->orderBy('tweets.created_at','desc')
        ->get();
        $allTweets = $tweets->merge($retweets);

        $sortedTweets = $allTweets->sortByDesc('created_at');
        return $this->success([
            'tweets'=>$sortedTweets,
            

            
        ],'tweets shiped');
        
    }

    public function likedTweets($slug)
    {
        $user =User::where('pseudo' , $slug)->first();

        if(is_null($user)) :

           return $this->error([] , "user doesn't exist" ,404);

        endif ;
        
        $data = User::where('pseudo' , $slug)->with(['like' => function (Builder $query) {
                    $query->with(['like_tweet' => function(Builder $query){

                         $query->with('tweet_user.extra_user')->withCount('tweet_comment' , 'tweet_like');

                    }]);
                }])->first() ;   

        return $this->success(RcLikes::collection($data->like) , "this is likes user for  {$user->name} ");

    }

    public function  follower($slug)
    {

        $user =User::where('pseudo' , $slug)->first();

        if(is_null($user)) :

           return $this->error([] , "user doesn't exist" ,404);

        endif ;

        $data = User::where('pseudo' , $slug)
        ->with('user_follower.follower.extra_user')->first();

        return $this->success(RcFollower::collection($data->user_follower) , "this is follower user for  {$user->name} ");

    }

    public function  following($slug)
    {
        $user =User::where('pseudo' , $slug)->first();

        if(is_null($user)) :

           return $this->error([] , "user doesn't exist" ,404);

        endif ;
        
         $data = User::where('pseudo' , $slug)
        ->with('user_following.following.extra_user')->first();
        
        return $this->success(RcFollowing::collection($data->user_following) , "this is following user for  {$user->name} ");

    }


    public function bookmarks($slug)
    {

        $user =User::where('pseudo' , $slug)->first();

        if(is_null($user)) :

           return $this->error([] , "user doesn't exist" ,404);

        endif ;

        $data = User::where('pseudo' , $slug)->with(['bookmarks' => function (Builder $query) {
            $query->with(['save_tweets' => function(Builder $query){

                 $query->with('tweet_user.extra_user')->withCount('tweet_comment' , 'tweet_like');

            }]);
        }])->first() ;  

        return $this->success(RcBookmarks::collection($data->bookmarks) , "this is bookmarks (saved) user for  {$user->name} ");

    }
}
