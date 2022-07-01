<?php

namespace Partymeister\Accounting\Http\Controllers\Backend;

use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Grids\SaleGrid;
use Partymeister\Accounting\Models\Sale;
use Partymeister\Accounting\Services\SaleService;

/**
 * Class SalesController
 */
class SalesController extends Controller
{
    use FormBuilderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \ReflectionException
     */
    public function index()
    {
        $grid = new SaleGrid(Sale::class);

        $service = SaleService::collection($grid);
        $grid->setFilter($service->getFilter());
        $paginator = $service->getPaginator();

        return view('partymeister-accounting::backend.sales.index', compact('paginator', 'grid'));
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     */
    public function show($id)
    {
        //
    }
}
