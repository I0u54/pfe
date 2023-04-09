<?php

namespace App\Http\Controllers;

use App\Models\Sauvguarde;
use App\Models\Tweet;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class SauvguardeController extends Controller
{
    use HttpResponses;

    public function saveTweet($idTweet)
    {

        $save_tweet = Sauvguarde::where('idTweet' , $idTweet)->first() ;
        $tweet = Tweet::find($idTweet) ;

        if($tweet) :
            if(is_null($save_tweet)):

                Sauvguarde::create([
                     'idTweet' => $idTweet ,
                     'idUser' => Auth::user()->id 
                 ]);
         
                 return $this->success([],'tweet has been saved');
     
             endif ; 

             return $this->success([],'tweet already saved');

        endif ;  
        
        return $this->error([],"tweet don't exist",404);

    }

    public function unsaveTweet($idTweet)
    {

        $save_tweet = Sauvguarde::where('idTweet' , $idTweet)->first() ;
        $tweet = Tweet::find($idTweet) ;

        if($tweet) :
            if(is_null($save_tweet)):
         
                 return $this->error([],"tweet tweet don't exist in  bookmars ",404);
     
             endif ;
             
             $save_tweet->delete() ;

             return $this->success([],'tweet has been remove from bookmars');

        endif ;  
        
        return $this->error([],"tweet don't exist",404);

    }

    public function clearAllSaved()
    {

        $bookmars = Sauvguarde::truncate() ;

        return $this->success([],"clear all bookmars ");

    }
  
}
