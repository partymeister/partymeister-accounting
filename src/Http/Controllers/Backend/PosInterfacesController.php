<?php

namespace Partymeister\Accounting\Http\Controllers\Backend;

use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\Item;

class PosInterfacesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Account $record)
    {
        $items = Item::all();
        $last_booking = Booking::first();

        return view('partymeister-accounting::layouts.pos_interface', compact('record', 'items', 'last_booking'));
    }
}
