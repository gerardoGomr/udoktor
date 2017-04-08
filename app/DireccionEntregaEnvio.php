<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class DireccionEntregaEnvio extends Model
{
    protected $table='deliveryaddress';
    protected $fillable=['recipientfullname','recipientcontactnumber1','streeUdoktor','street2','boroughid','city',
        'stateid','latitude','longitude','shippingrequestid','deliverydate','deliveryuntildate','place','elevator',
        'callbefore','deliverytime','deliverywithin','updated','cityid','municipalityid','deliverytimefrom',
        'deliverytimeuntil','generalubication','deliveryrandomubication','anotherplace'];
    public $timestamps = false;
}

