<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Question
 * @package Udoktor
 * @author Gerardo Adrián Gómez Ruiz
 * @version 1.0
 */
class Question extends Model
{
    /**
     * @var string
     */
    protected $table = 'question';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipper()
    {
    	return $this->belongsTo('Udoktor\Shipper', 'shipperid');
    }

    /**
     * validar que el texto no contenga elementos como correos o telefonos
     * @return void
     */
    public function validar()
    {

    }
}