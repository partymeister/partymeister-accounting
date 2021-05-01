<?php

namespace Partymeister\Accounting\Services;

use Motor\Backend\Services\BaseService;
use Partymeister\Accounting\Models\Account;

/**
 * Class AccountService
 *
 * @package Partymeister\Accounting\Services
 */
class AccountService extends BaseService
{
    /**
     * @var string
     */
    protected $model = Account::class;
}
