<?php

namespace Partymeister\Accounting\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Accounting\Models\Account;

/**
 * @mixin Account
 */
class AccountResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'currency_iso_4217' => $this->currency_iso_4217,
            'has_pos' => (bool) $this->has_pos,
            'has_card_payments' => (bool) $this->has_card_payments,
            'has_coupon_payments' => (bool) $this->has_coupon_payments,
            'pos_configuration' => $this->pos_configuration,
            'cash_balance' => (float) $this->cash_balance,
            'card_balance' => (float) $this->card_balance,
            'coupon_balance' => (float) $this->coupon_balance,
            'account_type' => new AccountTypeResource($this->whenLoaded('account_type')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
