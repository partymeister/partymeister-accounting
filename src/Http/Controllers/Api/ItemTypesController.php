<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Http\Requests\Backend\ItemTypeRequest;
use Partymeister\Accounting\Models\ItemType;
use Partymeister\Accounting\Services\ItemTypeService;
use Partymeister\Accounting\Transformers\ItemTypeTransformer;

/**
 * Class ItemTypesController
 * @package Partymeister\Accounting\Http\Controllers\Api
 */
class ItemTypesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $paginator = ItemTypeService::collection()->getPaginator();
        $resource  = $this->transformPaginator($paginator, ItemTypeTransformer::class);

        return $this->respondWithJson('ItemType collection read', $resource);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ItemTypeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ItemTypeRequest $request)
    {
        $result   = ItemTypeService::create($request)->getResult();
        $resource = $this->transformItem($result, ItemTypeTransformer::class);

        return $this->respondWithJson('ItemType created', $resource);
    }


    /**
     * Display the specified resource.
     *
     * @param ItemType $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ItemType $record)
    {
        $result   = ItemTypeService::show($record)->getResult();
        $resource = $this->transformItem($result, ItemTypeTransformer::class);

        return $this->respondWithJson('ItemType read', $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param ItemTypeRequest $request
     * @param ItemType        $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ItemTypeRequest $request, ItemType $record)
    {
        $result   = ItemTypeService::update($record, $request)->getResult();
        $resource = $this->transformItem($result, ItemTypeTransformer::class);

        return $this->respondWithJson('ItemType updated', $resource);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param ItemType $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ItemType $record)
    {
        $result = ItemTypeService::delete($record)->getResult();

        if ($result) {
            return $this->respondWithJson('ItemType deleted', [ 'success' => true ]);
        }

        return $this->respondWithJson('ItemType NOT deleted', [ 'success' => false ]);
    }
}