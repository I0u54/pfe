<?php

namespace App\Http\Resources\profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Following extends JsonResource
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
            'idUser' => $this->following->id ,
            'name' => $this->following->name ,
            'email' => $this->following->email ,
            'pseudo'=> $this->following->pseudo  ,
            'image' =>  $this->following->extra_user->pp ?? null  ,
        
        ];
    }
}
