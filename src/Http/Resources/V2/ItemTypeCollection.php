<?php

namespace Partymeister\Accounting\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class ItemTypeCollection extends BaseCollection
{
    public $collects = ItemTypeResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
