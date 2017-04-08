<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Mensajes extends Model
{
    protected $table='message';
    protected $fillable=['body','senderid','recipientid','sent','shippingrequestid','updated'];
    public $timestamps = false;
}
