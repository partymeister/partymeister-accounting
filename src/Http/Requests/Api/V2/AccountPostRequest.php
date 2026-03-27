<?php

namespace Partymeister\Accounting\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class AccountPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'account_type_id' => 'required|exists:account_types,id',
            'currency_iso_4217' => 'required|string|size:3',
            'has_pos' => 'nullable|boolean',
            'has_card_payments' => 'nullable|boolean',
            'has_coupon_payments' => 'nullable|boolean',
            'pos_configuration' => 'nullable|array',
        ];
    }
}
