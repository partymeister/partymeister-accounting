<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Motor\Backend\Models\User;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\ItemType;

/**
 * Class ItemsTableSeeder
 * @package Partymeister\Accounting\Database\Seeds
 */
class ItemsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deposit = [
            'item_type_id'                     => ItemType::where('name', 'Beverages')->first()->id,
            'name'                             => 'Deposit',
            'description'                      => 'This is the default deposit',
            'vat_percentage'                   => 19,
            'price_with_vat'                   => 0.5,
            'price_without_vat'                => 0.42,
            'cost_price_with_vat'              => 0,
            'cost_price_without_vat'           => 0,
            'sort_position'                    => 10,
            'currency_iso_4217'                => 'EUR',
            'is_visible'                       => false,
            'pos_can_book_negative_quantities' => true,
        ];

        $this->createItem($deposit);

        $items = [
            [
                'item_type_id'                     => ItemType::where('name', 'Beverages')->first()->id,
                'name'                             => 'Beer #1',
                'description'                      => 'This is beer #1',
                'vat_percentage'                   => 19,
                'price_with_vat'                   => 1.5,
                'price_without_vat'                => 1.26,
                'cost_price_with_vat'              => 1.00,
                'cost_price_without_vat'           => 0.84,
                'sort_position'                    => 20,
                'currency_iso_4217'                => 'EUR',
                'is_visible'                       => true,
                'pos_can_book_negative_quantities' => false,
                'pos_create_booking_for_item_id'   => Item::where('name', 'Deposit')->first()->id,
            ],
            [
                'item_type_id'                     => ItemType::where('name', 'Beverages')->first()->id,
                'name'                             => 'Beer #2',
                'description'                      => 'This is beer #2',
                'vat_percentage'                   => 19,
                'price_with_vat'                   => 2,
                'price_without_vat'                => 1.68,
                'cost_price_with_vat'              => 1.50,
                'cost_price_without_vat'           => 1.26,
                'sort_position'                    => 30,
                'currency_iso_4217'                => 'EUR',
                'is_visible'                       => true,
                'pos_can_book_negative_quantities' => false,
                'pos_create_booking_for_item_id'   => Item::where('name', 'Deposit')->first()->id,
            ],
            [
                'item_type_id'                     => ItemType::where('name', 'Beverages')->first()->id,
                'name'                             => 'Softdrink #1',
                'description'                      => 'This is softdrink #1',
                'vat_percentage'                   => 19,
                'price_with_vat'                   => 1.5,
                'price_without_vat'                => 1.26,
                'cost_price_with_vat'              => 1.00,
                'cost_price_without_vat'           => 0.84,
                'sort_position'                    => 40,
                'currency_iso_4217'                => 'EUR',
                'is_visible'                       => true,
                'pos_can_book_negative_quantities' => false,
            ],
            [
                'item_type_id'                     => ItemType::where('name', 'Beverages')->first()->id,
                'name'                             => 'Softdrink #2',
                'description'                      => 'This is softdrink #2',
                'vat_percentage'                   => 19,
                'price_with_vat'                   => 2,
                'price_without_vat'                => 1.68,
                'cost_price_with_vat'              => 1.50,
                'cost_price_without_vat'           => 1.26,
                'sort_position'                    => 50,
                'currency_iso_4217'                => 'EUR',
                'is_visible'                       => true,
                'pos_can_book_negative_quantities' => false,
            ],
            [
                'item_type_id'                     => ItemType::where('name', 'Merchandise')->first()->id,
                'name'                             => 'T-Shirt',
                'description'                      => 'This is a T-Shirt',
                'vat_percentage'                   => 19,
                'price_with_vat'                   => 15,
                'price_without_vat'                => 12.61,
                'cost_price_with_vat'              => 5.00,
                'cost_price_without_vat'           => 4.20,
                'sort_position'                    => 60,
                'currency_iso_4217'                => 'EUR',
                'is_visible'                       => true,
                'pos_can_book_negative_quantities' => false,
            ],
            [
                'item_type_id'                     => ItemType::where('name', 'Entrance')->first()->id,
                'name'                             => 'Entrance Fee',
                'description'                      => 'This is the entrance fee',
                'vat_percentage'                   => 19,
                'price_with_vat'                   => 40,
                'price_without_vat'                => 33.61,
                'cost_price_with_vat'              => 0,
                'cost_price_without_vat'           => 0,
                'sort_position'                    => 70,
                'currency_iso_4217'                => 'EUR',
                'is_visible'                       => true,
                'pos_can_book_negative_quantities' => false,
            ],
        ];

        foreach ($items as $item) {
            $this->createItem($item);
        }
    }


    /**
     * @param $item
     */
    public function createItem($item)
    {
        DB::table('items')->insert([
            'item_type_id'                     => Arr::get($item, 'item_type_id'),
            'name'                             => Arr::get($item, 'name'),
            'description'                      => Arr::get($item, 'description'),
            'vat_percentage'                   => Arr::get($item, 'vat_percentage'),
            'price_with_vat'                   => Arr::get($item, 'price_with_vat'),
            'price_without_vat'                => Arr::get($item, 'price_without_vat'),
            'cost_price_with_vat'              => Arr::get($item, 'cost_price_with_vat'),
            'cost_price_without_vat'           => Arr::get($item, 'cost_price_without_vat'),
            'sort_position'                    => Arr::get($item, 'sort_position'),
            'currency_iso_4217'                => Arr::get($item, 'currency_iso_4217'),
            'is_visible'                       => Arr::get($item, 'is_visible'),
            'pos_can_book_negative_quantities' => Arr::get($item, 'pos_can_book_negative_quantities'),
            'pos_create_booking_for_item_id'   => Arr::get($item, 'pos_create_booking_for_item_id'),
            'created_by'                       => User::get()->first()->id,
            'updated_by'                       => User::get()->first()->id,
        ]);
    }
}
