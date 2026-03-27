<?php

namespace Partymeister\Accounting\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class SaleCollection extends BaseCollection
{
    public $collects = SaleResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
