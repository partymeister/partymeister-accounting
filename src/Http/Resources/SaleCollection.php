<?php

namespace Partymeister\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Motor\Admin\Http\Resources\BaseCollection;

class SaleCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
