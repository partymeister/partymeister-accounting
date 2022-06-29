<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\ApiController;
use Partymeister\Accounting\Http\Requests\Backend\AccountRequest;
use Partymeister\Accounting\Http\Resources\AccountCollection;
use Partymeister\Accounting\Http\Resources\AccountResource;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Services\AccountService;

/**
 * Class AccountsController
 */
class AccountsController extends ApiController
{
    protected string $model = 'Partymeister\Accounting\Models\Account';

    protected string $modelResource = 'account';

    /**
     * @OA\Get (
     *   tags={"AccountsController"},
     *   path="/api/accounts",
     *   summary="Get account collection",
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/AccountResource")
     *       ),
     *       @OA\Property(
     *         property="meta",
     *         ref="#/components/schemas/PaginationMeta"
     *       ),
     *       @OA\Property(
     *         property="links",
     *         ref="#/components/schemas/PaginationLinks"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Collection read"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   )
     * )
     *
     * Display a listing of the resource.
     *
     * @return AccountCollection
     */
    public function index()
    {
        $paginator = AccountService::collection()
                                   ->getPaginator();

        return (new AccountCollection($paginator))->additional(['message' => 'Account collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"AccountsController"},
     *   path="/api/accounts",
     *   summary="Create new account",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/AccountRequest")
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/AccountResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Account created"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *   )
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param  AccountRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AccountRequest $request)
    {
        $result = AccountService::create($request)
                                ->getResult();

        return (new AccountResource($result))->additional(['message' => 'Account created'])
                                             ->response()
                                             ->setStatusCode(201);
    }

    /**
     * @OA\Get (
     *   tags={"AccountsController"},
     *   path="/api/accounts/{account}",
     *   summary="Get single account",
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="integer"),
     *     in="path",
     *     name="account",
     *     parameter="account",
     *     description="Account id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/AccountResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Account read"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *   )
     * )
     *
     * Display the specified resource.
     *
     * @param  Account  $record
     * @return AccountResource
     */
    public function show(Account $record)
    {
        $result = AccountService::show($record)
                                ->getResult();

        return (new AccountResource($result))->additional(['message' => 'Account read']);
    }

    /**
     * @OA\Put (
     *   tags={"AccountsController"},
     *   path="/api/accounts/{account}",
     *   summary="Update an existing account",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/AccountRequest")
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="integer"),
     *     in="path",
     *     name="account",
     *     parameter="account",
     *     description="Account id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/AccountResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Account updated"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *   )
     * )
     *
     * Update the specified resource in storage.
     *
     * @param  AccountRequest  $request
     * @param  Account  $record
     * @return AccountResource
     */
    public function update(AccountRequest $request, Account $record)
    {
        $result = AccountService::update($record, $request)
                                ->getResult();

        return (new AccountResource($result))->additional(['message' => 'Account updated']);
    }

    /**
     * @OA\Delete (
     *   tags={"AccountsController"},
     *   path="/api/accounts/{account}",
     *   summary="Delete a account",
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="integer"),
     *     in="path",
     *     name="account",
     *     parameter="account",
     *     description="Account id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Account deleted"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="Bad request",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Problem deleting account"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  Account  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Account $record)
    {
        $result = AccountService::delete($record)
                                ->getResult();

        if ($result) {
            return response()->json(['message' => 'Account deleted']);
        }

        return response()->json(['message' => 'Problem deleting Account'], 404);
    }
}
