<?php

namespace Partymeister\Accounting\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class SalePatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => 'sometimes|required|exists:items,id',
            'earnings_booking_id' => 'sometimes|required|exists:bookings,id',
            'cost_booking_id' => 'nullable|exists:bookings,id',
            'quantity' => 'sometimes|required|integer|min:1',
            'vat_percentage' => 'sometimes|required|numeric',
            'price_with_vat' => 'sometimes|required|numeric',
            'price_without_vat' => 'sometimes|required|numeric',
            'currency_iso_4217' => 'sometimes|required|string|size:3',
        ];
    }
}
