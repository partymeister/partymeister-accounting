<?php

namespace Partymeister\Accounting\Services;

use Motor\Admin\Services\BaseService;
use Partymeister\Accounting\Models\Account;

/**
 * Class AccountService
 */
class AccountService extends BaseService
{
    protected string $model = Account::class;

    protected array $loadColumns = ['account_type'];
}
