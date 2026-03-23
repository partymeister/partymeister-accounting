<?php

namespace Partymeister\Accounting\Http\Requests\Backend;

use Motor\Admin\Http\Requests\Request;

/**
 * Class BookingRequest
 */
class BookingRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="BookingRequest",
     *   @OA\Property(
     *     property="sale_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="from_account_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="to_account_id",
     *     type="integer",
     *     example="2"
     *   ),
     *   @OA\Property(
     *     property="description",
     *     type="string",
     *     example="1x Karlsberg, 2x Orange Juice, 1x T-Shirt XL"
     *   ),
     *   @OA\Property(
     *     property="vat_percentage",
     *     type="float",
     *     example="19"
     *   ),
     *   @OA\Property(
     *     property="price_with_vat",
     *     type="decimal",
     *     example="11.9"
     *   ),
     *   @OA\Property(
     *     property="price_without_vat",
     *     type="decimal",
     *     example="10"
     *   ),
     *   @OA\Property(
     *     property="currency_iso_4217",
     *     type="string",
     *     example="EUR"
     *   ),
     *   @OA\Property(
     *     property="is_manual_booking",
     *     type="boolean",
     *     example="false"
     *   ),
     *   required={"description", "currency_iso_4217", "price_with_vat", "vat_percentage"},
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
            'sale_id'           => 'nullable|integer',
            'from_account_id'   => 'nullable|integer',
            'to_account_id'     => 'nullable|integer',
            'description'       => 'required',
            'vat_percentage'    => 'required|numeric',
            'price_with_vat'    => 'required|numeric',
            'price_without_vat' => 'nullable|numeric',
            'currency_iso_4217' => 'currency_compatibility',
            'is_manual_booking' => 'nullable|boolean',
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
