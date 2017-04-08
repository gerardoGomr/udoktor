<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    /**
     * @var string
     */
    protected $table = 'deliveryaddress';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estado()
    {
        return $this->belongsTo('Udoktor\AdministrativeUnit', 'stateid');
    }
}
