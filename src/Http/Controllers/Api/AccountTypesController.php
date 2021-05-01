<?php

namespace Partymeister\Accounting\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\ApiController;
use Partymeister\Accounting\Http\Requests\Backend\AccountTypeRequest;
use Partymeister\Accounting\Http\Resources\AccountTypeCollection;
use Partymeister\Accounting\Http\Resources\AccountTypeResource;
use Partymeister\Accounting\Models\AccountType;
use Partymeister\Accounting\Services\AccountTypeService;

/**
 * Class AccountTypesController
 *
 * @package Partymeister\Accounting\Http\Controllers\Api
 */
class AccountTypesController extends ApiController
{
    protected string $model = 'Partymeister\Accounting\Models\AccountType';

    protected string $modelResource = 'account_type';

    /**
     * @OA\Get (
     *   tags={"AccountTypesController"},
     *   path="/api/account_types",
     *   summary="Get account_type collection",
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
     *         @OA\Items(ref="#/components/schemas/AccountTypeResource")
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
     * @return AccountTypeCollection
     */
    public function index()
    {
        $paginator = AccountTypeService::collection()
                                       ->getPaginator();

        return (new AccountTypeCollection($paginator))->additional(['message' => 'AccountType collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"AccountTypesController"},
     *   path="/api/account_types",
     *   summary="Create new account_type",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/AccountTypeRequest")
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
     *         ref="#/components/schemas/AccountTypeResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="AccountType created"
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
     * @param AccountTypeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AccountTypeRequest $request)
    {
        $result = AccountTypeService::create($request)
                                    ->getResult();

        return (new AccountTypeResource($result))->additional(['message' => 'AccountType created'])
                                                 ->response()
                                                 ->setStatusCode(201);
    }

    /**
     * @OA\Get (
     *   tags={"AccountTypesController"},
     *   path="/api/account_types/{account_type}",
     *   summary="Get single account_type",
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
     *     name="account_type",
     *     parameter="account_type",
     *     description="AccountType id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/AccountTypeResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="AccountType read"
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
     * @param AccountType $record
     * @return AccountTypeResource
     */
    public function show(AccountType $record)
    {
        $result = AccountTypeService::show($record)
                                    ->getResult();

        return (new AccountTypeResource($result))->additional(['message' => 'AccountType read']);
    }

    /**
     * @OA\Put (
     *   tags={"AccountTypesController"},
     *   path="/api/account_types/{account_type}",
     *   summary="Update an existing account_type",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/AccountTypeRequest")
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
     *     name="account_type",
     *     parameter="account_type",
     *     description="AccountType id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/AccountTypeResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="AccountType updated"
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
     * @param AccountTypeRequest $request
     * @param AccountType $record
     * @return AccountTypeResource
     */
    public function update(AccountTypeRequest $request, AccountType $record)
    {
        $result = AccountTypeService::update($record, $request)
                                    ->getResult();

        return (new AccountTypeResource($result))->additional(['message' => 'AccountType updated']);
    }

    /**
     * @OA\Delete (
     *   tags={"AccountTypesController"},
     *   path="/api/account_types/{account_type}",
     *   summary="Delete a account_type",
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
     *     name="account_type",
     *     parameter="account_type",
     *     description="AccountType id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="AccountType deleted"
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
     *         example="Problem deleting account_type"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param AccountType $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(AccountType $record)
    {
        $result = AccountTypeService::delete($record)
                                    ->getResult();

        if ($result) {
            return response()->json(['message' => 'AccountType deleted']);
        }

        return response()->json(['message' => 'Problem deleting AccountType'], 404);
    }
}
