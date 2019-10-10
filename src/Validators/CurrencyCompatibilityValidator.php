<?php

namespace Partymeister\Accounting\Validators;

use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;
use Partymeister\Accounting\Models\Account;

/**
 * Class CurrencyCompatibilityValidator
 * @package Partymeister\Accounting\Validators
 */
class CurrencyCompatibilityValidator
{

    /**
     * @param                                  $attribute
     * @param                                  $value
     * @param                                  $parameters
     * @param Validator $validator
     * @return bool
     */
    public function validate($attribute, $value, $parameters, Validator $validator)
    {
        $data = $validator->getData();

        $from_account_id = Arr::get($data, 'from_account_id');
        $to_account_id   = Arr::get($data, 'to_account_id');

        $from_account = (! is_null($from_account_id) ? Account::find($from_account_id) : null);
        $to_account   = (! is_null($to_account_id) ? Account::find($to_account_id) : null);

        if ((! is_null($from_account) && $from_account->currency_iso_4217 != Arr::get(
            $data,
            $attribute
        )) || (! is_null($to_account) && $to_account->currency_iso_4217 != Arr::get(
                        $data,
                        $attribute
                    ))) {
            return false;
        }

        return true;
    }
}
