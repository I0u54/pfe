<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use App\Models\User ;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth ;

class TestController extends Controller
{
    use HttpResponses ;
    public function test()
    {
        // $user = Tweet::find(1)->with('tweet_user')->first()->only('tweet_user');
        // $user = User::find(4);
        // return $this->success($user , 'ok'); ;
 
    }
}
