<?php

namespace Partymeister\Accounting\Grids;

use Motor\Admin\Grid\Grid;
use Motor\Admin\Grid\Renderers\BooleanRenderer;
use Motor\Admin\Grid\Renderers\CurrencyRenderer;
use Motor\Admin\Grid\Renderers\DateRenderer;
use Partymeister\Accounting\Grid\Renderers\BalanceRowRenderer;

/**
 * Class AccountGrid
 */
class AccountGrid extends Grid
{
    protected function setup()
    {
        $this->setDefaultSorting('name', 'ASC');
        $this->addColumn('name', trans('motor-backend::backend/global.name'), true);
        $this->addColumn('account_type.name', trans('partymeister-accounting::backend/account_types.account_type'));
        $this->addColumn('has_pos', trans('partymeister-accounting::backend/accounts.has_pos'))
             ->renderer(BooleanRenderer::class);
        $this->addColumn('currency_iso_4217', trans('partymeister-accounting::backend/accounts.currency_iso_4217'));
        $this->addColumn('last_booking', trans('partymeister-accounting::backend/accounts.last_booking'))
             ->renderer(DateRenderer::class);
        $this->addColumn('balance', trans('partymeister-accounting::backend/accounts.balance'))
             ->renderer(CurrencyRenderer::class, ['currency_column' => 'currency_iso_4217'])
             ->style('text-align: right');
        $this->addAction(trans('partymeister-accounting::backend/accounts.show_pos'), 'backend.pos.show', ['class' => 'btn-primary'])
             ->needsPermissionTo('bookings.write')
             ->onCondition('has_pos', true);
        $this->addAction(trans('partymeister-accounting::backend/accounts.edit_pos'), 'backend.pos.edit', ['class' => 'btn-warning'])
             ->needsPermissionTo('bookings.write')
             ->onCondition('has_pos', true);
        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.accounts.edit');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.accounts.destroy');

        $this->addSpecialRow('partymeister-accounting::backend.accounts.balance')
             ->renderer(BalanceRowRenderer::class);
    }
}
