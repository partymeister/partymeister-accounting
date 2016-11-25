<?php

namespace Partymeister\Accounting\Grids;

use Motor\Backend\Grid\Grid;
use Motor\Backend\Grid\Renderers\BooleanRenderer;

class ItemTypeGrid extends Grid
{

    protected function setup()
    {
        $this->setDefaultSorting('sort_position', 'ASC');
        $this->addColumn('name', trans('motor-backend::backend/global.name'));
        $this->addColumn('item_count', trans('partymeister-accounting::backend/items.items'));
        $this->addColumn('sort_position', trans('motor-backend::backend/global.sort_position'), true);
        $this->addColumn('is_visible', trans('motor-backend::backend/global.is_visible'))->renderer(BooleanRenderer::class);
        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.item_types.edit');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.item_types.destroy');
    }
}
