<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class RolUsuario extends Model
{
    protected $table='user_role';
    protected $fillable=['created_at','updated_at','userid','roleid'];
    public $timestamps = false;
}
