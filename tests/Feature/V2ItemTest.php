<?php

use Motor\Admin\Models\User;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\ItemType;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'Item');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create([
        'email' => 'admin@motor-cms.com',
        'name' => 'Admin',
    ]);
    $user->assignRole($role);

    $type = ItemType::create(['name' => 'Beverages', 'is_visible' => true, 'sort_position' => 1]);

    Item::create([
        'name' => 'Water',
        'description' => '1L PET',
        'internal_description' => '',
        'item_type_id' => $type->id,
        'vat_percentage' => 19,
        'price_with_vat' => 2.50,
        'price_without_vat' => 2.10,
        'cost_price_with_vat' => 0.50,
        'cost_price_without_vat' => 0.42,
        'currency_iso_4217' => 'EUR',
        'can_be_ordered' => false,
        'is_visible' => true,
        'sort_position' => 1,
        'pos_can_book_negative_quantities' => false,
    ]);
    Item::create([
        'name' => 'Beer',
        'description' => '0.5L',
        'internal_description' => '',
        'item_type_id' => $type->id,
        'vat_percentage' => 19,
        'price_with_vat' => 3.00,
        'price_without_vat' => 2.52,
        'cost_price_with_vat' => 1.00,
        'cost_price_without_vat' => 0.84,
        'currency_iso_4217' => 'EUR',
        'can_be_ordered' => false,
        'is_visible' => true,
        'sort_position' => 2,
        'pos_can_book_negative_quantities' => false,
    ]);
});

describe('V2 Items API', function () {

    it('requires authentication', function () {
        assertV2RequiresAuth('/api/v2/items');
    });

    it('includes api_version v2 in response meta', function () {
        $response = $this->asAdmin()->getJson('/api/v2/items');

        $response->assertStatus(200)
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can get all items', function () {
        assertV2CrudIndex('/api/v2/items', 2, ['id', 'name', 'price_with_vat', 'currency_iso_4217']);
    });

    it('can get a specific item with item_type relation', function () {
        $response = assertV2CrudShow(
            '/api/v2/items/'.Item::first()->id,
            ['id', 'name', 'item_type']
        );

        $response->assertJsonPath('data.item_type.name', 'Beverages');
    });

    it('can create an item', function () {
        $type = ItemType::first();
        assertV2CrudCreate('/api/v2/items', [
            'name' => 'Soda',
            'description' => 'Cola 0.33L',
            'internal_description' => 'N/A',
            'item_type_id' => $type->id,
            'vat_percentage' => 19,
            'price_with_vat' => 2.00,
            'price_without_vat' => 1.68,
            'cost_price_with_vat' => 0.30,
            'cost_price_without_vat' => 0.25,
            'currency_iso_4217' => 'EUR',
        ], Item::class);
    });

    it('validates required fields on create', function () {
        $countBefore = Item::count();
        $this->asAdmin()
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v2/items', [])
            ->assertStatus(422);
        expect(Item::count() - $countBefore)->toBe(0);
    });

    it('can update an item', function () {
        assertV2CrudUpdate(
            '/api/v2/items/'.Item::first()->id,
            ['name' => 'Sparkling Water'],
            'name',
            'Sparkling Water'
        );
    });

    it('can delete an item with 204 No Content', function () {
        assertV2CrudDelete(
            '/api/v2/items/'.Item::latest('id')->first()->id,
            Item::class
        );
    });
});
