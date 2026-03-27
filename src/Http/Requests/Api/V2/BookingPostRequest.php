<?php

namespace Partymeister\Accounting\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class BookingPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'required|string',
            'vat_percentage' => 'required|numeric',
            'price_with_vat' => 'required|numeric',
            'price_without_vat' => 'required|numeric',
            'currency_iso_4217' => 'required|string|size:3|currency_compatibility',
            'from_account_id' => 'nullable|exists:accounts,id',
            'to_account_id' => 'nullable|exists:accounts,id',
            'is_manual_booking' => 'nullable|boolean',
            'is_card_payment' => 'nullable|boolean',
            'is_coupon_payment' => 'nullable|boolean',
        ];
    }
}
