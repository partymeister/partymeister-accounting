<?php

namespace Partymeister\Accounting\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Accounting\Http\Requests\Api\V2\ItemTypeGetRequest;
use Partymeister\Accounting\Http\Requests\Api\V2\ItemTypePatchRequest;
use Partymeister\Accounting\Http\Requests\Api\V2\ItemTypePostRequest;
use Partymeister\Accounting\Http\Resources\V2\ItemTypeCollection;
use Partymeister\Accounting\Http\Resources\V2\ItemTypeResource;
use Partymeister\Accounting\Models\ItemType;
use Partymeister\Accounting\Services\ItemTypeService;

/**
 * @tags Accounting: Item Types
 */
class ItemTypesController extends ApiController
{
    protected string $model = ItemType::class;

    protected string $modelResource = 'item_type';

    public function index(ItemTypeGetRequest $request): ItemTypeCollection
    {
        $paginator = ItemTypeService::collection()->getPaginator();

        return (new ItemTypeCollection($paginator))
            ->additional(['meta' => ['message' => 'Item types retrieved']]);
    }

    public function store(ItemTypePostRequest $request): JsonResponse
    {
        $result = ItemTypeService::create($request)->getResult();

        return (new ItemTypeResource($result))
            ->additional(['meta' => ['message' => 'Item type created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(ItemType $item_type): ItemTypeResource
    {
        $result = ItemTypeService::show($item_type)->getResult();

        return (new ItemTypeResource($result))
            ->additional(['meta' => ['message' => 'Item type retrieved']]);
    }

    public function update(ItemTypePatchRequest $request, ItemType $item_type): ItemTypeResource
    {
        $result = ItemTypeService::update($item_type, $request)->getResult();

        return (new ItemTypeResource($result))
            ->additional(['meta' => ['message' => 'Item type updated']]);
    }

    public function destroy(ItemType $item_type): Response
    {
        $result = ItemTypeService::delete($item_type)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting item type');
    }
}
