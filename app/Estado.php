<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table='state';
    
    protected $fillable=['name','countryid','updated','active'];
    
    public $timestamps = false;
}
