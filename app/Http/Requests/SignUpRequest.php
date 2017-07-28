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
            'nombre'         => 'required|max:80',
            'paterno'        => 'required|max:80',
            'municipio'      => 'required|numeric',
            'telefono'       => 'required|max:20',
            'aceptaTerminos' => 'required',
            'clasificacion'  => 'required_if:tipoCuenta,2|numeric',
            'servicios'      => 'required_if:tipoCuenta,2'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.unique'                       => 'Este correo ya está en uso',
            'servicios.required_if'   => 'Por favor, especifique un servicio',
        ];
    }
}