<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class V_classifications extends Model
{
    protected $table='classifications';
    protected $fillable=['name','description','created','updated','deleted','active'];
    public $timestamps = false;
}
