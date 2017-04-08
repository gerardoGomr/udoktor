<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users(){
        return $this->belongsToMany('Udoktor\User','user_role','roleid','userid');
    }
}
