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
}
