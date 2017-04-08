<?php
namespace Udoktor;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

/**
 * Class ShippingRequest
 * @package Udoktor
 * @author Gerardo Adrián Gómez Ruiz
 * @version 1.0
 */
class ShippingRequest extends Model
{
    /**
     * @var string
     */
    protected $table = 'shippingrequest';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * el cliente
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function person()
    {
        return $this->belongsTo('Udoktor\Person', 'requesterid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function collectionAddress()
    {
        return $this->hasOne('Udoktor\CollectionAddress', 'shippingrequestid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function deliveryAddress()
    {
        return $this->hasOne('Udoktor\DeliveryAddress', 'shippingrequestid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('Udoktor\ShippingItem', 'shippingrequestid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany('Udoktor\Question', 'shippingrequestid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceOffers()
    {
        return $this->hasMany('Udoktor\ServiceOffer', 'shippingrequestid');
    }

    /**
     * verificar si el envío tiene descuento
     * @return boolean
     */
    public function hasDiscount()
    {
        if (!empty($this->discountrate) || !is_null($this->discountrate)) {
            return true;
        }

        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany('Udoktor\ShipmentLog', 'shippingrequestid');
    }

    /**
     * obtener el costo siguiente a asignar
     * @return double
     */
    public function getCostAvailableToOffer()
    {
        $costo = 0;

        $serviceOffers = $this->serviceOffers()->get();
        if (count($serviceOffers) > 0) {
            // hay ofertas, obtener la más barata
            $costo = (double) $this->serviceOffers()->where('status', '1')->min('shipmentcost');
        }

        if ($costo === 0.0 || $costo === 0) {
            $costo = (double) $this->totalprice;

        }

        return $costo;
    }

    /**
     * verificar que no haya expirado, al comparar la fecha de expiración
     * con la fecha actual
     * @return bool
     */
    public function hasExpired()
    {
        $currentDateTime     = date('Y-m-d H:i:s');
        // $currentDateTime     = strtotime($currentDateTime);
        // $shippingRequestDate = strtotime($this->expirationdate);

        if ($currentDateTime > $this->expirationdate) {
            return true;
        }

        return false;
    }

    /**
     * verificar que el costo que se estipula sea el más bajo
     * @param $costo
     * @return bool
     */
    public function isTheLowerCost($costo)
    {
        $serviceOffers = $this->serviceOffers()->where('status', '1')->get();
        if (count($serviceOffers) > 0) {
            // hay ofertas, obtener la más barata
            $min = (double) $this->serviceOffers()->where('status', '1')->min('shipmentcost');

            if($costo < $min){
                return 'ok';
            }else{
                return 'Ya hay otra oferta. El precio de la oferta ahora debe ser menor a '.$min;
            }
        }
        else{
            $min = (double) $this->totalprice;

            if($costo > $min){
                return 'El precio de la oferta debe ser menor o igual a '.$min;
            }
            else{
                return 'ok';
            }
        }

        
    }

    /**
     * construir el query de order by para el query de busqueda de ofertas
     * @param string $orderBy
     * @return string
     */
    public static function getOrderByToObtainShippingRequests($orderBy)
    {
        $valor = '';
        switch ($orderBy) {
            case '1':
                // costo ascendente
                $valor = ' ORDER BY shipping.totalprice';
                break;

            case '2':
                // costo descendente
                $valor = ' ORDER BY shipping.totalprice DESC';
                break;

            case '3':
                // origen ascendente
                $valor = ' ORDER BY collection.city';
                break;

            case '4':
                // origen ascendente
                $valor = ' ORDER BY collection.city DESC';
                break;

            case '5':
                // destino ascendente
                $valor = ' ORDER BY delivery.city';
                break;

            case '6':
                // destino ascendente
                $valor = ' ORDER BY delivery.city DESC';
                break;

            case '7':
                // km ascendente
                $valor = ' ORDER BY shipping.km';
                break;

            case '8':
                // km descendente
                $valor = ' ORDER BY shipping.km DESC';
                break;

            case '9':
                // recientemente publicado
                $valor = ' ORDER BY shipping.createdat DESC';
                break;

            case '10':
                // recientemente modificado
                $valor = ' ORDER BY shipping.updated DESC';
                break;

            case '11':
                // proximas a vencer
                $valor = ' ORDER BY shipping.expirationdate DESC';
                break;
        }

        return $valor;
    }

    /**
     * obtener una lista de shipping requests en base a los parámetros de búsqueda
     * @param string $collectionQuery
     * @param string $deliveryQuery
     * @param string $costQuery
     * @param string $orderBy
     * @return array
     */
    public static function getByCollectionOrDeliveryQuery($collectionQuery, $deliveryQuery, $costQuery, $orderBy)
    {
        $date = date('Y-m-d H:i:s');

        $preferences             = DB::select('SELECT administrativeunitid FROM preferences WHERE personid = ' . Auth::user()->personid);
        $administrativeUnitId    = [];
        $administrativeUnitQuery = '';

        if (count($preferences > 0)) {
            foreach ($preferences as $preference) {
                $administrativeUnitId[] = $preference->administrativeunitid;
            }

            $implode = '(' . implode(',', $administrativeUnitId) . ')';
            $administrativeUnitQuery = ' AND collection.stateid IN ' . $implode . ' AND delivery.stateid IN ' . $implode;
            $administrativeUnitQuery2 = ' AND stateidOrigen IN ' . $implode . ' AND stateidDestino IN ' . $implode;
        }

        $queryOrigin = "SELECT shipping.id,shipping.title,shipping.status,shipping.createdat,shipping.coldprotection,shipping.sortout,shipping.blindperson,shipping.costtype,shipping.cost,shipping.firstofferdiscount,shipping.discountrate,shipping.km,shipping.tiempo,shipping.peso_total,collection.contactfullname,collection.contactnumber1,collectionState.name stateOrigen,collectionCountry.name countryOrigen, collection.latitude latitudOrigen, collection.longitude longitudOrigen, collection.collectiondate collectiondateOrigen,collection.collectionuntildate collectionuntildateOrigen,collection.place placeOrigen,collection.elevator elevatorOrigen,collection.collectinside collectinsideOrigen,collection.city cityOrigen,collection.collecttimefrom collecttimefromOrigen,collection.collecttimeuntil collecttimeuntilOrigen, collection.streeUdoktor streeUdoktorOrigen,collection.stateid stateidOrigen, delivery.recipientfullname recipientfullnameDestino,delivery.recipientcontactnumber1 recipientcontactnumber1Destino,delivery.streeUdoktor streeUdoktorDestino,delivery.stateid stateidDestino,deliveryState.name stateDestino,deliveryCountry.name countryDestino, delivery.longitude longitudDestino, delivery.latitude latitudDestino,delivery.deliverydate deliverydateDestino,delivery.deliveryuntildate deliveryuntildateDestino, delivery.place placeDestino,delivery.elevator elevatorDestino,delivery.callbefore callbeforeDestino,delivery.deliverywithin deliverywithinDestino,delivery.deliverytimefrom deliverytimeDestino,delivery.deliverytimeuntil deliverytimeuntilDestino,delivery.city cityDestino, (SELECT CASE WHEN company IS NULL THEN CONCAT(firstname, ' ', lastname) ELSE company END FROM person WHERE id=shipping.requesterid) requestername, collection.collectionrandomubication, delivery.deliveryrandomubication, shipping.expirationdate, shipping.totalprice FROM shippingrequest shipping LEFT JOIN deliveryaddress delivery on delivery.shippingrequestid=shipping.id LEFT JOIN administrativeunit deliveryState ON deliveryState.id=delivery.stateid LEFT JOIN country deliveryCountry ON deliveryCountry.id=deliveryState.countryid LEFT JOIN collectionaddress collection ON collection.shippingrequestid=shipping.id LEFT JOIN administrativeunit collectionState ON collectionState.id=collection.stateid LEFT JOIN country collectionCountry ON collectionCountry.id=collectionState.countryid WHERE shipping.status=1 AND shipping.expirationdate > '$date' $collectionQuery";

        $queryDestiny = "SELECT shipping.id,shipping.title,shipping.status,shipping.createdat,shipping.coldprotection,shipping.sortout,shipping.blindperson,shipping.costtype,shipping.cost,shipping.firstofferdiscount,shipping.discountrate,shipping.km,shipping.tiempo,shipping.peso_total, collection.contactfullname,collection.contactnumber1,collectionState.name stateOrigen, collectionCountry.name countryOrigen, collection.latitude latitudOrigen, collection.longitude longitudOrigen, collection.collectiondate collectiondateOrigen,collection.collectionuntildate collectionuntildateOrigen,collection.place placeOrigen,collection.elevator elevatorOrigen,collection.collectinside collectinsideOrigen,collection.city cityOrigen,collection.collecttimefrom collecttimefromOrigen,collection.collecttimeuntil collecttimeuntilOrigen, collection.streeUdoktor streeUdoktorOrigen,collection.stateid stateidOrigen, delivery.recipientfullname recipientfullnameDestino,delivery.recipientcontactnumber1 recipientcontactnumber1Destino,delivery.streeUdoktor streeUdoktorDestino,delivery.stateid stateidDestino,deliveryState.name stateDestino,deliveryCountry.name countryDestino, delivery.longitude longitudDestino, delivery.latitude latitudDestino,delivery.deliverydate deliverydateDestino,delivery.deliveryuntildate deliveryuntildateDestino, delivery.place placeDestino,delivery.elevator elevatorDestino,delivery.callbefore callbeforeDestino,delivery.deliverywithin deliverywithinDestino,delivery.deliverytimefrom deliverytimeDestino,delivery.deliverytimeuntil deliverytimeuntilDestino,delivery.city cityDestino, (SELECT CASE WHEN company IS NULL THEN CONCAT(firstname, ' ', lastname) ELSE company END FROM person WHERE id=shipping.requesterid) requestername, collection.collectionrandomubication, delivery.deliveryrandomubication, shipping.expirationdate, shipping.totalprice FROM shippingrequest shipping LEFT JOIN deliveryaddress delivery on delivery.shippingrequestid=shipping.id LEFT JOIN administrativeunit deliveryState ON deliveryState.id=delivery.stateid LEFT JOIN country deliveryCountry ON deliveryCountry.id=deliveryState.countryid LEFT JOIN collectionaddress collection ON collection.shippingrequestid=shipping.id LEFT JOIN administrativeunit collectionState ON collectionState.id=collection.stateid LEFT JOIN country collectionCountry ON collectionCountry.id=collectionState.countryid WHERE shipping.status=1 AND shipping.expirationdate > '$date' $deliveryQuery";

        if (strlen($collectionQuery)) {
            if (strlen($deliveryQuery)) {
                $query = "SELECT * FROM ($queryOrigin UNION $queryDestiny) envios WHERE 1 = 1 $costQuery $administrativeUnitQuery2 $orderBy";

            } else {
                $query = "SELECT * FROM ($queryOrigin) envios WHERE 1 = 1 $costQuery $administrativeUnitQuery2 $orderBy";
            }
        } else {
            if (strlen($deliveryQuery)) {
                $query = "SELECT * FROM ($queryDestiny) envios WHERE 1 = 1 $costQuery $administrativeUnitQuery2 $orderBy";

            } else {
                $query = "SELECT shipping.id,shipping.title,shipping.status,shipping.createdat,shipping.coldprotection,shipping.sortout,shipping.blindperson,shipping.costtype,shipping.cost,shipping.firstofferdiscount,shipping.discountrate,shipping.km,shipping.tiempo,shipping.peso_total, collection.contactfullname,collection.contactnumber1,collectionState.name stateOrigen, collectionCountry.name countryOrigen, collection.latitude latitudOrigen, collection.longitude longitudOrigen, collection.collectiondate collectiondateOrigen,collection.collectionuntildate collectionuntildateOrigen,collection.place placeOrigen,collection.elevator elevatorOrigen,collection.collectinside collectinsideOrigen,collection.city cityOrigen,collection.collecttimefrom collecttimefromOrigen,collection.collecttimeuntil collecttimeuntilOrigen, collection.streeUdoktor streeUdoktorOrigen,collection.stateid stateidOrigen, delivery.recipientfullname recipientfullnameDestino,delivery.recipientcontactnumber1 recipientcontactnumber1Destino,delivery.streeUdoktor streeUdoktorDestino,delivery.stateid stateidDestino,deliveryState.name stateDestino,deliveryCountry.name countryDestino, delivery.longitude longitudDestino, delivery.latitude latitudDestino,delivery.deliverydate deliverydateDestino,delivery.deliveryuntildate deliveryuntildateDestino, delivery.place placeDestino,delivery.elevator elevatorDestino,delivery.callbefore callbeforeDestino,delivery.deliverywithin deliverywithinDestino,delivery.deliverytimefrom deliverytimeDestino,delivery.deliverytimeuntil deliverytimeuntilDestino,delivery.city cityDestino, (SELECT CASE WHEN company IS NULL THEN CONCAT(firstname, ' ', lastname) ELSE company END FROM person WHERE id=shipping.requesterid) requestername, collection.collectionrandomubication, delivery.deliveryrandomubication, shipping.expirationdate, shipping.totalprice FROM shippingrequest shipping LEFT JOIN deliveryaddress delivery on delivery.shippingrequestid=shipping.id LEFT JOIN administrativeunit deliveryState ON deliveryState.id=delivery.stateid LEFT JOIN country deliveryCountry ON deliveryCountry.id=deliveryState.countryid LEFT JOIN collectionaddress collection ON collection.shippingrequestid=shipping.id LEFT JOIN administrativeunit collectionState ON collectionState.id=collection.stateid LEFT JOIN country collectionCountry ON collectionCountry.id=collectionState.countryid WHERE shipping.status=1 AND shipping.expirationdate > '$date' $costQuery $administrativeUnitQuery $orderBy";
            }
        }//dd($query);

        $shippingRequests = DB::select($query);
        return $shippingRequests;
    }

    public function pago()
    {
        return $this->belongsTo('Udoktor\PaymentMethod', 'paymentmethodid');
    }
}