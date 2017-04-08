<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    /**
     * @var string
     */
    protected $table = 'state';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
    	return $this->belongsTo('Udoktor\Pais', 'countryid');
    }
}
