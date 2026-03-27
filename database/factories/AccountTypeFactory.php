<?php

namespace Partymeister\Accounting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Partymeister\Accounting\Models\AccountType;

class AccountTypeFactory extends Factory
{
    protected $model = AccountType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word.'.'.Str::random(20),
        ];
    }
}
