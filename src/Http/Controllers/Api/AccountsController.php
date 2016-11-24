<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Http\Requests\Backend\AccountRequest;
use Partymeister\Accounting\Services\AccountService;
use Partymeister\Accounting\Transformers\AccountTransformer;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = AccountService::collection()->getPaginator();
        $resource = $this->transformPaginator($paginator, AccountTransformer::class);

        return $this->respondWithJson('Account collection read', $resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AccountRequest $request)
    {
        $result = AccountService::create($request)->getResult();
        $resource = $this->transformItem($result, AccountTransformer::class);

        return $this->respondWithJson('Account created', $resource);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Account $record)
    {
        $result = AccountService::show($record)->getResult();
        $resource = $this->transformItem($result, AccountTransformer::class);

        return $this->respondWithJson('Account read', $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(AccountRequest $request, Account $record)
    {
        $result = AccountService::update($record, $request)->getResult();
        $resource = $this->transformItem($result, AccountTransformer::class);

        return $this->respondWithJson('Account updated', $resource);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $record)
    {
        $result = AccountService::delete($record)->getResult();

        if ($result) {
            return $this->respondWithJson('Account deleted', ['success' => true]);
        }
        return $this->respondWithJson('Account NOT deleted', ['success' => false]);
    }
}