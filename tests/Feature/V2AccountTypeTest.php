<?php

use Motor\Admin\Models\User;
use Partymeister\Accounting\Models\AccountType;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'AccountType');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    AccountType::create(['name' => 'Cash']);
    AccountType::create(['name' => 'Card']);
});

describe('V2 AccountTypes API', function () {

    it('requires authentication', function () {
        assertV2RequiresAuth('/api/v2/account-types');
    });

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/account-types');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all account types', function () {
        assertV2CrudIndex('/api/v2/account-types', 2, ['id', 'name']);
    });

    it('can get a specific account type', function () {
        assertV2CrudShow(
            '/api/v2/account-types/'.AccountType::first()->id,
            ['id', 'name']
        );
    });

    it('can create an account type', function () {
        assertV2CrudCreate('/api/v2/account-types', [
            'name' => 'Test Type',
        ], AccountType::class);
    });

    it('validates required fields on create', function () {
        $countBefore = AccountType::count();
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/account-types', [])
            ->assertStatus(422);
        expect(AccountType::count() - $countBefore)->toBe(0);
    });

    it('can update an account type', function () {
        assertV2CrudUpdate(
            '/api/v2/account-types/'.AccountType::first()->id,
            ['name' => 'Updated Name'],
            'name',
            'Updated Name'
        );
    });

    it('can delete an account type with 204 No Content', function () {
        assertV2CrudDelete(
            '/api/v2/account-types/'.AccountType::latest('id')->first()->id,
            AccountType::class
        );
    });
});
