<?php

namespace Motor\CMS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Motor\Backend\Models\User;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\Sale;

class SaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id'             => Item::factory()->make()->id,
            'earnings_booking_id' => Booking::factory()->make()->id,
            'cost_booking_id'     => Booking::factory()->make()->id,
            'quantity'            => rand(1, 10),
            'vat_percentage'      => rand(0, 19),
            'price_with_vat'      => rand(0, 10000) / 100,
            'price_without_vat'   => rand(0, 10000) / 100,
            'currency_iso_4217'   => 'EUR',
            'created_by'          => User::factory()->make()->id,
            'updated_by'          => User::factory()->make()->id,
        ];
    }
}
