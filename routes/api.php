<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\TweetsController;
use App\Http\Controllers\SauvguardeController;
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
    Route::post('/tweets/createTweet',[TweetsController::class,'createTweet']);
    Route::post('/tweets/createImage',[TweetsController::class,'createImage']);
    Route::post('/tweets/createVideo',[TweetsController::class,'createVideo']);
    Route::put('/tweets/updateTweet/{id}',[TweetsController::class,'updateTweet']);
    Route::delete('/tweets/deleteTweet/{id}',[TweetsController::class,'deleteTweet']);
   
    Route::get('likedTweets/{slug}',[ProfilController::class,'likedTweets']);
    Route::get('bookmarks/{slug}' ,[ProfilController::class,'bookmarks'] );

    
    Route::post('/saveTweet/{idTweet}',[SauvguardeController::class,'saveTweet']);
    Route::post('/unsaveTweet/{idTweet}',[SauvguardeController::class,'unsaveTweet']);
    Route::post('/clearAllSaved',[SauvguardeController::class,'clearAllSaved']);

    
});
Route::post('/verifyEmail',[AuthController::class,'verifyEmail']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/forget',[AuthController::class,'forget']);
Route::post('/reset',[AuthController::class,'reset']);


Route::get('profile/{slug}',[ProfilController::class,'index']);
Route::get('tweets/{slug}',[ProfilController::class,'getTweets']);
Route::get('likes/{slug}',[ProfilController::class,'likedTweets']);
Route::get('follower/{slug}',[ProfilController::class,'follower']);
Route::get('following/{slug}',[ProfilController::class,'following']);





