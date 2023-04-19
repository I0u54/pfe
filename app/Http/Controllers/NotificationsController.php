<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Resources\Notifications as RcNotification ;

class NotificationsController extends Controller
{

    use HttpResponses ;

    public function getCountNotifications()
    {
        $count_Not = Auth::user()->unreadNotifications->count() ;

       return $this->success([
            'count_notify' => $count_Not
        ] , "get count notification");
    }

    public function getAllNotifications()
    {
        $all_Not = Auth::user()->unreadNotifications;

        if(count($all_Not) > 0 ) : 
           
            return $this->success(RcNotification::collection($all_Not) , "get all notifications");

        endif ;  
        
        return $this->success([], "There are no notifications");

        
    }
}
