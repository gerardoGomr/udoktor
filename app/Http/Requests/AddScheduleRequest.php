<?php

namespace Udoktor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AddScheduleRequest
 *
 * @package Udoktor\Http\Requests
 * @category Request
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class AddScheduleRequest extends FormRequest
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
            'start-hour'    => 'required|date_format:H:i',
            'clients-limit' => 'required_if:diary-schedule-type,fixed|numeric',
            'end-hour'      => 'required_if:diary-schedule-type,interval|date_format:H:i'
        ];
    }
}
