<?php

namespace Partymeister\Accounting\Services;

use Motor\Backend\Services\BaseService;
use Partymeister\Accounting\Models\ItemType;

/**
 * Class ItemTypeService
 */
class ItemTypeService extends BaseService
{
    /**
     * @var string
     */
    protected $model = ItemType::class;
}
