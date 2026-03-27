<?php

namespace Partymeister\Accounting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\AccountType;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word.'.'.Str::random(20),
            'account_type_id' => fn () => AccountType::factory()->create()->id,
            'has_pos' => (bool) rand(0, 1),
            'currency_iso_4217' => 'EUR',
            'pos_configuration' => [],
        ];
    }
}
