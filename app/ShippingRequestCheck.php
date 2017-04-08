<?php
namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class ShippingRequestCheck extends Model
{
	/**
	 * @var string
	 */
    protected $table = 'shippingrequestcheck';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person()
    {
    	return $this->belongsTo('Udoktor\Shipper', 'shipperid');
    }

}