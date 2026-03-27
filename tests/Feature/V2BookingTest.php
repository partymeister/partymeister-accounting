<?php

use Motor\Admin\Models\User;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\AccountType;
use Partymeister\Accounting\Models\Booking;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'Booking');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    $type = AccountType::create(['name' => 'Cash']);
    $from = Account::create([
        'name' => 'Customer',
        'account_type_id' => $type->id,
        'currency_iso_4217' => 'EUR',
    ]);
    $to = Account::create([
        'name' => 'POS',
        'account_type_id' => $type->id,
        'currency_iso_4217' => 'EUR',
        'has_pos' => true,
    ]);

    Booking::create([
        'description' => '2x Water',
        'vat_percentage' => 19,
        'price_with_vat' => 5.00,
        'price_without_vat' => 4.20,
        'currency_iso_4217' => 'EUR',
        'to_account_id' => $to->id,
        'is_manual_booking' => false,
    ]);
    Booking::create([
        'description' => '1x Beer',
        'vat_percentage' => 19,
        'price_with_vat' => 3.00,
        'price_without_vat' => 2.52,
        'currency_iso_4217' => 'EUR',
        'to_account_id' => $to->id,
        'is_manual_booking' => false,
    ]);
});

describe('V2 Bookings API', function () {

    it('requires authentication', function () {
        assertV2RequiresAuth('/api/v2/bookings');
    });

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/bookings');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all bookings', function () {
        assertV2CrudIndex('/api/v2/bookings', 2, ['id', 'description', 'price_with_vat', 'currency_iso_4217']);
    });

    it('can get a specific booking with account relations', function () {
        $response = assertV2CrudShow(
            '/api/v2/bookings/'.Booking::first()->id,
            ['id', 'description', 'to_account']
        );

        $response->assertJsonPath('data.to_account.name', 'POS');
    });

    it('can create a booking', function () {
        $to = Account::where('name', 'POS')->first();
        assertV2CrudCreate('/api/v2/bookings', [
            'description' => '3x Soda',
            'vat_percentage' => 19,
            'price_with_vat' => 6.00,
            'price_without_vat' => 5.04,
            'currency_iso_4217' => 'EUR',
            'to_account_id' => $to->id,
            'is_manual_booking' => true,
        ], Booking::class);
    });

    it('validates required fields on create', function () {
        $countBefore = Booking::count();
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/bookings', [])
            ->assertStatus(422);
        expect(Booking::count() - $countBefore)->toBe(0);
    });

    it('can update a booking', function () {
        assertV2CrudUpdate(
            '/api/v2/bookings/'.Booking::first()->id,
            ['description' => 'Updated description'],
            'description',
            'Updated description'
        );
    });

    it('rejects mismatched currency between booking and account', function () {
        $usdType = AccountType::create(['name' => 'USD Type']);
        $usdAccount = Account::create([
            'name' => 'USD Account',
            'account_type_id' => $usdType->id,
            'currency_iso_4217' => 'USD',
        ]);

        $response = $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/v2/bookings', [
                'description' => 'Cross-currency booking',
                'vat_percentage' => 19,
                'price_with_vat' => 5.00,
                'price_without_vat' => 4.20,
                'currency_iso_4217' => 'EUR',
                'to_account_id' => $usdAccount->id,
            ]);

        $response->assertStatus(422);
    });

    it('can delete a booking with 204 No Content', function () {
        assertV2CrudDelete(
            '/api/v2/bookings/'.Booking::latest('id')->first()->id,
            Booking::class
        );
    });
});
