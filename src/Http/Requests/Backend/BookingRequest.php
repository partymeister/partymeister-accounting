<?php

namespace Partymeister\Accounting\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class BookingRequest
 * @package Partymeister\Accounting\Http\Requests\Backend
 */
class BookingRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="BookingRequest",
     *   @OA\Property(
     *     property="name",
     *     type="string",
     *     example="Example data"
     *   ),
     *   required={"name"},
     * )
     */

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
            'description'       => 'required',
            'currency_iso_4217' => 'currency_compatibility'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'currency_compatibility' => trans('partymeister-accounting::backend/bookings.currency_compatibility_error'),
        ];
    }
}
