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
 * Partymeister\Accounting\Models\AccountType
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Motor\Backend\Models\User $creator
 * @property-read \Motor\Backend\Models\User|null $eraser
 * @property-read \Motor\Backend\Models\User $updater
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\AccountType filteredBy(\Motor\Core\Filter\Filter $filter, $column)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\AccountType filteredByMultiple(\Motor\Core\Filter\Filter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\AccountType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\AccountType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\AccountType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\AccountType search($q, $full_text = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\AccountType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\AccountType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\AccountType whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\AccountType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\AccountType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\AccountType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Accounting\Models\AccountType whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class AccountType extends Model
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
        'name'
    ];
}
