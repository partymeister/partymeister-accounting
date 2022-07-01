<?php

namespace Partymeister\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Motor\Admin\Models\User;

/**
 * Class ItemTypesTableSeeder
 * @package Partymeister\Accounting\Database\Seeds
 */
class ItemTypesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $itemTypes = [
            [
                'name'          => 'Beverages',
                'sort_position' => 10,
                'is_visible'    => true
            ],
            [
                'name'          => 'Entrance',
                'sort_position' => 20,
                'is_visible'    => true
            ],
            [
                'name'          => 'Merchandise',
                'sort_position' => 30,
                'is_visible'    => true
            ],
        ];

        foreach ($itemTypes as $itemType) {
            DB::table('item_types')->insert([
                'name'          => $itemType['name'],
                'sort_position' => $itemType['sort_position'],
                'is_visible'    => $itemType['is_visible'],
                'created_by'    => User::get()->first()->id,
                'updated_by'    => User::get()->first()->id,
            ]);
        }
    }
}
