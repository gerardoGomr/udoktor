<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    protected $table='vehicletype';
    protected $fillable=['name','active','updated','maximumweight','chargecapacity','width','high','vlong','capacitance'];
    public $timestamps = false;
}
