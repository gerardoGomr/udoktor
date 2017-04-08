<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class EnvioReservado extends Model
{
    protected $table='shipment';
    protected $fillable=['shippingrequestid','paymentmethodid','acceptedserviceofferid','shipmentcost','currencyid',
        'servicefee','servicefeeaspercentageofshipmentcost','financingcompanyid','paymentpromotionid','copyoftaxinformation',
        'copyofcreditcard','copyofshippertaxinformation','createdat','updated','clienthasfeedback','shipperhasfeedback'];
    public $timestamps = false;
}