<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class ShippingItem extends Model
{
    /**
     * @var string
     */
    protected $table = 'shippingitem';

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function image()
    {
        return $this->hasMany('Udoktor\ImagenArticuloEnvio', 'shippingitemid');
    }
}