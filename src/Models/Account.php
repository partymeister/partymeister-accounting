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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Partymeister\Accounting\Models\AccountType|null $account_type
 * @property-read \Motor\Backend\Models\User $creator
 * @property-read \Motor\Backend\Models\User|null $eraser
 * @property-read mixed $balance
 * @property-read mixed $last_booking
 * @property-read \Motor\Backend\Models\User $updater
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account filteredBy(\Motor\Core\Filter\Filter $filter, $column)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account filteredByMultiple(\Motor\Core\Filter\Filter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account search($q, $full_text = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account whereAccountTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account whereCurrencyIso4217($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account whereHasPos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account wherePosConfiguration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\Account whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Account extends Model
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
    protected $searchableColumns = [ 'accounts.name', 'account_type.name' ];

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
        'pos_configuration'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'pos_configuration' => 'array',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account_type()
    {
        return $this->belongsTo(AccountType::class);
    }


    public function getBalanceAttribute()
    {
        $incoming = DB::table('bookings')->where('to_account_id', $this->id)->sum('price_with_vat');
        $outgoing = DB::table('bookings')->where('from_account_id', $this->id)->sum('price_with_vat');

        return $incoming - $outgoing;
    }


    public function getLastBookingAttribute()
    {
        $booking = Booking::where('to_account_id', $this->id)
                          ->orWhere('from_account_id', $this->id)
                          ->orderBy('created_at', 'DESC')
                          ->first();
        if ( ! is_null($booking)) {
            return $booking->created_at;
        }

        return null;
    }
}
