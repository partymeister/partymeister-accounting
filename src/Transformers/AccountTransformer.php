<?php

namespace Partymeister\Accounting\Transformers;

use League\Fractal;
use Partymeister\Accounting\Models\Account;

/**
 * Class AccountTransformer
 * @package Partymeister\Accounting\Transformers
 */
class AccountTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [ 'account_type' ];

    /**
     * @var array
     */
    protected $defaultIncludes = [ 'account_type' ];


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
        if (! is_null($record->account_type)) {
            return $this->item($record->account_type, new AccountTypeTransformer());
        }
    }
}
