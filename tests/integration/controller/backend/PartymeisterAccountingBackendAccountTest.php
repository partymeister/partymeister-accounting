<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Accounting\Models\Account;

/**
 * Class PartymeisterAccountingBackendAccountTest
 */
class PartymeisterAccountingBackendAccountTest extends TestCase
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
        'accounts',
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

        $this->readPermission = create_test_permission_with_name('accounts.read');
        $this->writePermission = create_test_permission_with_name('accounts.write');
        $this->deletePermission = create_test_permission_with_name('accounts.delete');

        $this->actingAs($this->user);
    }

    /** @test */
    public function can_see_grid_without_account()
    {
        $this->visit('/backend/accounts')->see('Accounts')->see('No records');
    }

    /** @test */
    public function can_see_grid_with_one_account()
    {
        $record = create_test_account();
        $this->visit('/backend/accounts')->see('Accounts')->see($record->name);
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_account_and_use_the_back_button()
    {
        $record = create_test_account();
        $this->visit('/backend/accounts')->within('table', function () {
            $this->click('Edit');
        })->seePageIs('/backend/accounts/'.$record->id.'/edit')->click('back')->seePageIs('/backend/accounts');
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_account_and_change_values()
    {
        $record = create_test_account();

        $this->visit('/backend/accounts/'.$record->id.'/edit')
            ->see($record->name)
            ->type('Updated Account', 'name')
            ->within('.box-footer', function () {
                $this->press('Save account');
            })
            ->see('Account updated')
            ->see('Updated Account')
            ->seePageIs('/backend/accounts');

        $record = Account::find($record->id);
        $this->assertEquals('Updated Account', $record->name);
    }

    /** @test */
    public function can_click_the_create_button()
    {
        $this->visit('/backend/accounts')->click('Create account')->seePageIs('/backend/accounts/create');
    }

    /** @test */
    public function can_create_a_new_account()
    {
        $this->visit('/backend/accounts/create')
            ->see('Create account')
            ->type('Create Account Name', 'name')
            ->within('.box-footer', function () {
                $this->press('Save account');
            })
            ->see('Account created')
            ->see('Create Account Name')
            ->seePageIs('/backend/accounts');
    }

    /** @test */
    public function cannot_create_a_new_account_with_empty_fields()
    {
        $this->visit('/backend/accounts/create')->see('Create account')->within('.box-footer', function () {
            $this->press('Save account');
        })->see('Data missing!')->seePageIs('/backend/accounts/create');
    }

    /** @test */
    public function can_modify_a_account()
    {
        $record = create_test_account();
        $this->visit('/backend/accounts/'.$record->id.'/edit')
            ->see('Edit account')
            ->type('Modified Account Name', 'name')
            ->within('.box-footer', function () {
                $this->press('Save account');
            })
            ->see('Account updated')
            ->see('Modified Account Name')
            ->seePageIs('/backend/accounts');
    }

    /** @test */
    public function can_delete_a_account()
    {
        create_test_account();

        $this->assertCount(1, Account::all());

        $this->visit('/backend/accounts')->within('table', function () {
            $this->press('Delete');
        })->seePageIs('/backend/accounts');

        $this->assertCount(0, Account::all());
    }

    /** @test */
    public function can_paginate_results()
    {
        $records = create_test_account(100);
        $this->visit('/backend/accounts')->within('.pagination', function () {
            $this->click('3');
        })->seePageIs('/backend/accounts?page=3');
    }

    /** @test */
    public function can_search_results()
    {
        $records = create_test_account(10);
        $this->visit('/backend/accounts')
            ->type(substr($records[6]->name, 0, 3), 'search')
            ->press('grid-search-button')
            ->seeInField('search', substr($records[6]->name, 0, 3))
            ->see($records[6]->name);
    }
}
