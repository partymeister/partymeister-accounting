<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class PartymeisterAccountingApiPosInterfaceTest
 */
class PartymeisterAccountingApiPosInterfaceTest extends TestCase
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
        'account_types',
        'accounts',
        'items',
        'item_types',
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
    public function can_get_pos_data_for_valid_account()
    {
        $account = create_test_account();
        $items   = create_test_items_for_earnings_account($account->id, 5);
        $this->json('GET', '/api/pos/' . $account->id . '?api_token=' . $this->user->api_token)
             ->seeStatusCode(200)
             ->seeJson([ 'name' => $account->name ])
             ->seeJson([ 'name' => $items[3]->name ]);
    }


    /** @test */
    public function gets_403_for_invalid_user()
    {
        $this->json('GET', '/api/pos')->seeStatusCode(403)->seeJson([ 'error' => 'Access denied.' ]);
    }


    /** @test */
    public function gets_404_for_invalid_account()
    {
        $this->json('GET', '/api/pos/999?api_token=' . $this->user->api_token)
             ->seeStatusCode(404)
             ->seeJson([ 'message' => 'Record not found' ]);
    }


    /** @test */
    public function can_make_a_booking_without_items()
    {
        $account = create_test_account();
        $this->json('POST', '/api/pos/' . $account->id . '?api_token=' . $this->user->api_token, [])
             ->seeStatusCode(404)
             ->seeJson([ 'message' => 'No items received' ]);
    }


    /** @test */
    public function can_make_a_booking_with_items()
    {
        $account = create_test_account();
        $items   = create_test_items_for_earnings_account($account->id, 5);

        $itemData = [ $items[0]->id => 1, $items[1]->id => 2 ];

        $description = [ '1x ' . $items[0]->name, '2x ' . $items[1]->name ];

        $this->json('POST', '/api/pos/' . $account->id . '?api_token=' . $this->user->api_token,
            [ 'items' => $itemData ])->seeStatusCode(200)->seeJson([
                    'message' => 'Booking created'
                ])->seeJson([
                    'description' => implode("\r\n", $description)
                ]);
    }
}
