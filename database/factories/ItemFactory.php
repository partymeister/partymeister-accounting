<?php

namespace Partymeister\Accounting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\ItemType;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'internal_description' => $this->faker->paragraph,
            'vat_percentage' => rand(0, 19),
            'price_with_vat' => rand(0, 10000) / 100,
            'price_without_vat' => rand(0, 10000) / 100,
            'cost_price_with_vat' => rand(0, 10000) / 100,
            'cost_price_without_vat' => rand(0, 10000) / 100,
            'currency_iso_4217' => 'EUR',
            'can_be_ordered' => (bool) rand(0, 1),
            'is_visible' => (bool) rand(0, 1),
            'sort_position' => $this->faker->numberBetween(0, 100),
            'pos_can_book_negative_quantities' => (bool) rand(0, 1),
            'item_type_id' => fn () => ItemType::factory()->create()->id,
        ];
    }
}
