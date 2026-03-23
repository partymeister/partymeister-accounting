<?php

namespace Partymeister\Accounting\Services;

use Motor\Admin\Services\BaseService;
use Motor\Core\Filter\Renderers\SelectRenderer;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\ItemType;

/**
 * Class ItemService
 */
class ItemService extends BaseService
{
    /**
     * @var string
     */
    protected $model = Item::class;

    public function filters()
    {
        $this->filter->add(new SelectRenderer('item_type_id'))
                     ->setOptionPrefix(trans('partymeister-accounting::backend/item_types.item_type'))
                     ->setEmptyOption('-- '.trans('partymeister-accounting::backend/item_types.item_type').' --')
                     ->setOptions(ItemType::orderBy('sort_position', 'ASC')
                                          ->pluck('name', 'id'));
    }
}
