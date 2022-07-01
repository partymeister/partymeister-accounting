<?php

namespace Partymeister\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Class AccountsTableSeeder
 */
class PartymeisterAccountingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                AccountsTableSeeder::class,
                ItemTypesTableSeeder::class,
                ItemsTableSeeder::class,
                PosItemsTableSeeder::class,
            ]
        );
    }
}
