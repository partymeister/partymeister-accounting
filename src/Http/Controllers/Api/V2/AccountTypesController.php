<?php

namespace Partymeister\Accounting\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Accounting\Http\Requests\Api\V2\AccountTypeGetRequest;
use Partymeister\Accounting\Http\Requests\Api\V2\AccountTypePatchRequest;
use Partymeister\Accounting\Http\Requests\Api\V2\AccountTypePostRequest;
use Partymeister\Accounting\Http\Resources\V2\AccountTypeCollection;
use Partymeister\Accounting\Http\Resources\V2\AccountTypeResource;
use Partymeister\Accounting\Models\AccountType;
use Partymeister\Accounting\Services\AccountTypeService;

/**
 * @tags Accounting: Account Types
 */
class AccountTypesController extends ApiController
{
    protected string $model = AccountType::class;

    protected string $modelResource = 'account_type';

    public function index(AccountTypeGetRequest $request): AccountTypeCollection
    {
        $paginator = AccountTypeService::collection()->getPaginator();

        return (new AccountTypeCollection($paginator))
            ->additional(['meta' => ['message' => 'Account types retrieved']]);
    }

    public function store(AccountTypePostRequest $request): JsonResponse
    {
        $result = AccountTypeService::create($request)->getResult();

        return (new AccountTypeResource($result))
            ->additional(['meta' => ['message' => 'Account type created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(AccountType $account_type): AccountTypeResource
    {
        $result = AccountTypeService::show($account_type)->getResult();

        return (new AccountTypeResource($result))
            ->additional(['meta' => ['message' => 'Account type retrieved']]);
    }

    public function update(AccountTypePatchRequest $request, AccountType $account_type): AccountTypeResource
    {
        $result = AccountTypeService::update($account_type, $request)->getResult();

        return (new AccountTypeResource($result))
            ->additional(['meta' => ['message' => 'Account type updated']]);
    }

    public function destroy(AccountType $account_type): Response
    {
        $result = AccountTypeService::delete($account_type)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting account type');
    }
}
