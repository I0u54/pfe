<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Tweet;
use App\Models\User;
use App\Traits\HttpResponses;
use App\Http\Resources\profile\Profile as RCProfile ;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller

{
    use HttpResponses;
    public function index($slug){

        $user =User::where('pseudo' , $slug)->first();

        if(is_null($user)) :

           return $this->error([] , "user doesn't exist" ,404);

        endif ;
        
        $data = User::where('pseudo' , $slug)
        ->with('extra_user')
        ->with(['follows' => function (Builder $query) {
            $query->with('following')->with('follower')->get() ;
        }])->first() ;

        return $this->success(RcProfile::collection([$data]) , "this is profile user for  {$user->name} ");

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
