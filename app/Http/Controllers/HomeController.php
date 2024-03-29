<?php

namespace App\Http\Controllers;

use App\Http\Resources\Home as RcHome ;

use App\Http\Resources\Users_To_Follow;
use App\Models\Like;
use App\Models\Tweet;
use App\Traits\HttpResponses;
use App\Models\User ;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Eloquent\Builder;

class HomeController extends Controller
{
    use HttpResponses ;
    public function getAllTweets()
    {
        $user_following_tweets =  User::where('id' , Auth::user()->id)->with(['user_following' => function (Builder $query){
            $query->with(['following'=> function (Builder $query) {
                $query->with(['user_tweets' => function(Builder $query){
                    $query->with( 'tweet_user.extra_user')->with('liked_tweet')
                    ->with('tweet_save')
                    ->withCount( 'tweet_like' , 'tweet_comment' , 'retweet_tweet');
                }] ) ;
            }]);

        }])->first();

        $user_following_retweets=  User::where('id' , Auth::user()->id)->with(['user_following' => function (Builder $query){
            $query->with(['following'=> function (Builder $query) {
                $query->with(['user_retweets' => function(Builder $query){
                    $query->with( 'retweet_user.extra_user')
                    ->with('tweet_retweet.tweet_user.extra_user')
                    ->with('liked_retweet')->with('retweet_save')
                    ->withCount( 'retweet_like' , 'retweet_comment' , 'tweet_retweet');
                }] ) ;
            }]);


        }])->first();
        
        $following_tweets =  Arr::pluck($user_following_tweets->user_following, ['following']);
        $following_retweets =  Arr::pluck($user_following_retweets->user_following, ['following']);
       
        $getTweets =  Arr::pluck($following_tweets, ['user_tweets']);
        $getRetweets =  Arr::pluck($following_retweets, ['user_retweets']);

        $tweets = Arr::collapse($getTweets);
        $retweets = Arr::collapse($getRetweets);


        $id_user_tweet =  Arr::pluck($tweets, ['idUser']);

        $tweets_other_user=  Tweet::whereNotIn('idUser' , array_unique($id_user_tweet))
        ->with('tweet_user.extra_user')->with('liked_tweet')
        ->with('tweet_save')
        ->withCount( 'tweet_like' , 'tweet_comment' , 'retweet_tweet')
        ->orderBy('created_at' , 'desc')->get();


        return $this->success(new RcHome( $tweets , $retweets , $tweets_other_user  ) , 'get all tweet ') ;

    }
    public function getTrends() { 
        $hashtags = DB::select("
        SELECT COUNT(*) AS count, SUBSTRING_INDEX(SUBSTRING_INDEX(words, ' ', n), ' ', -1) AS hashtag
        FROM (
          SELECT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(description, ' ', n.n), ' ', -1)) AS words, n.n
          FROM `tweets`
          CROSS JOIN (
            SELECT a.N + b.N * 10 + 1 AS n
            FROM (
              SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
              UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
              UNION SELECT 8 UNION SELECT 9
            ) AS a
            CROSS JOIN (
              SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
              UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
              UNION SELECT 8 UNION SELECT 9
            ) AS b
          ) n
          WHERE n.n <= 1 + (LENGTH(description) - LENGTH(REPLACE(description, ' ', '')))
        ) AS hashtags
        WHERE words LIKE '#%'
        GROUP BY hashtag
        ORDER BY count DESC
       
    ");
        
        return $this->success($hashtags,'hashtags fetched with success');
    }

    public function getTweetsByHashtag($hashtag)
    {
        $tweets = Tweet::where('description', 'like', '%#' . $hashtag . '%')
        ->select('tweets.idUser','tweets.id','description','image','video','tweets.created_at','name','pseudo','email',DB::raw('count(likes.idLike) as likes'),DB::raw('count(comments.idComment) as comments'),'pp',DB::raw('count(retweets.id) as retweet_count'))
        ->leftJoin('likes','likes.idTweet','=','tweets.id')
        ->leftJoin('comments','comments.idTweet','=','tweets.id')
        ->join('users','users.id','=','tweets.idUser')
        ->leftJoin('extar_users','extar_users.idUser','=','users.id')
        ->leftJoin('retweets','retweets.idTweet','=','tweets.id')
        
        ->groupBy('tweets.idUser', 'tweets.id', 'description', 'image', 'video', 'tweets.created_at', 'name', 'pseudo', 'email','pp')
      
        ->get();
        foreach($tweets as $tweet){
            $tweet->liked = Like::where('idUser',Auth::user()->id)->where('idTweet',$tweet->id)->first() != null ? true : false;
        }
        
        
    
        return $this->success($tweets,'hashtags fetched with success');
    }
    

    public function Who_to_follow(){
        $user = Auth::user();
        $users =User::where('id' , '!=' , $user->id)->with('extra_user')->whereNotIn('id', $user->user_follower()->pluck('idFollower')->toArray())->inRandomOrder()->limit(20)->get();
        $usersToFollow = User::whereIn('id', $user->user_follower()->pluck('idFollower')->toArray())
                    ->where('id', '!=', $user->id)->with('extra_user')
                    ->inRandomOrder()
                    ->limit(10)
                    ->get();
                    
        $all_users = Arr::collapse([$users , $usersToFollow]);
        
        return $this->success(Users_To_Follow::collection($all_users) , 'users to follow') ;         
    }

    public function search($name)
    {
        $user = User::where('name' , 'like' , "%".$name."%")->with('extra_user')->get() ;

        if( count($user) > 0){
            return $this->success(Users_To_Follow::collection($user), 'search user');
        }

        return $this->error([] , 'user not found' , 403);

    }
}
