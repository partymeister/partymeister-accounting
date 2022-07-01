<?php

namespace Motor\CMS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Motor\Admin\Models\User;
use Partymeister\Accounting\Models\AccountType;

class AccountTypeFactory extends Factory {
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = AccountType::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'name'       => $this->faker->word . '.' . Str::random(20),
			'created_by' => User::factory()->make()->id,
			'updated_by' => User::factory()->make()->id,
		];
	}
}
