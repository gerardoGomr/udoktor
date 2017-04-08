<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Vehicle
 * @package Udoktor
 * @author Gerardo Adrián Gómez Ruiz
 * @version 1.0
 */
class Vehicle extends Model
{
    /**
     * @var string
     */
    protected $table = 'vehicle';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipper()
    {
    	return $this->belongsTo('Udoktor\Shipper', 'shipperid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shipments()
    {
        return $this->hasMany('Udoktor\Shipment', 'vehicleid');
    }
}
