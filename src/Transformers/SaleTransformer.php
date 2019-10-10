<?php

namespace Partymeister\Accounting\Transformers;

use League\Fractal;
use Partymeister\Accounting\Models\Sale;

/**
 * Class SaleTransformer
 * @package Partymeister\Accounting\Transformers
 */
class SaleTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [ 'item', 'earnings_booking', 'cost_booking' ];

    /**
     * @var array
     */
    protected $defaultIncludes = [ 'item', 'earnings_booking', 'cost_booking' ];


    /**
     * Transform record to array
     *
     * @param Sale $record
     *t
     *
     * @return array
     */
    public function transform(Sale $record)
    {
        return [
            'id'                  => (int) $record->id,
            'item_id'             => (int) $record->item_id,
            'earnings_booking_id' => (int) $record->earnings_booking_id,
            'cost_booking_id'     => (int) $record->cost_booking_id,
            'vat_percentage'      => (float) $record->vat_percentage,
            'price_with_vat'      => (float) $record->price_with_vat,
            'price_withouth_vat'  => (float) $record->price_without_vat,
            'currency_iso_4217'   => $record->currency_iso_4217,
            'quantity'            => (int) $record->quantity
        ];
    }


    /**
     * @param Sale $record
     *
     * @return Fractal\Resource\Item
     */
    public function includeItem(Sale $record)
    {
        if (! is_null($record->item)) {
            return $this->item($record->item, new ItemTransformer());
        }
    }


    /**
     * @param Sale $record
     *
     * @return Fractal\Resource\Item
     */
    public function includeEarningsBooking(Sale $record)
    {
        if (! is_null($record->earnings_booking)) {
            return $this->item($record->earnings_booking, new BookingTransformer());
        }
    }


    /**
     * @param Sale $record
     *
     * @return Fractal\Resource\Item
     */
    public function includeCostBooking(Sale $record)
    {
        if (! is_null($record->cost_booking)) {
            return $this->item($record->cost_booking, new BookingTransformer());
        }
    }
}
