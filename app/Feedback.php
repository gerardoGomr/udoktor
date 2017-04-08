<?php
namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Feedback
 * @author Gerardo Adrián Gómez Ruiz
 * @version 1.0
 */
class Feedback extends Model
{
    /**
     * @var string
     */
    protected $table = 'feedback';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipment()
    {
    	return $this->belongsTo('Udoktor\Shipment', 'shipmentid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
    	return $this->belongsTo('Udoktor\Person', 'authorid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient()
    {
    	return $this->belongsTo('Udoktor\Person', 'recipientid');
    }
}
