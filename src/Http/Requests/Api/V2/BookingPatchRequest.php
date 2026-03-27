<?php

namespace Partymeister\Accounting\Http\Requests\Api\V2;

class BookingPatchRequest extends BookingPostRequest
{
    public function rules(): array
    {
        return collect(parent::rules())
            ->mapWithKeys(fn ($rule, $key) => [$key => 'sometimes|'.$rule])
            ->all();
    }
}
