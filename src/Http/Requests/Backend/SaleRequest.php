<?php

namespace Partymeister\Accounting\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class SaleRequest
 *
 * @package Partymeister\Accounting\Http\Requests\Backend
 */
class SaleRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="SaleRequest",
     *   @OA\Property(
     *     property="earnings_booking_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="cost_booking_id",
     *     type="integer",
     *     example="2"
     *   ),
     *   @OA\Property(
     *     property="item_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="quantity",
     *     type="integer",
     *     example="1"
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
     *   required={"item_id", "quantity", "currenty_iso_4217", "price_with_vat", "vat_percentage"},
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
            'item_id'             => 'required|integer',
            'earnings_booking_id' => 'nullable|integer',
            'cost_booking_id'     => 'nullable|integer',
            'quantity'            => 'required|integer',
            'vat_percentage'      => 'required|integer',
            'price_with_vat'      => 'required|decimal',
            'price_without_vat'   => 'nullable|decimal',
            'currency_iso_4217'   => 'required',
        ];
    }
}
