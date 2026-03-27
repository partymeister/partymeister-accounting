<?php

namespace Partymeister\Accounting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\Booking;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'from_account_id' => fn () => Account::factory()->create()->id,
            'to_account_id' => fn () => Account::factory()->create()->id,
            'description' => $this->faker->sentence,
            'is_manual_booking' => (bool) rand(0, 1),
            'vat_percentage' => rand(0, 19),
            'price_with_vat' => rand(0, 10000) / 100,
            'price_without_vat' => rand(0, 10000) / 100,
            'currency_iso_4217' => 'EUR',
        ];
    }
}
