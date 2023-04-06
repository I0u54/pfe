<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Tweet;
use App\Models\User;
use App\Traits\HttpResponses;
use App\Http\Resources\profile\Profile as RcProfile ;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Http\Resources\profile\Follows as RcFollows;
use App\Http\Resources\profile\Likes as RcLikes ;
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
        ->select('tweets.idUser','tweets.id','description','image','video','tweets.created_at','tweets.updated_at','name','pseudo','email',DB::raw('count(likes.idLike) as likes'),DB::raw('count(comments.idComment) as comments'),'pp')
        ->leftJoin('likes','likes.idTweet','=','tweets.id')
        ->leftJoin('comments','comments.idTweet','=','tweets.id')
        ->join('users','users.id','=','tweets.idUser')
        ->leftJoin('extar_users','extar_users.idUser','=','users.id')
        ->groupBy('tweets.idUser', 'tweets.id', 'description', 'image', 'video', 'tweets.created_at', 'tweets.updated_at', 'name', 'pseudo', 'email','pp')
        ->orderBy('tweets.created_at','desc')
        ->get();
    

        $retweets = Tweet::where('retweets.idUser',$slug[strlen($slug)-1])
        ->join('retweets','retweets.idTweet','=','tweets.id')
        ->select('tweets.idUser','retweets.idUser as orginaUserId','tweets.id','description','image','video','tweets.created_at','tweets.updated_at','name','pseudo','email',DB::raw('count(likes.idLike) as likes'),DB::raw('count(comments.idComment) as comments'),'pp')
        ->join('users','users.id','=','tweets.idUser')
        ->leftJoin('likes','likes.idTweet','=','tweets.id')
        ->leftJoin('comments','comments.idTweet','=','tweets.id')
        ->leftJoin('extar_users','extar_users.idUser','=','users.id')
     
        ->groupBy('tweets.idUser', 'tweets.id', 'description', 'image', 'video', 'tweets.created_at', 'tweets.updated_at', 'name', 'pseudo', 'email','pp','retweets.idUser')
        ->orderBy('tweets.created_at','desc')
        ->get();
        return $this->success([
            'tweets'=>$tweets,
            'retweets'=>$retweets

            
        ],'tweets shiped');

    }

    // public function likes($slug)
    // {
    //     $user =User::where('pseudo' , $slug)->first();

    //     if(is_null($user)) :

    //        return $this->error([] , "user doesn't exist" ,404);

    //     endif ;
        
    //     $data = User::where('pseudo' , $slug)->with(['like' => function (Builder $query) {
    //                 $query->with(['tweet_like' => function(Builder $query){

    //                      $query->with('tweet_user');

    //                 }]);
    //             }])->get() ;   
                
    
    //     return $this->success(RcLikes::collection($data[0]->like) , "this is like user for  {$user->name} ");

    

    // }

    public function  follower($slug)
    {

        $user =User::where('pseudo' , $slug)->first();

        if(is_null($user)) :

           return $this->error([] , "user doesn't exist" ,404);

        endif ;
        
        $data = User::where('pseudo' , $slug)
        ->with(['follows' => function (Builder $query) {
            $query->with(['follower' => function(Builder $query){
                $query->with('extra_user')->get();
            }]) ;
        }])->first() ;

        return $this->success(RcFollows::collection($data->follows) , "this is follower user for  {$user->name} ");

    }

    public function  following($slug)
    {
        $user =User::where('pseudo' , $slug)->first();

        if(is_null($user)) :

           return $this->error([] , "user doesn't exist" ,404);

        endif ;
        
        $data = User::where('pseudo' , $slug)
        ->with(['follows' => function (Builder $query) {
            $query->with(['following' => function(Builder $query){
                $query->with('extra_user')->get();
            }]) ;
        }])->first() ;

        return $this->success(RcFollows::collection($data->follows) , "this is following user for  {$user->name} ");

    }
}
