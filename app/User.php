<?php

namespace Udoktor;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password','resetpasswordtoken', 'confirmationtoken','personid','unlocktoken','resetpasswordsentat',
        'remembercreatedat','currentsigninat','lastsigninat','currentsigninip','lastsigninip','confirmedat',
        'confirmationsentat','lockedat','active','verifiedaccount','isverified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'resetpasswordtoken',
    ];
    
    
    public $timestamps = true;
    
    public function roles(){
        return $this->belongsToMany('Udoktor\Role','user_role','userid','roleid');
    }
    
    public function hasAnyRole($roles){
        if(is_array($roles)){
            foreach ($roles as $role){
                if($this->hasRole($role)){
                    return true;
                }
            }
        }else{
            if($this->hasRole($roles)){
                return true;
            }
        }
        return false;
    }
    
    public function hasRole($role){
        if($this->roles()->where('name',$role)->first()){
            return true;
        }
        return false;
    }
}
