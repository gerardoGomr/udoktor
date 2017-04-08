<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table='country';
    
    protected $fillable=['name','shortname','telephonecode','updated','active'];
    
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function administrativeUnits()
    {
    	return $this->hasMany('Udoktor\AdministrativeUnit', 'countryid');
    }
}
