<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use App\Notifications\follow as NotificationsFollow;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;


class FollowsController extends Controller
{
    use HttpResponses ;

    public function follow($idUser)
    {
        $user= User::find($idUser) ;
        $id_follower = Auth::user()->id ;
        $user_following = Follow::where('idFollowing' , $idUser)->where('idFollower' , $id_follower)->first();

        if($user) :
            if(is_null($user_following)):

                follow::create([
                     'idFollower' => $id_follower ,
                     'idFollowing' => $idUser
                 ]);

                 //Notification for follows

                 $user_follower = User::where('id' , $id_follower)->with('extra_user')->first();

                 $user->notify(new NotificationsFollow($user_follower)) ;

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
