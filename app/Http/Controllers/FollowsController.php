<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class FollowsController extends Controller
{
    use HttpResponses ;

    public function follow($idUser)
    {
        $user= User::find($idUser) ;
        $user_following = Follow::where('idFollowing' , $idUser)->where('idFollower' , Auth::user()->id)->first();


        if($user) :
            if(is_null($user_following)):

                follow::create([
                     'idFollower' => Auth::user()->id ,
                     'idFollowing' => $idUser
                 ]);
         
                 return $this->success([],"You follow {$user->name}");
     
             endif ; 

             return $this->success([],'follow already exist');

        endif ;  
        
        return $this->error([],"user don't exist",404);



        

    }



    public function unfollow($idUser)
    {
        $user= User::find($idUser) ;
        $user_following = Follow::where('idFollowing' , $idUser)->where('idFollower' , Auth::user()->id)->first();


        if($user) :
            if($user_following):

                $user_following->delete() ;
         
                 return $this->success([],"You unfollow {$user->name}");
     
             endif ; 

             return $this->success([],"you not follow {$user->name}");

        endif ;  
        
        return $this->error([],"user don't exist",404);


    }
}
