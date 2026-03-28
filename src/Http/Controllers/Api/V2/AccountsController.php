<?php

namespace Partymeister\Accounting\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Accounting\Http\Requests\Api\V2\AccountGetRequest;
use Partymeister\Accounting\Http\Requests\Api\V2\AccountPatchRequest;
use Partymeister\Accounting\Http\Requests\Api\V2\AccountPostRequest;
use Partymeister\Accounting\Http\Resources\V2\AccountCollection;
use Partymeister\Accounting\Http\Resources\V2\AccountResource;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Services\AccountService;

/**
 * @tags Accounting: Accounts
 */
class AccountsController extends ApiController
{
    protected string $model = Account::class;

    protected string $modelResource = 'account';

    public function index(AccountGetRequest $request): AccountCollection
    {
        $paginator = AccountService::collection()->getPaginator();

        return (new AccountCollection($paginator))
            ->additional(['meta' => ['message' => 'Accounts retrieved']]);
    }

    public function store(AccountPostRequest $request): JsonResponse
    {
        $result = AccountService::create($request)->getResult();

        return (new AccountResource($result))
            ->additional(['meta' => ['message' => 'Account created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Account $account): AccountResource
    {
        $result = AccountService::show($account)->getResult();

        return (new AccountResource($result))
            ->additional(['meta' => ['message' => 'Account retrieved']]);
    }

    public function update(AccountPatchRequest $request, Account $account): AccountResource
    {
        $result = AccountService::update($account, $request)->getResult();

        return (new AccountResource($result))
            ->additional(['meta' => ['message' => 'Account updated']]);
    }

    public function destroy(Account $account): Response
    {
        $result = AccountService::delete($account)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting account');
    }
}
