<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Replie;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RepliesController extends Controller
{
    use HttpResponses;
    public function createReply(Request $request,$id){

        if(!Comment::where('idComment',$id)->first()){
            return $this->error([],'comment not found',404);
        }

        $validator = Validator::make($request->all(), [
            'replyBody' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error( $validator->errors() , 'Verify inputs' , 404);
        }
        
        Replie::create([
            'idReplier'=>Auth::user()->id,
            'idComment'=>$id,
            'replyBody'=>$request->replyBody,

        ]);
        return $this->success([],'You replied successfully',201);
    }

    public function updateReply(Request $request,$id){

        if(!Replie::where('id',$id)->where('idReplier',Auth::user()->id)->first()){
            return $this->error([],'replie not found',404);
        }

        $validator = Validator::make($request->all(), [
            'replyBody' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error( $validator->errors() , 'Verify inputs' , 404);
        }

        

        Replie::where('id',$id)->where('idReplier',Auth::user()->id)->update([
            'replyBody'=>$request->replyBody
        ]);
        return $this->success([],'Reply updated successfully',201);


    }

    public function destroyReply($id){
        if(!Replie::where('id',$id)->where('idReplier',Auth::user()->id)->first()){
            return $this->error([],'reply not found',404);
        }
        Replie::where('id',$id)->where('idReplier',Auth::user()->id)->delete();
            return $this->success([] ,'the reply has been deleted',200);
          
    }
    // get replies for a comment
    public function getReplies($id){
        if(!Comment::where('idComment',$id)->first()){
            return $this->error([],'comment not found',404);
        }
        $replies = Replie::where('idComment',$id)
        ->join('users','users.id','=','replies.idReplier')
        ->leftJoin('extar_users','extar_users.idUser','=','users.id')
        ->select('replyBody','replies.created_at','pp','pseudo','name','email','replies.id')
        ->orderBy('replies.created_at','desc')
        ->get();
        return $this->success($replies,'replies fetched with success');
    }


    }
    

