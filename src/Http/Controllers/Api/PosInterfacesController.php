<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Http\Requests\Backend\PosInterfaceRequest;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Transformers\AccountTransformer;
use Partymeister\Accounting\Transformers\BookingTransformer;
use Partymeister\Accounting\Transformers\ItemTransformer;

/**
 * Class PosInterfacesController
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
        $items       = Item::where('pos_earnings_account_id', $record->id)->orderBy('pos_sort_position', 'ASC')->get();
        $lastBooking = Booking::where('to_account_id', $record->id)->orderBy('created_at', 'DESC')->first();

        $itemsResource = $this->transformCollection($items, ItemTransformer::class);
        $itemsData     = $this->fractal->createData($itemsResource)->toArray();

        $accountResource = $this->transformItem($record, AccountTransformer::class);
        $accountData     = $this->fractal->createData($accountResource)->toArray();

        if ( ! is_null($lastBooking)) {
            $bookingResource = $this->transformItem($lastBooking, BookingTransformer::class);
            $bookingData     = $this->fractal->createData($bookingResource)->toArray();
        } else {
            $bookingData = [ 'data' => [] ];
        }

        return $this->respondWithJson('POS data for Account',
            [ 'account' => $accountData, 'items' => $itemsData, 'last_booking' => $bookingData ]);
    }


    /**
     * @param PosInterfaceRequest $request
     * @param Account             $record
     * @return JsonResponse
     */
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
