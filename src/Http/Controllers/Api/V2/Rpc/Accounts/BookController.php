<?php

namespace Partymeister\Accounting\Http\Controllers\Api\V2\Rpc\Accounts;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Partymeister\Accounting\Http\Requests\Api\V2\BookPostRequest;
use Partymeister\Accounting\Http\Resources\V2\BookingResource;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\Booking;

/**
 * @tags Accounting: POS
 */
class BookController extends Controller
{
    public function __invoke(BookPostRequest $request, Account $account): JsonResponse
    {
        $items = $request->validated('items');
        $cardPayment = $request->validated('is_card_payment', false);
        $couponPayment = $request->validated('is_coupon_payment', false);

        $booking = Booking::createSales($account, $items, $cardPayment, $couponPayment);

        return (new BookingResource($booking->load('from_account', 'to_account')))
            ->additional(['meta' => ['message' => 'Sale processed']])
            ->response()
            ->setStatusCode(201);
    }
}
