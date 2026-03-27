<?php

namespace Partymeister\Accounting\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Accounting\Http\Requests\Api\V2\BookingGetRequest;
use Partymeister\Accounting\Http\Requests\Api\V2\BookingPatchRequest;
use Partymeister\Accounting\Http\Requests\Api\V2\BookingPostRequest;
use Partymeister\Accounting\Http\Resources\V2\BookingCollection;
use Partymeister\Accounting\Http\Resources\V2\BookingResource;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Services\BookingService;

/**
 * @tags Bookings
 */
class BookingsController extends ApiController
{
    protected string $model = Booking::class;

    protected string $modelResource = 'booking';

    public function index(BookingGetRequest $request): BookingCollection
    {
        $paginator = BookingService::collection()->getPaginator();

        return (new BookingCollection($paginator))
            ->additional(['meta' => ['message' => 'Bookings retrieved']]);
    }

    public function store(BookingPostRequest $request): JsonResponse
    {
        $result = BookingService::create($request)->getResult();

        return (new BookingResource($result))
            ->additional(['meta' => ['message' => 'Booking created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Booking $booking): BookingResource
    {
        $result = BookingService::show($booking)->getResult();

        return (new BookingResource($result))
            ->additional(['meta' => ['message' => 'Booking retrieved']]);
    }

    public function update(BookingPatchRequest $request, Booking $booking): BookingResource
    {
        $result = BookingService::update($booking, $request)->getResult();

        return (new BookingResource($result))
            ->additional(['meta' => ['message' => 'Booking updated']]);
    }

    public function destroy(Booking $booking): Response
    {
        $result = BookingService::delete($booking)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting booking');
    }
}
