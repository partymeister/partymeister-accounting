<?php

namespace Partymeister\Accounting\Http\Controllers\Backend;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Http\Requests\Backend\PosInterfaceRequest;
use Partymeister\Accounting\Http\Resources\BookingResource;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\ItemType;

/**
 * Class PosInterfacesController
 */
class PosInterfacesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return Factory|View
     */
    public function show(Account $record)
    {
        $booking = Booking::where('to_account_id', $record->id)
            ->orderBy('created_at', 'DESC')
            ->first();

        $last_booking = '{}';

        if (! is_null($booking)) {
            $last_booking = json_encode((new BookingResource($booking))->toArrayRecursive());
        }

        return view('partymeister-accounting::layouts.pos_interface', compact('record', 'last_booking'));
    }

    /**
     * @return Factory|View
     */
    public function edit(Account $record)
    {
        $itemTypes = ItemType::orderBy('sort_position', 'ASC')
            ->get();

        return view('partymeister-accounting::layouts.pos_interface_editor', compact('record', 'itemTypes'));
    }

    /**
     * @return JsonResponse
     */
    public function update(Account $record, PosInterfaceRequest $request)
    {
        $record->pos_configuration = $request->get('pos_configuration');
        $record->save();

        return response()->json(['message' => 'POS configuration saved'], 200);
    }

    /**
     * @return JsonResponse
     */
    public function create(PosInterfaceRequest $request, Account $record)
    {
        $items = $request->get('items');
        if (is_array($items)) {
            $booking = Booking::createSales($record, $items, $request->get('is_card_payment', 0), $request->get('is_coupon_payment', 0));

            return response()->json(new BookingResource($booking));
        }

        return response()->json(['message' => 'No items received'], 404);
    }
}
