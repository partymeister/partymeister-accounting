<?php

namespace Partymeister\Accounting\Http\Resources;

use Illuminate\Support\Str;
use Motor\Backend\Http\Resources\BaseResource;

/**
 * @OA\Schema(
 *   schema="SaleResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
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
 * )
 */
class SaleResource extends BaseResource
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
            'id'                => (int) $this->id,
            'earnings_booking'  => new BookingResource($this->earnings_booking),
            'cost_booking'      => new BookingResource($this->cost_booking),
            'item'              => new ItemResource($this->item),
            'item_and_quantity' => $this->item_and_quantity,
            'quantity'          => (int) $this->quantity,
            'vat_percentage'    => (float) $this->vat_percentage,
            'price_with_vat'    => (float) $this->price_with_vat,
            'price_without_vat' => (float) $this->price_without_vat,
            'currency_iso_4217' => $this->currency_iso_4217,
            'created_at'        => Str::replaceFirst(' ', 'T', $this->created_at),
        ];
    }
}
