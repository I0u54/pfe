<?php

namespace App\Http\Resources\profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Retweets extends JsonResource
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
            'id_retweet' => $this->id ,
            'created_at' =>$this->created_at->diffForHumans() ,
            'tweet_retweet' => [
                'id_tweet' => $this->tweet_retweet->id ,
                'image' => $this->tweet_retweet->image ,
                'video' => $this->tweet_retweet->video ,
                'description' => $this->tweet_retweet->description ,
                'created_at' =>$this->tweet_retweet->created_at->diffForHumans() ,
                'tweet_user' => [
                    'id_user' => $this->tweet_retweet->tweet_user->id ,
                    'name' => $this->tweet_retweet->tweet_user->name ,
                    'pseudo' => $this->tweet_retweet->tweet_user->pseudo ,
                ]      
            ]
        ];
    }
}
