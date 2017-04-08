<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class CarrierCredit extends Model
{
    protected $table='carriercredit';
    protected $fillable=['personid','amount','created','usuarioid','description','updated'];
    public $timestamps = false;
}
