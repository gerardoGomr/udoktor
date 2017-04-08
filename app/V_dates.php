<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class V_dates extends Model
{
    protected $table='dates';
    protected $fillable=['client','serviceprovider','date','created','updated','timedate','secundsdate','latitude','longitude',
        'address','addressdetails'];
    public $timestamps = false;
}
