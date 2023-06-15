<?php

namespace App\Http\Controllers;

use App\Models\Extar_user;
use Illuminate\Http\Request;
use App\Models\User ;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    use HttpResponses ;
    public function editProfile(Request $request) 
    {
        $validate =Validator::make($request->all() ,[
                'pp'=>'image|mimes:png,jpg,jpeg',
                'cover'=>'image|mimes:png,jpg,jpeg',
                'name'=>'required',
                'birthDay'=>'required|date',
        ]) ;
        if($validate->fails()){
           return $this->error( $validate->errors() , 'Verify inputs' , 404);
        };

        $pseudo = Auth::user()->pseudo ;
        $user = Extar_user::where('idUser' , Auth::user()->id);
        $this->UpdateImg($request , $user , $pseudo);

        $user->update([
            'bio' => $request->bio ?? null , 
            'adresse' => $request->adresse ?? null ,
        ]);

        User::where('id' , Auth::user()->id)->update([
            'name' => $request->name ,
            'birthDay' => $request->birthDay ,
        ]);

        return $this->success([] , 'update profile successfuly');
            
    }

    public function UpdateImg($request, $user  , $pseudo)
    {
        
        
        if($request->hasFile('pp')){
            $img = $request->file('pp');
            $pp = $img->hashName();
            Storage::putFileAs("public/images/{$pseudo}/pp/",$img,$pp);
            $user->update([
                'pp' => asset("images/{$pseudo}/pp/".$pp) ,
            ]);
        }
        if($request->hasFile('cover')){
            $img = $request->file('cover');
            $cover = $img->hashName();
            Storage::putFileAs("public/images/{$pseudo}/cover/",$img,$cover);
            $user->update([
                'cover' => asset("images/{$pseudo}/cover/".$cover) ,
            ]);
        }

    }

    public function updateInfo( Request $request )
    {
        $user =  User::where('id' , Auth::user()->id);
        $validate = Validator::make($request->all() , [
            'email'=> ['unique:users' , 'email'] , 
            'pseudo' => ['unique:users'] ,
            'password' =>  ['required']
        ]);

        if($validate->fails()){
            return $this->error('verify input' , $validate->errors() , 403);
        }
        if($request->email) {

            $user->update([
                'email' => $request->email
            ]);

            return $this->success([] , 'email update');
           
        }
        if($request->pseudo) {

            $user->update([
                'pseudo' => $request->pseudo
            ]);

            return $this->success([] , 'pseudo update');
           
        }

        if($request->password) {

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return $this->success([] , 'password update');
           
        }

        return $this->error([] , 'update : email , password , pseudo' , 403);

        
    }
}
