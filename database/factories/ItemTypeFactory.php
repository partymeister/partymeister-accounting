<?php

namespace Partymeister\Accounting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Accounting\Models\ItemType;

class ItemTypeFactory extends Factory
{
    protected $model = ItemType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'sort_position' => $this->faker->numberBetween(0, 100),
            'is_visible' => (bool) rand(0, 1),
        ];
    }
}
