<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Motor\Admin\Http\Controllers\ApiController;
use Partymeister\Accounting\Http\Requests\Backend\ItemRequest;
use Partymeister\Accounting\Http\Resources\ItemCollection;
use Partymeister\Accounting\Http\Resources\ItemResource;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Services\ItemService;

/**
 * Class ItemsController
 */
class ItemsController extends ApiController
{
    protected string $model = 'Partymeister\Accounting\Models\Item';

    protected string $modelResource = 'item';

    /**
     * @OA\Get (
     *   tags={"ItemsController"},
     *   path="/api/items",
     *   summary="Get item collection",
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
     *         @OA\Items(ref="#/components/schemas/ItemResource")
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
     * @return ItemCollection
     */
    public function index()
    {
        $paginator = ItemService::collection()
                                ->getPaginator();

        return (new ItemCollection($paginator->load('item_type')))->additional(['message' => 'Item collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"ItemsController"},
     *   path="/api/items",
     *   summary="Create new item",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/ItemRequest")
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
     *         ref="#/components/schemas/ItemResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Item created"
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
     * @param  ItemRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ItemRequest $request)
    {
        $result = ItemService::create($request)
                             ->getResult();

        return (new ItemResource($result))->additional(['message' => 'Item created'])
                                          ->response()
                                          ->setStatusCode(201);
    }

    /**
     * @OA\Get (
     *   tags={"ItemsController"},
     *   path="/api/items/{item}",
     *   summary="Get single item",
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
     *     name="item",
     *     parameter="item",
     *     description="Item id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/ItemResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Item read"
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
     * @param  Item  $record
     * @return ItemResource
     */
    public function show(Item $record)
    {
        $result = ItemService::show($record)
                             ->getResult();

        return (new ItemResource($result))->additional(['message' => 'Item read']);
    }

    /**
     * @OA\Put (
     *   tags={"ItemsController"},
     *   path="/api/items/{item}",
     *   summary="Update an existing item",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/ItemRequest")
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
     *     name="item",
     *     parameter="item",
     *     description="Item id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/ItemResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Item updated"
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
     * @param  ItemRequest  $request
     * @param  Item  $record
     * @return ItemResource
     */
    public function update(ItemRequest $request, Item $record)
    {
        $result = ItemService::update($record, $request)
                             ->getResult();

        return (new ItemResource($result))->additional(['message' => 'Item updated']);
    }

    /**
     * @OA\Delete (
     *   tags={"ItemsController"},
     *   path="/api/items/{item}",
     *   summary="Delete a item",
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
     *     name="item",
     *     parameter="item",
     *     description="Item id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Item deleted"
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
     *         example="Problem deleting item"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  Item  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Item $record)
    {
        $result = ItemService::delete($record)
                             ->getResult();

        if ($result) {
            return response()->json(['message' => 'Item deleted']);
        }

        return response()->json(['message' => 'Problem deleting Item'], 404);
    }
}
