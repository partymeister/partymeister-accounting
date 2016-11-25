<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiSaleTest extends TestCase
{

    use DatabaseTransactions;

    protected $user;

    protected $readPermission;

    protected $writePermission;

    protected $deletePermission;

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
        $this->user           = create_test_user();
        $this->readPermission = create_test_permission_with_name('sales.read');
    }


    /**
     * @test
     */
    public function returns_403_for_sale_if_not_authenticated()
    {
        $this->json('GET', '/api/sales/1')->seeStatusCode(401)->seeJson([ 'error' => 'Unauthenticated.' ]);
    }


    /** @test */
    public function returns_404_for_non_existing_sale_record()
    {
        $this->user->givePermissionTo($this->readPermission);
        $this->json('GET', '/api/sales/1?api_token=' . $this->user->api_token)->seeStatusCode(404)->seeJson([
            'message' => 'Record not found',
        ]);
    }


    /** @test */
    public function can_show_a_single_sale()
    {
        $this->user->givePermissionTo($this->readPermission);
        $record = create_test_sale();
        $this->json('GET',
            '/api/sales/' . $record->id . '?api_token=' . $this->user->api_token)->seeStatusCode(200)->seeJson([
            'quantity' => $record->quantity
        ]);
    }


    /** @test */
    public function fails_to_show_a_single_sale_without_permission()
    {
        $record = create_test_sale();
        $this->json('GET',
            '/api/sales/' . $record->id . '?api_token=' . $this->user->api_token)->seeStatusCode(403)->seeJson([
            'error' => 'Access denied.'
        ]);
    }


    /** @test */
    public function can_get_empty_result_when_trying_to_show_multiple_sale()
    {
        $this->user->givePermissionTo($this->readPermission);
        $this->json('GET', '/api/sales?api_token=' . $this->user->api_token)->seeStatusCode(200)->seeJson([
            'total' => 0
        ]);
    }


    /** @test */
    public function can_show_multiple_sale()
    {
        $this->user->givePermissionTo($this->readPermission);
        $records = create_test_sale(10);
        $this->json('GET', '/api/sales?api_token=' . $this->user->api_token)->seeStatusCode(200)->seeJson([
            'quantity' => $records[0]->quantity
        ]);
    }


    /** @test */
    public function can_search_for_a_sale()
    {
        $this->user->givePermissionTo($this->readPermission);
        $records = create_test_sale(10);
        $this->json('GET',
            '/api/sales?api_token=' . $this->user->api_token . '&search=' . $records[2]->name)->seeStatusCode(200)->seeJson([
            'quantity'    => $records[2]->quantity,
            'description' => $records[2]->earnings_booking->description
        ]);
    }


    /** @test */
    public function can_show_a_second_sale_results_page()
    {
        $this->user->givePermissionTo($this->readPermission);
        create_test_sale(50);
        $this->json('GET', '/api/sales?api_token=' . $this->user->api_token . '&page=2')->seeStatusCode(200)->seeJson([
            'current_page' => 2
        ]);
    }

}
