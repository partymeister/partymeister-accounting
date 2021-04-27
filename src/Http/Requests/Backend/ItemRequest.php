<?php

namespace Partymeister\Accounting\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class ItemRequest
 *
 * @package Partymeister\Accounting\Http\Requests\Backend
 */
class ItemRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="ItemRequest",
     *   @OA\Property(
     *     property="name",
     *     type="string",
     *     example="Water"
     *   ),
     *   @OA\Property(
     *     property="description",
     *     type="string",
     *     example="1 Liter PET"
     *   ),
     *   @OA\Property(
     *     property="internal_description",
     *     type="string",
     *     example="Only to be sold to adults!"
     *   ),
     *   @OA\Property(
     *     property="item_type_id",
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
     *     property="cost_price_with_vat",
     *     type="decimal",
     *     example="1.19"
     *   ),
     *   @OA\Property(
     *     property="cost_price_without_vat",
     *     type="decimal",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="currency_iso_4217",
     *     type="string",
     *     example="EUR"
     *   ),
     *   @OA\Property(
     *     property="pos_cost_account_id",
     *     type="integer",
     *     example="2"
     *   ),
     *   @OA\Property(
     *     property="pos_create_booking_for_item_id",
     *     type="integer",
     *     example="3",
     *     description="Useful for booking something like deposit"
     *   ),
     *   @OA\Property(
     *     property="pos_can_book_negative_quantities",
     *     type="boolean",
     *     example="false",
     *     description="Useful for something like returning deposit"
     *   ),
     *   @OA\Property(
     *     property="can_be_ordered",
     *     type="boolean",
     *     example="true",
     *     description="This item will show up on the POS interface"
     *   ),
     *   @OA\Property(
     *     property="sort_position",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="is_visible",
     *     type="boolean",
     *     example="true",
     *     description="This item will show up on the frontend"
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
            'item_type_id'                     => 'required',
            'name'                             => 'required',
            'description'                      => 'nullable',
            'internal_description'             => 'nullable',
            'vat_percentage'                   => 'required|integer',
            'price_with_vat'                   => 'required|decimal',
            'price_without_vat'                => 'nullable|decimal',
            'cost_price_with_vat'              => 'required|decimal',
            'cost_price_without_vat'           => 'nullable|decimal',
            'can_be_ordered'                   => 'nullable|boolean',
            'is_visible'                       => 'nullable|boolean',
            'sort_position'                    => 'nullable|integer',
            'pos_can_book_negative_quantities' => 'nullable|boolean',
            'pos_cost_account_id'              => 'nullable|integer',
            'pos_create_booking_for_item_id'   => 'nullable|integer',
            'currency_iso_4217'                => 'currency_compatibility',
        ];
    }
}
