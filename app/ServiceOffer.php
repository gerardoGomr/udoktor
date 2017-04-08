<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceOffer
 * @author Gerardo Adrián Gómez Ruiz
 * @version 1.0
 */
class ServiceOffer extends Model
{
    /**
	 * @var string
	 */
    protected $table = 'serviceoffer';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingRequest()
    {
    	return $this->belongsTo('Udoktor\ShippingRequest', 'shippingrequestid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipper()
    {
        return $this->belongsTo('Udoktor\Shipper', 'shipperid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currency()
    {
        return $this->belongsTo('Udoktor\Currency', 'currencyid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod()
    {
        return $this->belongsTo('Udoktor\PaymentMethod', 'paymentmethodid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shipment()
    {
        return $this->hasOne('Udoktor\Shipment', 'acceptedserviceofferid');
    }
}