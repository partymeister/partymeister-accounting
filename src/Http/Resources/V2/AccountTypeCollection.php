<?php

namespace Partymeister\Accounting\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class AccountTypeCollection extends BaseCollection
{
    public $collects = AccountTypeResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
