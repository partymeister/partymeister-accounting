<?php

namespace Motor\CMS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Motor\Backend\Models\User;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\AccountType;

class AccountFactory extends Factory {
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Account::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'name'              => $this->faker->word . '.' . Str::random(20),
			'account_type_id'   => AccountType::factory()->make()->id,
			'has_pos'           => (bool)rand(0, 1),
			'currency_iso_4217' => 'EUR',
			'created_by'        => User::factory()->make()->id,
			'updated_by'        => User::factory()->make()->id,
		];
	}
}
