<?php

namespace Partymeister\Accounting\Http\Controllers\Backend;

use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Forms\Backend\ItemTypeForm;
use Partymeister\Accounting\Grids\ItemTypeGrid;
use Partymeister\Accounting\Http\Requests\Backend\ItemTypeRequest;
use Partymeister\Accounting\Models\ItemType;
use Partymeister\Accounting\Services\ItemTypeService;

/**
 * Class ItemTypesController
 *
 * @package Partymeister\Accounting\Http\Controllers\Backend
 */
class ItemTypesController extends Controller
{
    use FormBuilderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     */
    public function index()
    {
        $grid = new ItemTypeGrid(ItemType::class);

        $service = ItemTypeService::collection($grid);
        $grid->setFilter($service->getFilter());
        $paginator = $service->getPaginator();

        return view('partymeister-accounting::backend.item_types.index', compact('paginator', 'grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $form = $this->form(ItemTypeForm::class, [
            'method'  => 'POST',
            'route'   => 'backend.item_types.store',
            'enctype' => 'multipart/form-data',
        ]);

        return view('partymeister-accounting::backend.item_types.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ItemTypeRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ItemTypeRequest $request)
    {
        $form = $this->form(ItemTypeForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if (! $form->isValid()) {
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        ItemTypeService::createWithForm($request, $form);

        flash()->success(trans('partymeister-accounting::backend/item_types.created'));

        return redirect('backend/item_types');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param ItemType $record
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(ItemType $record)
    {
        $form = $this->form(ItemTypeForm::class, [
            'method'  => 'PATCH',
            'url'     => route('backend.item_types.update', [$record->id]),
            'enctype' => 'multipart/form-data',
            'model'   => $record,
        ]);

        return view('partymeister-accounting::backend.item_types.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ItemTypeRequest $request
     * @param ItemType $record
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ItemTypeRequest $request, ItemType $record)
    {
        $form = $this->form(ItemTypeForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if (! $form->isValid()) {
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        ItemTypeService::updateWithForm($record, $request, $form);

        flash()->success(trans('partymeister-accounting::backend/item_types.updated'));

        return redirect('backend/item_types');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ItemType $record
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(ItemType $record)
    {
        ItemTypeService::delete($record);

        flash()->success(trans('partymeister-accounting::backend/item_types.deleted'));

        return redirect('backend/item_types');
    }
}
