<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Http\Requests\Backend\AccountTypeRequest;
use Partymeister\Accounting\Models\AccountType;
use Partymeister\Accounting\Services\AccountTypeService;
use Partymeister\Accounting\Transformers\AccountTypeTransformer;

/**
 * Class AccountTypesController
 * @package Partymeister\Accounting\Http\Controllers\Api
 */
class AccountTypesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $paginator = AccountTypeService::collection()->getPaginator();
        $resource  = $this->transformPaginator($paginator, AccountTypeTransformer::class);

        return $this->respondWithJson('AccountType collection read', $resource);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param AccountTypeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AccountTypeRequest $request)
    {
        $result   = AccountTypeService::create($request)->getResult();
        $resource = $this->transformItem($result, AccountTypeTransformer::class);

        return $this->respondWithJson('AccountType created', $resource);
    }


    /**
     * Display the specified resource.
     *
     * @param AccountType $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(AccountType $record)
    {
        $result   = AccountTypeService::show($record)->getResult();
        $resource = $this->transformItem($result, AccountTypeTransformer::class);

        return $this->respondWithJson('AccountType read', $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param AccountTypeRequest $request
     * @param AccountType        $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AccountTypeRequest $request, AccountType $record)
    {
        $result   = AccountTypeService::update($record, $request)->getResult();
        $resource = $this->transformItem($result, AccountTypeTransformer::class);

        return $this->respondWithJson('AccountType updated', $resource);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param AccountType $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(AccountType $record)
    {
        $result = AccountTypeService::delete($record)->getResult();

        if ($result) {
            return $this->respondWithJson('AccountType deleted', [ 'success' => true ]);
        }

        return $this->respondWithJson('AccountType NOT deleted', [ 'success' => false ]);
    }
}
