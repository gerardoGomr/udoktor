<?php
namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ShipmentLog
 * @package Udoktor
 * @author Gerardo Adrián Gómez Ruiz
 * @version 1.0
 */
class ShipmentLog extends Model
{
    /**
     * @var string
     */
    protected $table = 'shippingrequestlog';

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
}
