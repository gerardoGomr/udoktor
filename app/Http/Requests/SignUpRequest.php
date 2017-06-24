<?php

namespace Udoktor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SignUpRequest
 *
 * @package Udoktor\Http\Requests
 * @category Request
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'          => 'required|email|unique:users',
            'pass'           => 'required|min:8',
            'tipoCuenta'     => 'required|numeric|min:1|max:2',
            'nombre'         => 'required',
            'paterno'        => 'required',
            'municipio'      => 'required|numeric',
            'telefono'       => 'required',
            'aceptaTerminos' => 'required',
            'clasificacion'  => 'required_if:tipoCuenta,2|numeric',
            'servicios'      => 'required_if:tipoCuenta,2'
        ];
    }
}
