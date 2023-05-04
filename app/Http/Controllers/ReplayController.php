<?php

namespace App\Http\Controllers;


use App\Models\Replay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReplayController extends Controller
{
    public function createReplayComment($idComment , Request $request){

        if(!Replay::where('id',$idComment)->first()){
            return $this->error([],'tweet not found',404);
        }

        $validator = Validator::make($request->all(), [
            'replyBody' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error( $validator->errors() , 'Verify inputs' , 404);
        }
        
        $replay=Replay::create([
            'idReplier'=>Auth::user()->id,
            'idComment'=>$idComment,
            'replyBody'=>$request->body,

        ]);
        return $this->success($replay,'You replayed successfully',201);
    }

    public function updateReplayComment($idComment , Request $request){

        if(!Replay::where('id',$idComment)->first()){
            return $this->error([],'tweet not found',404);
        }

        $validator = Validator::make($request->all(), [
            'replyBody' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error( $validator->errors() , 'Verify inputs' , 404);
        }

        $replay=Replay::find($idComment);
        if(!$replay){
            return $this->error( $validator->errors() , 'not found' , 404);
        }

        $replay->update($request->all());
        return $this->success($replay,'update Replay comment successfully successfully',201);


    }

    public function destroyReplayComment($idComment){
        $replay=Replay::find($idComment);
        if(!$replay){
            return $this->error(null , 'not found' , 404);
        }

        $replay->delete($idComment);
        if($replay){
            return $this->success(null ,'the Replay  deleted',200);
          }

    }
    
}
