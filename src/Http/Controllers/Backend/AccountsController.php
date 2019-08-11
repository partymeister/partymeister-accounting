<?php

namespace Partymeister\Accounting\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Forms\Backend\AccountForm;
use Partymeister\Accounting\Grids\AccountGrid;
use Partymeister\Accounting\Http\Requests\Backend\AccountRequest;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Services\AccountService;

/**
 * Class AccountsController
 * @package Partymeister\Accounting\Http\Controllers\Backend
 */
class AccountsController extends Controller
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
        $grid = new AccountGrid(Account::class);

        $service = AccountService::collection($grid);
        $grid->setFilter($service->getFilter());
        $paginator = $service->getPaginator();

        return view('partymeister-accounting::backend.accounts.index', compact('paginator', 'grid'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $form = $this->form(AccountForm::class, [
            'method'  => 'POST',
            'route'   => 'backend.accounts.store',
            'enctype' => 'multipart/form-data'
        ]);

        return view('partymeister-accounting::backend.accounts.create', compact('form'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param AccountRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(AccountRequest $request)
    {
        $form = $this->form(AccountForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if ( ! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        AccountService::createWithForm($request, $form);

        flash()->success(trans('partymeister-accounting::backend/accounts.created'));

        return redirect('backend/accounts');
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
     * @param Account $record
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Account $record)
    {
        $form = $this->form(AccountForm::class, [
            'method'  => 'PATCH',
            'url'     => route('backend.accounts.update', [ $record->id ]),
            'enctype' => 'multipart/form-data',
            'model'   => $record
        ]);

        return view('partymeister-accounting::backend.accounts.edit', compact('form'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param AccountRequest $request
     * @param Account        $record
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(AccountRequest $request, Account $record)
    {
        $form = $this->form(AccountForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if ( ! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        AccountService::updateWithForm($record, $request, $form);

        flash()->success(trans('partymeister-accounting::backend/accounts.updated'));

        return redirect('backend/accounts');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Account $record
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Account $record)
    {
        AccountService::delete($record);

        flash()->success(trans('partymeister-accounting::backend/accounts.deleted'));

        return redirect('backend/accounts');
    }
}
