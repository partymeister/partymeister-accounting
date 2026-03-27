<?php

namespace Partymeister\Accounting\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class ItemPatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'internal_description' => 'nullable|string',
            'item_type_id' => 'sometimes|required|exists:item_types,id',
            'vat_percentage' => 'sometimes|required|numeric',
            'price_with_vat' => 'sometimes|required|numeric',
            'price_without_vat' => 'sometimes|required|numeric',
            'cost_price_with_vat' => 'sometimes|required|numeric',
            'cost_price_without_vat' => 'sometimes|required|numeric',
            'currency_iso_4217' => 'sometimes|required|string|size:3',
            'can_be_ordered' => 'nullable|boolean',
            'is_visible' => 'nullable|boolean',
            'sort_position' => 'nullable|integer',
            'pos_cost_account_id' => 'nullable|exists:accounts,id',
            'pos_create_booking_for_item_id' => 'nullable|exists:items,id',
            'pos_can_book_negative_quantities' => 'nullable|boolean',
        ];
    }
}
