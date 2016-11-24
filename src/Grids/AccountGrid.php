<?php

namespace Partymeister\Accounting\Grids;

use Motor\Backend\Grid\Grid;
use Motor\Backend\Grid\Renderers\BooleanRenderer;

class AccountGrid extends Grid
{

    protected function setup()
    {
        $this->addColumn('id', 'ID', true);
        $this->setDefaultSorting('id', 'ASC');
        $this->addColumn('account_type.name', trans('partymeister-accounting::backend/account_types.account_type'));
        $this->addColumn('name', trans('motor-backend::backend/global.name'));
        $this->addColumn('has_pos', trans('partymeister-accounting::backend/accounts.has_pos'))->renderer(BooleanRenderer::class);
        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.accounts.edit');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.accounts.destroy');
    }
}
