<?php

namespace Partymeister\Accounting\Http\Controllers\Backend;

use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Accounting\Models\AccountType;
use Partymeister\Accounting\Http\Requests\Backend\AccountTypeRequest;
use Partymeister\Accounting\Services\AccountTypeService;
use Partymeister\Accounting\Grids\AccountTypeGrid;
use Partymeister\Accounting\Forms\Backend\AccountTypeForm;

use Kris\LaravelFormBuilder\FormBuilderTrait;

class AccountTypesController extends Controller
{
    use FormBuilderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new AccountTypeGrid(AccountType::class);

        $service = AccountTypeService::collection($grid);
        $grid->filter = $service->getFilter();
        $paginator    = $service->getPaginator();

        return view('partymeister-accounting::backend.account_types.index', compact('paginator', 'grid'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AccountTypeRequest $request)
    {
        $form = $this->form(AccountTypeForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if ( ! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        AccountTypeService::createWithForm($request, $form);

        flash()->success(trans('partymeister-accounting::backend/account_types.created'));

        return redirect('backend/account_types');
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


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(AccountTypeRequest $request, AccountType $record)
    {
        $form = $this->form(AccountTypeForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if ( ! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        AccountTypeService::updateWithForm($record, $request, $form);

        flash()->success(trans('partymeister-accounting::backend/account_types.updated'));

        return redirect('backend/account_types');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountType $record)
    {
        AccountTypeService::delete($record);

        flash()->success(trans('partymeister-accounting::backend/account_types.deleted'));

        return redirect('backend/account_types');
    }
}
