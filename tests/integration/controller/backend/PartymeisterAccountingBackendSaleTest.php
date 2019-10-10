<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class PartymeisterAccountingBackendSaleTest
 */
class PartymeisterAccountingBackendSaleTest extends TestCase
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
        'sales',
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

        $this->withFactories(__DIR__ . '/../../../../database/factories');

        $this->addDefaults();
    }


    protected function addDefaults()
    {
        $this->user = create_test_superadmin();

        $this->readPermission   = create_test_permission_with_name('sales.read');
        $this->writePermission  = create_test_permission_with_name('sales.write');
        $this->deletePermission = create_test_permission_with_name('sales.delete');

        $this->actingAs($this->user);
    }


    /** @test */
    public function can_see_grid_without_sale()
    {
        $this->visit('/backend/sales')->see('Sales')->see('No records');
    }


    /** @test */
    public function can_see_grid_with_one_sale()
    {
        $record = create_test_sale();
        $this->visit('/backend/sales')->see('Sales')->see($record->name);
    }


    /** @test */
    public function can_paginate_sale_results()
    {
        $records = create_test_sale(100);
        $this->visit('/backend/sales')->within('.pagination', function () {
            $this->click('3');
        })->seePageIs('/backend/sales?page=3');
    }


    /** @test */
    public function can_search_sale_results()
    {
        $records = create_test_sale(10);
        $this->visit('/backend/sales')
             ->type(substr($records[6]->name, 0, 3), 'search')
             ->press('grid-search-button')
             ->seeInField('search', substr($records[6]->name, 0, 3))
             ->see($records[6]->name);
    }
}
