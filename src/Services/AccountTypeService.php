<?php

namespace Partymeister\Accounting\Services;

use Motor\Backend\Services\BaseService;
use Partymeister\Accounting\Models\AccountType;

/**
 * Class AccountTypeService
 * @package Partymeister\Accounting\Services
 */
class AccountTypeService extends BaseService
{

    /**
     * @var string
     */
    protected $model = AccountType::class;
}
