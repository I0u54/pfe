<?php

namespace App\Http\Controllers;

use App\Http\Resources\Home as RcHome ;
use App\Models\Tweet;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Models\User ;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Database\Eloquent\Builder;

class HomeController extends Controller
{
    use HttpResponses ;
    public function getAllTweets()
    {
        $user_followers_tweets =  User::where('id' , Auth::user()->id)->with(['user_follower' => function (Builder $query){
            $query->with(['follower'=> function (Builder $query) {
                $query->with('user_tweets.tweet_user.extra_user') ;
            }]);

        }])->first();

        $user_followers_retweets=  User::where('id' , Auth::user()->id)->with(['user_follower' => function (Builder $query){
            $query->with(['follower'=> function (Builder $query) {
                $query->with('user_retweets.retweet_user.extra_user')->with('user_retweets.tweet_retweet.tweet_user.extra_user') ;
            }]);

        }])->first();

        $followers_tweets =  Arr::pluck($user_followers_tweets->user_follower, ['follower']);
        $followers_retweets =  Arr::pluck($user_followers_retweets->user_follower, ['follower']);

       
        $getTweets =  Arr::pluck($followers_tweets, ['user_tweets']);
        $getRetweets =  Arr::pluck($followers_retweets, ['user_retweets']);

        
        $tweets = Arr::collapse($getTweets);
        $retweets = Arr::collapse($getRetweets);

        $id_user_tweet =  Arr::pluck($tweets, ['idUser']);

        $tweets_other_user=  Tweet::whereNotIn('idUser' , array_unique($id_user_tweet))->with('tweet_user.extra_user')->orderBy('created_at' , 'desc')->get();

    

        return $this->success(new RcHome( $tweets , $retweets , $tweets_other_user  ) , 'get all tweet ') ;

    }

    public function Who_to_follow(){
        return 1 ;
    }
}
