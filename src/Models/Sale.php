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
use Motor\Admin\Models\User;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Booking|null $cost_booking
 * @property-read User $creator
 * @property-read Booking $earnings_booking
 * @property-read User|null $eraser
 * @property-read mixed $item_and_quantity
 * @property-read Item $item
 * @property-read User $updater
 *
 * @method static Builder|Sale filteredBy(Filter $filter, $column)
 * @method static Builder|Sale filteredByMultiple(Filter $filter)
 * @method static Builder|Sale newModelQuery()
 * @method static Builder|Sale newQuery()
 * @method static Builder|Sale query()
 * @method static Builder|Sale search($q, $full_text = false)
 * @method static Builder|Sale whereCostBookingId($value)
 * @method static Builder|Sale whereCreatedAt($value)
 * @method static Builder|Sale whereCreatedBy($value)
 * @method static Builder|Sale whereCurrencyIso4217($value)
 * @method static Builder|Sale whereDeletedBy($value)
 * @method static Builder|Sale whereEarningsBookingId($value)
 * @method static Builder|Sale whereId($value)
 * @method static Builder|Sale whereItemId($value)
 * @method static Builder|Sale wherePriceWithVat($value)
 * @method static Builder|Sale wherePriceWithoutVat($value)
 * @method static Builder|Sale whereQuantity($value)
 * @method static Builder|Sale whereUpdatedAt($value)
 * @method static Builder|Sale whereUpdatedBy($value)
 * @method static Builder|Sale whereVatPercentage($value)
 * @mixin Eloquent
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
    protected $blameable = ['created', 'updated', 'deleted'];

    /**
     * Searchable columns for the searchable trait
     *
     * @var array
     */
    protected $searchableColumns = [
        'sales.quantity',
        'item.name',
        'sales.price_with_vat',
        'sales.price_without_vat',
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
        'currency_iso_4217',
    ];

    /**
     * @return BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * @return BelongsTo
     */
    public function earnings_booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * @return BelongsTo
     */
    public function cost_booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * @return string
     */
    public function getItemAndQuantityAttribute()
    {
        return $this->quantity.'x '.$this->item->name;
    }
}
