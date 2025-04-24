<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Accounting\Models\ItemType;

/**
 * Class PartymeisterAccountingBackendItemTypeTest
 */
class PartymeisterAccountingBackendItemTypeTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $readPermission;

    protected $writePermission;

    protected $deletePermission;

    /**
     * @var array
     */
    protected $tables = [
        'item_types',
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

    protected function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../../../../database/factories');

        $this->addDefaults();
    }

    protected function addDefaults()
    {
        $this->user = create_test_superadmin();

        $this->readPermission = create_test_permission_with_name('item_types.read');
        $this->writePermission = create_test_permission_with_name('item_types.write');
        $this->deletePermission = create_test_permission_with_name('item_types.delete');

        $this->actingAs($this->user);
    }

    /** @test */
    public function can_see_grid_without_item_type()
    {
        $this->visit('/backend/item_types')->see('Item types')->see('No records');
    }

    /** @test */
    public function can_see_grid_with_one_item_type()
    {
        $record = create_test_item_type();
        $this->visit('/backend/item_types')->see('Item types')->see($record->name);
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_item_type_and_use_the_back_button()
    {
        $record = create_test_item_type();
        $this->visit('/backend/item_types')
            ->within('table', function () {
                $this->click('Edit');
            })
            ->seePageIs('/backend/item_types/'.$record->id.'/edit')
            ->click('back')
            ->seePageIs('/backend/item_types');
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_item_type_and_change_values()
    {
        $record = create_test_item_type();

        $this->visit('/backend/item_types/'.$record->id.'/edit')
            ->see($record->name)
            ->type('Updated Item type', 'name')
            ->within('.box-footer', function () {
                $this->press('Save item type');
            })
            ->see('Item type updated')
            ->see('Updated Item type')
            ->seePageIs('/backend/item_types');

        $record = ItemType::find($record->id);
        $this->assertEquals('Updated Item type', $record->name);
    }

    /** @test */
    public function can_click_the_create_button()
    {
        $this->visit('/backend/item_types')->click('Create item type')->seePageIs('/backend/item_types/create');
    }

    /** @test */
    public function can_create_a_new_item_type()
    {
        $this->visit('/backend/item_types/create')
            ->see('Create item type')
            ->type('Create Item type Name', 'name')
            ->within('.box-footer', function () {
                $this->press('Save item type');
            })
            ->see('Item type created')
            ->see('Create Item type Name')
            ->seePageIs('/backend/item_types');
    }

    /** @test */
    public function cannot_create_a_new_item_type_with_empty_fields()
    {
        $this->visit('/backend/item_types/create')->see('Create item type')->within('.box-footer', function () {
            $this->press('Save item type');
        })->see('Data missing!')->seePageIs('/backend/item_types/create');
    }

    /** @test */
    public function can_modify_a_item_type()
    {
        $record = create_test_item_type();
        $this->visit('/backend/item_types/'.$record->id.'/edit')
            ->see('Edit item type')
            ->type('Modified Item type Name', 'name')
            ->within('.box-footer', function () {
                $this->press('Save item type');
            })
            ->see('Item type updated')
            ->see('Modified Item type Name')
            ->seePageIs('/backend/item_types');
    }

    /** @test */
    public function can_delete_a_item_type()
    {
        create_test_item_type();

        $this->assertCount(1, ItemType::all());

        $this->visit('/backend/item_types')->within('table', function () {
            $this->press('Delete');
        })->seePageIs('/backend/item_types');

        $this->assertCount(0, ItemType::all());
    }

    /** @test */
    public function can_paginate_results()
    {
        $records = create_test_item_type(100);
        $this->visit('/backend/item_types')->within('.pagination', function () {
            $this->click('3');
        })->seePageIs('/backend/item_types?page=3');
    }

    /** @test */
    public function can_search_item_type_results()
    {
        $records = create_test_item_type(10);
        $this->visit('/backend/item_types')
            ->type(substr($records[6]->name, 0, 3), 'search')
            ->press('grid-search-button')
            ->seeInField('search', substr($records[6]->name, 0, 3))
            ->see($records[6]->name);
    }
}
