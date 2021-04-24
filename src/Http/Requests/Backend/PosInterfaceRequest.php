<?php

namespace Partymeister\Accounting\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class PosInterfaceRequest
 *
 * @package Partymeister\Accounting\Http\Requests\Backend
 */
class PosInterfaceRequest extends Request
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
        return [];
    }
}
