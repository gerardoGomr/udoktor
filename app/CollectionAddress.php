<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class CollectionAddress extends Model
{
    /**
     * @var string
     */
    protected $table = 'collectionaddress';

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
