<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class GroupRequest extends Model
{
    protected $table='grouprequest';
    protected $fillable=['shippingrequest','personid','createdat','deletedat','updated'];
    public $timestamps = false;
}
