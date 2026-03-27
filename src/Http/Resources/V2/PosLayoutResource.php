<?php

namespace Partymeister\Accounting\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Accounting\Models\Account;

/**
 * @mixin Account
 */
class PosLayoutResource extends BaseResource
{
    public function toArray($request): array
    {
        $config = $this->pos_configuration ?? [];
        $items = $this->pos_items ?? collect();

        $zones = [];
        foreach ($config as $zoneNumber => $zoneItems) {
            $zones[$zoneNumber] = [];
            foreach ($zoneItems as $entry) {
                if ($entry === 'separator') {
                    $zones[$zoneNumber][] = 'separator';
                } elseif ($items->has($entry)) {
                    $item = $items->get($entry);
                    $zones[$zoneNumber][] = [
                        'id' => (int) $item->id,
                        'name' => $item->name,
                        'price_with_vat' => (float) $item->price_with_vat,
                        'pos_can_book_negative_quantities' => (bool) $item->pos_can_book_negative_quantities,
                        'pos_create_booking_for_item_id' => $item->pos_create_booking_for_item_id,
                    ];
                }
            }
        }

        $lastBooking = $this->pos_last_booking;

        return [
            'account' => [
                'id' => (int) $this->id,
                'name' => $this->name,
                'currency_iso_4217' => $this->currency_iso_4217,
                'has_card_payments' => (bool) $this->has_card_payments,
                'has_coupon_payments' => (bool) $this->has_coupon_payments,
            ],
            'zones' => $zones,
            'last_booking' => $lastBooking
                ? (new BookingResource($lastBooking))->toArray($request)
                : null,
        ];
    }
}
