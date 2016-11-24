<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Accounting\Models\AccountType;

class BackendAccountTypeTest extends TestCase
{

    use DatabaseTransactions;

    protected $user;

    protected $readPermission;

    protected $writePermission;

    protected $deletePermission;

    protected $tables = [
        'account_types',
        'users',
        'languages',
        'clients',
        'permissions',
        'roles',
        'user_has_permissions',
        'user_has_roles',
        'role_has_permissions',
        'media'
    ];


    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../../../../database/factories');

        $this->addDefaults();
    }


    protected function addDefaults()
    {
        $this->user   = create_test_superadmin();

        $this->readPermission   = create_test_permission_with_name('account_types.read');
        $this->writePermission  = create_test_permission_with_name('account_types.write');
        $this->deletePermission = create_test_permission_with_name('account_types.delete');

        $this->actingAs($this->user);
    }


    /** @test */
    public function can_see_grid_without_account_type()
    {
        $this->visit('/backend/account_types')
            ->see('Account types')
            ->see('No records');
    }

    /** @test */
    public function can_see_grid_with_one_account_type()
    {
        $record = create_test_account_type();
        $this->visit('/backend/account_types')
            ->see('Account types')
            ->see($record->name);
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_account_type_and_use_the_back_button()
    {
        $record = create_test_account_type();
        $this->visit('/backend/account_types')
            ->within('table', function(){
                $this->click('Edit');
            })
            ->seePageIs('/backend/account_types/'.$record->id.'/edit')
            ->click('back')
            ->seePageIs('/backend/account_types');
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_account_type_and_change_values()
    {
        $record = create_test_account_type();

        $this->visit('/backend/account_types/'.$record->id.'/edit')
            ->see($record->name)
            ->type('Updated Account type', 'name')
            ->within('.box-footer', function(){
                $this->press('Save account type');
            })
            ->see('Account type updated')
            ->see('Updated Account type')
            ->seePageIs('/backend/account_types');

        $record = AccountType::find($record->id);
        $this->assertEquals('Updated Account type', $record->name);
    }

    /** @test */
    public function can_click_the_create_button()
    {
        $this->visit('/backend/account_types')
            ->click('Create account type')
            ->seePageIs('/backend/account_types/create');
    }

    /** @test */
    public function can_create_a_new_account_type()
    {
        $this->visit('/backend/account_types/create')
            ->see('Create account type')
            ->type('Create Account type Name', 'name')
            ->within('.box-footer', function(){
                $this->press('Save account type');
            })
            ->see('Account type created')
            ->see('Create Account type Name')
            ->seePageIs('/backend/account_types');
    }

    /** @test */
    public function cannot_create_a_new_account_type_with_empty_fields()
    {
        $this->visit('/backend/account_types/create')
            ->see('Create account type')
            ->within('.box-footer', function(){
                $this->press('Save account type');
            })
            ->see('Data missing!')
            ->seePageIs('/backend/account_types/create');
    }

    /** @test */
    public function can_modify_a_account_type()
    {
        $record = create_test_account_type();
        $this->visit('/backend/account_types/'.$record->id.'/edit')
            ->see('Edit account type')
            ->type('Modified Account type Name', 'name')
            ->within('.box-footer', function(){
                $this->press('Save account type');
            })
            ->see('Account type updated')
            ->see('Modified Account type Name')
            ->seePageIs('/backend/account_types');
    }

    /** @test */
    public function can_delete_a_account_type()
    {
        create_test_account_type();

        $this->assertCount(1, AccountType::all());

        $this->visit('/backend/account_types')
            ->within('table', function(){
                $this->press('Delete');
            })
            ->seePageIs('/backend/account_types');

        $this->assertCount(0, AccountType::all());
    }

    /** @test */
    public function can_paginate_results()
    {
        $records = create_test_account_type(100);
        $this->visit('/backend/account_types')
            ->within('.pagination', function(){
                $this->click('3');
            })
            ->seePageIs('/backend/account_types?page=3');
    }

    /** @test */
    public function can_search_results()
    {
        $records = create_test_account_type(100);
        $this->visit('/backend/account_types')
            ->type(substr($records[6]->name, 0, 3), 'search')
            ->press('grid-search-button')
            ->seeInField('search', substr($records[6]->name, 0, 3))
            ->see($records[6]->name);
    }
}
