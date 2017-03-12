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
    protected $searchableColumns = [ 'name', 'account_type.name' ];

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
        'has_pos'
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

        return $total = $incoming - $outgoing;
    }


    public function getLastBookingAttribute()
    {
        return Booking::where('to_account_id', $this->id)->orWhere('from_account_id', $this->id)->orderBy('created_at',
            'DESC')->first()->created_at;
    }
}
