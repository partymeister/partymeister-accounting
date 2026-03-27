<?php

namespace Partymeister\Accounting\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Accounting\Models\Item;

/**
 * @mixin Item
 */
class ItemResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'internal_description' => $this->internal_description,
            'vat_percentage' => (float) $this->vat_percentage,
            'price_with_vat' => (float) $this->price_with_vat,
            'price_without_vat' => (float) $this->price_without_vat,
            'cost_price_with_vat' => (float) $this->cost_price_with_vat,
            'cost_price_without_vat' => (float) $this->cost_price_without_vat,
            'currency_iso_4217' => $this->currency_iso_4217,
            'can_be_ordered' => (bool) $this->can_be_ordered,
            'is_visible' => (bool) $this->is_visible,
            'sort_position' => (int) $this->sort_position,
            'pos_can_book_negative_quantities' => (bool) $this->pos_can_book_negative_quantities,
            'item_type' => new ItemTypeResource($this->whenLoaded('item_type')),
            'pos_cost_account' => new AccountResource($this->whenLoaded('pos_cost_account')),
            'pos_create_booking_for_item' => new ItemResource($this->whenLoaded('pos_create_booking_for_item')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
