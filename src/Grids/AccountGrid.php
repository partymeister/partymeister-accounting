<?php

namespace Partymeister\Accounting\Grids;

use Motor\Backend\Grid\Grid;
use Motor\Backend\Grid\Renderers\BooleanRenderer;
use Motor\Backend\Grid\Renderers\CurrencyRenderer;
use Motor\Backend\Grid\Renderers\DateRenderer;
use Partymeister\Accounting\Grid\Renderers\BalanceRowRenderer;

class AccountGrid extends Grid
{

    protected function setup()
    {
        $this->setDefaultSorting('name', 'ASC');
        $this->addColumn('name', trans('motor-backend::backend/global.name'), true);
        $this->addColumn('account_type.name', trans('partymeister-accounting::backend/account_types.account_type'));
        $this->addColumn('has_pos', trans('partymeister-accounting::backend/accounts.has_pos'))->renderer(BooleanRenderer::class);
        $this->addColumn('currency_iso_4217', trans('partymeister-accounting::backend/accounts.currency_iso_4217'));
        $this->addColumn('last_booking', trans('partymeister-accounting::backend/accounts.last_booking'))->renderer(DateRenderer::class);
        $this->addColumn('balance', trans('partymeister-accounting::backend/accounts.balance'))->renderer(CurrencyRenderer::class, ['currency_column' => 'currency_iso_4217'])->style('text-align: right');
        $this->addAction(trans('partymeister-accounting::backend/accounts.show_pos'), 'backend.pos.show', ['class' => 'btn-primary'])->needsPermissionTo('backend.bookings.create')->onCondition('has_pos', true);
        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.accounts.edit');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.accounts.destroy');

        $this->addSpecialRow('partymeister-accounting::backend.accounts.balance')->renderer(BalanceRowRenderer::class);
    }
}
