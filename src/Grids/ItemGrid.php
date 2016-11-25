<?php

namespace Partymeister\Accounting\Grids;

use Motor\Backend\Grid\Grid;
use Motor\Backend\Grid\Renderers\BooleanRenderer;
use Motor\Backend\Grid\Renderers\CurrencyRenderer;

class ItemGrid extends Grid
{

    protected function setup()
    {
        $this->addColumn('item_type.name', trans('partymeister-accounting::backend/item_types.item_type'));
        $this->addColumn('name', trans('partymeister-accounting::backend/items.name'));
        $this->addColumn('pos_earnings_account.name', trans('partymeister-accounting::backend/items.pos_earnings_account'));
        $this->addColumn('price_with_vat', trans('partymeister-accounting::backend/bookings.price_with_vat'))->renderer(CurrencyRenderer::class)->style('text-align: right');
        $this->addColumn('sales', trans('partymeister-accounting::backend/items.sales'));
        $this->addColumn('revenue', trans('partymeister-accounting::backend/items.revenue'));
        $this->addColumn('sort_position', trans('motor-backend::backend/global.sort_position'));
        $this->addColumn('pos_sort_position', trans('partymeister-accounting::backend/items.pos_sort_position'));
        $this->addColumn('pos_do_break', trans('partymeister-accounting::backend/items.pos_do_break'))->renderer(BooleanRenderer::class);
        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.items.edit');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.items.destroy');
    }
}
