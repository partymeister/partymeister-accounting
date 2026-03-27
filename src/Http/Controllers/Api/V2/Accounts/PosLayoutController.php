<?php

namespace Partymeister\Accounting\Http\Controllers\Api\V2\Accounts;

use Illuminate\Routing\Controller;
use Partymeister\Accounting\Http\Requests\Api\V2\PosLayoutPutRequest;
use Partymeister\Accounting\Http\Resources\V2\PosLayoutResource;
use Partymeister\Accounting\Models\Account;

class PosLayoutController extends Controller
{
    public function show(Account $account): PosLayoutResource
    {
        return (new PosLayoutResource($account))
            ->additional(['meta' => ['message' => 'POS layout retrieved']]);
    }

    public function update(PosLayoutPutRequest $request, Account $account): PosLayoutResource
    {
        $account->pos_configuration = $request->validated('pos_configuration');
        $account->save();

        return (new PosLayoutResource($account))
            ->additional(['meta' => ['message' => 'POS layout updated']]);
    }
}
