<?php

use Motor\Admin\Models\User;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\AccountType;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\ItemType;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'AccountItems');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    $accountType = AccountType::create(['name' => 'Cash']);
    $itemType = ItemType::create(['name' => 'Beverages', 'is_visible' => true, 'sort_position' => 1]);

    $water = Item::create([
        'name' => 'Water',
        'item_type_id' => $itemType->id,
        'vat_percentage' => 19,
        'price_with_vat' => 2.50,
        'price_without_vat' => 2.10,
        'cost_price_with_vat' => 0.50,
        'cost_price_without_vat' => 0.42,
        'currency_iso_4217' => 'EUR',
        'sort_position' => 1,
    ]);
    $beer = Item::create([
        'name' => 'Beer',
        'item_type_id' => $itemType->id,
        'vat_percentage' => 19,
        'price_with_vat' => 3.00,
        'price_without_vat' => 2.52,
        'cost_price_with_vat' => 1.00,
        'cost_price_without_vat' => 0.84,
        'currency_iso_4217' => 'EUR',
        'sort_position' => 2,
    ]);
    $tshirt = Item::create([
        'name' => 'T-Shirt',
        'item_type_id' => $itemType->id,
        'vat_percentage' => 19,
        'price_with_vat' => 15.00,
        'price_without_vat' => 12.61,
        'cost_price_with_vat' => 5.00,
        'cost_price_without_vat' => 4.20,
        'currency_iso_4217' => 'EUR',
        'sort_position' => 3,
    ]);

    // POS account with water and beer in zone 1, tshirt in zone 2
    Account::create([
        'name' => 'POS',
        'account_type_id' => $accountType->id,
        'currency_iso_4217' => 'EUR',
        'has_pos' => true,
        'pos_configuration' => [
            '1' => [$water->id, $beer->id],
            '2' => [$tshirt->id],
        ],
    ]);

    // Cost account with no items
    Account::create([
        'name' => 'Cost Account',
        'account_type_id' => $accountType->id,
        'currency_iso_4217' => 'EUR',
        'has_pos' => false,
        'pos_configuration' => [],
    ]);
});

describe('V2 Account Items API', function () {

    it('returns items for the given account from pos_configuration', function () {
        $pos = Account::where('name', 'POS')->first();
        $response = $this->asAdmin()->getJson('/api/v2/accounts/'.$pos->id.'/items');

        $response->assertOk()
            ->assertJsonPath('meta.api_version', 'v2')
            ->assertJsonCount(3, 'data');
    });

    it('returns empty for account with no items configured', function () {
        $cost = Account::where('name', 'Cost Account')->first();
        $response = $this->asAdmin()->getJson('/api/v2/accounts/'.$cost->id.'/items');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    });
});
