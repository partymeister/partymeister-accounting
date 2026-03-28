<?php

namespace Partymeister\Accounting\Http\Controllers\Api\V2\Accounts;

use Illuminate\Routing\Controller;
use Partymeister\Accounting\Http\Requests\Api\V2\PosLayoutPutRequest;
use Partymeister\Accounting\Http\Resources\V2\PosLayoutResource;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\Item;

/**
 * @tags Accounting: POS
 */
class PosLayoutController extends Controller
{
    public function show(Account $account): PosLayoutResource
    {
        $this->loadPosLayoutData($account);

        return (new PosLayoutResource($account))
            ->additional(['meta' => ['message' => 'POS layout retrieved']]);
    }

    public function update(PosLayoutPutRequest $request, Account $account): PosLayoutResource
    {
        $account->pos_configuration = $request->validated('pos_configuration');
        $account->save();

        $this->loadPosLayoutData($account);

        return (new PosLayoutResource($account))
            ->additional(['meta' => ['message' => 'POS layout updated']]);
    }

    private function loadPosLayoutData(Account $account): void
    {
        $config = $account->pos_configuration ?? [];

        $itemIds = collect($config)
            ->flatten()
            ->filter(fn ($id) => $id !== 'separator')
            ->unique()
            ->values();

        $account->setAttribute('pos_items', Item::whereIn('id', $itemIds)->get()->keyBy('id'));

        $account->setAttribute('pos_last_booking', Booking::where('to_account_id', $account->id)
            ->orderBy('created_at', 'DESC')
            ->first());
    }
}
