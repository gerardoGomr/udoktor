<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class ShipperAccountDetail extends Model
{
    protected $table='shipperaccountdetail';
    protected $fillable=['shipperid','amount','created','carriercreditid','promotionid','shippingrequestid'];
    public $timestamps = false;
}
