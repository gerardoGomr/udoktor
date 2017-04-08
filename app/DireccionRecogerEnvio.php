<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class DireccionRecogerEnvio extends Model
{
    protected $table='collectionaddress';
    protected $fillable=['contactfullname','contactnumber1','streeUdoktor','street2','boroughid','city',
        'stateid','latitude','longitude','shippingrequestid','collectiondate','collectionuntildate','place',
        'elevator','collectinside','updated','cityid','municipalityid','collecttimefrom','collecttimeuntil','generalubication',
        'collectionrandomubication','anotherplace'];
    public $timestamps = false;
}
