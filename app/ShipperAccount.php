<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class ShipperAccount extends Model
{
    protected $table='shipperaccount';
    protected $fillable=['balance','createdat','currencyid','shipperid','updated','available'];
    public $timestamps = false;
}
