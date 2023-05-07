<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Users_To_Follow extends JsonResource
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

            'idUser' => $this->id ,
            'name' => $this->name ,
            'pseudo'=> $this->pseudo  ,
            'image' =>  $this->extra_user->pp ?? null  ,

        ];
    }
}
