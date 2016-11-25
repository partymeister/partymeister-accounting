<?php

namespace Partymeister\Accounting\Http\Controllers\Backend;

use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Accounting\Models\Sale;
use Partymeister\Accounting\Http\Requests\Backend\SaleRequest;
use Partymeister\Accounting\Services\SaleService;
use Partymeister\Accounting\Grids\SaleGrid;
use Partymeister\Accounting\Forms\Backend\SaleForm;

use Kris\LaravelFormBuilder\FormBuilderTrait;

class SalesController extends Controller
{

    use FormBuilderTrait;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new SaleGrid(Sale::class);

        $service      = SaleService::collection($grid);
        $grid->filter = $service->getFilter();
        $paginator    = $service->getPaginator();

        return view('partymeister-accounting::backend.sales.index', compact('paginator', 'grid'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}
