<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;


     public function sendPasswordResetNotification($token)
    {
 $this->notify(new ResetPassword($token)); 
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'level', 'password', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function medias()
    {
        return $this->morphMany('App\Media', 'entity');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    static function getLogin($email, $password){
        $user = parent::where('email',$email ) ->where('level', 3)->first();

        if( $user ){
            if(!$user->password ){
                return ['message' => 'incomplete'];
            }
    
            if(password_verify ( $password , $user->password ) ){
                return $user;
            }
        }

        return ['message' => 'notfound'];

    }
    public function imgs_compress_path()
    {
        return $this->hasMany(ImgCompressPath::class, 'user_id');
    }
}
