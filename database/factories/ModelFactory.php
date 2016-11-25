<?php

$factory->define(Partymeister\Accounting\Models\AccountType::class, function (Faker\Generator $faker) {
    return [
        'name'       => $faker->word . '.' . str_random(20),
        'created_by' => factory(Motor\Backend\Models\User::class)->create()->id,
        'updated_by' => factory(Motor\Backend\Models\User::class)->create()->id,
    ];
});

$factory->define(Partymeister\Accounting\Models\Account::class, function (Faker\Generator $faker) {
    return [
        'name'              => $faker->word . '.' . str_random(20),
        'account_type_id'   => factory(Partymeister\Accounting\Models\AccountType::class)->create()->id,
        'has_pos'           => (bool) rand(0, 1),
        'currency_iso_4217' => 'EUR',
        'created_by'        => factory(Motor\Backend\Models\User::class)->create()->id,
        'updated_by'        => factory(Motor\Backend\Models\User::class)->create()->id,
    ];
});

$factory->define(Partymeister\Accounting\Models\Booking::class, function (Faker\Generator $faker) {
    return [
        'from_account_id'   => factory(Partymeister\Accounting\Models\Account::class)->create()->id,
        'to_account_id'     => factory(Partymeister\Accounting\Models\Account::class)->create()->id,
        'description'       => $faker->sentence,
        'is_manual_booking' => (bool) rand(0, 1),
        'vat_percentage'    => rand(0, 19),
        'price_with_vat'    => rand(0, 10000) / 100,
        'price_without_vat' => rand(0, 10000) / 100,
        'currency_iso_4217' => 'EUR',
        'created_by'        => factory(Motor\Backend\Models\User::class)->create()->id,
        'updated_by'        => factory(Motor\Backend\Models\User::class)->create()->id,
    ];
});

$factory->define(Partymeister\Accounting\Models\ItemType::class, function (Faker\Generator $faker) {
    return [
        'name'          => $faker->word,
        'sort_position' => $faker->numberBetween(0, 100),
        'is_visible'    => (bool) rand(0, 1),
        'created_by'    => factory(Motor\Backend\Models\User::class)->create()->id,
        'updated_by'    => factory(Motor\Backend\Models\User::class)->create()->id,
    ];
});

$factory->define(Partymeister\Accounting\Models\Item::class, function (Faker\Generator $faker) {
    return [
        'name'                             => $faker->word,
        'description'                      => $faker->paragraph,
        'internal_description'             => $faker->paragraph,
        'vat_percentage'                   => rand(0, 19),
        'price_with_vat'                   => rand(0, 10000) / 100,
        'price_without_vat'                => rand(0, 10000) / 100,
        'cost_price_with_vat'              => rand(0, 10000) / 100,
        'cost_price_without_vat'           => rand(0, 10000) / 100,
        'currency_iso_4217'                => 'EUR',
        'can_be_ordered'                   => (bool) rand(0, 1),
        'sort_position'                    => $faker->numberBetween(0, 100),
        'pos_sort_position'                => $faker->numberBetween(0, 100),
        'is_visible_in_pos'                => (bool) rand(0, 1),
        //'pos_create_booking_for_item_id'   => factory(Partymeister\Accounting\Models\Item::class)->create()->id,
        'pos_can_book_negative_quantities' => (bool) rand(0, 1),
        'pos_do_break'                     => (bool) rand(0, 1),
        'item_type_id'                     => factory(Partymeister\Accounting\Models\ItemType::class)->create()->id,
        'pos_earnings_account_id'          => factory(Partymeister\Accounting\Models\Account::class)->create()->id,
        'pos_cost_account_id'              => factory(Partymeister\Accounting\Models\Account::class)->create()->id,
        'created_by'                       => factory(Motor\Backend\Models\User::class)->create()->id,
        'updated_by'                       => factory(Motor\Backend\Models\User::class)->create()->id,
    ];
});