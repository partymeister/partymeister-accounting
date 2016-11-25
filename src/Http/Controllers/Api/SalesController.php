<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Accounting\Models\Sale;
use Partymeister\Accounting\Http\Requests\Backend\SaleRequest;
use Partymeister\Accounting\Services\SaleService;
use Partymeister\Accounting\Transformers\SaleTransformer;

class SalesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $record)
    {
        $result   = SaleService::show($record)->getResult();
        $resource = $this->transformItem($result, SaleTransformer::class);

        return $this->respondWithJson('Sale read', $resource);
    }
}