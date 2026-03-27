<?php

namespace Partymeister\Accounting\Http\Requests\Api\V2;

class AccountPatchRequest extends AccountPostRequest
{
    public function rules(): array
    {
        return collect(parent::rules())
            ->mapWithKeys(fn ($rule, $key) => [$key => 'sometimes|'.$rule])
            ->all();
    }
}
