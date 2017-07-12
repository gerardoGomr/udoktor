<?php

namespace Udoktor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdatePricesRequest
 *
 * @package Udoktor\Http\Requests
 * @category Request
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class UpdatePricesRequest extends FormRequest
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
        $rules = [];

        foreach ($this->request->get('prices') as $index => $currentPrice) {
            $rules['prices.' . $index] = 'required|numeric';
        }

        return $rules;
    }
}
