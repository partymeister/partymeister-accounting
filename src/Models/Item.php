<?php

namespace Partymeister\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Motor\Core\Traits\Filterable;
use Culpa\Traits\Blameable;
use Culpa\Traits\CreatedBy;
use Culpa\Traits\DeletedBy;
use Culpa\Traits\UpdatedBy;

class Item extends Model
{

    use Eloquence;
    use Filterable;
    use Blameable, CreatedBy, UpdatedBy, DeletedBy;

    /**
     * Columns for the Blameable trait
     *
     * @var array
     */
    protected $blameable = [ 'created', 'updated', 'deleted' ];

    /**
     * Searchable columns for the Eloquence trait
     *
     * @var array
     */
    protected $searchableColumns = [
        'name',
        'description',
        'item_type.name',
        'internal_description',
        'price_with_vat',
        'price_without_vat'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'pos_earnings_account_id',
        'pos_cost_account_id',
        'item_type_id',
        'internal_description',
        'vat_percentage',
        'price_with_vat',
        'price_without_vat',
        'cost_price_with_vat',
        'cost_price_without_vat',
        'currency_iso_4217',
        'can_be_ordered',
        'is_visible',
        'sort_position',
        'is_visible_in_pos',
        'pos_create_booking_for_item_id',
        'pos_can_book_negative_quantities',
        'pos_sort_position',
        'pos_do_break'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pos_earnings_account()
    {
        return $this->belongsTo(Account::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pos_cost_account()
    {
        return $this->belongsTo(Account::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item_type()
    {
        return $this->belongsTo(ItemType::class);
    }


    public function sell($quantity, Booking $booking)
    {
        $sale                      = new Sale;
        $sale->quantity            = $quantity;
        $sale->earnings_booking_id = $booking->id;
        $sale->item_id             = $this->id;
        $sale->price_with_vat      = $quantity * $this->price_with_vat;
        $sale->price_without_vat   = $quantity * $this->price_without_vat;
        $sale->vat_percentage      = $this->vat_percentage;
        $sale->currency_iso_4217   = $this->currency_iso_4217;
        $sale->save();

        if ( ! is_null($this->pos_cost_account)) {
            $costBooking                    = new Booking();
            $costBooking->sale_id           = $sale->id;
            $costBooking->description       = $sale->item_and_quantity;
            $costBooking->vat_percentage    = $this->vat_percentage;
            $costBooking->price_with_vat    = $quantity * $this->cost_price_with_vat;
            $costBooking->price_without_vat = $quantity * $this->cost_price_without_vat;
            $costBooking->currency_iso_4217 = $this->currency_iso_4217;
            $costBooking->to_account_id     = $this->pos_cost_account_id;
            $costBooking->save();

            $sale->cost_booking_id = $costBooking->id;
            $sale->save();
        }

        return $sale;
    }

}