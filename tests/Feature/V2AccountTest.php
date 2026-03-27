<?php

use Motor\Admin\Models\User;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\AccountType;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'Account');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    $type = AccountType::create(['name' => 'Cash']);

    Account::create([
        'name' => 'POS',
        'account_type_id' => $type->id,
        'currency_iso_4217' => 'EUR',
        'has_pos' => true,
        'pos_configuration' => [],
    ]);
    Account::create([
        'name' => 'Cost Account',
        'account_type_id' => $type->id,
        'currency_iso_4217' => 'EUR',
        'has_pos' => false,
        'pos_configuration' => [],
    ]);
});

describe('V2 Accounts API', function () {

    it('requires authentication', function () {
        assertV2RequiresAuth('/api/v2/accounts');
    });

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/accounts');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all accounts', function () {
        assertV2CrudIndex('/api/v2/accounts', 2, ['id', 'name', 'currency_iso_4217', 'has_pos']);
    });

    it('includes balance fields on index', function () {
        $response = $this->asAdmin()->getJson('/api/v2/accounts');

        $response->assertOk()
            ->assertJsonStructure(['data' => ['*' => ['cash_balance', 'card_balance', 'coupon_balance']]]);
    });

    it('can get a specific account with account_type relation', function () {
        $response = assertV2CrudShow(
            '/api/v2/accounts/'.Account::first()->id,
            ['id', 'name', 'currency_iso_4217', 'has_pos', 'account_type']
        );

        $response->assertJsonPath('data.account_type.name', 'Cash');
    });

    it('can create an account', function () {
        $type = AccountType::first();
        assertV2CrudCreate('/api/v2/accounts', [
            'name' => 'New Account',
            'account_type_id' => $type->id,
            'currency_iso_4217' => 'EUR',
        ], Account::class);
    });

    it('validates required fields on create', function () {
        $countBefore = Account::count();
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/accounts', [])
            ->assertStatus(422);
        expect(Account::count() - $countBefore)->toBe(0);
    });

    it('can update an account', function () {
        assertV2CrudUpdate(
            '/api/v2/accounts/'.Account::first()->id,
            ['name' => 'Updated POS'],
            'name',
            'Updated POS'
        );
    });

    it('can delete an account with 204 No Content', function () {
        assertV2CrudDelete(
            '/api/v2/accounts/'.Account::latest('id')->first()->id,
            Account::class
        );
    });
});
