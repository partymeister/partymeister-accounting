<?php

namespace Partymeister\Accounting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\Sale;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition()
    {
        return [
            'item_id' => fn () => Item::factory()->create()->id,
            'earnings_booking_id' => fn () => Booking::factory()->create()->id,
            'cost_booking_id' => null,
            'quantity' => rand(1, 10),
            'vat_percentage' => rand(0, 19),
            'price_with_vat' => rand(0, 10000) / 100,
            'price_without_vat' => rand(0, 10000) / 100,
            'currency_iso_4217' => 'EUR',
        ];
    }
}
