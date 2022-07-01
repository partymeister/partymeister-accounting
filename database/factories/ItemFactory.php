<?php

namespace Motor\CMS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Motor\Admin\Models\User;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\ItemType;

class ItemFactory extends Factory {
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = ItemType::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'name'                             => $this->faker->word,
			'description'                      => $this->faker->paragraph,
			'internal_description'             => $this->faker->paragraph,
			'vat_percentage'                   => rand(0, 19),
			'price_with_vat'                   => rand(0, 10000) / 100,
			'price_without_vat'                => rand(0, 10000) / 100,
			'cost_price_with_vat'              => rand(0, 10000) / 100,
			'cost_price_without_vat'           => rand(0, 10000) / 100,
			'currency_iso_4217'                => 'EUR',
			'can_be_ordered'                   => (bool)rand(0, 1),
			'sort_position'                    => $this->faker->numberBetween(0, 100),
			'pos_sort_position'                => $this->faker->numberBetween(0, 100),
			'is_visible_in_pos'                => (bool)rand(0, 1),
			//'pos_create_booking_for_item_id'   => factory(Partymeister\Accounting\Models\Item::class)->create()->id,
			'pos_can_book_negative_quantities' => (bool)rand(0, 1),
			'pos_do_break'                     => (bool)rand(0, 1),
			'item_type_id'                     => ItemType::factory()->make()->id,
			'pos_earnings_account_id'          => Account::factory()->make()->id,
			'pos_cost_account_id'              => Account::factory()->make()->id,
			'created_by'                       => User::factory()->make()->id,
			'updated_by'                       => User::factory()->make()->id,
		];
	}
}
