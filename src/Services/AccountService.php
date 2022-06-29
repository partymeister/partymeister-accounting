<?php

namespace Partymeister\Accounting\Services;

use Motor\Backend\Services\BaseService;
use Partymeister\Accounting\Models\Account;

/**
 * Class AccountService
 */
class AccountService extends BaseService
{
    /**
     * @var string
     */
    protected $model = Account::class;
}
