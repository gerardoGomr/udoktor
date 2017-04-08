<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table='vehicle';
    protected $fillable=['shipperid','description','active','updated','deleted','vehicletypeid','plate','driverid'];
    public $timestamps = false;
}
