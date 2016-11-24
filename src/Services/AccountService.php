<?php

namespace Partymeister\Accounting\Services;

use Partymeister\Accounting\Models\Account;
use Motor\Backend\Services\BaseService;

class AccountService extends BaseService
{

    protected $model = Account::class;
}
