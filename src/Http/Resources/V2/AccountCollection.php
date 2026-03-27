<?php

namespace Partymeister\Accounting\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class AccountCollection extends BaseCollection
{
    public $collects = AccountResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
