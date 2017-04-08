<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class V_personservice extends Model
{
    protected $table='personservice';
    protected $fillable=['personid','serviceid','updated','cost'];
    public $timestamps = false;
}
