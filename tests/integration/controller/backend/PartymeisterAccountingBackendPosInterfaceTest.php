<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class PartymeisterAccountingBackendPosInterfaceTest
 */
class PartymeisterAccountingBackendPosInterfaceTest extends TestCase
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
    public function can_see_pos_interface_for_valid_account()
    {
        $account = create_test_account();
        $this->visit('/backend/pos/' . $account->id)->see('BOOK');
    }


    /** @test */
    public function sees_404_for_invalid_account()
    {
        $this->get('/backend/pos')->seeStatusCode(404);
    }


    /** @test */
    public function can_make_a_booking_without_items()
    {
        $account = create_test_account();
        $this->get('/backend/pos/' . $account->id)->seeStatusCode(200)->post('/backend/pos/' . $account->id)->seeJson([
                    'message' => 'No items received'
                ])->seeStatusCode(404);
    }


    /** @test */
    public function can_make_a_booking_with_items()
    {
        $account = create_test_account();
        $items   = create_test_items_for_earnings_account($account->id, 5);

        $itemData = [ $items[0]->id => 1, $items[1]->id => 2 ];

        $description = [ '1x ' . $items[0]->name, '2x ' . $items[1]->name ];

        $this->get('/backend/pos/' . $account->id)
             ->seeStatusCode(200)
             ->post('/backend/pos/' . $account->id, [ 'items' => $itemData ])
             ->seeJson([
                     'message' => 'Booking created'
                 ])
             ->seeJson([
                     'description' => implode("\r\n", $description)
                 ])
             ->seeStatusCode(200);
    }
}
