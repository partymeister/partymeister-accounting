<?php

namespace Partymeister\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Motor\Core\Traits\Filterable;
use Culpa\Traits\Blameable;
use Culpa\Traits\CreatedBy;
use Culpa\Traits\DeletedBy;
use Culpa\Traits\UpdatedBy;

class Sale extends Model
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
    protected $searchableColumns = [ 'quantity', 'item.name', 'cost_price_with_vat', 'cost_price_without_vat' ];

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