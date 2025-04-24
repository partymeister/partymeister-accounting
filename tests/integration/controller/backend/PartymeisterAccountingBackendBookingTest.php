<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Partymeister\Accounting\Models\Booking;

/**
 * Class PartymeisterAccountingBackendBookingTest
 */
class PartymeisterAccountingBackendBookingTest extends TestCase
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
        'bookings',
        'accounts',
        'account_types',
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

        $this->readPermission = create_test_permission_with_name('bookings.read');
        $this->writePermission = create_test_permission_with_name('bookings.write');
        $this->deletePermission = create_test_permission_with_name('bookings.delete');

        $this->actingAs($this->user);
    }

    /** @test */
    public function can_see_grid_without_booking()
    {
        $this->visit('/backend/bookings')->see('Bookings')->see('No records');
    }

    /** @test */
    public function can_see_grid_with_one_booking()
    {
        $record = create_test_booking();
        $this->visit('/backend/bookings')->see('Bookings')->see($record->description);
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_booking_and_use_the_back_button()
    {
        $record = create_test_booking();
        $this->visit('/backend/bookings')->within('table', function () {
            $this->click('Edit');
        })->seePageIs('/backend/bookings/'.$record->id.'/edit')->click('back')->seePageIs('/backend/bookings');
    }

    /** @test */
    public function can_visit_the_edit_form_of_a_booking_and_change_values()
    {
        $record = create_test_booking();

        $this->visit('/backend/bookings/'.$record->id.'/edit')
            ->see($record->name)
            ->type('Updated Booking', 'description')
            ->within('.box-footer', function () {
                $this->press('Save booking');
            })
            ->see('Booking updated')
            ->see('Updated Booking')
            ->seePageIs('/backend/bookings');

        $record = Booking::find($record->id);
        $this->assertEquals('Updated Booking', $record->description);
    }

    /** @test */
    public function can_click_the_create_button()
    {
        $this->visit('/backend/bookings')->click('Create booking')->seePageIs('/backend/bookings/create');
    }

    /** @test */
    public function can_create_a_new_booking()
    {
        $account = create_test_account();
        $this->visit('/backend/bookings/create')
            ->see('Create booking')
            ->select($account->id, 'from_account_id')
            ->type('Create Booking Name', 'description')
            ->type($account->currency_iso_4217, 'currency_iso_4217')
            ->within('.box-footer', function () {
                $this->press('Save booking');
            })
            ->see('Booking created')
            ->see('Create Booking Name')
            ->seePageIs('/backend/bookings');
    }

    /** @test */
    public function cannot_create_a_new_booking_with_empty_fields()
    {
        $this->visit('/backend/bookings/create')->see('Create booking')->within('.box-footer', function () {
            $this->press('Save booking');
        })->see('Data missing!')->seePageIs('/backend/bookings/create');
    }

    /** @test */
    public function can_modify_a_booking()
    {
        $record = create_test_booking();
        $this->visit('/backend/bookings/'.$record->id.'/edit')
            ->see('Edit booking')
            ->type('Modified Booking Name', 'description')
            ->within('.box-footer', function () {
                $this->press('Save booking');
            })
            ->see('Booking updated')
            ->see('Modified Booking Name')
            ->seePageIs('/backend/bookings');
    }

    /** @test */
    public function can_delete_a_booking()
    {
        create_test_booking();

        $this->assertCount(1, Booking::all());

        $this->visit('/backend/bookings')->within('table', function () {
            $this->press('Delete');
        })->seePageIs('/backend/bookings');

        $this->assertCount(0, Booking::all());
    }

    /** @test */
    public function can_paginate_results()
    {
        $records = create_test_booking(100);
        $this->visit('/backend/bookings')->within('.pagination', function () {
            $this->click('3');
        })->seePageIs('/backend/bookings?page=3');
    }

    /** @test */
    public function can_search_results()
    {
        $records = create_test_booking(10);
        $this->visit('/backend/bookings')
            ->type(substr($records[6]->name, 0, 3), 'search')
            ->press('grid-search-button')
            ->seeInField('search', substr($records[6]->name, 0, 3))
            ->see($records[6]->description);
    }
}
