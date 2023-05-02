<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'body'=>$this->body,
            'idUser'=>$this->id_user,
            'idTweet'=>$this->id_post,
            // 'reply_comments' => ReplayCommpentResource::collection($this->replyComments),
        ];

    }
}
