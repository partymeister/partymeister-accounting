<?php

namespace Partymeister\Accounting\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class AccountRequest
 * @package Partymeister\Accounting\Http\Requests\Backend
 */
class AccountRequest extends Request
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
            'name'              => 'required',
            'currency_iso_4217' => 'required'
        ];
    }
}
