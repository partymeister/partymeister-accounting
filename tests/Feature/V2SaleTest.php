<?php

use Motor\Admin\Models\User;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\AccountType;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\Sale;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'Sale');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    $accountType = AccountType::factory()->create(['name' => 'Cash']);
    $account = Account::factory()->create([
        'name' => 'POS',
        'account_type_id' => $accountType->id,
        'has_pos' => true,
    ]);

    $item = Item::factory()->create([
        'name' => 'Water',
        'vat_percentage' => 19,
        'price_with_vat' => 2.50,
        'price_without_vat' => 2.10,
        'cost_price_with_vat' => 0.50,
        'cost_price_without_vat' => 0.42,
    ]);

    $booking = Booking::factory()->create([
        'description' => '2x Water',
        'vat_percentage' => 19,
        'price_with_vat' => 5.00,
        'price_without_vat' => 4.20,
        'to_account_id' => $account->id,
    ]);

    Sale::factory()->create([
        'item_id' => $item->id,
        'earnings_booking_id' => $booking->id,
        'quantity' => 2,
        'vat_percentage' => 19,
        'price_with_vat' => 5.00,
        'price_without_vat' => 4.20,
    ]);
});

describe('V2 Sales API', function () {

    it('requires authentication', function () {
        assertV2RequiresAuth('/api/v2/sales');
    });

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/sales');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all sales', function () {
        assertV2CrudIndex('/api/v2/sales', 1, ['id', 'quantity', 'price_with_vat', 'currency_iso_4217']);
    });

    it('can get a specific sale with relations', function () {
        $response = assertV2CrudShow(
            '/api/v2/sales/'.Sale::first()->id,
            ['id', 'quantity', 'item', 'earnings_booking']
        );

        $response->assertJsonPath('data.item.name', 'Water');
        $response->assertJsonPath('data.earnings_booking.description', '2x Water');
    });

    it('can create a sale', function () {
        $item = Item::first();
        $booking = Booking::first();
        assertV2CrudCreate('/api/v2/sales', [
            'item_id' => $item->id,
            'earnings_booking_id' => $booking->id,
            'quantity' => 3,
            'vat_percentage' => 19,
            'price_with_vat' => 7.50,
            'price_without_vat' => 6.30,
            'currency_iso_4217' => 'EUR',
        ], Sale::class);
    });

    it('validates required fields on create', function () {
        $countBefore = Sale::count();
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/sales', [])
            ->assertStatus(422);
        expect(Sale::count() - $countBefore)->toBe(0);
    });

    it('can update a sale', function () {
        assertV2CrudUpdate(
            '/api/v2/sales/'.Sale::first()->id,
            ['quantity' => 5],
            'quantity',
            5
        );
    });

    it('can delete a sale with 204 No Content', function () {
        assertV2CrudDelete(
            '/api/v2/sales/'.Sale::latest('id')->first()->id,
            Sale::class
        );
    });
});
