<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'password', 'age', 'position', 'responsible_area', 'resident_institution', 'open_id', 'weixin_session_key', 'entity_name', 'reg_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function situation()
    {
        return $this->belongsToMany('App\Models\Matter', 'user_has_matters', 'user_id', 'matter_id')->withPivot('see_image', 'see_images', 'information', 'status');
    }

    public function patrolMatters()
    {
        return $this->hasMany(PatrolMatter::class);
    }

    public function patrols()
    {
        return $this->hasMany(Patrol::class);
    }

    public function alarm()
    {
        return $this->belongsToMany('App\Models\Alarm', 'alarm_users', 'user_id', 'alarm_id')->withPivot('see_image', 'information', 'status');
    }
}
