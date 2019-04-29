<?php

namespace Partymeister\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Motor\Core\Traits\Searchable;
use Motor\Core\Traits\Filterable;
use Culpa\Traits\Blameable;
use Culpa\Traits\CreatedBy;
use Culpa\Traits\DeletedBy;
use Culpa\Traits\UpdatedBy;

/**
 * Partymeister\Accounting\Models\Item
 *
 * @property int $id
 * @property int|null $pos_cost_account_id
 * @property int|null $item_type_id
 * @property string $name
 * @property string $description
 * @property string $internal_description
 * @property float $vat_percentage
 * @property float $price_with_vat
 * @property float $price_without_vat
 * @property float $cost_price_with_vat
 * @property float $cost_price_without_vat
 * @property string $currency_iso_4217
 * @property int $can_be_ordered
 * @property int $is_visible
 * @property int|null $sort_position
 * @property int|null $pos_create_booking_for_item_id
 * @property int $pos_can_book_negative_quantities
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Motor\Backend\Models\User $creator
 * @property-read \Motor\Backend\Models\User|null $eraser
 * @property-read mixed $revenue
 * @property-read mixed $sales
 * @property-read \Partymeister\Accounting\Models\ItemType|null $item_type
 * @property-read \Partymeister\Accounting\Models\Account|null $pos_cost_account
 * @property-read \Partymeister\Accounting\Models\Account $pos_earnings_account
 * @property-read \Motor\Backend\Models\User $updater
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item filteredBy(\Motor\Core\Filter\Filter $filter, $column)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item filteredByMultiple(\Motor\Core\Filter\Filter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item search($q, $full_text = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereCanBeOrdered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereCostPriceWithVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereCostPriceWithoutVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereCurrencyIso4217($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereInternalDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereItemTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item wherePosCanBookNegativeQuantities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item wherePosCostAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item wherePosCreateBookingForItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item wherePriceWithVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item wherePriceWithoutVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereSortPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Item whereVatPercentage($value)
 * @mixin \Eloquent
 */
class Item extends Model
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
        'items.name',
        'items.description',
        'item_type.name',
        'internal_description',
        'items.price_with_vat',
        'items.price_without_vat'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
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
        'pos_create_booking_for_item_id',
        'pos_can_book_negative_quantities'
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
            $costBooking->price_with_vat    = ( $quantity * $this->cost_price_with_vat ) * -1;
            $costBooking->price_without_vat = ( $quantity * $this->cost_price_without_vat ) * -1;
            $costBooking->currency_iso_4217 = $this->currency_iso_4217;
            $costBooking->to_account_id     = $this->pos_cost_account_id;
            $costBooking->save();

            $sale->cost_booking_id = $costBooking->id;
            $sale->save();
        }

        return $sale;
    }

    public function getSalesAttribute()
    {
        $result = DB::table('sales')->select(DB::raw('SUM(quantity) as quantity'))->where('item_id', $this->id)->get();
        if (!is_null($result)) {
            return (!is_null($result[0]->quantity) ? $result[0]->quantity : 0);
        }
        return 0;
    }

    public function getRevenueAttribute()
    {
        $revenue = 0;
        $result = DB::table('sales')->select(DB::raw('SUM(quantity*items.price_with_vat) as quantity'))->join('items', 'item_id', '=', 'items.id')->where('item_id', $this->id)->get();
        if (!is_null($result)) {
            $revenue = (!is_null($result[0]->quantity) ? $result[0]->quantity : 0);
        }
        return number_format($revenue, 2, ',', '.').' €';
    }
}