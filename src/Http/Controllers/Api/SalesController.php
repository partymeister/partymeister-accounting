<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Motor\Admin\Http\Controllers\ApiController;
use Partymeister\Accounting\Http\Requests\Backend\SaleRequest;
use Partymeister\Accounting\Http\Resources\SaleCollection;
use Partymeister\Accounting\Http\Resources\SaleResource;
use Partymeister\Accounting\Models\Sale;
use Partymeister\Accounting\Services\SaleService;

/**
 * Class SalesController
 */
class SalesController extends ApiController
{
    protected string $model = 'Partymeister\Accounting\Models\Sale';

    protected string $modelResource = 'sale';

    /**
     * @OA\Get (
     *   tags={"SalesController"},
     *   path="/api/sales",
     *   summary="Get sale collection",
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
     *         @OA\Items(ref="#/components/schemas/SaleResource")
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
     * @return SaleCollection
     */
    public function index()
    {
        $paginator = SaleService::collection()
                                ->getPaginator();

        return (new SaleCollection($paginator))->additional(['message' => 'Sale collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"SalesController"},
     *   path="/api/sales",
     *   summary="Create new sale",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/SaleRequest")
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
     *         ref="#/components/schemas/SaleResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Sale created"
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
     * @param  SaleRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SaleRequest $request)
    {
        $result = SaleService::create($request)
                             ->getResult();

        return (new SaleResource($result))->additional(['message' => 'Sale created'])
                                          ->response()
                                          ->setStatusCode(201);
    }

    /**
     * @OA\Get (
     *   tags={"SalesController"},
     *   path="/api/sales/{sale}",
     *   summary="Get single sale",
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
     *     name="sale",
     *     parameter="sale",
     *     description="Sale id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/SaleResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Sale read"
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
     * @param  Sale  $record
     * @return SaleResource
     */
    public function show(Sale $record)
    {
        $result = SaleService::show($record)
                             ->getResult();

        return (new SaleResource($result))->additional(['message' => 'Sale read']);
    }

    /**
     * @OA\Put (
     *   tags={"SalesController"},
     *   path="/api/sales/{sale}",
     *   summary="Update an existing sale",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/SaleRequest")
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
     *     name="sale",
     *     parameter="sale",
     *     description="Sale id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/SaleResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Sale updated"
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
     * @param  SaleRequest  $request
     * @param  Sale  $record
     * @return SaleResource
     */
    public function update(SaleRequest $request, Sale $record)
    {
        $result = SaleService::update($record, $request)
                             ->getResult();

        return (new SaleResource($result))->additional(['message' => 'Sale updated']);
    }

    /**
     * @OA\Delete (
     *   tags={"SalesController"},
     *   path="/api/sales/{sale}",
     *   summary="Delete a sale",
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
     *     name="sale",
     *     parameter="sale",
     *     description="Sale id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Sale deleted"
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
     *         example="Problem deleting sale"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  Sale  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Sale $record)
    {
        $result = SaleService::delete($record)
                             ->getResult();

        if ($result) {
            return response()->json(['message' => 'Sale deleted']);
        }

        return response()->json(['message' => 'Problem deleting Sale'], 404);
    }
}
