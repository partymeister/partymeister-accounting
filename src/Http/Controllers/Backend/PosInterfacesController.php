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
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Account $record)
    {
        $items        = Item::where('pos_earnings_account_id', $record->id)->orderBy('pos_sort_position', 'ASC')->get();
        $last_booking = Booking::where('to_account_id', $record->id)->orderBy('created_at', 'DESC')->first();
        if ($last_booking instanceof Booking) {
            $last_booking = $last_booking->toJson();
        } else {
            $last_booking = json_encode(null);
        }

        return view('partymeister-accounting::layouts.pos_interface', compact('record', 'items', 'last_booking'));
    }


    public function create(PosInterfaceRequest $request, Account $record)
    {
        $items = $request->get('items');
        if (is_array($items)) {
            $booking = Booking::createSales($record, $items);

            return response()->json([ 'booking' => $booking->toArray() ]);
        }

        return response()->json([ 'message' => 'No items received' ], 404);

    }
}
