<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Http\Requests\Backend\PosInterfaceRequest;
use Partymeister\Accounting\Http\Resources\AccountResource;
use Partymeister\Accounting\Http\Resources\BookingResource;
use Partymeister\Accounting\Http\Resources\ItemResource;
use Partymeister\Accounting\Http\Resources\ItemTypeResource;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\ItemType;

/**
 * Class PosInterfacesController
 */
class PosInterfacesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  Account  $record
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
     * Return POS viewer data: account info, items grouped by zone, and last booking.
     *
     * @param  Account  $record
     * @return JsonResponse
     */
    public function configured(Account $record)
    {
        $config = $record->pos_configuration ?? [];

        // Collect all item IDs from the configuration to batch-load
        $itemIds = collect($config)
            ->flatten()
            ->filter(fn ($id) => $id !== 'separator')
            ->unique()
            ->values();

        $items = Item::whereIn('id', $itemIds)->get()->keyBy('id');

        // Build zones with resolved item data
        $zones = [];
        foreach ($config as $zoneNumber => $zoneItems) {
            $zones[$zoneNumber] = [];
            foreach ($zoneItems as $entry) {
                if ($entry === 'separator') {
                    $zones[$zoneNumber][] = 'separator';
                } elseif ($items->has($entry)) {
                    $item = $items->get($entry);
                    $zones[$zoneNumber][] = [
                        'id'                               => (int) $item->id,
                        'name'                             => $item->name,
                        'price_with_vat'                   => (float) $item->price_with_vat,
                        'pos_can_book_negative_quantities' => (bool) $item->pos_can_book_negative_quantities,
                        'pos_create_booking_for_item_id'   => $item->pos_create_booking_for_item_id,
                        'is_coupon_item'                   => $item->name === config('partymeister-accounting.coupon_item_pos'),
                    ];
                }
            }
        }

        $lastBooking = Booking::where('to_account_id', $record->id)
                              ->orderBy('created_at', 'DESC')
                              ->first();

        return response()->json([
            'account'      => [
                'id'                   => (int) $record->id,
                'name'                 => $record->name,
                'currency_iso_4217'    => $record->currency_iso_4217,
                'has_card_payments'    => (bool) $record->has_card_payments,
                'has_coupon_payments'  => (bool) $record->has_coupon_payments,
            ],
            'zones'        => $zones,
            'last_booking' => $lastBooking
                ? (new BookingResource($lastBooking))->toArrayRecursive()
                : null,
        ]);
    }

    /**
     * Return POS editor data: account info and all item types with their items.
     *
     * @param  Account  $record
     * @return JsonResponse
     */
    public function editor(Account $record)
    {
        $itemTypes = ItemType::with(['items' => fn ($q) => $q->orderBy('sort_position', 'ASC')])
                             ->orderBy('sort_position', 'ASC')
                             ->get();

        return response()->json([
            'account'    => [
                'id'                => (int) $record->id,
                'name'              => $record->name,
                'pos_configuration' => $record->pos_configuration ?? [],
            ],
            'item_types' => ItemTypeResource::collection($itemTypes),
        ]);
    }

    /**
     * @param  PosInterfaceRequest  $request
     * @param  Account  $record
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
