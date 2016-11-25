<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Accounting\Models\ItemType;
use Partymeister\Accounting\Http\Requests\Backend\ItemTypeRequest;
use Partymeister\Accounting\Services\ItemTypeService;
use Partymeister\Accounting\Transformers\ItemTypeTransformer;

class ItemTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = ItemTypeService::collection()->getPaginator();
        $resource = $this->transformPaginator($paginator, ItemTypeTransformer::class);

        return $this->respondWithJson('ItemType collection read', $resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ItemTypeRequest $request)
    {
        $result = ItemTypeService::create($request)->getResult();
        $resource = $this->transformItem($result, ItemTypeTransformer::class);

        return $this->respondWithJson('ItemType created', $resource);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ItemType $record)
    {
        $result = ItemTypeService::show($record)->getResult();
        $resource = $this->transformItem($result, ItemTypeTransformer::class);

        return $this->respondWithJson('ItemType read', $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ItemTypeRequest $request, ItemType $record)
    {
        $result = ItemTypeService::update($record, $request)->getResult();
        $resource = $this->transformItem($result, ItemTypeTransformer::class);

        return $this->respondWithJson('ItemType updated', $resource);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemType $record)
    {
        $result = ItemTypeService::delete($record)->getResult();

        if ($result) {
            return $this->respondWithJson('ItemType deleted', ['success' => true]);
        }
        return $this->respondWithJson('ItemType NOT deleted', ['success' => false]);
    }
}