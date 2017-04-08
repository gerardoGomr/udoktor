<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class ImagenArticuloEnvio extends Model
{
    protected $table='fileattachment';
    protected $fillable=['filename','filetypeid','shippingitemid','updated','filenameminiature'];
    public $timestamps = false;
}
