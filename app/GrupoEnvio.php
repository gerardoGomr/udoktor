<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class GrupoEnvio extends Model
{
    protected $table='shippingrequestgroup';
    protected $fillable=['shippingrequestid','groupid','updated'];
    public $timestamps = false;

}


