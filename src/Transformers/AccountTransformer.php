<?php

namespace Partymeister\Accounting\Transformers;

use League\Fractal;
use Partymeister\Accounting\Models\Account;

class AccountTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [ 'accountType' ];

    protected $defaultIncludes = [ 'accountType' ];


    /**
     * Transform record to array
     *
     * @param Account $record
     *
     * @return array
     */
    public function transform(Account $record)
    {
        return [
            'id'                => (int) $record->id,
            'name'              => $record->name,
            'currency_iso_4217' => $record->currency_iso_4217,
            'account_type_id'   => (int) $record->account_type_id,
            'has_pos'           => (bool) $record->has_pos,
        ];
    }


    /**
     * @param Account $record
     *
     * @return Fractal\Resource\Item
     */
    public function includeAccountType(Account $record)
    {
        if ( ! is_null($record->accoun_type)) {
            return $this->item($record->account_type, new AccountTypeTransformer());
        }
    }
}
