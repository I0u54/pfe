<?php

namespace App\Http\Resources\profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Bookmarks extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [

            'idTweet' => $this->save_tweets->id ,
            'originalUserId' => $this->idUser ,
            'description' =>$this->save_tweets->description ,
            'image' =>$this->save_tweets->image ,
            'video' =>$this->save_tweets->video ,
            'comment_count' =>$this->save_tweets->tweet_comment_count ,
            'retweet_count'=> $this->save_tweets->retweet_tweet_count ,
            'like_count' =>$this->save_tweets->tweet_like_count ,
            'created_at' =>$this->save_tweets->created_at,
            'like' => $this->save_tweets->liked_tweet ? true  : false ,
            'save' => $this->save_tweets->tweet_save ? true  : false ,
            'idUserTweet' => $this->save_tweets->tweet_user->id ,
            'name' => $this->save_tweets->tweet_user->name ,
            'email' => $this->save_tweets->tweet_user->email ,
            'pseudo' => $this->save_tweets->tweet_user->pseudo ,
            'pp' =>$this->save_tweets->tweet_user->extra_user->pp ?? null  ,

        ];
    }
}
