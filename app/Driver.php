<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table='driver';
    protected $fillable=['personid','shipperid','updated','phone','license'];
    public $timestamps = false;
}
