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
            'tweet_id' => $this->tweet_like->id ,
            'description' =>$this->tweet_like->description ,
            'image' =>$this->tweet_like->image ,
            'video' =>$this->tweet_like->video ,
            'created_at' =>$this->tweet_like->created_at->diffForHumans(),
            'user' => [
                'id_user' => $this->tweet_like->tweet_user->id ,
                'name' => $this->tweet_like->tweet_user->name ,
                'email' => $this->tweet_like->tweet_user->email ,
                'pseudo' => $this->tweet_like->tweet_user->pseudo ,
            ]
        ];
    }
}
