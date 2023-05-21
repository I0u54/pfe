<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birthDay',
        'pseudo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function extra_user()
    {
        return $this->hasOne(Extar_user::class , 'idUser');
    }

    public function user_follower()
    {
        return $this->hasMany(Follow::class , 'idFollowing' )->orderBy('created_at' , 'asc');
    }

    public function user_following()
    {
        return $this->hasMany(Follow::class , 'idFollower')->orderBy('created_at' , 'asc');
    }

    public function like()
    {
        return $this->hasMany(Like::class , 'idUser')->orderBy('created_at' , 'asc');
    }

   

    public function bookmarks()
    {
        return $this->hasMany(Sauvguarde::class , 'idUser')->orderBy('created_at' , 'desc');
    }


    public function user_tweets()
    {
        return $this->hasMany(Tweet::class , 'idUser')->orderBy('created_at' , 'desc');
    }

    public function user_retweets()
    {
        return $this->hasMany(Retweet::class , 'idUser')->orderBy('created_at' , 'desc');
    }
    
    
    // non utulisee
    public function comment()
    {
        return $this->hasMany(comment::class , 'idFollowing' )->orderBy('created_at' , 'asc');
    }

}
