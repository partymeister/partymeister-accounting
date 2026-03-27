<?php

namespace Partymeister\Accounting\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class BookPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*' => 'required|integer',
            'is_card_payment' => 'nullable|boolean',
            'is_coupon_payment' => 'nullable|boolean',
        ];
    }
}
