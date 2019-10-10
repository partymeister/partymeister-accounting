<?php

namespace Partymeister\Accounting\Models;

use Culpa\Traits\Blameable;
use Culpa\Traits\CreatedBy;
use Culpa\Traits\DeletedBy;
use Culpa\Traits\UpdatedBy;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Motor\Backend\Models\User;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;

/**
 * Partymeister\Accounting\Models\Booking
 *
 * @property int                                               $id
 * @property int|null                                          $sale_id
 * @property int|null                                          $from_account_id
 * @property int|null                                          $to_account_id
 * @property string                                            $description
 * @property float                                             $vat_percentage
 * @property float                                             $price_with_vat
 * @property float                                             $price_without_vat
 * @property string                                            $currency_iso_4217
 * @property int                             $is_manual_booking
 * @property int                             $created_by
 * @property int                             $updated_by
 * @property int|null                        $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User                       $creator
 * @property-read User|null                  $eraser
 * @property-read Account|null               $from_account
 * @property-read Account|null               $to_account
 * @property-read User                       $updater
 * @method static Builder|Booking filteredBy( Filter $filter, $column )
 * @method static Builder|Booking filteredByMultiple( Filter $filter )
 * @method static Builder|Booking newModelQuery()
 * @method static Builder|Booking newQuery()
 * @method static Builder|Booking query()
 * @method static Builder|Booking search( $q, $full_text = false )
 * @method static Builder|Booking whereCreatedAt( $value )
 * @method static Builder|Booking whereCreatedBy( $value )
 * @method static Builder|Booking whereCurrencyIso4217( $value )
 * @method static Builder|Booking whereDeletedBy( $value )
 * @method static Builder|Booking whereDescription( $value )
 * @method static Builder|Booking whereFromAccountId( $value )
 * @method static Builder|Booking whereId( $value )
 * @method static Builder|Booking whereIsManualBooking( $value )
 * @method static Builder|Booking wherePriceWithVat( $value )
 * @method static Builder|Booking wherePriceWithoutVat( $value )
 * @method static Builder|Booking whereSaleId( $value )
 * @method static Builder|Booking whereToAccountId( $value )
 * @method static Builder|Booking whereUpdatedAt( $value )
 * @method static Builder|Booking whereUpdatedBy( $value )
 * @method static Builder|Booking whereVatPercentage( $value )
 * @mixin Eloquent
 */
class Booking extends Model
{
    use Searchable;
    use Filterable;

    use Blameable, CreatedBy, UpdatedBy, DeletedBy;

    /**
     * Columns for the Blameable trait
     *
     * @var array
     */
    protected $blameable = [ 'created', 'updated', 'deleted' ];

    /**
     * Searchable columns for the searchable trait
     *
     * @var array
     */
    protected $searchableColumns = [
        'bookings.description',
        //'bookings.quantity',
        'bookings.price_with_vat',
        'bookings.price_without_vat',
        //'item.name'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from_account_id',
        'to_account_id',
        'description',
        'vat_percentage',
        'price_with_vat',
        'price_without_vat',
        'currency_iso_4217',
        'is_manual_booking'
    ];


    /**
     * @param Account $account
     * @param         $item
     * @param int     $quantity
     * @return Booking
     */
    public static function createSale(Account $account, $item, $quantity = 1)
    {
        if (! $item instanceof Item) {
            return self::createSales($account, [ $item => $quantity ]);
        }

        return self::createSales($account, [ $item->id => $quantity ]);
    }


    /**
     * @param Account $account
     * @param         $items
     * @return Booking
     */
    public static function createSales(Account $account, $items)
    {
        $record = new static;

        $description       = [];
        $price_without_vat = 0;
        $price_with_vat    = 0;

        foreach ($items as $itemId => $quantity) {
            $item = Item::find($itemId);
            if (is_null($item)) {
                continue;
            }

            $description[]     = $quantity . 'x ' . $item->name;
            $price_with_vat    += $quantity * $item->price_with_vat;
            $price_without_vat += $quantity * $item->price_without_vat;

            // TODO: separate bookings per vat percentage (and currency theoretically)
            $record->vat_percentage    = $item->vat_percentage;
            $record->currency_iso_4217 = $item->currency_iso_4217;
        }

        $record->description       = implode("\r\n", $description);
        $record->price_with_vat    = $price_with_vat;
        $record->price_without_vat = $price_without_vat;
        $record->to_account_id     = $account->id;
        $record->save();

        // Add sales to items
        foreach ($items as $itemId => $quantity) {
            $item = Item::find($itemId);
            if (is_null($item)) {
                continue;
            }

            $item->sell($quantity, $record);
        }

        return $record;
    }


    /**
     * @return BelongsTo
     */
    public function from_account()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }


    /**
     * @return BelongsTo
     */
    public function to_account()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }
}
