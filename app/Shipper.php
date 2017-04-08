<?php
namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Shipper extends Model
{
	/**
	 * @var string
	 */
    protected $table = 'shipper';

    protected $fillable=['personid','paymentbank','paymentbankaccountownerfullname','paymentbankaccountnumber','paymentbankbranchnumber','paymentbankroutingnumber'];

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

    public function vehicles()
    {
        return $this->hasMany('Udoktor\Vehicle', 'shipperid');
    }
}