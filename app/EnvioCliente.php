<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class EnvioCliente extends Model
{
    protected $table='shippingrequest';
    protected $fillable=['requesterid','deliverydaterequired','deliverytimerequired','createdat','coldprotection',
        'sortout','blindperson','costtype','cost','firstofferdiscount','discountrate','title','updated','totalprice',
        'expirationdate','ispublic','paymentmethodid','paymentconditions'];
    public $timestamps = false;
}
