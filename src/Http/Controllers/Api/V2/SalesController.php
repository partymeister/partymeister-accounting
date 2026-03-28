<?php

namespace Partymeister\Accounting\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Accounting\Http\Requests\Api\V2\SaleGetRequest;
use Partymeister\Accounting\Http\Requests\Api\V2\SalePatchRequest;
use Partymeister\Accounting\Http\Requests\Api\V2\SalePostRequest;
use Partymeister\Accounting\Http\Resources\V2\SaleCollection;
use Partymeister\Accounting\Http\Resources\V2\SaleResource;
use Partymeister\Accounting\Models\Sale;
use Partymeister\Accounting\Services\SaleService;

/**
 * @tags Accounting: Sales
 */
class SalesController extends ApiController
{
    protected string $model = Sale::class;

    protected string $modelResource = 'sale';

    public function index(SaleGetRequest $request): SaleCollection
    {
        $paginator = SaleService::collection()->getPaginator();

        return (new SaleCollection($paginator))
            ->additional(['meta' => ['message' => 'Sales retrieved']]);
    }

    public function store(SalePostRequest $request): JsonResponse
    {
        $result = SaleService::create($request)->getResult();

        return (new SaleResource($result))
            ->additional(['meta' => ['message' => 'Sale created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Sale $sale): SaleResource
    {
        $result = SaleService::show($sale)->getResult();

        return (new SaleResource($result))
            ->additional(['meta' => ['message' => 'Sale retrieved']]);
    }

    public function update(SalePatchRequest $request, Sale $sale): SaleResource
    {
        $result = SaleService::update($sale, $request)->getResult();

        return (new SaleResource($result))
            ->additional(['meta' => ['message' => 'Sale updated']]);
    }

    public function destroy(Sale $sale): Response
    {
        $result = SaleService::delete($sale)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting sale');
    }
}
