<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\StoreTweetRequest;
use App\Models\Tweet;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User ;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Arr;
use App\Notifications\Tweet as NotificationsTweet;

class TweetsController extends Controller
{
    use HttpResponses;
    public function createTweet(StoreTweetRequest $request){
        $request->validated($request->all());
        Tweet::create([
            'idUser'=>Auth::user()->id,
            'description'=>$request->description,
        ]);

         // Notifications for tweets 

         $this->tweetNotifications();

        return $this->success([],'tweet has been created');
        
    }
    public function createImage(Request $request){
        $this->validate($request,[
            'image'=>'image|required|mimes:png,jpg,jpeg',
          
        ]);
        $image = $request->image;
        $imageName = $image->hashName();
        Storage::putFileAs('public/images',$image,$imageName);
        Tweet::create([
            'idUser'=>Auth::user()->id,
            'description'=>$request->description == null ? '':$request->description,
            'image'=>asset('images/'.$imageName)
            
            
        ]);

        // Notifications for tweets 

        $this->tweetNotifications();

        return $this->success([],'tweet has been created');
        
    }
    public function createVideo(Request $request){
        $this->validate($request,[
            'video' => 'required|mimes:mp4,avi,wmv|max:50000',
          
        ]);
        $video = $request->video;
        $videoName = $video->hashName();
        Storage::putFileAs('public/videos',$video,$videoName);
        Tweet::create([
            'idUser'=>Auth::user()->id,
            'description'=>$request->description == null ? '':$request->description,
            'video'=>asset('videos/'.$videoName)
            
            
        ]);
        // Notifications for tweets 

        $this->tweetNotifications();
        
        return $this->success([],'tweet has been created');
        
    }
    public function updateTweet($id,StoreTweetRequest $request){
        if(!Tweet::where('id',$id)->where('idUser',Auth::user()->id)->first()){
            return $this->error([],'tweet do not exist',404);
        }
        Tweet::where('id',$id)->where('idUser',Auth::user()->id)->update([
            'description'=>$request->description
        ]);
        return $this->success([],'tweet has been updated');
    }
    public function deleteTweet($id){
        if(!Tweet::where('id',$id)->where('idUser',Auth::user()->id)->first()){
            return $this->error([],'tweet do not exist',404);
        }
        Tweet::where('id',$id)->where('idUser',Auth::user()->id)->delete();
        return $this->success([],'tweet has been deleted');

    }

    public function getTweet($id){
        $tweet = Tweet::where('tweets.id',$id)
        ->select('tweets.idUser','tweets.id','description','image','video','tweets.created_at','name','pseudo','email',DB::raw('count(likes.idLike) as likes'),DB::raw('count(comments.idComment) as comments'),'pp')
        ->leftJoin('likes','likes.idTweet','=','tweets.id')
        ->leftJoin('comments','comments.idTweet','=','tweets.id')
        ->join('users','users.id','=','tweets.idUser')
        ->leftJoin('extar_users','extar_users.idUser','=','users.id')
        ->groupBy('tweets.idUser', 'tweets.id', 'description', 'image', 'video', 'tweets.created_at', 'name', 'pseudo', 'email','pp')
      
        ->first();
        // waiting for soufiane to push comments controller
        $comments = [];
        return $this->success([
            'tweet'=>$tweet,
            'comments' =>$comments
        ]);
    }

    public function tweetNotifications() 
    {

        $user_tweet = User::where('id' , Auth::user()->id)->with('extra_user')->first();

        $user_followers =  User::where('id' , Auth::user()->id)->with('user_follower.follower')->first();

        $followers =  Arr::pluck($user_followers->user_follower, ['follower']);
                
        Notification::send($followers , new NotificationsTweet($user_tweet)) ;

    }
}
