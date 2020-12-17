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
use Motor\CMS\Database\Factories\AccountTypeFactory;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;

/**
 * Partymeister\Accounting\Models\AccountType
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $creator
 * @property-read User|null $eraser
 * @property-read User $updater
 * @method static Builder|AccountType filteredBy(Filter $filter, $column)
 * @method static Builder|AccountType filteredByMultiple(Filter $filter)
 * @method static Builder|AccountType newModelQuery()
 * @method static Builder|AccountType newQuery()
 * @method static Builder|AccountType query()
 * @method static Builder|AccountType search($q, $full_text = FALSE)
 * @method static Builder|AccountType whereCreatedAt($value)
 * @method static Builder|AccountType whereCreatedBy($value)
 * @method static Builder|AccountType whereDeletedBy($value)
 * @method static Builder|AccountType whereId($value)
 * @method static Builder|AccountType whereName($value)
 * @method static Builder|AccountType whereUpdatedAt($value)
 * @method static Builder|AccountType whereUpdatedBy($value)
 * @mixin Eloquent
 */
class AccountType extends Model {
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
		'deleted'
	];

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

	protected static function newFactory()
	{
		return AccountTypeFactory::new();
	}
}
