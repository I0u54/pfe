<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Notifications extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_notify' => $this->id ,
            'idUser' => $this->data['idUser'],
            'idTweet' => $this->data['idTweet'] ?? null ,
            'type' => substr($this->type ,18 ,  ) ,
            'name' => $this->data['name'] ,
            'pseudo' => $this->data['pseudo'] ,
            'pp' => $this->data['pp'] ?? null ,
            'created_at' =>$this->created_at,
        ];
    }
}
