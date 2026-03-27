<?php

namespace Partymeister\Accounting\Http\Controllers\Backend;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\Admin\Http\Controllers\Controller;
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
     * @return Factory|View
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
     */
    public function show($id)
    {
        //
    }
}
