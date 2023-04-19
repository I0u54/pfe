<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExtraUserData;
use App\Models\Extar_user;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller

{
    use HttpResponses;
    public function insertExtraUserData(StoreExtraUserData $request){
        $request->validated($request->all());
        if(Extar_user::where('idUser',Auth::user()->id)->first()){
            return $this->error([],'message user already have extra data !',403);
        }
        $pp = $request->pp != null ? $request->pp : null;
        $ppName = $pp != null ? $pp->hashName() : null;
        $pp != null && Storage::putFileAs('public/images',$pp,$ppName) ;
        $cover = $request->cover != null ? $request->cover : null;
        $coverName = $cover != null ? $cover->hashName() : null;
        $cover != null && Storage::putFileAs('public/images',$cover,$coverName) ;
        Extar_user::create([
            'idUser'=>Auth::user()->id,
            'bio'=>$request->bio != null ? $request->bio :null,
            'adresse'=>$request->adresse != null ? $request->adresse:null,
            'pp'=>$ppName != null ? asset('images/'.$ppName) : null,
            'cover'=>$coverName != null ? asset('images/'.$coverName) : null,

        ]);
        return $this->success([],'data inserted with success !');

    }
}
