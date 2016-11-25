<?php

namespace Partymeister\Accounting\Services;

use Partymeister\Accounting\Models\Item;
use Motor\Backend\Services\BaseService;

class ItemService extends BaseService
{

    protected $model = Item::class;
}
