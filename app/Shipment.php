<?php
namespace Udoktor;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * Class Shipment
 * @package Udoktor
 * @author Gerardo Adrián Gómez Ruiz
 * @version 1.0
 */
class Shipment extends Model
{
    /**
     * @var string
     */
    protected $table = 'shipment';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function serviceOffer()
    {
        return $this->belongsTo('Udoktor\ServiceOffer', 'acceptedserviceofferid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo('Udoktor\Currency', 'currencyid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function vehicle()
    {
        return $this->belongsTo('Udoktor\Vehicle', 'vehicleid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedbacks()
    {
        return $this->hasMany('Udoktor\Feedback', 'shipmentid');
    }

    /**
     * obtener ratings agrupados
     * @param  Person $person
     * @return array
     */
    public static function findRatings(Person $person)
    {
        if (!is_null($person->shipper)) {
            $query = "select f.starrating, count(f.starrating) as total from shipment s inner join serviceoffer as so on s.acceptedserviceofferid = so.id inner join feedback f on s.id = f.shipmentid where so.shipperid = ".$person->shipper->id." and f.recipientid = ".$person->id." group by f.starrating";

        } else {
            $query = "select f.starrating, count(f.starrating) as total from shipment s inner join serviceoffer as so on s.acceptedserviceofferid = so.id inner join feedback f on s.id = f.shipmentid where f.recipientid = ".$person->id." group by f.starrating";
        }

        $shipments = DB::select($query);

        return $shipments;
    }
}