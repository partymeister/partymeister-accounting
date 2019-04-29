<?php

namespace Partymeister\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Motor\Core\Traits\Searchable;
use Motor\Core\Traits\Filterable;

use Culpa\Traits\Blameable;
use Culpa\Traits\CreatedBy;
use Culpa\Traits\DeletedBy;
use Culpa\Traits\UpdatedBy;

/**
 * Partymeister\Accounting\Models\Booking
 *
 * @property int $id
 * @property int|null $sale_id
 * @property int|null $from_account_id
 * @property int|null $to_account_id
 * @property string $description
 * @property float $vat_percentage
 * @property float $price_with_vat
 * @property float $price_without_vat
 * @property string $currency_iso_4217
 * @property int $is_manual_booking
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Motor\Backend\Models\User $creator
 * @property-read \Motor\Backend\Models\User|null $eraser
 * @property-read \Partymeister\Accounting\Models\Account|null $from_account
 * @property-read \Partymeister\Accounting\Models\Account|null $to_account
 * @property-read \Motor\Backend\Models\User $updater
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking filteredBy(\Motor\Core\Filter\Filter $filter, $column)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking filteredByMultiple(\Motor\Core\Filter\Filter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking search($q, $full_text = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking whereCurrencyIso4217($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking whereFromAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking whereIsManualBooking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking wherePriceWithVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking wherePriceWithoutVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking whereToAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Booking whereVatPercentage($value)
 * @mixin \Eloquent
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function from_account()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function to_account()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }


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


    public static function createSale(Account $account, $item, $quantity = 1)
    {
        if ( ! $item instanceof Item) {
            return self::createSales($account, [ $item => $quantity ]);
        }

        return self::createSales($account, [ $item->id => $quantity ]);
    }
}
