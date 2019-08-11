<?php

namespace Partymeister\Accounting\Transformers;

use League\Fractal;
use Partymeister\Accounting\Models\AccountType;

/**
 * Class AccountTypeTransformer
 * @package Partymeister\Accounting\Transformers
 */
class AccountTypeTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];


    /**
     * Transform record to array
     *
     * @param AccountType $record
     *
     * @return array
     */
    public function transform(AccountType $record)
    {
        return [
            'id'   => (int) $record->id,
            'name' => $record->name
        ];
    }
}
