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
            'idLike' => $this->idLike ,
            'created_at' =>$this->created_at->diffForHumans() ,
            'tweet_like' => [
                'id_tweet' => $this->tweet_like->id ,
                'description' =>$this->tweet_like->description ,
                'image' =>$this->tweet_like->image ,
                'video' =>$this->tweet_like->video ,
                'created_at' =>$this->tweet_like->created_at->diffForHumans(),
                'tweet_user' => [
                    'id_user' => $this->tweet_like->tweet_user->id ,
                    'name' => $this->tweet_like->tweet_user->name ,
                    'pseudo' => $this->tweet_like->tweet_user->pseudo ,
    
                ]
            ] 
            
            
                

        ];
    }
}
