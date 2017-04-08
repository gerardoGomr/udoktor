<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class V_personDiary extends Model
{
    protected $table='persondiary';
    protected $fillable=['personid','start','vend','vlimit','updated','secondsstart','secondsend'];
    public $timestamps = false;
}