<?php

namespace Partymeister\Accounting\Transformers;

use League\Fractal;
use Partymeister\Accounting\Models\Item;

class ItemTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [ 'pos_earnings_account', 'pos_cost_account', 'pos_book_item' ];

    protected $defaultIncludes = [ 'item_type' ];


    /**
     * Transform record to array
     *
     * @param Item $record
     *
     * @return array
     */
    public function transform(Item $record)
    {
        return [
            'id'                               => (int) $record->id,
            'item_type_id'                     => (int) $record->item_type_id,
            'name'                             => $record->name,
            'description'                      => $record->description,
            'internal_description'             => $record->internal_description,
            'vat_percentage'                   => (float) $record->vat_percentage,
            'price_with_vat'                   => (float) $record->price_with_vat,
            'price_without_vat'                => (float) $record->price_without_vat,
            'cost_price_with_vat'              => (float) $record->cost_price_with_vat,
            'cost_price_without_vat'           => (float) $record->cost_price_without_vat,
            'currency_iso_4217'                => $record->currency_iso_4217,
            'can_be_ordered'                   => (bool) $record->can_be_ordered,
            'sort_position'                    => (int) $record->sort_position,
            'pos_sort_position'                => (int) $record->pos_sort_position,
            'is_visible_in_pos'                => (bool) $record->is_visible_in_pos,
            'pos_create_booking_for_item_id'   => (int) $record->pos_create_booking_for_item_id,
            'pos_can_book_negative_quantities' => (bool) $record->pos_can_book_negative_quantities,
            'pos_do_break'                     => (bool) $record->pos_do_break,
            'pos_earnings_account_id'          => (int) $record->pos_earnings_account_id,
            'pos_cost_account_id'              => (int) $record->pos_cost_account_id,

        ];
    }


    /**
     * @param Item $record
     *
     * @return Fractal\Resource\Item
     */
    public function includeItemType(Item $record)
    {
        if ( ! is_null($record->item_type)) {
            return $this->item($record->item_type, new ItemTypeTransformer());
        }
    }


    /**
     * @param Item $record
     *
     * @return Fractal\Resource\Item
     */
    public function includePosEarningsAccount(Item $record)
    {
        if ( ! is_null($record->earnings_account)) {
            return $this->item($record->earnings_account, new AccountTransformer());
        }
    }


    /**
     * @param Item $record
     *
     * @return Fractal\Resource\Item
     */
    public function includePosCostAccount(Item $record)
    {
        if ( ! is_null($record->cost_account)) {
            return $this->item($record->cost_account, new AccountTransformer());
        }
    }


    /**
     * @param Item $record
     *
     * @return Fractal\Resource\Item
     */
    public function includePosBookItem(Item $record)
    {
        if ( ! is_null($record->pos_create_booking_for_item_id)) {
            return $this->item($record->pos_create_booking_for_item_id, new ItemTransformer());
        }
    }

}
