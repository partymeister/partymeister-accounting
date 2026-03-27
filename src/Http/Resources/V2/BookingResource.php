<?php

namespace Partymeister\Accounting\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Accounting\Models\Booking;

/**
 * @mixin Booking
 */
class BookingResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'description' => $this->description,
            'vat_percentage' => (float) $this->vat_percentage,
            'price_with_vat' => (float) $this->price_with_vat,
            'price_without_vat' => (float) $this->price_without_vat,
            'currency_iso_4217' => $this->currency_iso_4217,
            'is_manual_booking' => (bool) $this->is_manual_booking,
            'is_card_payment' => (bool) $this->is_card_payment,
            'is_coupon_payment' => (bool) $this->is_coupon_payment,
            'from_account' => new AccountResource($this->whenLoaded('from_account')),
            'to_account' => new AccountResource($this->whenLoaded('to_account')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
