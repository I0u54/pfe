<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\StoreTweetRequest;
use App\Models\Tweet;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class TweetsController extends Controller
{
    use HttpResponses;
    public function createTweet(StoreTweetRequest $request){
        $request->validated($request->all());
        Tweet::create([
            'idUser'=>Auth::user()->id,
            'description'=>$request->description,
        ]);
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
        return $this->success([],'tweet has been created');
        
    }
    public function createVideo(Request $request){
        $this->validate($request,[
            'video'=>'required',
          
        ]);
        $video = $request->video;
        $videoName = $video->hashName();
        Storage::putFileAs('public/videos',$video,$videoName);
        Tweet::create([
            'idUser'=>Auth::user()->id,
            'description'=>$request->description == null ? '':$request->description,
            'video'=>asset('videos/'.$videoName)
            
            
        ]);
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
}