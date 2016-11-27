<?php

namespace Partymeister\Accounting\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Motor\Backend\Models\User;
use Partymeister\Accounting\Models\AccountType;

class AccountsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_types')->insert([
            'name'       => 'Cash',
            'created_by' => User::get()->first()->id,
            'updated_by' => User::get()->first()->id,
        ]);

        DB::table('accounts')->insert([
            'account_type_id'   => AccountType::get()->first()->id,
            'name'              => 'POS',
            'currency_iso_4217' => 'EUR',
            'has_pos'           => true,
            'created_by'        => User::get()->first()->id,
            'updated_by'        => User::get()->first()->id,
        ]);

        DB::table('accounts')->insert([
            'account_type_id'   => AccountType::get()->first()->id,
            'name'              => 'Earnings account',
            'currency_iso_4217' => 'EUR',
            'has_pos'           => false,
            'created_by'        => User::get()->first()->id,
            'updated_by'        => User::get()->first()->id,
        ]);
        DB::table('accounts')->insert([
            'account_type_id'   => AccountType::get()->first()->id,
            'name'              => 'Cost account',
            'currency_iso_4217' => 'EUR',
            'has_pos'           => false,
            'created_by'        => User::get()->first()->id,
            'updated_by'        => User::get()->first()->id,
        ]);
    }
}
