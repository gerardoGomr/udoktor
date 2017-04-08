<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class ArticuloEnvioCliente extends Model
{
    protected $table='shippingitem';
    protected $fillable=['description','perishable','livingbeing','shippingitemcategoryid','shippingrequestid',
        'collectionaddressid','deliveryaddressid','quantity','dangerous','stackble','long','high','width',
        'unitdimensions','weight','unitweight','comments','updated'];
    public $timestamps = false;
}
