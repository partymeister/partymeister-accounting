<?php

namespace Partymeister\Accounting\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

class BookingRequest extends Request
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
            'description' => 'required',
            'currency_iso_4217' => 'currency_compatibility'

        ];
    }

    public function messages()
    {
        return [
            'currency_compatibility' => trans('partymeister-accounting::backend/bookings.currency_compatibility_error'),
        ];
    }
}
