<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $table='alert';
    protected $fillable=['recipientid','createdat','updated','type','relationid','relationid2'];
    public $timestamps = false;
}
