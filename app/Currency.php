<?php
namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Currency
 * @package Udoktor
 * @author Gerardo Adrián Gómez Ruiz
 * @version 1.0
 */
class Currency extends Model
{
	/**
     * @var string
     */
    protected $table = 'currency';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function serviceOffer()
    {
    	return $this->hasMany('Udoktor\ServiceOffer', 'currencyid');
    }
}