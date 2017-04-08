<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class ShipperPromotion extends Model
{
    protected $table='shipperpromotion';
    protected $fillable=['shipperid','promotionid','updated'];
    public $timestamps = false;
}
