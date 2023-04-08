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
            'comments' =>$this->save_tweets->tweet_comment_count ,
            'likes' =>$this->save_tweets->tweet_comment_count ,
            'created_at' =>$this->save_tweets->created_at->diffForHumans(),
            'idUserTweet' => $this->save_tweets->tweet_user->id ,
            'name' => $this->save_tweets->tweet_user->name ,
            'email' => $this->save_tweets->tweet_user->email ,
            'pseudo' => $this->save_tweets->tweet_user->pseudo ,
            'imageProfile' =>$this->save_tweets->tweet_user->extra_user->pp ?? null  ,

        ];
    }
}
