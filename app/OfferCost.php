<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class OfferCost extends Model
{
    protected $table='offercostpublic';
    protected $fillable=['quantity','updated','type','start','v_end','deleted','groupid'];
    public $timestamps = false;
}
