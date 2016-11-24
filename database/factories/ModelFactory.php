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
