<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class V_service extends Model
{
    protected $table='services';
    protected $fillable=['name','description','created','updated','deleted','active','price','minprice','maxprice'];
    public $timestamps = false;
}
