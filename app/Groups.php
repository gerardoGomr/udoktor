<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $table='groups';
    protected $fillable=['name','updated','personid','priority'];
    public $timestamps = false;
}
