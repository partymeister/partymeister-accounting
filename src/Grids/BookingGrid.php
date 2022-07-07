<?php

namespace Partymeister\Accounting\Grids;

use Motor\Admin\Grid\Grid;
use Motor\Admin\Grid\Renderers\CurrencyRenderer;
use Motor\Admin\Grid\Renderers\DateRenderer;

/**
 * Class BookingGrid
 */
class BookingGrid extends Grid
{
    protected function setup()
    {
        $this->setDefaultSorting('created_at', 'DESC');
        $this->addColumn('created_at', trans('partymeister-accounting::backend/bookings.time'))
             ->renderer(DateRenderer::class);
        $this->addColumn('description', trans('partymeister-accounting::backend/bookings.description'));
        $this->addColumn('from_account.name', trans('partymeister-accounting::backend/bookings.from_account'));
        $this->addColumn('to_account.name', trans('partymeister-accounting::backend/bookings.to_account'));
        $this->addColumn('price_with_vat', trans('partymeister-accounting::backend/bookings.price_with_vat'))
             ->renderer(CurrencyRenderer::class, ['currency_column' => 'currency_iso_4217'])
             ->style('text-align: right');
        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.bookings.edit');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.bookings.destroy');
    }
}
