<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\ApiController;

use Partymeister\Accounting\Models\ItemType;
use Partymeister\Accounting\Http\Requests\Backend\ItemTypeRequest;
use Partymeister\Accounting\Services\ItemTypeService;
use Partymeister\Accounting\Http\Resources\ItemTypeResource;
use Partymeister\Accounting\Http\Resources\ItemTypeCollection;

/**
 * Class ItemTypesController
 * @package Partymeister\Accounting\Http\Controllers\Api
 */
class ItemTypesController extends ApiController
{

    protected string $modelResource = 'item_type';

    /**
     * @OA\Get (
     *   tags={"ItemTypesController"},
     *   path="/api/item_types",
     *   summary="Get item_type collection",
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
     *         @OA\Items(ref="#/components/schemas/ItemTypeResource")
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
     * @return ItemTypeCollection
     */
    public function index()
    {
        $paginator = ItemTypeService::collection()->getPaginator();
        return (new ItemTypeCollection($paginator))->additional(['message' => 'ItemType collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"ItemTypesController"},
     *   path="/api/item_types",
     *   summary="Create new item_type",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/ItemTypeRequest")
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
     *         ref="#/components/schemas/ItemTypeResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="ItemType created"
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
     * @param ItemTypeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ItemTypeRequest $request)
    {
        $result = ItemTypeService::create($request)->getResult();
        return (new ItemTypeResource($result))->additional(['message' => 'ItemType created'])->response()->setStatusCode(201);
    }


    /**
     * @OA\Get (
     *   tags={"ItemTypesController"},
     *   path="/api/item_types/{item_type}",
     *   summary="Get single item_type",
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
     *     name="item_type",
     *     parameter="item_type",
     *     description="ItemType id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/ItemTypeResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="ItemType read"
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
     * @param ItemType $record
     * @return ItemTypeResource
     */
    public function show(ItemType $record)
    {
        $result = ItemTypeService::show($record)->getResult();
        return (new ItemTypeResource($result))->additional(['message' => 'ItemType read']);
    }


    /**
     * @OA\Put (
     *   tags={"ItemTypesController"},
     *   path="/api/item_types/{item_type}",
     *   summary="Update an existing item_type",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/ItemTypeRequest")
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
     *     name="item_type",
     *     parameter="item_type",
     *     description="ItemType id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/ItemTypeResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="ItemType updated"
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
     * @param ItemTypeRequest $request
     * @param ItemType        $record
     * @return ItemTypeResource
     */
    public function update(ItemTypeRequest $request, ItemType $record)
    {
        $result = ItemTypeService::update($record, $request)->getResult();
        return (new ItemTypeResource($result))->additional(['message' => 'ItemType updated']);
    }


    /**
     * @OA\Delete (
     *   tags={"ItemTypesController"},
     *   path="/api/item_types/{item_type}",
     *   summary="Delete a item_type",
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
     *     name="item_type",
     *     parameter="item_type",
     *     description="ItemType id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="ItemType deleted"
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
     *         example="Problem deleting item_type"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param ItemType $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ItemType $record)
    {
        $result = ItemTypeService::delete($record)->getResult();

        if ($result) {
            return response()->json(['message' => 'ItemType deleted']);
        }
        return response()->json(['message' => 'Problem deleting ItemType'], 404);
    }
}
