<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;
use Auth;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * [gravatar description]
     * 
     * @param  string $size [description]
     * @return [type]       [description]
     */
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    /**
     * [boot description]
     * 
     * @return [type] [description]
     */
    public static function boot(){

        parent::boot();

        static::creating(function($user){
            $user->activation_token = str_random(30);
        });
    }

    /**
     * [sendPasswordResetNotification description]
     * 
     * @param  [type] $token [description]
     * @return [type]        [description]
     */
    public function sendPasswordResetNotification($token){

        $this->notify(new ResetPassword($token));
    }

    /**
     * [statuses description]
     * 
     * @return [type] [description]
     */
    public function statuses(){
        
        return $this->hasMany(Status::class);
    }

    /**
     * [feed description]
     * 
     * @return [type] [description]
     */
    public function feed(){

        $user_ids = Auth::user()->followings->pluck('id')->toArray();

        array_push($user_ids,Auth::user()->id);

        return Status::whereIn('user_id',$user_ids)
                     ->with('user')
                     ->orderBy('created_at','desc'); 
    }

    /**
     * [followers description]
     * 
     * @return [type] [description]
     */
    public function followers(){

        return $this->belongsToMany(User::class,'followers','user_id','follower_id');
    }


    public function followings(){

        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }


    public function follow($user_ids){

        if(!is_array($user_ids)){

            $user_ids = compact('user_ids');
        }

        $this -> followings()->sync($user_ids,false);
    }



    public function unfollow($user_ids){

        if(!is_array($user_ids)){

            $user_ids = compact('user_ids');
        }
        $this ->followings()->detach($user_ids);
    }



    public function isFollowing($user_id){

        return $this->followings->contains($user_id);
    }


}
