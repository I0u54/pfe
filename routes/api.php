<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RetweetsController;
use App\Http\Controllers\TweetsController;
use App\Http\Controllers\SauvguardeController;
use App\Http\Controllers\FollowsController ;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\RepliesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageCotroller;

use App\Http\Controllers\TestController ;
use App\Http\Controllers\SettingsController ;

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

    Route::post('likeTweet/{id}',[LikesController::class,'like']);
    Route::post('disLikeTweet/{id}',[LikesController::class,'disLike']);
    
    Route::post('reTweet/{id}',[RetweetsController::class,'reTweet']);
    Route::post('removeReTweet/{id}',[RetweetsController::class,'removeReTweet']);

    Route::post('/saveTweet/{idTweet}',[SauvguardeController::class,'saveTweet']);
    Route::post('/unsaveTweet/{idTweet}',[SauvguardeController::class,'unsaveTweet']);
    Route::post('/clearAllSaved',[SauvguardeController::class,'clearAllSaved']);

    Route::post('/follow/{idUser}',[FollowsController::class,'follow']);
    Route::post('/unfollow/{idUser}',[FollowsController::class,'unfollow']);

    Route::get('/countNotification' , [NotificationsController::class , 'getCountNotifications']) ;
    Route::get('/notifications' , [NotificationsController::class , 'getAllNotifications']) ;
    Route::get('/readNotifications' , [NotificationsController::class , 'readAllNotifications']) ;

    //Home
    Route::get('/' , [HomeController::class , 'getAllTweets']) ;
    Route::get('/usersToFollow' , [HomeController::class , 'Who_to_follow']) ;
    Route::get('/search/{name}' , [HomeController::class , 'search']) ;
    
    
   //Message
    Route::post('message/{idReceiver}', [MessageCotroller::class, 'createConversation']);
    Route::get('conversations/{userId}', [MessageCotroller::class, 'getConversations']);

    Route::delete('messages/{messageId}', [MessageCotroller::class, 'deleteMessage']);
    Route::delete('conversations/{userId}', [MessageCotroller::class, 'deleteConversation']);
        

    Route::post('/editProfile',[SettingsController::class,'editProfile']);

    // Route::get('/test',[TestController::class,'test']);

    
    //Comments
    Route::post('comments/create/{idTweet}',[CommentController::class,'CreateComment']);
    Route::put('comments/update/{id}',[CommentController::class,'updateComment']);
    Route::delete('/comments/delete/{id}',[CommentController::class,'destroyComment']);

    //replyComment
    Route::post('replies/create/{idComment}',[RepliesController::class,'createReply']);
    Route::put('replies/update/{id}',[RepliesController::class,'updateReply']);
    Route::delete('replies/delete/{id}',[RepliesController::class,'destroyReply']);
    Route::get('replies/{idComment}',[RepliesController::class,'getReplies']);

    

    
});
Route::get('/trends/{hashtag}',[HomeController::class ,'getTweetsByHashtag']);
Route::get('/trends',[HomeController::class , 'getTrends']);
    
Route::post('/verifyEmail',[AuthController::class,'verifyEmail']);
Route::post('/verifyToken/{email}/{token}',[AuthController::class,'verifyToken']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/VerifyEmailBeforeLogin',[AuthController::class,'VerifyEmailBeforeLogin']);
Route::post('/VerifyEmailBeforeRegister',[AuthController::class,'VerifyEmailBeforeRegister']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/forget',[AuthController::class,'forget']);
Route::post('/reset',[AuthController::class,'reset']);
Route::get('/SocialLogin/{social}',[AuthController::class , 'redirectToprovider'] );
Route::get('/SocialLogin/{social}/callback', [AuthController::class , 'callback']);

Route::get('profile/{slug}',[ProfilController::class,'index']);
Route::get('tweets/{slug}',[ProfilController::class,'getTweets']);
Route::get('tweet/{id}',[TweetsController::class,'getTweet']);
Route::get('followers/{slug}',[ProfilController::class,'followers']);
Route::get('followings/{slug}',[ProfilController::class,'followings']);








