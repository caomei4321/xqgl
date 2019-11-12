<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class ProgramUser extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected  $table = 'mini_program_users';

    protected $guard_name = 'programApi';

    protected  $fillable = [
        'open_id', 'weixin_session_key', 'integral', 'nickname', 'avatarurl'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function matters()
    {
        return $this->hasMany(Matter::class, 'program_user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

}
