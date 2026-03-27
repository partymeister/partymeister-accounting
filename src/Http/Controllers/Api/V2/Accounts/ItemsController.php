<?php

namespace Partymeister\Accounting\Http\Controllers\Api\V2\Accounts;

use Illuminate\Routing\Controller;
use Partymeister\Accounting\Http\Resources\V2\ItemCollection;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\Item;

class ItemsController extends Controller
{
    public function index(Account $account): ItemCollection
    {
        $config = $account->pos_configuration ?? [];

        $itemIds = collect($config)
            ->flatten()
            ->filter(fn ($id) => $id !== 'separator')
            ->unique()
            ->values();

        $items = Item::with('item_type')
            ->whereIn('id', $itemIds)
            ->orderBy('sort_position')
            ->paginate();

        return (new ItemCollection($items))
            ->additional(['meta' => ['message' => 'Account items retrieved']]);
    }
}
