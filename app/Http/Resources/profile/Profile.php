<?php

namespace App\Http\Resources\profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $following = [] ;

        $follower = [] ;

        if ($this->follows !== null) {
            foreach ($this->follows as $follow) {
                array_push($following , [
                        'idUser' => $follow->following->id ,
                        'name' => $follow->following->name ,
                        'email' => $follow->following->email ,
                        'pseudo'=> $follow->following->pseudo   
    
                ]);
            }
        }

        if ($this->follows !== null) {
            foreach ($this->follows as $follow) {
                array_push($follower , [
                        'idUser' => $follow->follower->id ,
                        'name' => $follow->follower->name ,
                        'email' => $follow->follower->email ,
                        'pseudo'=> $follow->follower->pseudo   
    
                ]);
            };
        }
  


        return [
            'id' => $this->id , 
            'name' => $this->name ,
            'email' => $this->email ,
            'pseaudo' => $this->pseudo ,
            'birthDay' => $this->birthDay ,
            'created_at' => $this->created_at->diffForHumans() ,
            'adresse' => $this->extra_user->adresse ?? '' ,
            'pp'      => $this->extra_user->pp ?? '' ,
            'cover'   => $this->extra_user->cover ?? '' , 
            'bio'  =>  $this->extra_user->bio ?? '' , 
            'following' => $following   ,
            'follower' => $follower ,
            'nb_following' => count($following) ,
            'nb_follower' => count($follower) ,

        ];
    }
}
