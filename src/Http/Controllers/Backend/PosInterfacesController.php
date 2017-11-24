<?php

namespace Partymeister\Accounting\Http\Controllers\Backend;

use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Http\Requests\Backend\PosInterfaceRequest;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\ItemType;
use Partymeister\Accounting\Transformers\BookingTransformer;

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
        $last_booking = Booking::where('to_account_id', $record->id)->orderBy('created_at', 'DESC')->first();
        if ($last_booking instanceof Booking) {
            $last_booking = $last_booking->toJson();
        } else {
            $last_booking = json_encode(null);
        }

        return view('partymeister-accounting::layouts.pos_interface', compact('record', 'last_booking'));
    }

    public function edit(Account $record)
    {
        $itemTypes = ItemType::orderBy('sort_position', 'ASC')->get();

        return view('partymeister-accounting::layouts.pos_interface_editor', compact('record', 'itemTypes'));
    }

    public function update(Account $record, PosInterfaceRequest $request)
    {
        $record->pos_configuration = $request->get('pos_configuration');
        $record->save();
        return response()->json([ 'message' => 'POS configuration saved' ], 200);
    }


    public function create(PosInterfaceRequest $request, Account $record)
    {
        $items = $request->get('items');
        if (is_array($items)) {
            $booking = Booking::createSales($record, $items);

            $resource = $this->transformItem($booking, BookingTransformer::class);

            return $this->respondWithJson('Booking created', $resource);
        }

        return response()->json([ 'message' => 'No items received' ], 404);

    }
}
