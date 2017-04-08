<?php
namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
	/**
	 * @var string
	 */
    protected $table = 'tracking';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person()
    {
    	return $this->belongsTo('Udoktor\Person', 'personid');
    }

}