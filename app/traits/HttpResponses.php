<?php
namespace App\Traits;
trait HttpResponses
{
    protected function success($data,$message=null,$code=200){
        return response()->json(
            [
                "data"=>$data,
                'message'=>$message
            ]
            );


    }
    protected function error($data,$message=null,$code){
        return response()->json(
            [
                "data"=>$data,
                'message'=>$message
            ],$code
            );


    }

}