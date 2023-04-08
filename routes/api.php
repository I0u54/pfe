<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('likes/{slug}',[ProfilController::class,'likes']);
});
Route::post('/verifyEmail',[AuthController::class,'verifyEmail']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/forget',[AuthController::class,'forget']);
Route::post('/reset',[AuthController::class,'reset']);


Route::get('profile/{slug}',[ProfilController::class,'index']);
Route::get('tweets/{slug}',[ProfilController::class,'getTweets']);
Route::get('follower/{slug}',[ProfilController::class,'follower']);
Route::get('following/{slug}',[ProfilController::class,'following']);

