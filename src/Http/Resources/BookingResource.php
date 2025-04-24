<?php

namespace Partymeister\Accounting\Http\Resources;

use Motor\Backend\Http\Resources\BaseResource;

/**
 * @OA\Schema(
 *   schema="BookingResource",
 *
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="sale",
 *     type="object",
 *     ref="#/components/schemas/SaleResource"
 *   ),
 *   @OA\Property(
 *     property="from_account_id",
 *     type="object",
 *     ref="#/components/schemas/AccountResource"
 *   ),
 *   @OA\Property(
 *     property="to_account_id",
 *     type="object",
 *     ref="#/components/schemas/AccountResource"
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
 *   @OA\Property(
 *     property="is_card_payment",
 *     type="boolean",
 *     example="false"
 *   ),
 * )
 */
class BookingResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'sale' => new SaleResource($this->sale),
            'from_account' => new AccountResource($this->from_account),
            'to_account' => new AccountResource($this->to_account),
            'description' => $this->description,
            'vat_percentage' => (float) $this->vat_percentage,
            'price_with_vat' => (float) $this->price_with_vat,
            'price_without_vat' => (float) $this->price_without_vat,
            'currency_iso_4217' => $this->currency_iso_4217,
            'is_manual_booking' => (bool) $this->is_manual_booking,
            'is_card_payment' => (bool) $this->is_card_payment,
        ];
    }
}
