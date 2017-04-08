<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Ofertas extends Model
{
    protected $table='serviceoffer';
    protected $fillable=['shipperid','shippingrequestid','shipmentcost','currencyid','conditions',
        'collectionday','collectiondate','collectionuntildate','collectiontype','deliveryday','deliverydate',
        'deliveryuntildate','deliverytype','updated'];
    public $timestamps = false;
}