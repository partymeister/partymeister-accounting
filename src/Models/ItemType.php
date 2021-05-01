<?php

namespace Partymeister\Accounting\Models;

use Culpa\Traits\Blameable;
use Culpa\Traits\CreatedBy;
use Culpa\Traits\DeletedBy;
use Culpa\Traits\UpdatedBy;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Motor\Backend\Models\User;
use Motor\CMS\Database\Factories\ItemTypeFactory;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $creator
 * @property-read User|null $eraser
 * @property-read mixed $item_count
 * @property-read Item $items
 * @property-read User $updater
 * @method static Builder|ItemType filteredBy(Filter $filter, $column)
 * @method static Builder|ItemType filteredByMultiple(Filter $filter)
 * @method static Builder|ItemType newModelQuery()
 * @method static Builder|ItemType newQuery()
 * @method static Builder|ItemType query()
 * @method static Builder|ItemType search($q, $full_text = false)
 * @method static Builder|ItemType whereCreatedAt($value)
 * @method static Builder|ItemType whereCreatedBy($value)
 * @method static Builder|ItemType whereDeletedBy($value)
 * @method static Builder|ItemType whereId($value)
 * @method static Builder|ItemType whereIsVisible($value)
 * @method static Builder|ItemType whereName($value)
 * @method static Builder|ItemType whereSortPosition($value)
 * @method static Builder|ItemType whereUpdatedAt($value)
 * @method static Builder|ItemType whereUpdatedBy($value)
 * @mixin Eloquent
 */
class ItemType extends Model
{
    use Searchable;
    use Filterable;
    use Blameable, CreatedBy, UpdatedBy, DeletedBy;
    use HasFactory;

    /**
     * Columns for the Blameable trait
     *
     * @var array
     */
    protected $blameable = [
        'created',
        'updated',
        'deleted',
    ];

    /**
     * Searchable columns for the searchable trait
     *
     * @var array
     */
    protected $searchableColumns = [
        'name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sort_position',
        'is_visible',
    ];

    protected static function newFactory()
    {
        return ItemTypeFactory::new();
    }

    /**
     * @return mixed
     */
    public function getItemCountAttribute()
    {
        return $this->items()
                    ->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
