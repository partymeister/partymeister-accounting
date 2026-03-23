<?php

namespace Partymeister\Accounting\Grids;

use Motor\Admin\Grid\Grid;

/**
 * Class AccountTypeGrid
 */
class AccountTypeGrid extends Grid
{
    protected function setup()
    {
        $this->addColumn('id', 'ID', true);
        $this->setDefaultSorting('id', 'ASC');
        $this->addColumn('name', trans('motor-admin::backend/global.name'));
        $this->addEditAction(trans('motor-admin::backend/global.edit'), 'backend.account_types.edit');
        $this->addDeleteAction(trans('motor-admin::backend/global.delete'), 'backend.account_types.destroy');
    }
}
