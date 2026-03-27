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
    protected string $model = Item::class;

    protected array $loadColumns = ['item_type', 'pos_cost_account'];

    public function filters(): void
    {
        $this->filter->add(new SelectRenderer('item_type_id'))
            ->setOptionPrefix(trans('partymeister-accounting::backend/item_types.item_type'))
            ->setEmptyOption('-- '.trans('partymeister-accounting::backend/item_types.item_type').' --')
            ->setOptions(ItemType::orderBy('sort_position', 'ASC')
                ->pluck('name', 'id'));
    }
}
