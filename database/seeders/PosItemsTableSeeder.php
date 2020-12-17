<?php

namespace Partymeister\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\ItemType;

/**
 * Class AccountsTableSeeder
 * @package Partymeister\Accounting\Database\Seeds
 */
class PosItemsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posConfig = [
            '1' => ItemType::where('name', 'Entrance')->first()->items()->pluck('id')->toArray(),
            '2' => ItemType::where('name', 'Beverages')->first()->items()->pluck('id')->toArray(),
            '3' => ItemType::where('name', 'Merchandise')->first()->items()->pluck('id')->toArray()
        ];

        $account = Account::where('name', 'POS')->first();
        $account->pos_configuration = $posConfig;
        $account->save();
    }
}
