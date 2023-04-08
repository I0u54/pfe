<?php

namespace App\Http\Resources\profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Likes extends JsonResource
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
            
            'idTweet' => $this->like_tweet->id ,
            'originalUserId' => $this->idUser ,
            'description' =>$this->like_tweet->description ,
            'image' =>$this->like_tweet->image ,
            'video' =>$this->like_tweet->video ,
            'comments' =>$this->like_tweet->tweet_comment_count ,
            'likes' =>$this->like_tweet->tweet_comment_count ,
            'created_at' =>$this->like_tweet->created_at->diffForHumans(),
            'idUserTweet' => $this->like_tweet->tweet_user->id ,
            'name' => $this->like_tweet->tweet_user->name ,
            'email' => $this->like_tweet->tweet_user->email ,
            'pseudo' => $this->like_tweet->tweet_user->pseudo ,
            'imageProfile' =>$this->like_tweet->tweet_user->extra_user->pp ?? null  ,
            
        ];
    }
}
