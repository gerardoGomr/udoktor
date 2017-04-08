<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class V_person extends Model
{
    protected $table='person';
    protected $fillable=['fullname','suspended','isserviceprovider','isclient','company',
        'newoffer','offercanceled','newquestion','offeraccepted','offerrejected','newreply','shipmentcollected',
        'shipmentdelivered','feedback','updated','assignedvehicle','newshipping','shippingexpiration',
        'shippingcheck','competingoffer','phone','id_classification','latitude','longitude','priceservice',
        'generalprice','newdate','confirmationdate','rejectiondate','canceldate'];
    
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shipper()
    {
    	return $this->hasOne('Udoktor\Shipper', 'personid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function preferences()
    {
        return $this->hasMany('Udoktor\Preference', 'personid');
    }
}
