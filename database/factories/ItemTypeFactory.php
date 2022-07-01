<?php

namespace Motor\CMS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Motor\Admin\Models\User;
use Partymeister\Accounting\Models\ItemType;

class ItemTypeFactory extends Factory {
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
			'name'          => $this->faker->word,
			'sort_position' => $this->faker->numberBetween(0, 100),
			'is_visible'    => (bool)rand(0, 1),
			'created_by'    => User::factory()->make()->id,
			'updated_by'    => User::factory()->make()->id,
		];
	}
}
