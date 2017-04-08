<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $table='unit';
    protected $fillable=['name','abbreviation','isimperial'];
    public $timestamps = false;
}
