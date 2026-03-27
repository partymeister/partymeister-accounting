<?php

namespace Partymeister\Accounting\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class PosLayoutPutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pos_configuration' => 'required|array',
        ];
    }
}
