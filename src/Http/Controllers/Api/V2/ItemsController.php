<?php

namespace Partymeister\Accounting\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Accounting\Http\Requests\Api\V2\ItemGetRequest;
use Partymeister\Accounting\Http\Requests\Api\V2\ItemPatchRequest;
use Partymeister\Accounting\Http\Requests\Api\V2\ItemPostRequest;
use Partymeister\Accounting\Http\Resources\V2\ItemCollection;
use Partymeister\Accounting\Http\Resources\V2\ItemResource;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Services\ItemService;

/**
 * @tags Accounting: Items
 */
class ItemsController extends ApiController
{
    protected string $model = Item::class;

    protected string $modelResource = 'item';

    public function index(ItemGetRequest $request): ItemCollection
    {
        $paginator = ItemService::collection()->getPaginator();

        return (new ItemCollection($paginator))
            ->additional(['meta' => ['message' => 'Items retrieved']]);
    }

    public function store(ItemPostRequest $request): JsonResponse
    {
        $result = ItemService::create($request)->getResult();

        return (new ItemResource($result))
            ->additional(['meta' => ['message' => 'Item created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Item $item): ItemResource
    {
        $result = ItemService::show($item)->getResult();

        return (new ItemResource($result))
            ->additional(['meta' => ['message' => 'Item retrieved']]);
    }

    public function update(ItemPatchRequest $request, Item $item): ItemResource
    {
        $result = ItemService::update($item, $request)->getResult();

        return (new ItemResource($result))
            ->additional(['meta' => ['message' => 'Item updated']]);
    }

    public function destroy(Item $item): Response
    {
        $result = ItemService::delete($item)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting item');
    }
}
