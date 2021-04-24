<?php

namespace Partymeister\Accounting\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *   schema="ItemResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="name",
 *     type="string",
 *     example="Water"
 *   ),
 *   @OA\Property(
 *     property="description",
 *     type="text",
 *     example="1 Liter PET"
 *   ),
 *   @OA\Property(
 *     property="internal_description",
 *     type="text",
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
 * )
 */
class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                               => (int) $this->id,
            'name'                             => $this->name,
            'description'                      => $this->description,
            'internal_description'             => $this->internal_description,
            'item_type'                        => new ItemTypeResource($this->item_type),
            'vat_percentage'                   => (float) $this->vat_percentage,
            'price_with_vat'                   => (float) $this->price_with_vat,
            'price_without_vat'                => (float) $this->price_without_vat,
            'cost_price_with_vat'              => (float) $this->price_with_vat,
            'cost_price_without_vat'           => (float) $this->price_without_vat,
            'currency_iso_4217'                => $this->currency_iso_4217,
            'pos_cost_account'                 => new AccountResource($this->pos_cost_account_id),
            'pos_create_booking_for_item'      => new ItemResource($this->pos_create_booking_for_item_id),
            'pos_can_book_negative_quantities' => (boolean) $this->pos_can_book_negative_quantities,
            'can_be_ordered'                   => (boolean) $this->can_be_ordered,
            'is_visible'                       => (boolean) $this->is_visible,
            'sort_position'                    => (int) $this->sort_position,
        ];
    }
}
