<?php

namespace Partymeister\Accounting\Grids;

use Motor\Backend\Grid\Grid;

/**
 * Class AccountTypeGrid
 * @package Partymeister\Accounting\Grids
 */
class AccountTypeGrid extends Grid
{
    protected function setup()
    {
        $this->addColumn('id', 'ID', true);
        $this->setDefaultSorting('id', 'ASC');
        $this->addColumn('name', trans('motor-backend::backend/global.name'));
        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.account_types.edit');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.account_types.destroy');
    }
}
