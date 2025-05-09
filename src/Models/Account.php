<?php

namespace Partymeister\Accounting\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Kra8\Snowflake\HasShortflakePrimary;
use Motor\Backend\Models\User;
use Motor\CMS\Database\Factories\AccountFactory;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

/**
 * Partymeister\Accounting\Models\Account
 *
 * @property int $id
 * @property int|null $account_type_id
 * @property string $name
 * @property string $currency_iso_4217
 * @property int $has_pos
 * @property array $pos_configuration
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AccountType|null $account_type
 * @property-read User $creator
 * @property-read User|null $eraser
 * @property-read mixed $balance
 * @property-read mixed $last_booking
 * @property-read User $updater
 *
 * @method static Builder|Account filteredBy(Filter $filter, $column)
 * @method static Builder|Account filteredByMultiple(Filter $filter)
 * @method static Builder|Account newModelQuery()
 * @method static Builder|Account newQuery()
 * @method static Builder|Account query()
 * @method static Builder|Account search($q, $full_text = false)
 * @method static Builder|Account whereAccountTypeId($value)
 * @method static Builder|Account whereCreatedAt($value)
 * @method static Builder|Account whereCreatedBy($value)
 * @method static Builder|Account whereCurrencyIso4217($value)
 * @method static Builder|Account whereDeletedBy($value)
 * @method static Builder|Account whereHasPos($value)
 * @method static Builder|Account whereId($value)
 * @method static Builder|Account whereName($value)
 * @method static Builder|Account wherePosConfiguration($value)
 * @method static Builder|Account whereUpdatedAt($value)
 * @method static Builder|Account whereUpdatedBy($value)
 *
 * @mixin Eloquent
 */
class Account extends Model
{
    use BlameableTrait;
    use Filterable;
    use HasFactory;
    use HasShortflakePrimary;
    use Searchable;

    /**
     * Searchable columns for the searchable trait
     *
     * @var array
     */
    protected $searchableColumns = [
        'accounts.name',
        'account_type.name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'account_type_id',
        'is_cashbox',
        'currency_iso_4217',
        'has_pos',
        'has_card_payments',
        'has_coupon_payments',
        'pos_configuration',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'pos_configuration' => 'array',
    ];

    protected static function newFactory()
    {
        return AccountFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function account_type()
    {
        return $this->belongsTo(AccountType::class);
    }

    /**
     * @return mixed
     */
    public function getCashBalanceAttribute()
    {
        $incoming = DB::table('bookings')
            ->where('to_account_id', $this->id)
            ->where('is_card_payment', false)
            ->where('is_coupon_payment', false)
            ->sum('price_with_vat');
        $outgoing = DB::table('bookings')
            ->where('from_account_id', $this->id)
            ->where('is_card_payment', false)
            ->where('is_coupon_payment', false)
            ->sum('price_with_vat');

        return $incoming - $outgoing;
    }

    /**
     * @return mixed
     */
    public function getCardBalanceAttribute()
    {
        $incoming = DB::table('bookings')
            ->where('to_account_id', $this->id)
            ->where('is_card_payment', true)
            ->sum('price_with_vat');

        return $incoming;
    }

    /**
     * @return mixed
     */
    public function getCouponBalanceAttribute()
    {
        $incoming = DB::table('bookings')
            ->where('to_account_id', $this->id)
            ->where('is_coupon_payment', true)
            ->sum('price_with_vat');

        return $incoming;
    }

    /**
     * @return mixed|null
     */
    public function getLastBookingAttribute()
    {
        $booking = Booking::where('to_account_id', $this->id)
            ->orWhere('from_account_id', $this->id)
            ->orderBy('created_at', 'DESC')
            ->first();
        if (! is_null($booking)) {
            return $booking->created_at;
        }

        return null;
    }
}
