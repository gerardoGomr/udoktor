<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class GroupsPerson extends Model
{
    protected $table='groupsperson';
    protected $fillable=['groupid','personid','updated'];
    public $timestamps = false;
}
