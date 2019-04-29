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
 * Partymeister\Accounting\Models\ItemType
 *
 * @property int $id
 * @property string $name
 * @property int $is_visible
 * @property int|null $sort_position
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Motor\Backend\Models\User $creator
 * @property-read \Motor\Backend\Models\User|null $eraser
 * @property-read mixed $item_count
 * @property-read \Partymeister\Accounting\Models\Item $items
 * @property-read \Motor\Backend\Models\User $updater
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType filteredBy(\Motor\Core\Filter\Filter $filter, $column)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType filteredByMultiple(\Motor\Core\Filter\Filter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType search($q, $full_text = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType whereSortPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\ItemType whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class ItemType extends Model
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
        'name'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sort_position',
        'is_visible'
    ];


    /**
     * @return mixed
     */
    public function getItemCountAttribute()
    {
        return $this->items()->count();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
