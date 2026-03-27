<?php

use Motor\Admin\Models\User;
use Partymeister\Accounting\Models\ItemType;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'ItemType');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    ItemType::factory()->create(['name' => 'Beverages', 'is_visible' => true, 'sort_position' => 1]);
    ItemType::factory()->create(['name' => 'Merchandise', 'is_visible' => true, 'sort_position' => 2]);
});

describe('V2 ItemTypes API', function () {

    it('requires authentication', function () {
        assertV2RequiresAuth('/api/v2/item-types');
    });

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/item-types');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all item types', function () {
        assertV2CrudIndex('/api/v2/item-types', 2, ['id', 'name', 'is_visible', 'sort_position']);
    });

    it('can get a specific item type', function () {
        assertV2CrudShow(
            '/api/v2/item-types/'.ItemType::first()->id,
            ['id', 'name', 'is_visible', 'sort_position']
        );
    });

    it('can create an item type', function () {
        assertV2CrudCreate('/api/v2/item-types', [
            'name' => 'Food',
            'is_visible' => true,
            'sort_position' => 3,
        ], ItemType::class);
    });

    it('validates required fields on create', function () {
        $countBefore = ItemType::count();
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/item-types', [])
            ->assertStatus(422);
        expect(ItemType::count() - $countBefore)->toBe(0);
    });

    it('can update an item type', function () {
        assertV2CrudUpdate(
            '/api/v2/item-types/'.ItemType::first()->id,
            ['name' => 'Updated Name'],
            'name',
            'Updated Name'
        );
    });

    it('can delete an item type with 204 No Content', function () {
        assertV2CrudDelete(
            '/api/v2/item-types/'.ItemType::latest('id')->first()->id,
            ItemType::class
        );
    });
});
