<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\ApiController;

use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Http\Requests\Backend\BookingRequest;
use Partymeister\Accounting\Services\BookingService;
use Partymeister\Accounting\Http\Resources\BookingResource;
use Partymeister\Accounting\Http\Resources\BookingCollection;

/**
 * Class BookingsController
 * @package Partymeister\Accounting\Http\Controllers\Api
 */
class BookingsController extends ApiController
{

    protected string $modelResource = 'booking';

    /**
     * @OA\Get (
     *   tags={"BookingsController"},
     *   path="/api/bookings",
     *   summary="Get booking collection",
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/BookingResource")
     *       ),
     *       @OA\Property(
     *         property="meta",
     *         ref="#/components/schemas/PaginationMeta"
     *       ),
     *       @OA\Property(
     *         property="links",
     *         ref="#/components/schemas/PaginationLinks"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Collection read"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   )
     * )
     *
     * Display a listing of the resource.
     *
     * @return BookingCollection
     */
    public function index()
    {
        $paginator = BookingService::collection()->getPaginator();
        return (new BookingCollection($paginator))->additional(['message' => 'Booking collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"BookingsController"},
     *   path="/api/bookings",
     *   summary="Create new booking",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/BookingRequest")
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/BookingResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Booking created"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *   )
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param BookingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BookingRequest $request)
    {
        $result = BookingService::create($request)->getResult();
        return (new BookingResource($result))->additional(['message' => 'Booking created'])->response()->setStatusCode(201);
    }


    /**
     * @OA\Get (
     *   tags={"BookingsController"},
     *   path="/api/bookings/{booking}",
     *   summary="Get single booking",
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="integer"),
     *     in="path",
     *     name="booking",
     *     parameter="booking",
     *     description="Booking id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/BookingResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Booking read"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *   )
     * )
     *
     * Display the specified resource.
     *
     * @param Booking $record
     * @return BookingResource
     */
    public function show(Booking $record)
    {
        $result = BookingService::show($record)->getResult();
        return (new BookingResource($result))->additional(['message' => 'Booking read']);
    }


    /**
     * @OA\Put (
     *   tags={"BookingsController"},
     *   path="/api/bookings/{booking}",
     *   summary="Update an existing booking",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/BookingRequest")
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="integer"),
     *     in="path",
     *     name="booking",
     *     parameter="booking",
     *     description="Booking id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/BookingResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Booking updated"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *   )
     * )
     *
     * Update the specified resource in storage.
     *
     * @param BookingRequest $request
     * @param Booking        $record
     * @return BookingResource
     */
    public function update(BookingRequest $request, Booking $record)
    {
        $result = BookingService::update($record, $request)->getResult();
        return (new BookingResource($result))->additional(['message' => 'Booking updated']);
    }


    /**
     * @OA\Delete (
     *   tags={"BookingsController"},
     *   path="/api/bookings/{booking}",
     *   summary="Delete a booking",
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="integer"),
     *     in="path",
     *     name="booking",
     *     parameter="booking",
     *     description="Booking id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Booking deleted"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="Bad request",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Problem deleting booking"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param Booking $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Booking $record)
    {
        $result = BookingService::delete($record)->getResult();

        if ($result) {
            return response()->json(['message' => 'Booking deleted']);
        }
        return response()->json(['message' => 'Problem deleting Booking'], 404);
    }
}
