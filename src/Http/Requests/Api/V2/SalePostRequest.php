<?php

namespace Partymeister\Accounting\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class SalePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => 'required|exists:items,id',
            'earnings_booking_id' => 'required|exists:bookings,id',
            'cost_booking_id' => 'nullable|exists:bookings,id',
            'quantity' => 'required|integer|min:1',
            'vat_percentage' => 'required|numeric',
            'price_with_vat' => 'required|numeric',
            'price_without_vat' => 'required|numeric',
            'currency_iso_4217' => 'required|string|size:3',
        ];
    }
}
