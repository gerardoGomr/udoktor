<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table='address';
    protected $fillable=['streeUdoktor','street2','cityid','stateid','personid','postalcode','telephone'];
    public $timestamps = false;
}
