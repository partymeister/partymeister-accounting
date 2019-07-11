<?php

namespace Partymeister\Accounting\Http\Controllers\Backend;

use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Http\Requests\Backend\ItemRequest;
use Partymeister\Accounting\Services\ItemService;
use Partymeister\Accounting\Grids\ItemGrid;
use Partymeister\Accounting\Forms\Backend\ItemForm;

use Kris\LaravelFormBuilder\FormBuilderTrait;

class ItemsController extends Controller
{

    use FormBuilderTrait;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new ItemGrid(Item::class);

        $service = ItemService::collection($grid);
        $grid->setFilter($service->getFilter());
        $paginator = $service->getPaginator();

        return view('partymeister-accounting::backend.items.index', compact('paginator', 'grid'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = $this->form(ItemForm::class, [
            'method'  => 'POST',
            'route'   => 'backend.items.store',
            'enctype' => 'multipart/form-data'
        ]);

        return view('partymeister-accounting::backend.items.create', compact('form'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        $form = $this->form(ItemForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if ( ! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        ItemService::createWithForm($request, $form);

        flash()->success(trans('partymeister-accounting::backend/items.created'));

        return redirect('backend/items');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $record)
    {
        $form = $this->form(ItemForm::class, [
            'method'  => 'PATCH',
            'url'     => route('backend.items.update', [ $record->id ]),
            'enctype' => 'multipart/form-data',
            'model'   => $record
        ]);

        return view('partymeister-accounting::backend.items.edit', compact('form'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request, Item $record)
    {
        $form = $this->form(ItemForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if ( ! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        ItemService::updateWithForm($record, $request, $form);

        flash()->success(trans('partymeister-accounting::backend/items.updated'));

        return redirect('backend/items');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $record)
    {
        ItemService::delete($record);

        flash()->success(trans('partymeister-accounting::backend/items.deleted'));

        return redirect('backend/items');
    }
}
