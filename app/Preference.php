<?php
namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    /**
     * @var string
     */
    protected $table = 'preferences';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function administrativeUnit()
    {
    	return $this->belongsTo('Udoktor\AdministrativeUnit', 'administrativeunitid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person()
    {
    	return $this->belongsTo('Udoktor\Person', 'personid');
    }
}