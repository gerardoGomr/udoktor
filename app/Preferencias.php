<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Preferencias extends Model
{
    protected $table='preferences';
    protected $fillable=['personid','administrativeunitid'];
    public $timestamps = false;
}
