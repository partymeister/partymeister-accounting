<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Illuminate\Http\Response;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Http\Requests\Backend\SaleRequest;
use Partymeister\Accounting\Models\Sale;
use Partymeister\Accounting\Services\SaleService;
use Partymeister\Accounting\Transformers\SaleTransformer;

/**
 * Class SalesController
 * @package Partymeister\Accounting\Http\Controllers\Api
 */
class SalesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $paginator = SaleService::collection()->getPaginator();
        $resource  = $this->transformPaginator($paginator, SaleTransformer::class);

        return $this->respondWithJson('Sale collection read', $resource);
    }


    /**
     * Display the specified resource.
     *
     * @param Sale $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Sale $record)
    {
        $result   = SaleService::show($record)->getResult();
        $resource = $this->transformItem($result, SaleTransformer::class);

        return $this->respondWithJson('Sale read', $resource);
    }
}