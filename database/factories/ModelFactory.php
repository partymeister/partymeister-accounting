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
        'created_by'        => factory(Motor\Backend\Models\User::class)->create()->id,
        'updated_by'        => factory(Motor\Backend\Models\User::class)->create()->id,
    ];
});
