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
 * Partymeister\Accounting\Models\Sale
 *
 * @property int $id
 * @property int $earnings_booking_id
 * @property int|null $cost_booking_id
 * @property int $item_id
 * @property int $quantity
 * @property float $price_with_vat
 * @property float $price_without_vat
 * @property float $vat_percentage
 * @property string $currency_iso_4217
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Partymeister\Accounting\Models\Booking|null $cost_booking
 * @property-read \Motor\Backend\Models\User $creator
 * @property-read \Partymeister\Accounting\Models\Booking $earnings_booking
 * @property-read \Motor\Backend\Models\User|null $eraser
 * @property-read mixed $item_and_quantity
 * @property-read \Partymeister\Accounting\Models\Item $item
 * @property-read \Motor\Backend\Models\User $updater
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale filteredBy(\Motor\Core\Filter\Filter $filter, $column)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale filteredByMultiple(\Motor\Core\Filter\Filter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale search($q, $full_text = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale whereCostBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale whereCurrencyIso4217($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale whereEarningsBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale wherePriceWithVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale wherePriceWithoutVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Sale whereVatPercentage($value)
 * @mixin \Eloquent
 */
class Sale extends Model
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
        'sales.quantity',
        'item.name',
        'sales.price_with_vat',
        'sales.price_without_vat'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'earnings_booking_id',
        'cost_booking_id',
        'quantity',
        'vat_percentage',
        'price_with_vat',
        'price_without_vat',
        'currency_iso_4217'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function earnings_booking()
    {
        return $this->belongsTo(Booking::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cost_booking()
    {
        return $this->belongsTo(Booking::class);
    }


    public function getItemAndQuantityAttribute()
    {
        return $this->quantity . 'x ' . $this->item->name;
    }
}
