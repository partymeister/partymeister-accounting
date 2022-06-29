<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Accounting\Models\Item;

/**
 * Class PartymeisterAccountingBackendItemTest
 */
class PartymeisterAccountingBackendItemTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var
     */
    protected $user;

    /**
     * @var
     */
    protected $readPermission;

    /**
     * @var
     */
    protected $writePermission;

    /**
     * @var
     */
    protected $deletePermission;

    /**
     * @var array
     */
    protected $tables = [
        'items',
        'item_types',
        'accounts',
        'bookings',
        'users',
        'languages',
        'clients',
        'permissions',
        'roles',
        'user_has_permissions',
        'user_has_roles',
        'role_has_permissions',
        'media',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../../../../database/factories');

        $this->addDefaults();
    }

    protected function addDefaults()
    {
        $this->user = create_test_superadmin();

        $this->readPermission = create_test_permission_with_name('items.read');
        $this->writePermission = create_test_permission_with_name('items.write');
        $this->deletePermission = create_test_permission_with_name('items.delete');

        $this->actingAs($this->user);
    }

    /** @test */
    public function can_see_grid_without_item()
    {
        $this->visit('/backend/items')->see('Items')->see('No records');
    }

    /** @test */
    public function can_see_grid_with_one_item()
    {
        $record = create_test_item();
        $this->visit('/backend/items')->see('Items')->see($record->name);
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_item_and_use_the_back_button()
    {
        $record = create_test_item();
        $this->visit('/backend/items')->within('table', function () {
            $this->click('Edit');
        })->seePageIs('/backend/items/'.$record->id.'/edit')->click('back')->seePageIs('/backend/items');
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_item_and_change_values()
    {
        $record = create_test_item();

        $this->visit('/backend/items/'.$record->id.'/edit')
             ->see($record->name)
             ->type('Updated Item', 'name')
             ->type('Updated Item Description', 'description')
             ->within('.box-footer', function () {
                 $this->press('Save item');
             })
             ->see('Item updated')
             ->see('Updated Item')
             ->seePageIs('/backend/items');

        $record = Item::find($record->id);
        $this->assertEquals('Updated Item', $record->name);
    }

    /** @test */
    public function can_click_the_item_create_button()
    {
        $this->visit('/backend/items')->click('Create item')->seePageIs('/backend/items/create');
    }

    /** @test */
    public function can_create_a_new_item()
    {
        $item_type = create_test_item_type();
        $this->visit('/backend/items/create')
             ->see('Create item')
             ->type($item_type->id, 'item_type_id')
             ->type('Create Item Name', 'name')
             ->type('Create Item Description', 'description')
             ->within('.box-footer', function () {
                 $this->press('Save item');
             })
             ->see('Item created')
             ->see('Create Item Name')
             ->seePageIs('/backend/items');
    }

    /** @test */
    public function cannot_create_a_new_item_with_empty_fields()
    {
        $this->visit('/backend/items/create')->see('Create item')->within('.box-footer', function () {
            $this->press('Save item');
        })->see('Data missing!')->seePageIs('/backend/items/create');
    }

    /** @test */
    public function can_modify_a_item()
    {
        $record = create_test_item();
        $this->visit('/backend/items/'.$record->id.'/edit')
             ->see('Edit item')
             ->type('Modified Item Name', 'name')
             ->type('Modified Item Description', 'description')
             ->within('.box-footer', function () {
                 $this->press('Save item');
             })
             ->see('Item updated')
             ->see('Modified Item Name')
             ->seePageIs('/backend/items');
    }

    /** @test */
    public function can_delete_a_item()
    {
        create_test_item();

        $this->assertCount(1, Item::all());

        $this->visit('/backend/items')->within('table', function () {
            $this->press('Delete');
        })->seePageIs('/backend/items');

        $this->assertCount(0, Item::all());
    }

    /** @test */
    public function can_paginate_item_results()
    {
        $records = create_test_item(100);
        $this->visit('/backend/items')->within('.pagination', function () {
            $this->click('3');
        })->seePageIs('/backend/items?page=3');
    }

    /** @test */
    public function can_search_item_results()
    {
        $records = create_test_item(10);
        $this->visit('/backend/items')
             ->type(substr($records[6]->name, 0, 3), 'search')
             ->press('grid-search-button')
             ->seeInField('search', substr($records[6]->name, 0, 3))
             ->see($records[6]->name);
    }
}
