<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\Sale;

/**
 * Class PartymeisterAccountingPosInterfaceTest
 */
class PartymeisterAccountingPosInterfaceTest extends TestCase
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
        'media',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../../database/factories');

        $this->addDefaults();
    }

    protected function addDefaults()
    {
        $this->user = create_test_superadmin();

        $this->actingAs($this->user);
    }

    /** @test */
    public function can_create_a_booking_with_one_item()
    {
        $item = create_test_item();

        $booking = Booking::createSale($item, 5);

        $this->assertNotEquals(null, $booking);
        $this->assertEquals(5 * $item->price_with_vat, $booking->price_with_vat);

        // check if we have a sale in the database
        $this->seeInDatabase('sales', ['earnings_booking_id' => $booking->id]);
    }

    /** @test */
    public function can_create_a_booking_with_multiple_items()
    {
        $items = create_test_item(5);

        $itemsToSell = [];
        $price_with_vat = 0;
        foreach ($items as $item) {
            $itemsToSell[$item->id] = rand(1, 5);
            $price_with_vat += $itemsToSell[$item->id] * $item->price_with_vat;
        }

        $booking = Booking::createSales($itemsToSell);

        $this->assertNotEquals(null, $booking);
        $this->assertEquals($price_with_vat, $booking->price_with_vat);

        // check if we have 5 sale in the database
        $this->assertCount(5, Sale::where('earnings_booking_id', $booking->id)->get());
    }

    /** @test */
    public function can_create_a_booking_with_negative_quantities_of_items()
    {
        $items = create_test_item(5);

        $itemsToSell = [];
        $price_with_vat = 0;
        foreach ($items as $item) {
            $itemsToSell[$item->id] = rand(-1, -10);
            $price_with_vat += $itemsToSell[$item->id] * $item->price_with_vat;
        }

        $booking = Booking::createSales($itemsToSell);

        $this->assertNotEquals(null, $booking);
        $this->assertEquals($price_with_vat, $booking->price_with_vat);

        $sales = Sale::where('earnings_booking_id', $booking->id)->get();
        foreach ($sales as $sale) {
            $this->assertEquals($sale->quantity, $itemsToSell[$sale->item_id]);
        }

        // check if we have 5 sale in the database
        $this->assertCount(5, $sales);
    }

    /** @test */
    public function if_a_booking_is_deleted_all_sales_and_cost_bookings_are_deleted_as_well()
    {
        $items = create_test_item(5);

        $itemsToSell = [];
        $price_with_vat = 0;
        foreach ($items as $item) {
            $itemsToSell[$item->id] = rand(-1, -10);
            $price_with_vat += $itemsToSell[$item->id] * $item->price_with_vat;
        }

        $booking = Booking::createSales($itemsToSell);

        $sales = Sale::where('earnings_booking_id', $booking->id)->get();

        $this->assertCount(5, $sales);

        // Get ids for the associate cost_bookings
        $costBookings = [];
        foreach ($sales as $sale) {
            if (! is_null($sale->cost_booking)) {
                $costBookings[] = $sale->cost_booking;
            }
        }

        $booking->delete();

        $this->assertCount(0, Sale::where('earnings_booking_id', $booking->id)->get());

        foreach ($costBookings as $costBooking) {
            $this->assertEquals(null, $costBooking->fresh());
        }
    }
}
