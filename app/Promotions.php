<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Promotions extends Model
{
    protected $table='promotions';
    protected $fillable=['description','expirationdate','amount','groupid','updated','deleted','active','created','code'];
    public $timestamps = false;
}
