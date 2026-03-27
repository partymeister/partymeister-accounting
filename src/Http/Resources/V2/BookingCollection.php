<?php

namespace Partymeister\Accounting\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class BookingCollection extends BaseCollection
{
    public $collects = BookingResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
