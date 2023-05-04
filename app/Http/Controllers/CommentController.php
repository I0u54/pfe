<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Tweet;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    use HttpResponses ;
    public function CreateComment($idTweet  , Request $request){

        if(!Tweet::where('id',$idTweet)->first()){
            return $this->error([],'tweet not found',404);
        }

        $validator = Validator::make($request->all(), [
            'body' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error( $validator->errors() , 'Verify inputs' , 404);
        }

        $comment=Comment::create([
            'idTweet'=>$idTweet,
            'idUser'=>Auth::user()->id,
            'body'=>$request->body,

        ]);

         return $this->success($comment,'You commented successfully',201);
    }


    public function updateComment(Request $request,$id){
        if(!Tweet::where('id',$id)->first()){
            return $this->error([],'tweet not found',404);
        }

        $validator = Validator::make($request->all(), [
            'body' => 'required',
  
        ]);
        if ($validator->fails()) {
            return $this->error( $validator->errors() , 'Verify inputs' , 404);
        }

        $comment =Comment::find($id);
        if(!$comment){
            return $this->error( $validator->errors() , 'not found' , 404);
        }

        $comment->update($request->all());

        return $this->success($comment,'update successfully successfully',201);
    }

    public function destroyComment($id){
        $comment =Comment::find($id);
        if(!$comment){
            return $this->error(null , 'not found' , 404);
        }
        $comment->delete($id);
        if($comment){
            return $this->success(null ,'the Comment deleted',200);
          }
    }
}
