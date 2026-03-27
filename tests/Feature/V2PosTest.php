<?php

use Motor\Admin\Models\User;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\AccountType;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\ItemType;
use Partymeister\Accounting\Models\Sale;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'POS');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    $accountType = AccountType::factory()->create(['name' => 'Cash']);
    $itemType = ItemType::factory()->create(['name' => 'Beverages', 'is_visible' => true, 'sort_position' => 1]);

    $water = Item::factory()->create([
        'name' => 'Water',
        'item_type_id' => $itemType->id,
        'vat_percentage' => 19,
        'price_with_vat' => 2.50,
        'price_without_vat' => 2.10,
        'cost_price_with_vat' => 0.50,
        'cost_price_without_vat' => 0.42,
        'sort_position' => 1,
    ]);
    $beer = Item::factory()->create([
        'name' => 'Beer',
        'item_type_id' => $itemType->id,
        'vat_percentage' => 19,
        'price_with_vat' => 3.00,
        'price_without_vat' => 2.52,
        'cost_price_with_vat' => 1.00,
        'cost_price_without_vat' => 0.84,
        'sort_position' => 2,
    ]);

    Account::factory()->create([
        'name' => 'POS',
        'account_type_id' => $accountType->id,
        'has_pos' => true,
        'has_card_payments' => true,
        'has_coupon_payments' => false,
        'pos_configuration' => [
            '1' => [$water->id, $beer->id],
        ],
    ]);
});

describe('V2 POS Layout API', function () {

    it('requires authentication', function () {
        $account = Account::where('name', 'POS')->first();
        assertV2RequiresAuth('/api/v2/accounts/'.$account->id.'/pos');
    });

    it('can get POS layout with zones and resolved items', function () {
        $account = Account::where('name', 'POS')->first();
        $response = $this->asAdmin()->getJson('/api/v2/accounts/'.$account->id.'/pos');

        $response->assertOk()
            ->assertJsonPath('meta.api_version', 'v2')
            ->assertJsonPath('data.account.name', 'POS')
            ->assertJsonStructure([
                'data' => [
                    'account' => ['id', 'name', 'currency_iso_4217'],
                    'zones',
                ],
            ]);

        // Verify zones have resolved item data
        $zones = $response->json('data.zones');
        $firstZone = head($zones);
        expect($firstZone)->toBeArray();
        expect(count($firstZone))->toBe(2);
        expect($firstZone[0])->toHaveKeys(['id', 'name', 'price_with_vat']);
        expect($firstZone[0]['name'])->toBe('Water');
        expect($firstZone[1]['name'])->toBe('Beer');
    });

    it('can update POS layout', function () {
        $account = Account::where('name', 'POS')->first();
        $water = Item::where('name', 'Water')->first();

        $response = $this->asAdmin()->putJson('/api/v2/accounts/'.$account->id.'/pos', [
            'pos_configuration' => [
                '1' => [$water->id],
            ],
        ]);

        $response->assertOk()
            ->assertJsonPath('meta.api_version', 'v2');

        $account->refresh();
        expect(count($account->pos_configuration['1']))->toBe(1);
    });

    it('validates pos_configuration is required on update', function () {
        $account = Account::where('name', 'POS')->first();

        $this->asAdmin()
            ->putJson('/api/v2/accounts/'.$account->id.'/pos', [])
            ->assertStatus(422);
    });
});

describe('V2 POS Book RPC', function () {

    it('requires authentication', function () {
        $account = Account::where('name', 'POS')->first();
        assertV2RequiresAuth('/api/v2/rpc/accounts/'.$account->id.'/book', 'post');
    });

    it('can process a cart into bookings and sales', function () {
        $account = Account::where('name', 'POS')->first();
        $water = Item::where('name', 'Water')->first();
        $beer = Item::where('name', 'Beer')->first();

        $response = $this->asAdmin()->postJson('/api/v2/rpc/accounts/'.$account->id.'/book', [
            'items' => [
                $water->id => 2,
                $beer->id => 1,
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('meta.api_version', 'v2')
            ->assertJsonStructure(['data' => ['id', 'description', 'price_with_vat']]);

        // Should have created 1 booking and 2 sales
        expect(Booking::count())->toBe(1);
        expect(Sale::count())->toBe(2);
    });

    it('handles card payment flag', function () {
        $account = Account::where('name', 'POS')->first();
        $water = Item::where('name', 'Water')->first();

        $response = $this->asAdmin()->postJson('/api/v2/rpc/accounts/'.$account->id.'/book', [
            'items' => [$water->id => 1],
            'is_card_payment' => true,
        ]);

        $response->assertStatus(201);
        expect((bool) Booking::first()->is_card_payment)->toBeTrue();
    });

    it('handles coupon payment flag', function () {
        $account = Account::where('name', 'POS')->first();
        $water = Item::where('name', 'Water')->first();

        $response = $this->asAdmin()->postJson('/api/v2/rpc/accounts/'.$account->id.'/book', [
            'items' => [$water->id => 1],
            'is_coupon_payment' => true,
        ]);

        $response->assertStatus(201);
        expect((bool) Booking::first()->is_coupon_payment)->toBeTrue();
    });

    it('returns 422 for empty items', function () {
        $account = Account::where('name', 'POS')->first();

        $this->asAdmin()
            ->postJson('/api/v2/rpc/accounts/'.$account->id.'/book', ['items' => []])
            ->assertStatus(422);
    });
});
