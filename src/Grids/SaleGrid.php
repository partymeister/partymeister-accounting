<?php

namespace Partymeister\Accounting\Grids;

use Motor\Backend\Grid\Grid;
use Motor\Backend\Grid\Renderers\CurrencyRenderer;
use Motor\Backend\Grid\Renderers\DateRenderer;

/**
 * Class SaleGrid
 */
class SaleGrid extends Grid
{
    protected function setup()
    {
        $this->addColumn('created_at', trans('partymeister-accounting::backend/sales.time'))
             ->renderer(DateRenderer::class);
        $this->addColumn('item_and_quantity', trans('partymeister-accounting::backend/items.item'));
        $this->addColumn('earnings_booking.to_account.name', trans('partymeister-accounting::backend/bookings.to_account'));
        $this->addColumn('price_with_vat', trans('partymeister-accounting::backend/bookings.price_with_vat'))
             ->renderer(CurrencyRenderer::class, ['currency_column' => 'currency_iso_4217'])
             ->style('text-align: right');
    }
}
