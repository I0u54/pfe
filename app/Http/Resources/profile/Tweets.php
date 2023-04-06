<?php

namespace App\Http\Resources\profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Tweets extends JsonResource
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
            'id_tweet' => $this->id ,
            'image' => $this->image ,
            'video' => $this->video ,
            'description' => $this->description ,
            'created_at' =>$this->created_at->diffForHumans() ,

        ];
    }
}
