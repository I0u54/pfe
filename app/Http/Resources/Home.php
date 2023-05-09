<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class Home extends JsonResource
{
    private $tweets = [] ;
    private $retweets = [] ;
    private $allTweets = [] ;
    private $tweets_other_user = [] ;
    private $finalData  ;
    public function __construct($tweets , $retweets , $tweets_other_user) {
        // Ensure we call the parent constructor
         if(!empty($tweets)){
            foreach($tweets as $tweet){
                array_push($this->tweets , [
                    'idTweet' => $tweet->id ,
                    'idUser' => $tweet->idUser ,
                    'image' => $tweet->image ,
                    'video' => $tweet->video ,
                    'type' => 'tweet' ,
                    'name' => $tweet->tweet_user->name ,
                    'pseudo' =>$tweet->tweet_user->pseudo ,
                    'pp' => $tweet->tweet_user->extra_user->pp ?? null ,
                    'created_at' => $tweet->created_at
    
                ]);
        
            }
         }
         

       if(!empty($retweets)){
        foreach($retweets as $tweet){
            array_push($this->retweets , [
                'idTweet' => $tweet->id ,
                'idUser' => $tweet->idUser ,
                'image' => $tweet->tweet_retweet->image ,
                'video' => $tweet->tweet_retweet->video ,
                'description' => $tweet->tweet_retweet->description ,
                'type' => 'retweet' ,
                'name' => $tweet->retweet_user->name ,
                'pseudo' =>$tweet->retweet_user->pseudo ,
                'pp' => $tweet->tweet_user->extra_user->pp ?? null ,
                'originalUserId' =>$tweet->tweet_retweet->tweet_user->id ,
                'originalUserName' =>$tweet->tweet_retweet->tweet_user->name ,
                'originalUserPseudo' => $tweet->tweet_retweet->tweet_user->pseudo ,
                'originalUserPP' =>  $tweet->tweet_retweet->tweet_user->extra_user->pp ?? null ,
                'created_at' => $tweet->created_at         

            ]);
        };
       }

       if(!empty($tweets_other_user)){
        foreach($tweets_other_user as $tweet){
            array_push($this->tweets_other_user , [
                'idTweet' => $tweet->id ,
                'idUser' => $tweet->idUser ,
                'image' => $tweet->image ,
                'video' => $tweet->video ,
                'type' => 'tweet' ,
                'name' => $tweet->tweet_user->name ,
                'pseudo' =>$tweet->tweet_user->pseudo ,
                'pp' => $tweet->tweet_user->extra_user->pp ?? null ,
                'created_at' => $tweet->created_at

            ]);
        };
       }

        $this->finalData = collect(Arr::collapse([$this->tweets , $this->retweets]))->sortByDesc('created_at')->all();

        $this->allTweets = Arr::collapse([$this->finalData , $this->tweets_other_user]);
    }
    public function toArray(Request $request): array
    {

        return $this->allTweets;
    }
}
