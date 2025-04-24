<?php

namespace Motor\CMS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Motor\Backend\Models\User;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\Booking;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'from_account_id' => Account::factory()->make()->id,
            'to_account_id' => Account::factory()->make()->id,
            'description' => $this->faker->sentence,
            'is_manual_booking' => (bool) rand(0, 1),
            'vat_percentage' => rand(0, 19),
            'price_with_vat' => rand(0, 10000) / 100,
            'price_without_vat' => rand(0, 10000) / 100,
            'currency_iso_4217' => 'EUR',
            'created_by' => User::factory()->make()->id,
            'updated_by' => User::factory()->make()->id,
        ];
    }
}
