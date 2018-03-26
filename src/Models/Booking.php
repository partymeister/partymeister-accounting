<?php

namespace Partymeister\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Motor\Core\Traits\Searchable;
use Motor\Core\Traits\Filterable;

use Culpa\Traits\Blameable;
use Culpa\Traits\CreatedBy;
use Culpa\Traits\DeletedBy;
use Culpa\Traits\UpdatedBy;

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
        'bookings.quantity',
        'bookings.price_with_vat',
        'bookings.price_without_vat',
        'item.name'
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
