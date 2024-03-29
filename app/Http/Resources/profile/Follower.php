<?php


namespace App\Http\Resources\profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Follower extends JsonResource
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
                'idUser' => $this->follower->id ,
                'name' => $this->follower->name ,
                'pseudo'=> $this->follower->pseudo  ,
                'image' =>  $this->follower->extra_user->pp ?? null  ,
    
            ];   

        
    }
}
