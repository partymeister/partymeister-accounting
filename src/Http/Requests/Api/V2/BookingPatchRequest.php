<?php

namespace Partymeister\Accounting\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class BookingPatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'sometimes|required|string',
            'vat_percentage' => 'sometimes|required|numeric',
            'price_with_vat' => 'sometimes|required|numeric',
            'price_without_vat' => 'sometimes|required|numeric',
            'currency_iso_4217' => 'sometimes|required|string|size:3|currency_compatibility',
            'from_account_id' => 'nullable|exists:accounts,id',
            'to_account_id' => 'nullable|exists:accounts,id',
            'is_manual_booking' => 'nullable|boolean',
            'is_card_payment' => 'nullable|boolean',
            'is_coupon_payment' => 'nullable|boolean',
        ];
    }
}
