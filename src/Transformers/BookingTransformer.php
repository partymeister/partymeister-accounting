<?php

namespace Partymeister\Accounting\Transformers;

use League\Fractal;
use Partymeister\Accounting\Models\Booking;

/**
 * Class BookingTransformer
 * @package Partymeister\Accounting\Transformers
 */
class BookingTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * @var array
     */
    protected $defaultIncludes = [ 'from_account', 'to_account' ];


    /**
     * Transform record to array
     *
     * @param Booking $record
     *
     * @return array
     */
    public function transform(Booking $record)
    {
        return [
            'id'                => (int) $record->id,
            'description'       => $record->description,
            'to_account_id'     => $record->to_account_id,
            'from_account_id'   => $record->from_account_id,
            'vat_percentage'    => (float) $record->vat_percentage,
            'price_with_vat'    => (float) $record->price_with_vat,
            'price_without_vat' => (float) $record->price_without_vat,
            'currency_iso_4217' => $record->currency_iso_4217
        ];
    }


    /**
     * @param Booking $record
     *
     * @return Fractal\Resource\Item
     */
    public function includeFromAccount(Booking $record)
    {
        if (! is_null($record->from_account)) {
            return $this->item($record->from_account, new AccountTransformer());
        }
    }


    /**
     * @param Booking $record
     *
     * @return Fractal\Resource\Item
     */
    public function includeToAccount(Booking $record)
    {
        if (! is_null($record->to_account)) {
            return $this->item($record->to_account, new AccountTransformer());
        }
    }
}
