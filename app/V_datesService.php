<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class V_datesService extends Model
{
    protected $table='datesservice';
    protected $fillable=['dateid','serviceid','updated'];
    public $timestamps = false;
}
