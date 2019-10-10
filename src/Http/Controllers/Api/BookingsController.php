<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Http\Requests\Backend\BookingRequest;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Services\BookingService;
use Partymeister\Accounting\Transformers\BookingTransformer;

/**
 * Class BookingsController
 * @package Partymeister\Accounting\Http\Controllers\Api
 */
class BookingsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $paginator = BookingService::collection()->getPaginator();
        $resource  = $this->transformPaginator($paginator, BookingTransformer::class);

        return $this->respondWithJson('Booking collection read', $resource);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param BookingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BookingRequest $request)
    {
        $result   = BookingService::create($request)->getResult();
        $resource = $this->transformItem($result, BookingTransformer::class);

        return $this->respondWithJson('Booking created', $resource);
    }


    /**
     * Display the specified resource.
     *
     * @param Booking $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Booking $record)
    {
        $result   = BookingService::show($record)->getResult();
        $resource = $this->transformItem($result, BookingTransformer::class);

        return $this->respondWithJson('Booking read', $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param BookingRequest $request
     * @param Booking        $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BookingRequest $request, Booking $record)
    {
        $result   = BookingService::update($record, $request)->getResult();
        $resource = $this->transformItem($result, BookingTransformer::class);

        return $this->respondWithJson('Booking updated', $resource);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Booking $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Booking $record)
    {
        $result = BookingService::delete($record)->getResult();

        if ($result) {
            return $this->respondWithJson('Booking deleted', [ 'success' => true ]);
        }

        return $this->respondWithJson('Booking NOT deleted', [ 'success' => false ]);
    }
}
