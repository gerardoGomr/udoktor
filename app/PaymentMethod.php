<?php
namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    /**
     * @var string
     */
    protected $table = 'paymentmethod';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceOffers()
    {
        return $this->hasMany('Udoktor\ServiceOffer', 'paymentmethodid');
    }

    public function shippingRequest()
    {
        return $this->hasMany('Udoktor\ShippingRequest', 'paymentmethodid');
    }
}
