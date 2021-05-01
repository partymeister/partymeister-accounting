<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Http\Requests\Backend\PosInterfaceRequest;
use Partymeister\Accounting\Http\Resources\AccountResource;
use Partymeister\Accounting\Http\Resources\BookingResource;
use Partymeister\Accounting\Http\Resources\ItemResource;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\Item;

/**
 * Class PosInterfacesController
 *
 * @package Partymeister\Accounting\Http\Controllers\Api
 */
class PosInterfacesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Account $record
     * @return JsonResponse
     */
    public function show(Account $record)
    {
        $items = Item::where('pos_earnings_account_id', $record->id)
                     ->orderBy('pos_sort_position', 'ASC')
                     ->get();
        $lastBooking = Booking::where('to_account_id', $record->id)
                              ->orderBy('created_at', 'DESC')
                              ->first();

        $itemsData = ItemResource::collection($items)
                                 ->toArrayRecursive();

        $accountData = (new AccountResource($record))->toArrayRecursive();

        if (! is_null($lastBooking)) {
            $bookingData = (new BookingResource($lastBooking))->toArrayRecursive();
        } else {
            $bookingData = ['data' => []];
        }

        return response()->json(['account' => $accountData, 'items' => $itemsData, 'last_booking' => $bookingData]);
    }

    /**
     * @param PosInterfaceRequest $request
     * @param Account $record
     * @return JsonResponse
     */
    public function create(PosInterfaceRequest $request, Account $record)
    {
        $items = $request->get('items');
        if (is_array($items)) {
            $booking = Booking::createSales($record, $items);

            return response()->json(new BookingResource($booking));
        }

        return response()->json(['message' => 'No items received'], 404);
    }
}
