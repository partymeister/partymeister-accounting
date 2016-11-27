<?php

namespace Partymeister\Accounting\Http\Controllers\Backend;

use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Http\Requests\Backend\PosInterfaceRequest;
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
        $items = Item::where('pos_earnings_account_id', $record->id)->orderBy('pos_sort_position', 'ASC')->get();
        $last_booking = Booking::where('to_account_id', $record->id)->orderBy('created_at', 'DESC')->first();

        return view('partymeister-accounting::layouts.pos_interface', compact('record', 'items', 'last_booking'));
    }

    public function create(PosInterfaceRequest $request, Account $record)
    {
        $items = json_decode($request->get('booking'), true);
        if (is_array($items)) {
            Booking::createSales($record, $items);
        }
        return redirect('backend/pos/'.$record->id);
    }
}
