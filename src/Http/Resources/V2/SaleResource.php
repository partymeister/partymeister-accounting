<?php

namespace Partymeister\Accounting\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Accounting\Models\Sale;

/**
 * @mixin Sale
 */
class SaleResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'quantity' => (int) $this->quantity,
            'vat_percentage' => (float) $this->vat_percentage,
            'price_with_vat' => (float) $this->price_with_vat,
            'price_without_vat' => (float) $this->price_without_vat,
            'currency_iso_4217' => $this->currency_iso_4217,
            'item' => new ItemResource($this->whenLoaded('item')),
            'earnings_booking' => new BookingResource($this->whenLoaded('earnings_booking')),
            'cost_booking' => new BookingResource($this->whenLoaded('cost_booking')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
