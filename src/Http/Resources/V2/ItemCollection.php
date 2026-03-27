<?php

namespace Partymeister\Accounting\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class ItemCollection extends BaseCollection
{
    public $collects = ItemResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
