<?php

namespace Partymeister\Accounting\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Forms\Backend\AccountTypeForm;
use Partymeister\Accounting\Grids\AccountTypeGrid;
use Partymeister\Accounting\Http\Requests\Backend\AccountTypeRequest;
use Partymeister\Accounting\Models\AccountType;
use Partymeister\Accounting\Services\AccountTypeService;

/**
 * Class AccountTypesController
 * @package Partymeister\Accounting\Http\Controllers\Backend
 */
class AccountTypesController extends Controller
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
        $grid = new AccountTypeGrid(AccountType::class);

        $service = AccountTypeService::collection($grid);
        $grid->setFilter($service->getFilter());
        $paginator = $service->getPaginator();

        return view('partymeister-accounting::backend.account_types.index', compact('paginator', 'grid'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $form = $this->form(AccountTypeForm::class, [
            'method'  => 'POST',
            'route'   => 'backend.account_types.store',
            'enctype' => 'multipart/form-data'
        ]);

        return view('partymeister-accounting::backend.account_types.create', compact('form'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param AccountTypeRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(AccountTypeRequest $request)
    {
        $form = $this->form(AccountTypeForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        AccountTypeService::createWithForm($request, $form);

        flash()->success(trans('partymeister-accounting::backend/account_types.created'));

        return redirect('backend/account_types');
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
     * @param AccountType $record
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(AccountType $record)
    {
        $form = $this->form(AccountTypeForm::class, [
            'method'  => 'PATCH',
            'url'     => route('backend.account_types.update', [ $record->id ]),
            'enctype' => 'multipart/form-data',
            'model'   => $record
        ]);

        return view('partymeister-accounting::backend.account_types.edit', compact('form'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param AccountTypeRequest $request
     * @param AccountType        $record
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(AccountTypeRequest $request, AccountType $record)
    {
        $form = $this->form(AccountTypeForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        AccountTypeService::updateWithForm($record, $request, $form);

        flash()->success(trans('partymeister-accounting::backend/account_types.updated'));

        return redirect('backend/account_types');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param AccountType $record
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(AccountType $record)
    {
        AccountTypeService::delete($record);

        flash()->success(trans('partymeister-accounting::backend/account_types.deleted'));

        return redirect('backend/account_types');
    }
}
