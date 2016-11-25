<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Http\Requests\Backend\ItemRequest;
use Partymeister\Accounting\Services\ItemService;
use Partymeister\Accounting\Transformers\ItemTransformer;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = ItemService::collection()->getPaginator();
        $resource = $this->transformPaginator($paginator, ItemTransformer::class);

        return $this->respondWithJson('Item collection read', $resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        $result = ItemService::create($request)->getResult();
        $resource = $this->transformItem($result, ItemTransformer::class);

        return $this->respondWithJson('Item created', $resource);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Item $record)
    {
        $result = ItemService::show($record)->getResult();
        $resource = $this->transformItem($result, ItemTransformer::class);

        return $this->respondWithJson('Item read', $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request, Item $record)
    {
        $result = ItemService::update($record, $request)->getResult();
        $resource = $this->transformItem($result, ItemTransformer::class);

        return $this->respondWithJson('Item updated', $resource);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $record)
    {
        $result = ItemService::delete($record)->getResult();

        if ($result) {
            return $this->respondWithJson('Item deleted', ['success' => true]);
        }
        return $this->respondWithJson('Item NOT deleted', ['success' => false]);
    }
}