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

         return $this->success([],'You commented successfully',201);
    }


    public function updateComment(Request $request,$id){
        if(!Comment::where('idComment',$id)->where('idUser',Auth::user()->id)->first()){
            return $this->error([],'comment not found',404);
        }

        $validator = Validator::make($request->all(), [
            'body' => 'required',
  
        ]);
        if ($validator->fails()) {
            return $this->error( $validator->errors() , 'Verify inputs' , 404);
        }
        Comment::where('idComment',$id)->where('idUser',Auth::user()->id)->update([
            'body'=>$request->body,
        ]);

        return $this->success([],'comment updated successfully',201);
    }

    public function destroyComment($id){
        if(!Comment::where('idComment',$id)->where('idUser',Auth::user()->id)->first()){
            return $this->error([],'comment not found',404);
        }
        Comment::where('idComment',$id)->where('idUser',Auth::user()->id)->delete();
            return $this->success([] ,'the Comment has been deleted',200);
          
    }
}
