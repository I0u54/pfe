<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailRequest;
use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StorePasswordReset;
use App\Http\Requests\StoreUserRequest;
use App\Mail\verifyEmail;
use App\Models\EmailVerification;
use App\Models\Extar_user;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Str;

use Socialite;
use Illuminate\Support\Carbon ;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use HttpResponses;
    public function verifyEmail(StoreEmailRequest $request)
    {
        $request->validated($request->all());
        $token = rand(100000, 999999);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return $this->error([], 'user already exist', 403);
        }

        if (!EmailVerification::where('email', '=', $request->email)->first()) {

            EmailVerification::create([
                'email' => $request->email,
                'token' => $token
            ]);
        } else {
            EmailVerification::where('email', $request->email)->update([
                'token' => $token

            ]);
        }
        Mail::to($request->email)->send(new verifyEmail($token));


        return $this->success([], 'token has been sent to your email');
    }

    public function verifyToken($email , $token) 
    {
        $token_verify =EmailVerification::where('email' , $email )->first() ;

        if($token == $token_verify->token) :

            return $this->success([] , 'token has been verify');
        endif;
        
        return $this->error([] , 'token mismatch or user already registred' , 403);

    }

    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());
        $data = User::create([
            'name' => $request->name,
            'birthDay' => $request->birthDay,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            
        ]);
        
        $data->pseudo='@'.Str::slug($data->name).$data->id;
        $data->save();
        $data = User::where('id', $data->id)->first();
        EmailVerification::where('email', $request->email)->delete();
        Extar_user::create([
            'idUser'=>$data->id,
            'bio'=>null,
            'pp'=>null,
            'cover'=>null
        ]);

        return $this->success([
            'user' => $data,
            'token' => $data->createToken('API token for' . $data->name)->plainTextToken
        ], 'user stored with success');
    }
    public function login(StoreLoginRequest $request)
    {
        $request->validated($request->all());
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return $this->success([
                    'user' => $user,
                    'token' => $user->createToken('API token for' . $user->name)->plainTextToken
                ], 'user logged in with success');
            } else {
                return $this->error([], 'data mismatch', 403);
            }
        } else {
            return $this->error([], 'data mismatch', 403);
        }
    }
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->success([], 'you have successfully logged out ');
    }
    public function forget(StoreEmailRequest $request)
    {
        $request->validated($request->all());
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->error([], 'user not found ', 404);
        }
        $token = rand(100000, 999999);

        if (PasswordResetToken::where('email', $request->email)->first()) {
            PasswordResetToken::where('email', $request->email)->update([
                'token' => $token
            ]);
        } else {
            PasswordResetToken::create([
                'email' => $request->email,
                'token' => $token
            ]);
        }
        Mail::to($request->email)->send(new verifyEmail($token));
        return $this->success([], 'token has been sent to your email');
    }
    public function reset(StorePasswordReset $request)
    {
        $request->validated($request->all());
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->error([], 'user not found ', 404);
        }
        $token = PasswordResetToken::where('email', $request->email)->first();
        if (!$token || $token->token != $request->token) {
            return $this->error([], 'token mismatch', 404);
        }

        if ($request->password == $request->passwordConfirmation) {
            $user->fill([
                'password' => Hash::make($request->password)
            ]);
            $user->save();
            $user->tokens()->delete();
            PasswordResetToken::where('email', $request->email)->delete();
            return $this->success([
                'user' => $user,
                'token' => $user->createToken('API token for' . $user->name)->plainTextToken
            ], 'password have been changed');
        } else {
            return $this->error([], 'password do not match', 403);
        }
    }

    public function redirectToprovider($social){
        
        $success['url'] = Socialite::driver($social)->stateless()->redirect()->getTargetUrl();
       
        return $this->success($success ,'successfuly' ) ;
    }

    public function callback($social) {
        $data = Socialite::driver($social)->stateless()->user();
        $user = User::where('email' , $data->email)->first();
        $name = $data->getName();
        $email = $data->getEmail();
        $avatar = $data->getAvatar();
        $birthday = $data->getBirthday();

        if(!$user){
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make($email),
                'birthDay' => $birthday ?? null 
            ]);
            $user->pseudo='@'.Str::slug($user->name).$user->id;
            $user->save();
            Extar_user::create([
                'idUser'=>$user->id,
                'bio'=>null,
                'pp'=>$avatar ?? null,
                'cover'=>null
            ]);
        }
        $success['token'] = $user->createToken('API token for' . $name)->plainTextToken;
        $success['user' ]= $user ;
        return $this->success($success , 'successfuly') ;
    }
}
