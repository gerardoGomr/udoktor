<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class EnvioClienteLog extends Model
{
    protected $table='shippingrequestlog';
    protected $fillable=['shippingrequestid','status','createdat','updated'];
    public $timestamps = false;
}
