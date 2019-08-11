<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Accounting\Models\Sale;

/**
 * Class PartymeisterAccountingSalesTest
 */
class PartymeisterAccountingSalesTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @var
     */
    protected $user;

    /**
     * @var
     */
    protected $sale;

    /**
     * @var
     */
    protected $booking;

    /**
     * @var
     */
    protected $item;

    /**
     * @var array
     */
    protected $tables = [
        'accounts',
        'bookings',
        'sales',
        'items',
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

        $this->withFactories(__DIR__ . '/../../database/factories');

        $this->addDefaults();
    }


    protected function addDefaults()
    {
        $this->user = create_test_superadmin();

        $this->actingAs($this->user);
    }


    public function create_basic_sale()
    {
        $booking                   = create_test_booking();
        $item                      = create_test_item();
        $sale                      = new Sale();
        $sale->earnings_booking_id = $booking->id;
        $sale->item_id             = $item->id;
        $sale->quantity            = -2;
        $sale->save();

        $this->sale    = $sale;
        $this->booking = $booking;
        $this->item    = $item;
    }


    /** @test */
    public function it_can_be_sold()
    {
        $booking = create_test_booking();
        $item    = create_test_item();

        $sale = $item->sell(4, $booking);

        $this->assertEquals(4, $sale->quantity);
        $this->assertEquals($item->id, $sale->item_id);
        $this->assertEquals($booking->id, $sale->earnings_booking_id);
    }


    /** @test */
    public function it_has_a_cost_booking()
    {
        $booking = create_test_booking();
        $item    = create_test_item();

        $sale = $item->sell(4, $booking);

        $this->assertNotNull($sale->cost_booking);
        $this->assertEquals($item->cost_account_id, $sale->cost_booking->account_id);
        $this->assertEquals(round(( 4 * $item->cost_price_with_vat ), 4) * -1, $sale->cost_booking->price_with_vat);
    }


    /** @test */
    public function it_can_have_a_negative_quantity()
    {
        $booking = create_test_booking();
        $item    = create_test_item();

        $sale = $item->sell(-4, $booking);

        $this->assertEquals($item->cost_account_id, $sale->cost_booking->account_id);
        $this->assertEquals(round(( 4 * $item->cost_price_with_vat ), 4), $sale->cost_booking->price_with_vat);
    }


    /** @test */
    public function if_the_booking_is_deleted_the_sale_is_also_deleted()
    {
        $booking = create_test_booking();
        $item    = create_test_item();

        $sale = $item->sell(-4, $booking);

        $booking->delete();

        $updatedSale = $sale->fresh();

        $this->assertEquals(null, $updatedSale);
    }


    /** @test */
    public function if_the_item_is_deleted_the_sale_is_also_deleted()
    {
        $booking = create_test_booking();
        $item    = create_test_item();

        $sale = $item->sell(-4, $booking);

        $item->delete();

        $updatedSale = $sale->fresh();

        $this->assertEquals(null, $updatedSale);
    }


    /** @test */
    public function if_the_sale_is_deleted_the_booking_is_not()
    {
        $booking = create_test_booking();
        $item    = create_test_item();

        $sale = $item->sell(-4, $booking);

        $sale->delete();

        $updatedBooking = $booking->fresh();

        $this->assertEquals(null, $sale->fresh());
        $this->assertNotEquals(null, $updatedBooking);
    }


    /** @test */
    public function if_the_sale_is_deleted_the_item_is_not()
    {
        $booking = create_test_booking();
        $item    = create_test_item();

        $sale = $item->sell(-4, $booking);

        $sale->delete();

        $updatedItem = $item->fresh();

        $this->assertEquals(null, $sale->fresh());
        $this->assertNotEquals(null, $updatedItem);
    }


    /** @test */
    public function if_the_cost_booking_is_deleted_the_sale_is_not()
    {
        $booking = create_test_booking();
        $item    = create_test_item();

        $sale = $item->sell(-4, $booking);

        $sale->cost_booking->delete();

        $updatedSale = $sale->fresh();

        $this->assertNotEquals(null, $updatedSale);
        $this->assertEquals(null, $updatedSale->cost_booking);
    }


    /** @test */
    public function if_the_sale_is_deleted_the_cost_booking_is_deleted_as_well()
    {
        $booking = create_test_booking();
        $item    = create_test_item();

        $sale = $item->sell(-4, $booking);

        $costBooking = $sale->cost_booking;

        $sale->delete();

        $this->assertEquals(null, $sale->cost_booking->fresh());
        $this->assertEquals(null, $costBooking->fresh());
    }


    /** @test */
    public function if_the_booking_is_deleted_a_sale_and_the_associated_cost_booking_is_deleted_as_well()
    {
        $booking = create_test_booking();
        $item    = create_test_item();

        $sale = $item->sell(4, $booking);

        $costBooking = $sale->cost_booking;

        $booking->delete();

        $this->assertEquals(null, $sale->fresh());
        $this->assertEquals(null, $costBooking->fresh());
    }

}
